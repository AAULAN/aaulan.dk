@extends('layouts.master')

@section('content')

<div class="panel panel-default">

  <div class="panel-heading"><h4 class="panel-title">Send live update</h4></div>
  <div class="panel-body">
    <p>Use this page to notify participants.</p>
    {{Form::open(['action'=>'LiveController@postMessage'])}}
    <div class="form-group">
      {{Form::label('groups','Send this message to the following groups:')}}
      <select name="groups[]" id="groups" class="multiselect" multiple="multiple">
        @foreach ($groups as $group)
        <option value="{{join(',',$group['users'])}}">{{$group['name']}}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      {{Form::label('users','And send it to the following users:')}}
      <select name="users[]" id="users" class="multiselect" multiple="multiple">
        @foreach ($users as $user)
        <option value="{{$user->id}}">{{$user->name}}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      {{Form::label('message','Send this message')}}
      {{Form::textarea('message','',['class'=>'form-control'])}}
    </div>
    <div class="form-group">
      <button class="btn btn-default">Send</button>
    </div>
    {{Form::close()}}

  </div>
</div>

@stop