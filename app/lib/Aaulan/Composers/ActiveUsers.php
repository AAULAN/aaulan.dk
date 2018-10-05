<?php

namespace Aaulan\Composers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\HTML;

use DB;

class ActiveUsers
{


    public function compose($view) {
        $activeUsers = DB::select('select count(id) num from users where last_activity + interval 125 minute > now()');
        
        $view->with('activeUsers',$activeUsers[0]->num);
    }
    

}