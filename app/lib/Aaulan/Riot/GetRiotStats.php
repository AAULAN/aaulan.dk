<?php

namespace Aaulan\Riot;
use Rhumsaa\Uuid\Uuid;
use User;
use URL;
use Queue;
use Lan;
use Log;

class GetRiotStats {

  public function fire($job,$data)
  {
    if ($job->attempts() > 3) {
      $job->delete();
      Mail::queue('emails.systemreport',['messages'=>['Number of attempts exceeded 3']],function($message) {
        $message->to('andersjkbsn@gmail.com')->subject(__FILE__);
      });
    }

    $user = User::findOrFail($data);
    if (empty($user->riot_summoner_name)) {
      return;
    }


    $apiKey = 'RGAPI-cb1a998a-e1a2-414f-95bf-991a30e9e069';

    $url = sprintf('https://euw1.api.riotgames.com/lol/summoner/v3/summoners/by-name/%s?api_key=%s', rawurlencode($user->riot_summoner_name), $apiKey);
	Log::info('Summoner URL:' . $url);
    // https://euw.api.pvp.net/api/lol/euw/v1.4/summoner/by-name/RiotSchmick?api_key=<key>


    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $r = curl_exec($ch);
	Log::info('Summoner response:' . $r);
    if (!$r) {
      $user->riot_status = 'CURL_ERROR';
      $user->riot_message = curl_error($ch);
      $user->save();
    }
    $info = curl_getinfo($ch);
    if ($info['http_code'] == 200) {
      $resp = json_decode($r,true);
      //$summoner = head($resp);
      $id = $resp['id'];
      $summoner_name = $resp['name'];

      curl_close($ch);



      $url = sprintf('https://euw1.api.riotgames.com/lol/league/v3/positions/by-summoner/%d?api_key=%s',$id,$apiKey);
	  Log::info('Positions URL:' . $url);
      $ch = curl_init($url);
      curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
      curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

      $r = curl_exec($ch);
	  Log::info('Position response:' . $r);
      if (!$r) {
        $user->riot_status = 'CURL_ERROR';
        $user->riot_message = curl_error($ch);
        $user->save();
      }
      $info = curl_getinfo($ch);
      
      if ($info['http_code'] == 200) {
        $summoner_data = json_decode($r,true);
		
        //$summoner_data = $summoner_data[$id];
        $leagueFound = false;
        foreach ($summoner_data as $queue) {
          if ($queue['queueType'] == 'RANKED_SOLO_5x5') {
            $tier = $queue['tier'];
            $division = $queue['rank'];
            $leaguePoints = $queue['leaguePoints'];
            $leagueFound = true;

            $user->riot_summoner_name = $summoner_name;
            $user->riot_summoner_id = $id;
            $user->riot_tier = $tier;
            $user->riot_division = $division;
            $user->riot_league_points = $leaguePoints;
            $user->riot_status = 'OK';
            $user->riot_message = sprintf("Successfully refreshed data at %s.",date('d/m-Y H:i:s'));
            $user->save();

            break;
		  }
        }
        if (!$leagueFound)  {
          $user->riot_status = 'OK';
          $user->riot_summoner_id = $id;
          $user->riot_message = 'Summoner has not played any RANKED 5x5 SOLO';
          $user->save();
        }
      } else if ($info['http_code'] == 404) {
        $user->riot_status = 'OK';
        $user->riot_summoner_id = $id;
        $user->riot_message = 'Summoner has not played any RANKED games.';
        $user->save();
      } else if ($info['http_code'] == 429) {
        Mail::queue('emails.systemreport',['messages'=>['Riot req./s limit exceeded']],function($message) {
          $message->to('andersjkbsn@gmail.com')->subject(__FILE__);
        });
        $job->release(600+rand(30,90));
      } else {
        $user->riot_status = 'ERROR';
        $user->riot_message = 'Could not retrieve summoner stats.';
        $user->save();
        $job->release(300);
      }
      


    } else if ($info['http_code'] == 404) {
      $user->riot_status = 'ERROR';
      $user->riot_message = 'Could not find summoner with entered name.';
      $user->save();
    } else if ($info['http_code'] == 429) {
      Mail::queue('emails.systemreport',['messages'=>['Riot req./s limit exceeded']],function($message) {
        $message->to('andersjkbsn@gmail.com')->subject(__FILE__);
      });
      $job->release(600+rand(30,90));
    } else {
      $user->riot_status = 'ERROR';
      $user->riot_message = 'Error while searching for summoner.';
      $user->save();
      $job->release(300);
    }


  }


}
