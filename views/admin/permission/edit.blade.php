@extends('public.header')

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
      <input type="hidden" name="id" value="{{$permission['id'] }}">
         <select name="pid">
         <option value="0">无</option>
         @foreach ($parent_menus as $item)
          @if($permission['pid'] == $item['id'])
          <option selected="selected" value="{{ $item['id'] }}">{{$item['name']}}</option>
          @else
            <option value="{{$item['id']}}">{{ $item['name'] }}</option>
          @endif
         @endforeach
         </select>
      </div>
    </div> 
    
    <div class="form-group">
 	 <label class="col-sm-2 control-label">权限名称</label>
      <div class="col-sm-10">
         <input type="text" valid="required" value="{{ $permission['name']}}" name="name" class="form-control" id="role_name" placeholder="角色名称">
      </div>
    </div>  
    
    <div class="form-group">
 	 <label class="col-sm-2 control-label">controller</label>
      <div class="col-sm-10">
         <input type="text" valid="required" name="controller" value="{{$permission['controller']}}" class="form-control" id="role_name" placeholder="角色名称">
      </div>
    </div>    
    
    <div class="form-group">
 	 <label class="col-sm-2 control-label">action</label>
      <div class="col-sm-10">
         <input type="text" valid="required" value="{{ $permission['action'] }}" name="action" class="form-control" id="role_name" placeholder="角色名称">
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
    var vt = esp.Validator($("input[type=text]"));

     $("#save_role").click(function(){
      vt.check(function (err, res) {
        //校验的结果通过res来通知，如果res.success == true，表明验证通过
        //res还有属性obis，所有can参与验证的input的数组，其中包含每个字段的验证结果
        if (err || !res || !res.success) {
            return;
        } 
        $("#form").submit();
      });    
     }); 
 });
 </script>
 @stop
