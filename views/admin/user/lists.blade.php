@extends('public.admin.header')

@section('content')
<div class="row">
 <div class="col-sm-12">
  <section class="panel">
   <header class="panel-heading">
      角色列表
   </header>
   <div class="panel-body">
    <div class="btn-group">
     <a id="editable-sample_new" href="/user/add" class="btn btn-primary">
                添加角色 <i class="fa fa-plus"></i>
     </a>
    </div>
    <table class="table  table-hover general-table">
      <thead>
      <tr>
       <th> ID</th>
        <th class="hidden-phone">用户名</th>
        <th>姓名</th>
        <th>电话号码</th>
        <th>密码</th>
        <th>角色</th>
        <th>操作</th>
       </tr>
      </thead>
      <tbody>
		@foreach($lists as $user)
        <tr>
         <td><a href="#"> {{$user['user_id']}}</a></td>
         <td class="hidden-phone"> {{$user['username']}}</td>
         <td class="hidden-phone"> {{$user['realname']}}</td>
         <td class="hidden-phone"> {{$user['mobile']}}</td>
         <td class="hidden-phone"> {{$user['password']}}</td>
         <td class="hidden-phone">@if($user['user_id']==1) 超级管理员 @else {{$user['role_name']}} @endif</td>
         <td><a href="/user/edit?id= {{$user['user_id']}}" class="btn btn-info" >编辑</a>
             <a href="/user/del?id= {{$user['user_id']}}" onclick="return confirm('您确定删除角色吗?');" class="btn btn-danger" >删除</a>
         </td>
        </tr>
		@endforeach
       </tbody>
      </table>
   @include('public.paginator')   
   </div>
  </section>
 </div>
</div>
@stop