 @extends('public.admin.header')

@section('content')
 <div class="row">
 <div class="col-sm-12">
  <section class="panel">
   <header class="panel-heading">
      角色添加
   </header>
   <div class="panel-body">
    
   <form method="post" id="form" class="form-horizontal bucket-form">
   
 
    
 	<div class="form-group">
 	 <label class="col-sm-2 control-label">电话号码</label>
      <div class="col-sm-10">
         <input type="text" valid="required"  class="form-control" name="user[mobile]" placeholder="电话号码">
      </div>
    </div>  
    
      <div class="form-group">
 	 <label class="col-sm-2 control-label">用户名</label>
      <div class="col-sm-10">
         <input type="text"  class="form-control" name="user[username]" placeholder="默认为电话号码">
      </div>
    </div>
    
    <div class="form-group">
 	 <label class="col-sm-2 control-label">姓名</label>
      <div class="col-sm-10">
         <input type="text" valid="required"  class="form-control" name="user[realname]" placeholder="姓名">
      </div>
    </div>    
    
    
    <div class="form-group">
    <label class="col-sm-2 control-label">角色</label>
      <div class="col-sm-10">
         <select name="user[role]" class="form-control">
         <?php foreach ($roles as $item){?>
          <option value="<?php echo $item['role_id']?>"><?php echo $item['role_name']?></option>
         <?php } ?>
         </select>
      </div>
    </div>    
         
    <div class="form-group">
       <div class="col-sm-offset-2 col-sm-10">
          <button id="save_role" class="btn btn-primary">注册</button>
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
//         $("#form").submit();
      });    
     }); 
 });
 </script>
  @stop
