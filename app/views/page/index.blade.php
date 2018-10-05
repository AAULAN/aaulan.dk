@extends('layouts.master')

@section('content')

  <div class="panel panel-default">
    <div class="panel-heading"><h4 class="panel-title">Information about AAULAN</h4></div>
    <div class="panel-body"><p>Select a page below to read</p></div>
    <ul class="list-group">
      @foreach ($pages as $page) 
        <li class="list-group-item"><a href="{{URL::action('PageController@getPage',$page->slug)}}">{{$page->title}}</a></li>
      @endforeach
      </ul>
  </div>


      

	
	

@stop
