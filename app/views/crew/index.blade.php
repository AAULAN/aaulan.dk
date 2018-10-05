@extends('layouts.master')

@section('content')


<div class="panel panel-default">
    <?php
    $crewStates = array("ACTIVE" => true, "FORMER/INACTIVE" => false);
    ?>
    @foreach ($crewStates as $state_caption=>$state)
    	<div class="panel-heading"><h4 class="panel-title">{{ $state_caption }} CREW</h4></div>
        <div class="panel-body">
            @foreach ($members as $member)
                @if ($member->active == $state)
                    <div class="col-sm-6 col-md-4 col-xs-12 thumbnail">
                        <img src="{{ asset('uploads/crew/' . (empty($member->filename) ? 'default.jpg' : $member->filename)) }}" title="{{ $member->user->name }}">
                        <div class="caption">
                            <h6><a href="{{URL::action('UserController@getShowProfile',$member->user->slugOrId())}}">{{ $member->user->name}}@if (!empty($member->user->display_name)) ({{ $member->user->display_name }})@endif</a></h6>
                            <p><b>{{ $member->role }}</b></p>
                            <p>@if (!empty($member->description)) {{ $member->description }} @else No description@endif</p>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endforeach
</div>

@stop
