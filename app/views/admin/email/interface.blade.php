@extends('layouts.master')

@section('content')

<div class="panel panel-default">

  <div class="panel-heading"><h4 class="panel-title">E-mail tool</h4></div>
  <div class="panel-body">
    <p>Use this page to send e-mails to guests.</p>
    {{Form::open(['action'=>'EmailController@postPreview'])}}
    <div class="form-group">
      {{Form::label('groups','Send this e-mail to the following groups of people:')}}
      <select name="groups[]" id="groups" class="multiselect" multiple="multiple">
        @foreach ($groups as $group)
        <option value="{{join(',',$group['users'])}}">{{$group['name']}}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      {{Form::label('users','And send this e-mail to the following users:')}}
      <select name="users[]" id="users" class="multiselect" multiple="multiple">
        @foreach ($users as $user)
        <option value="{{$user->id}}">{{$user->name}}</option>
        @endforeach
      </select>
    </div>
    <div class="form-group">
      {{Form::label('message','Send this message')}}
      {{Form::textarea('message','',['class'=>'form-control'])}}
      <span class="help-block">E-mail will be prepended with Hello &lt;Name&gt;</span>
    </div>
    <div class="form-group">
      <button class="btn btn-default">Preview</button>
    </div>
    {{Form::close()}}

  </div>
</div>

@stop