@extends('layouts.master')

@section('content')

<div class="panel panel-default">

  <div class="panel-heading"><h4 class="panel-title">E-mail preview</h4></div>
  <div class="panel-body">
    <iframe src="{{asset('/gen/'.$filename)}}" style="width:100%;height:500px;"></iframe>
  
  </div>
</div>

<div class="panel panel-default">

  <div class="panel-heading"><h4 class="panel-title">Recipients</h4></div>
  <table class="table">
    <thead>
      <tr>
        <th>Name</th>
        <th>E-mail</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($recipients as $user)
      <tr>
        <td>{{$user->name}}</td>
        <td>{{$user->email}}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

<div class="panel panel-primary">
  <div class="panel-heading"><h4 class="panel-title">Confirm</h4></div>
  <div class="panel-body">
    <p>If everything looks alright, press the button!</p>
    {{Form::open(['action'=>'EmailController@postSend'])}}
    <button class="btn btn-success">Send this e-mail</button>
    {{Form::close()}}
  </div>
</div>

@stop