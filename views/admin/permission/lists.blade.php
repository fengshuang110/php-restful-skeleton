@extends('public.admin.header')

@section('content')
<div class="row">
 <div class="col-sm-12">
  <section class="panel">
   <header class="panel-heading">
      权限列表
   </header>
   <div class="panel-body">
    <div class="btn-group">
     <a id="editable-sample_new" href="/permission/add" class="btn btn-primary">
                添加权限 <i class="fa fa-plus"></i>
     </a>
    </div>
    <table class="table  table-hover general-table">
      <thead>
       <tr>
        <th> ID</th>
        <th>权限名称</th>
        <th>上级ID</th>
        <th>controller</th>
        <th>action</th>
        <th>操作</th>
       </tr>
      </thead>
      <tbody>
		@foreach ($lists as $key => $item) 
        <tr>
         <td>{{ $item['menu_id'] }}</td>
         <td >{{ $item['name'] }}</td>
         <td >{{ $item['parent_id']}}</td>
         <td >{{ $item['controller'] }}</td>
         <td >{{  $item['action'] }}</td>
         <td>
             <a href="/permission/edit?id={{ $item['menu_id'] }}" class="btn btn-info" >编辑</a>
             <a href="/permission/del?id={{ $item['menu_id'] }}" onclick="return confirm('删除操作将会删除菜单权限和其子菜单权限，确定删除吗?');" class="btn btn-danger" >删除</a>
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

