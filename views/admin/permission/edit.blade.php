@extends('public.admin.header')

@section('content')
<div class="row">
 <div class="col-sm-12">
  <section class="panel">
   <header class="panel-heading">
      角色添加
   </header>
   <div class="panel-body">
    
   <form method="post" action="/permission/add" id="form" class="form-horizontal bucket-form">
 	  
    <div class="form-group">
 	 <label class="col-sm-2 control-label">上级权限</label>
      <div class="col-sm-10">
      <input type="hidden" name="menu_id" value="{{$menu['menu_id'] }}">
         <select name="parent_id">
         <option value="0">无</option>
         @foreach ($parent_menus as $item)
          @if($menu['parent_id'] == $item['menu_id'])
          <option selected="selected" value="{{ $item['menu_id'] }}">{{$item['name']}}</option>
          @else
            <option value="{{$item['menu_id']}}">{{ $item['name'] }}</option>
          @endif
         @endforeach
         </select>
      </div>
    </div> 
    
    <div class="form-group">
 	 <label class="col-sm-2 control-label">权限名称</label>
      <div class="col-sm-10">
         <input type="text" valid="required" value="{{ $menu['name']}}" name="name" class="form-control" id="role_name" placeholder="角色名称">
      </div>
    </div>  
     <div class="form-group">
 	 <label class="col-sm-2 control-label">是否是菜单</label>
      <div class="col-sm-10">
		<label class="radio-inline">
  			<input type="radio" name="ismenu"  @if($menu['ismenu'] == 0) checked="checked" @endif value="0"> 否
		</label>
		<label class="radio-inline">
 		 	<input type="radio" name="ismenu"  @if($menu['ismenu'] == 1) checked="checked" @endif value="1"> 是
		</label>
      </div>
    </div> 
    <div class="form-group">
 	 <label class="col-sm-2 control-label">controller</label>
      <div class="col-sm-10">
         <input type="text" valid="required" name="controller" value="{{$menu['controller']}}" class="form-control" id="role_name" placeholder="角色名称">
      </div>
    </div>    
    
    <div class="form-group">
 	 <label class="col-sm-2 control-label">action</label>
      <div class="col-sm-10">
         <input type="text" valid="required" value="{{ $menu['action'] }}" name="action" class="form-control" id="role_name" placeholder="角色名称">
      </div>
    </div>           
    
    <div class="form-group">
       <div class="col-sm-offset-2 col-sm-10">
          <a id="save_role" class="btn btn-primary">保存</a>
        </div>
     </div>
  </form>
  </div>
 </section>
</div>
</div>
 <script type="text/javascript">
 $(function(){

     $("#save_role").click(function(){
        $("#form").submit();
     }); 
 });
 </script>
 @stop
