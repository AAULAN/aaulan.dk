@extends('layouts.email')

@section('content')
<p>Hi {{$name}},</p>

@if ($result == 1)
<p>An AAULAN ticket was found for the user <strong>{{$checked}}</strong> in Studentersamfundets Webshop, however since this e-mail address differs from your registered e-mail address <strong>{{$user_email}}</strong>, you must click the below link to activate your ticket.</p>
<p><a href="{{$goto}}" class="btn-primary">Activate your ticket!</a></p>
@elseif ($result == 0)
<p>No AAULAN ticket was found for the entered ticket number in Studentersamfundets Webshop.</p>
@else
<p>The entered e-mail address <strong>{{$checked}}</strong> was not found in Studentersamfundets Webshop.</p>
<p>Therefore your ticket has not been activated the AAULAN website.</p>
@endif

<p>Best Regards<br />AAULAN Crew</p>

@stop
