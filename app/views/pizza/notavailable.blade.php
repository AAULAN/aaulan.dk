@extends('layouts.master')

@section('content')
<div class="panel panel-default">
  <div class="panel-heading"><h4 class="panel-title">Pizza ordering</h4></div>
  <div class="panel-body">
<p>It is not possible to order pizza at the moment.</p>
@if ($next)
<p>Please wait until {{$next->opens->format('l jS \\of F Y H:i')}}, when the next order opens up.</p>
@else 
<p>No order is currently planned.</p>
@endif
</div></div>

@stop
