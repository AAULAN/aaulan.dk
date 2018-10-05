@extends('layouts.email')

@section('content')
<p>Hi {{$name}},</p>
<p>{{$requestor}} has requested to join your team {{$teamname}}.</p>
<p>Please go to your profile to accept or decline their request.</p>
<p><a href="{{URL::action('UserController@getProfile')}}" class="btn-primary">Open my profile</a></p>
<p>Best Regards<br />AAULAN Crew</p>
@stop
