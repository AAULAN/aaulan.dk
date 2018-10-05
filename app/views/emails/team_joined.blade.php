@extends('layouts.email')

@section('content')
<p>Hi {{$name}},</p>
<p>{{$acceptor}} has accepted your request to join the team {{$teamname}}.</p>
<p>Best Regards<br />AAULAN Crew</p>
@stop
