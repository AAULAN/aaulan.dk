@extends('layouts.master',['live'=>true])

@section('content')
<div ng-controller="LiveUpdateController">
<div class="panel panel-default" ng-repeat="update in updates.slice().reverse()">
  <div class="panel-body">
    
    
    
        <p>@{{update.message}}</p>
    
  </div>
  <div class="panel-footer">Posted on @{{update.created_at|date:"EEEE 'at' H:mm:ss"}} by @{{update.poster}}</div>
</div>
</div>
@stop
