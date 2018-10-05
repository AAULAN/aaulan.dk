@extends('layouts.email')

@section('content')
<h1>Pizza order</h1>

<p>See the attached pdf file. Exported at {{date('d/m-Y H:i:s')}}</p>

@stop