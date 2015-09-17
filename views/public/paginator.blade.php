<nav>

 <ul class="pagination">
@if($totalPage == 1)
   <li class="disabled"> <span>上一页</span></li>
   <li class="active" ><a hre="#">1</a></li>
   <li><span>下一页</span></li>
@elseif($totalPage >= 2)
    @if($page==1) <li class="disabled"><span>上一页</span></li>
    	@else <li><a href="?{{$page_query}}&pn={{$page-1}}">上一页</a></li>
    @endif
    @if($totalPage-$page > 5)
            @if($page > 6)
                <li><a href="?{{$page_query}}&pn=1">1</a></li>
                <li><span>...</span> </li>
                @for($index=$page-5;$index<$page+4;$index++)
                    @if($index+1 == $page)
                       <li  class="active"> <span>{{$page}}</span> </li>
                    @else
                         <li><a href="?{{$page_query}}&pn={{$index+1}}">{{$index+1}}</a> </li>
                    @endif
                 @endfor
            @else
                 @for($index=0;$index<5;$index++)
                    @if($index+1 == $page)
                       <li  class="active">   <span>{{$page}}</span> </li>
                    @else
                       <li>  <a href="?{{$page_query}}&pn={{$index+1}}">{{$index+1}}</a> </li>
                    @endif
                @endfor
            @endif
            @if($totalPage >10)
            <li><span>...</span> </li>
           <li> <a href="?{{$page_query}}&pn={{$totalPage}}">{{$totalPage}}</a> </li>
            @endif
    @else
        @if($totalPage > 10 && $page >6 )
            <li> <a href="?{{$page_query}}&pn=1">1</a> </li>
             <li><span>...</span> </li>
             @for($index=$page-5;$index<$totalPage;$index++)
                @if($index+1 == $page)
                    <li class="active"><span>{{$page}}</span></li>
                @else
                    <li> <a href="?{{$page_query}}&pn={{$index+1}}">{{$index+1}}</a> </li>
                @endif
            @endfor
        @else
            @for($index=0;$index<$totalPage;$index++)
              @if($index+1 == $page)
               <li  class="active"> <span>{{$page}}</span></li>
              @else
                <li> <a href="?{{$page_query}}&pn={{$index+1}}">{{$index+1}}</a> </li>
              @endif
            @endfor
        @endif
    @endif
    @if($page==$totalPage)<li class="disabled"><span>下一页</span></li>
    @else <li><a href="?{{$page_query}}&pn={{$page+1}}">下一页</a> </li>
    @endif
@endif
</ul>

@if($totalPage == 0)
<div class="alert alert-danger" role="alert">
      <strong>好像什么都没有!</strong> 添加新的条目试试.
    </div>
@endif
</nav>
