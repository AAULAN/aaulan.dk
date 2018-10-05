<nav class="navbar navbar-default" role="navigation">
			  <div class="container-fluid">
			    <!-- Brand and toggle get grouped for better mobile display -->
			    <div class="navbar-header">
			      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
			        <span class="sr-only">Toggle navigation</span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			        <span class="icon-bar"></span>
			      </button>
			      <a class="navbar-brand" href="{{URL::action('HomeController@showWelcome')}}">AAULAN</a>
			    </div>
			
			    <!-- Collect the nav links, forms, and other content for toggling -->
			    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			      <ul class="nav navbar-nav">
					@if (Auth::check())
						@if (!Auth::user()->hasAdmission())
							{{HTML::menu_element('TicketController@getSignUp','Ticket')}}
						@else
							{{HTML::menu_element('PizzaController@getIndex','Pizza')}}
							{{HTML::menu_element('SeatController@getSeating','Seating')}}
						@endif
						{{HTML::menu_element('TicketController@getUsers','Userlist')}}
					@endif
					@if (Entrust::hasRole('Webmaster') || Entrust::hasRole('Administrator') || Entrust::hasRole('Crew'))
					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown">Admin <b class="caret"></b></a>
						<ul class="dropdown-menu">
							{{HTML::menu_element('SeatController@adminGetSeating','Seating')}}
						</ul>
					</li>
					@endif
			      </ul>
			      
			      
			      
			      <ul class="nav navbar-nav navbar-right">
			        <li class="dropdown">
			          <a href="#" class="dropdown-toggle" data-toggle="dropdown">Profile <b class="caret"></b></a>
			          @if (Auth::check())
			          <ul class="dropdown-menu">
			          	{{HTML::menu_element('UserController@getShowProfile',array('user'=>Auth::user()->slugOrId()),'Show profile')}}
			          	{{HTML::menu_element('UserController@getProfile','Edit profile')}}
			            <li class="divider"></li>
			            {{HTML::menu_element('UserController@logout','Sign out')}}
			          </ul>
			          @else
			          <ul class="dropdown-menu">
            			{{HTML::menu_element('UserController@getLogin','Sign in')}}
			            <li class="divider"></li>
			            {{HTML::menu_element('UserController@getRegister','Register user')}}
			          </ul>
			          @endif
			        </li>
			      </ul>
			    </div><!-- /.navbar-collapse -->
			  </div><!-- /.container-fluid -->
			</nav>