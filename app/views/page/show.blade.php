@extends('layouts.master')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading"><h4 class="panel-title">{{$page->title}}</h4></div>
    <div class="panel-body">
      {{Markdown::render($page->body)}}
    </div>
  </div>

@stop
