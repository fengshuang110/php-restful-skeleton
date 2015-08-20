# php-restful-skeleton
支持以接口输入输出为主的框架 ，同时输出除了支持接口格式的json xml string等等之外，还支持以mvc的输入-》处理-》模板回显的mvc风格
原生支持php模板引擎
基于composer 支持blade模板引擎



##源码获取
git clone https://github.com/fengshuang110/php-restful-skeleton.git


##composer 下载依赖

 composer install
 
##配置虚拟主机ngnix


	server {
        listen       80;
        server_name  www.rest_api.com;
        root   路径;
        
        access_log  logs/host.access.log  main;

        location / {
            index  index.html index.php;
        }

    	if (!-e $request_filename) {
	        rewrite  ^/(.*)$  /index.php?/$1  last;
	        break;
	     }  
        location ~ \.php$ {
            fastcgi_pass   127.0.0.1:9000;
            fastcgi_index  index.php;
            fastcgi_param  SCRIPT_FILENAME 路径/$fastcgi_script_name;
            include        fastcgi_params;
        }


    }

##查看和调试api文档

在浏览器中输入http://www.youdomian:80/doc
直接查看文档![](http://i.imgur.com/cm6INCt.png)

直接省区文档编写的时间 前后端工程师都以这个输入输出为对接墙梁



##浏览器测试

http://http://www.youdomian:80/controller/action/