@extends('public.header')

@section('content')
<div>
		<h1>blade输出变量测试</h1>
		<div>姓名：{{$data['name']}}</div>
		<div>年龄：{{$data['age']}}</div>
		<div>qq：{{$data['qq']}}</div>
</div>
 @stop