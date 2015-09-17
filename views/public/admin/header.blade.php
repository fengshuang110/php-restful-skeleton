<!DOCTYPE html>
<html lang="zh-CN">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- 上述3个meta标签*必须*放在最前面，任何其他内容都*必须*跟随其后！ -->
    <title>Bootstrap 101 Template</title>
    <!-- Bootstrap -->
    <link href="/public/css/bootstrap.min.css" rel="stylesheet">
	<link href="/public/css/dashboard.css" rel="stylesheet">
	<script src="//cdn.bootcss.com/jquery/1.11.3/jquery.min.js"></script>
  </head>
            
</head>

<body>
<nav class="navbar navbar-inverse navbar-fixed-top">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">商户管理后台</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#">我的信息</a></li>
            <li><a href="#">修改密码</a></li>
            <li><a href="#">退出</a></li>
          </ul>
          <form class="navbar-form navbar-right">
            <input type="text" class="form-control" placeholder="Search...">
          </form>
        </div>
      </div>
    </nav>

    <div class="container-fluid">
      <div class="row" style="font-size:18px;">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
          @foreach($menus as $menu)
             @if(!empty($menu['sub_menu']))
            <li class="">
            <a class="collapsed" role="button" data-toggle="collapse" href="#collapseListGroup{{$menu['menu_id']}}">
            <i class="glyphicon {{$menu['icon_class']}}" ></i>{{$menu['name']}}
			</a>
		 	</li>
             @else
            <li class="">
            <a  href="{{$menu['url']}}">
            <i class="glyphicon {{$menu['icon_class']}}" ></i>{{$menu['name']}}
			</a>
		 	</li>
             @endif
             
		  @if(!empty($menu['sub_menu']))
		   <ul id="collapseListGroup{{$menu['menu_id']}}"  style="padding-left:40px;" class="collapse nav nav-sidebar "  >
			   @foreach($menu['sub_menu'] as $item)
			<li class="">
            <a class="collapsed" role="button" data-toggle="collapse" href="{{$item['url']}}" >
				<i class="glyphicon {{$item['icon_class']}}" ></i>
				{{$item['name']}}
			</a>
		  </li>
		  	 @endforeach
		 </ul>
		  @endif
		  @endforeach
			
           
		

          </ul>

        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
		<div class="row">
		<ol class="breadcrumb">
			<li><a href="#">主页</a></li>
			<li><a href="#">权限管理</a></li>
			 <li class="active">编辑</li>
		</ol>
		 @yield('content')
		</div>
           
        </div>
      </div>
    </div>
     
</section>
 
   
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="/public/js/bootstrap.min.js"></script>
        <!--footer section end-->
    <!-- main content end-->
</body>
</html>



