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
     <a id="editable-sample_new" href="/role/add" class="btn btn-primary">
                添加角色 <i class="fa fa-plus"></i>
     </a>
    </div>
    <table class="table  table-hover general-table">
      <thead>
       <tr>
        <th> ID</th>
        <th class="hidden-phone">角色名称</th>
        <th>操作</th>
       </tr>
      </thead>
      <tbody>
		@foreach($lists as $role)
        <tr>
         <td><a href="#">{{ $role['role_id'] }}</a></td>
         <td class="hidden-phone">{{  $role['role_name']}}</td>
         <td><a href="/role/edit?id={{ $role['role_id'] }}" class="btn btn-info" >编辑</a>
             <a href="/role/del?id={{ $role['role_id'] }}" onclick="return confirm('您确定删除角色吗?');" class="btn btn-danger" >删除</a>
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