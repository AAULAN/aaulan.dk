@extends('layouts.master')

@section('content')
<div ng-controller="SeatingChartController">
<div class="row">
	<div class="col-md-3" id="seating-group-creator">

	<fieldset>
		<legend>Editor</legend>
		<div class="checkbox">
		    <label><input type="radio" value="h" ng-model="orientation" />Horizantal</label>
            <label><input type="radio" value="v" ng-model="orientation" />Vertical</label>
		</div>
		<div class="checkbox">
		    <label><input type="radio" value="small" ng-model="size" />Small</label>
            <label><input type="radio" value="large" ng-model="size" />Large</label>
		</div>
		<div class="row">
			<div class="col-md-6 form-group">
            <label>Height</label>
           	<input type="number" value="2" ng-model="ycount" class="form-control" />
			</div>
			<div class="col-md-6 form-group">
            <label>Width</label>
            <input type="number" value="2" ng-model="xcount" class="form-control" />
			</div>
		</div>
		<button ng-click="addGroup()">PRESS ME!</button>
		<button ng-click="save()">SAVE</button>
	</fieldset>
	</div>
</div>
<div class="seating-chart">
	
	<div ng-right-click="delete(group)" ng-mousedown="mouseDown(group,$event)" ng-mouseleave="mouseLeave(group)" ng-mouseup="mouseUp(group,$event)" ng-mousemove="mouseMove(group,$event)" ng-repeat="group in groups" class="group" ng-class="{'group-horizontal':(group.orientation == 'h'),'group-vertical':(group.orientation == 'v')}" style="top: @{{group.ypos}}px; left: @{{group.xpos}}px; width: @{{group.width}}px; height: @{{group.height}}px;">
		
		<p style="line-height:@{{group.height}}px;">@{{group.xcount}} x @{{group.ycount}}</p>
	</div>
	
</div>

</div>
@stop
