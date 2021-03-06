@extends('public.admin.login')

@section('content')

  <div class="page-header">
        <h2>登录</h2>
  </div>
 
      <form class="form-signin" id="form-login">
        <h2 class="form-signin-heading">请登录</h2>
        <div id="errormsg" style="color: red;text-align:center;">
      </div>
        <label for="inputEmail" class="sr-only">Email address</label>
        <input type="text" maxlength="16"  name="username" class="form-control" placeholder="邮箱/电话/用户名" required autofocus>
        <label for="inputPassword" class="sr-only">Password</label>
        <input type="password" name="password" class="form-control" placeholder="密码" required>

		<div class="row">
			<div class="col-md-7"><input class="form-control" name="vcode" type="text"></div>
			<div class="col-md-4"><img id="imgcode" style="margin-left: -15px;margin-top: 5px;" src="/vcode/create?type=login"></div>
			<div class="col-md-4">
			 <a href="#" id="flush_imgcode" >重新换一张</a>
          </div>
		</div>
        <div class="checkbox">
          <label>
            <input type="checkbox" value="remember-me"> 记住我
          </label>

		  <label>
           <a href="/register/index">没有账号？立即申请成为商家</a>
          </label>

        </div>
        <a class="btn btn-lg btn-primary btn-block" id="login">登录</a>
      </form>
	  <script>
	  $(function(){
		 //刷新重新换一张
		$("#flush_imgcode").click(function(){
			$("#imgcode").attr("src","/vcode/create?type=login&time="+Date.parse(new Date()));
		});

		$("#login").click(function(){
			$.ajax({
				url:'/login/check',
				data:$("#form-login").serialize(),
				type:'post',
				dataType:'json',
				success:function(res){
					alert(res.code);
					if(res.code * 1  == 0){
						window.location.href="/index/index";
					}else{
						alert(res.msg);
						$("#errormsg").html(res.msg);
					} 
				}
			});
			
		});


	  })
	  
	  </script>

 @stop