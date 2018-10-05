<?php

namespace Aaulan\Challonge;
use Log;

class Api {
	
	const BASE_URL = 'https://api.challonge.com/v1/';
	
	/**
	 * @var $url URL segment appended to base url
	 */
	protected $url = "";
	
	/**
	 * @var $ch CURL handle 
	 */
	protected $ch;
	
	/**
	 * @var $apiKey API key
	 */
	protected $apiKey = "LqINvt8GhS47G6Gz1hkOrt2J57A3TQfVdleIIYdU";
	
	protected function prepare($method = "get",array $data = null, $url = null) {
		$this->ch = curl_init();
		
		if (!$url) $url = $this->url;
		$count = preg_match_all('#:(\w+)#',$url,$matches);
		if ($count > 0) {
			foreach ($matches[1] as $var) {
				if (property_exists($this, $var)) {
					$url = str_replace(':'.$var,$this->$var,$url);
				} else {
					$url = str_replace(':'.$var,'',$url);
				}
			}
		}
		
		if ($data == null) {
			$data = array('api_key'=>$this->apiKey,'subdomain'=>'aaulan');
		} else {
			$data['api_key'] = $this->apiKey;
			$data['subdomain'] = 'aaulan';
		}
		
		$url = rtrim($url,'/').'.json';
		
		
		
		if (strtolower($method) == "get") {
			$url .= "?".http_build_query($data);
		} else {
			curl_setopt($this->ch,CURLOPT_POSTFIELDS,http_build_query($data));
			curl_setopt($this->ch,CURLOPT_CUSTOMREQUEST,strtoupper($method));
		}
		curl_setopt($this->ch,CURLOPT_URL,self::BASE_URL.$url);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);
	}
	
	protected function execute() {
		if (!$this->ch) {
			Log::error('$this->ch is not setup prior to execute() call.');
		}
		$data = curl_exec($this->ch);
		$this->result = json_decode($data,true);
		if ($this->result == null) {
			Log::error('curl_exec returned null instead of JSON.');
		}
	}
	
	public function __construct() {
		if (empty($this->url)) {
			Log::error("\$this->url not set for ".__CLASS__);
			return 0;
		}
	}
	
	public function index() {
		$this->prepare();
		$this->execute();
		
		return $this->result;
		
	}
	
	public function destroy() {
		$this->prepare('delete');
		$this->execute();
		
		return $this->result;
	}
	
	public function show() {
		$this->prepare();
		$this->execute();
		
		return $this->result;
	}
	
	public function update($data) {
		$this->prepare('PUT',$data);
		$this->execute();
		
		return $this->result;
	}
	
	public function create($data) {
		$this->prepare('post',$data);
		$this->execute();
		
		return $this->result;
	}
	
}
