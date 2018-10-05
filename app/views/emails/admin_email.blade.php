@extends('layouts.email')
@section('content')
<h3>Hi {{$name}},</h3>
{{Markdown::render($content)}}
<p>Best Regards<br />The AAULAN Crew</p>
@stop