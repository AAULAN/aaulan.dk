@extends('layouts.email')

@section('content')
<p>Hi {{$name}}</p>
<p>Please validate your AAULAN account by following the link below.</p>
<p><a href="{{$validate_url}}" class="btn-primary">Validate my AAULAN account</a></p>
<p>Should the above link not be displaying correctly, then copy and paste it into your browser manually. Remove all spaces in the link, if any.</p>
<p>Best Regards<br />AAULAN Crew</p>
@stop
