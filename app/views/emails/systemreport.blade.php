@extends('layouts.email')

@section('content')
<h1>System report</h1>

@if ($msg)
@foreach ($msg as $m)
<p>
  @if (is_array($m) || is_object($m))
  <pre>{{var_dump($m)}}</pre>
  @else
  {{$m}}
  @endif
</p>

@endforeach

@endif

@stop