 @extends('public.admin.header')

@section('content')
 <div class="row">
 <div class="col-sm-12">
  <section class="panel">
   <header class="panel-heading">
      角色添加
   </header>
   <div class="panel-body">
    
   <form method="post"  id="form" class="form-horizontal bucket-form">
 	  
    <div class="form-group">
 	 <label class="col-sm-2 control-label">上级权限</label>
      <div class="col-sm-10">
         <select name="parent_id">
         <option value="0">无</option>
          @foreach ($parent_menus as $item)
          <option value="{{$item['menu_id']}}">{{$item['name']}}</option>
          @endforeach
         </select>
      </div>
    </div> 
    
    <div class="form-group">
 	 <label class="col-sm-2 control-label">权限名称</label>
      <div class="col-sm-10">
         <input type="text" valid="required"  name="name" class="form-control" placeholder="权限名称">
      </div>
    </div>  
     <div class="form-group">
 	 <label class="col-sm-2 control-label">是否是菜单</label>
      <div class="col-sm-10">
		<label class="radio-inline">
  			<input type="radio" name="ismenu"  checked="checked" value="0"> 否
		</label>
		<label class="radio-inline">
 		 	<input type="radio" name="ismenu"  value="1"> 是
		</label>
      </div>
    </div> 
    <div class="form-group">
 	 <label class="col-sm-2 control-label">controller</label>
      <div class="col-sm-10">
         <input type="text" valid="required" name="controller"  class="form-control" id="role_name" placeholder="角色名称">
      </div>
    </div>    
    
    <div class="form-group">
 	 <label class="col-sm-2 control-label">action</label>
      <div class="col-sm-10">
         <input type="text" valid="required" name="action" class="form-control" id="role_name" placeholder="角色名称">
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
