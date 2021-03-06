layui.use(['element','layer'],function() {
	
    var $ = layui.jquery,element = layui.element(),layer = layui.layer;
	
    var l_o = $('.left-menu'),
		tab = 'top-tab',
		l_m = 'left-menu',
		t_m = 'top-menu';
		
    var mainHeight = $(window).height() - 60 - 41 - 44 - 5;
	
    element.on('nav(' + t_m + ')',function(data) {
        l_o.hide().eq(data.index()).show();
    });
	
    l_o.on("click", "li",function() {
        $(this).siblings().removeClass("layui-nav-itemed");
    });
	
    element.on('nav(' + l_m + ')',function(data) {
        var a_t 	= data.children("a"),
			id 		= a_t.data("id"),
			url 	= a_t.data("url"),
			title 	= a_t.html(),
			length 	= $(".layui-tab-title").children("li[lay-id='" + id + "']").length;
        if (!length) {
            var iframe = '<iframe src="' + url + '" style="height:' + mainHeight + 'px;"></iframe>';
            element.tabAdd(tab, {
                title	: title,
                content	: iframe,
                id		: id
            });
        }
        
        element.tabChange(tab, id);
        
        length && loadPage();
    });
	
    l_o.children("li:first").children("a:first").click();
	
	$(".menu-flexible").click(function(){
		$(".left-menu-all").toggle();
		$(".layui-body,.layui-footer").css("left",($(".left-menu-all").is(":hidden")) ? '0' : '200px');
	})
	
	$(".layui-tab-button").on("click","a",function(){loadPage()})
	
	// 刷新
	function loadPage(){
    	var index = $(".layui-tab-content").find(".layui-show").index();
		window[index].location.reload();
    }
});
layui.use('form', function(){
  var form = layui.form(),$ = layui.jquery;
  //return false;
  //监听提交
  form.on('submit(*)', function(data){
    //layer.msg(JSON.stringify(data.field),{offset: '20%'});
    // console.log(data.form);
    // console.log(data.field);
    // var ajobj = $.ajax({  type : "post",  url : data.form.action,  data : JSON.stringify(data.field),dataType : 'json'})
    // ajobj.done(function(re){
    //     console.log(re);
    // });
    // return false;
  });
  //全选
  form.on('checkbox(allChoose)', function(data){
    var child = $(data.elem).parents('table').find('tbody input[type="checkbox"]');
    child.each(function(index, item){
      item.checked = data.elem.checked;
    });
    form.render('checkbox');
  });
});