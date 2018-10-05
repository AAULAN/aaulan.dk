@extends('layouts.master')

@section('content')

<div class="panel panel-default">

  <div class="panel-heading"><h4 class="panel-title">E-mail status</h4></div>
  <div class="panel-body">
    <p>The message was queued for {{$queued}} recipients.</p>
  
  </div>
</div>


@stop