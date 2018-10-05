<!DOCTYPE html>
<html lang="en" ng-app="aaulan">
	<head prefix="og: http://ogp.me/ns#">
		<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
		
		<meta property="og:title" content="AAULAN" />
		<meta property="og:description" content="AAULAN - The e-sport event of Aalborg University." />
		<meta property="og:image" content="{{asset('img/aaulan-square.png')}}" />
		<meta property="og:image:type" content="image/png" />
		<meta property="og:image:width" content="282" />
		<meta property="og:image:height" content="282" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    
    
    <!-- Bootstrap -->
    <link href='https://fonts.googleapis.com/css?family=Nunito:400,700,300' rel='stylesheet' type='text/css'>
    <link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet">
    <link href="{{asset('css/style.css')}}" rel="stylesheet">


    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script src="{{asset('js/bootstrap.min.js')}}"></script>



	
		<script src="https://ajax.googleapis.com/ajax/libs/angularjs/1.2.16/angular.min.js"></script>
    <script src="{{asset('js/autobahn.min.js')}}"></script>
    <script src="{{asset('js/angular-wamp.min.js')}}"></script>
		<script src="{{asset('js/ui-bootstrap-tpls-0.10.0.min.js')}}"></script>
		<script src="{{asset('js/timer.js')}}"></script>
		<script src="{{asset('js/bootstrap-multiselect.js')}}"></script>
		<script src="{{asset('js/app.js')}}"></script>

    <title>AAULAN</title>
    

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <body @if (isset($live)) ng-controller="LiveBodyController"@endif>
    <nav class="navbar navbar-default" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navigation">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="{{URL::to('/')}}">AAULAN</a>
        </div>

        <div class="collapse navbar-collapse" id="navigation">
          <ul class="nav navbar-nav">
            <li><a href="{{URL::action('HalloffameController@index')}}">HALL OF FAME</a></li>
            <?php
            // Hardcoded due to laziness, plz fix
            $categories = array("INFO" => 0, "GAME RULES" => 1);
            ?>
            @foreach ($categories as $category_name=>$category_id)
              <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">{{ $category_name }} <b class="caret"></b></a>
                <ul class="dropdown-menu">
                  @if ($category_id == 0)<li><a href="{{URL::action('CrewController@index')}}">Crew members</a>@endif
                  @foreach (Page::where('category_id', '=', $category_id)->get() as $page)
                    <li><a href="{{URL::action('PageController@getPage', $page->slug)}}">{{$page->title}}</a></li>
                  @endforeach
                </ul>
              </li>
            @endforeach
            
            <li><a href="{{URL::action('ScheduleController@getIndex')}}">SCHEDULE</a></li>
            @if (Auth::check())
				<li><a href="{{URL::action('SeatController@getSeating')}}">SEATING</a></li>
				@if (Auth::user()->validated == 1)
				<li><a href="{{URL::action('GamesController@getTournaments')}}">GAMES</a></li>
				{{--<li ng-controller="LiveUpdateMenuController"><a href="{{URL::action('LiveController@index')}}">LIVE UPDATE <span class="badge" ng-bind="unread" ng-show="unread > 0"></span></a></li>--}}
					@if (!Auth::user()->hasAdmission())
						<li><a href="{{URL::action('TicketController@getSignUp')}}">ACTIVATE TICKET</a></li>
					@else
						<!-- <li><a href="http://pizza.aaulan.dk">ORDER PIZZA</a></li> -->
					@endif
				@endif
            @endif
            
            @if (Entrust::hasRole('Webmaster') || Entrust::hasRole('Crew') || Entrust::hasRole('Administrator'))
            <li><a href="{{URL::to('admin')}}">ADMIN</a></li>
            @endif

            <li><a href="javascript:void(0);">ONLINE: <strong>{{$activeUsers}}</strong></a></li>
          </ul>
          @if (!Auth::check())
          <form class="navbar-form navbar-right" action="{{URL::action('UserController@postLogin')}}" method="post">
            <div class="form-group">
              <input name="email" type="text" placeholder="E-mail" class="form-control input-sm" style="width:100px;">
            </div>
            <div class="form-group">
              <input name="password" type="password" placeholder="Password" class="form-control input-sm" style="width:100px;">
            </div>
            <div class="btn-group hidden-xs">
              <button class="btn btn-default btn-sm">Sign in</button>
              <button name="remember_me" value="1" class="btn btn-sm btn-default">Sign in and keep me logged in</button>
              <button type="button" class="btn btn-default btn-sm dropdown-toggle" data-toggle="dropdown">
                <span class="caret"></span>
                <span class="sr-only">Toggle Dropdown</span>
              </button>
              <ul class="dropdown-menu" role="menu">
                <li><a href="{{ URL::action('UserController@getRegister')}}">Register</a></li>
                <li><a href="{{URL::action('RemindersController@getRemind')}}">Request password</a></li>
              </ul>
            </div>
            <button class="btn btn-primary visible-xs-block btn-block">Sign in</button>
            <a href="{{ URL::action('UserController@getRegister')}}" class="btn btn-default visible-xs-block btn-block">Register</a>
            <a href="{{URL::action('RemindersController@getRemind')}}" class="btn btn-default visible-xs-block btn-block">Request password</a>
          </form>
          @else
          <ul class="nav navbar-nav navbar-right">
          	<li><a href="{{URL::action('UserController@getProfile')}}">PROFILE</a></li>
          	<li><a href="{{URL::action('UserController@logout')}}">LOGOUT</a></li>
        	</ul>
          @endif

        </div>



      </div><!-- #container -->

      
    </nav>
    <div class="container">

<!-- <div class="alert alert-success" role="alert"><h1>Want to join the crew?</h1> We are always looking to expand the team. If you want to join the team and take part in the planning process, then contact us at the crew area to hear more! </div> -->
			  	@if ($showValidation)
			  	<div class="alert alert-danger">Your account is not validated. Please check your e-mail account. If you haven't received a verification e-mail, click <a href="{{URL::action('UserController@getSendValidationEmail')}}">here</a>.</div>
			  	@endif

          <div class="row">
<!--            <div class="col-md-9">-->


    			  	{{ Notification::showAll() }}
    			  	@if (Session::has('gmessage'))
    			  	<?php $msg = Session::get('gmessage'); ?>
    			  	<div class="alert alert-{{$msg['type']}}">{{$msg['message']}}</div> 
    			  	@endif
    			    @yield('content')

            <!--  <div class="panel panel-default">
                <div class="panel-heading"><h4 class="panel-title">Sponsors</h4></div>
    			      @include('partials.sponsors')
              </div> -->
<!--            </div>  -->
<!--            <div class="col-md-3"> -->
<!--              
              <div class="panel panel-default">
                <div class="panel-heading"><h4 class="panel-title">Main sponsors</h4></div>

				</div> -->
<!--          </div> -->

		</div>
<p class="text-center">AAULAN - a part of <a href="https://studentersamfundet.aau.dk" target="_blank"><img src="{{asset('img/s-et.png')}}" alt="studentersamfundet" /></a></p>
</div>



  


  @yield('script')

  </body>
</html>
