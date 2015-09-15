@extends('public.header')

@section('content')
<div class="row">
 <div class="col-sm-12">
  <section class="panel">
   <header class="panel-heading">
      角色权限列表
   </header>
   <div class="panel-body">
  <form method="post" action="/role/edit">
  <input type="hidden" name="role_id" value="{{ $role_id }}">
 @foreach ($permissinon_menu as $menu)
  <div> 
  	<div><input type="checkbox" name="permission[]" {{{ !empty($item['selected'])?"checked ":"" }}}  value="{{ $menu['id'] }}">{{ $menu['name']}}</div>
  	 
  	  @if(!empty($menu['submenu']))
  	  	<span style="margin-left:10px; ">
          @foreach ($menu['submenu'] as $item)
  		<label class="checkbox-inline">
  		<input type="checkbox"  name="permission[]" {{{ !empty($item['selected'])?"checked":"" }}}  value="{{ $item['id'] }}"> {{ $item['name'] }}
		</label>
          @endforeach
         </span>
      @endif
   </div>
   @endforeach
 
   <div class="panel-footer">
          <button type="submit" id="save_role" class="btn btn-success">保存</button>
          <a href="/role/lists" class="btn btn-primary">返回</a>
   </div>
 </form>
 </div>
  </section>
 </div>
</div>
@stop