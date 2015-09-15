if (typeof esp == 'undefined') {
	esp = {};
}

esp.Event = function (){
	var _self = this;
	
	_self.event = {};
	
	/**
	 * 注册监听器
	 * e 为要监听的事件
	 * f 为回调函数
	 */
	_self.on = function(e, f){
		if(!_self.event[e]){
			_self.event[e] = [];
		}
		_self.event[e].push(f);
	};

	/**
	 * 触发事件 
	 */
	_self.fire = function(){
		var args = arguments, e = args[0];
		if(!_self.event[e]){
			return;
		}
		var nargs = [];
    	for (var j = 1; j < args.length; j++) {
    		nargs[j - 1] = args[j];
    	}

        for(var i = 0; i < _self.event[e].length; i++){
            try{
                _self.event[e][i].apply(null, nargs);
            }catch(e){};
        }
	};
	
	/**
	  清空已经注册的监听器
	 */
	_self.die = function(e){
		_self.event[e] = undefined;
	}
}
esp.createCss = function(cssText){
	var head = document.getElementsByTagName('head')[0];
    var css = document.createElement('style');
    css.setAttribute('type', 'text/css');
    head.appendChild(css);
    try{
        var cs = document.createTextNode(cssText);
        css.appendChild(cs);
    }catch(e){
        css.styleSheet.cssText += cssText;
    }
};
/**
 * @author iamlaobie
 * @since 2014-04-01
 */
esp.Validator = function(objs, cb){
	var ev = new esp.Event();
	esp.createCss('.error_border{border:1px solid #b90000 !important;}');
	var regs = {
			email : /^[a-z0-9_]+@[a-z0-9-]+(.[a-z])+$/,
			ip : /^(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[0-9]{1,2})(\.(25[0-5]|2[0-4][0-9]|1[0-9][0-9]|[0-9]{1,2})){3}$/,
			mobile : /^1[3-9][0-9]{9}$/,
			zip : /^\d{6}$/,
			url : /^(\w+\:\/\/)([\w\d]+(?:\.[\w]+)*)?(?:\:(\d+))?(\/[^?#]*)?(?:\?([^#]*))?(?:#(.*))?$/,
			chars : /^[a-zA-Z0-9_]+$/,
			number : /^\-?\d+$/,
			float : /^\-?\d+(\.\d+)?$/,
			zh : /^[\u4e00-\u9fcc]+$/,
			date : /^\d{4}\-\d{2}\-\d{2}$/,
			datetime : /^\d{4}\-\d{2}\-\d{2} \d{2}:\d{2}(:\d{2})?$/,
			time : /^\d{2}:\d{2}(:\d{2})?$/
	};
	var tip = function (o, res, msg) {
		var ox = {obj : o, result:res, msg : msg};
		ev.fire("tip", ox);
		$(o).popover('destroy');
		if(res){
			$(o).removeClass('error_border');
			$(o).val($(o).val().trim());
		}else{
			$(o).popover('destroy');
			$(o).popover({"content" : ox.msg, trigger : 'manual',"placement" : "top"});
			$(o).addClass('error_border');
			$(o).popover("show");
		}
	};
	var errorTip = function(obj, ccb){
		ev.fire("validate", obj);
		ccb = ccb||function(){};
		var validTypes = $(obj).attr('valid') || "";
		if (!validTypes) {
			ccb(obj, true);
			return;
		}
		validTypes = validTypes.split(' ');
		var result = true;
		
		for(var i = 0; i < validTypes.length; i++){
			var vt = validTypes[i].trim();
			if (!vt) {
				continue;
			}
			var val = $(obj).val().trim();
			var curResult = true;
			var res, regexp;
			//如果没有required和当前输入框为空字符串，不执行任何检查
			if (validTypes.indexOf('required') == -1 && val == "") {
				continue;
			}
			
			ev.fire("validating", obj, vt, val);	
			if(vt == 'required') {
				if (val == '' || $(obj).val().trim() == $(obj).attr('ignoreMsg')){
					result = false;
					curResult = false;
				}
			} else if((res = vt.match(/^(chars|number|zh|float)(?:\:(\-?\d(?:\.\d+)?)(?:,(\-?\d+(?:\.\d+)?)))?$/))){
				if (!regs[res[1]].test(val)) {
					result = false;
					curResult = false;
				} else if (res[1] == "number" || res[1] == "float") {
					if (res.length >= 3 && parseFloat(val) < parseFloat(res[2])) {
						result = false;
						curResult = false;
					} 
					if (res.length >= 4 && parseFloat(val) > parseFloat(res[3])) {
						result = false;
						curResult = false;
					}
				} else if (res[1] == "zh" || res[1] == "chars") {
					if (res.length >= 3 && val.length < parseInt(res[2])) {
						result = false;
						curResult = false;
					} 
					if (res.length >= 4 && val.length > parseInt(res[3])) {
						result = false;
						curResult = false;
					}
				}
			} else if (vt.match(/^regexp:/)) {
				var regexp = new RegExp(vt.replace(/^regexp\:/, ''));
				if (!regexp.test(val)) {
					result = false;
					curResult = false;
				}
			} else if (vt.match(/^equalTo:/)) {
				var et = vt.replace(/^equalTo:/, '');
				if ($('#' + et).val() != val) {
					result = false;
					curResult = false;					
				}
			} else if (regs[vt]){
				if (!regs[vt].test(val)) {
					result = false;
					curResult = false;
				}
			} else {
				throw new Error("unknow validating express \"" + vt + "\" at field \"" + $(obj).attr("name") + "\"");
			}
			
			if (!curResult || (result && curResult && i == validTypes.length - 1)) {
				tip(obj, curResult, $(obj).attr("inputTip") || "您的输入有误，请重新输入");
			} 
		} //end of validTypes for
		
		//输入格式正确，远程校验
		if (result && $(obj).attr('remote')) {
			$.get($(obj).attr('remote') + val, function (ret) {
				if (ret == 'true') {
					ev.fire('validated', obj, result, true);
					tip(obj, true, '');
					ccb(obj, true, true);
				} else {
					ev.fire('validated', obj, result, false);
					tip(obj, false, $(obj).attr("remoteTip") || "您的输入不可用，请重新输入");
					ccb(obj, true, false);
				}
			});
		} else {
			ccb(obj, result);
			ev.fire('validated', obj, result, '');
		}
		
	};
	//控制按钮不执行校验
	var ignoreKeys = [13,37,38,39,40,16,17,18,91];
	async.each(objs, function (obj) {
		$(obj).keyup(function (e) {
			if (ignoreKeys.indexOf(e.keyCode) == -1) {
				errorTip(this);
			}
		}).blur(function () {
			errorTip(this);
		});
	});
	
	var valid = function(cb){
		var result = {success : true};
		async.eachSeries(objs, function (obj, ccb) {
			errorTip(obj, function (o, r, rmt) {
				if (!r || rmt === false) {
					result.success = false;
				}
				obj.strResult = r;
				obj.rmtResult = rmt;
				ccb();
			});
		}, function () {
			result.objs = objs;
			cb(null, result);
		});
	};
	return {
		regs : regs,
		check : valid,
		on : ev.on,
		solo : errorTip
	};
};

/**
 * url解析器
 * 
 * @author iamlaobie
 * @since 20110907
 */
esp.url = function(url){
    if(typeof url == 'undefined'){
        url = location.href;   
    }
    var segment = url.match(/^(\w+\:\/\/)?([\w\d]+(?:\.[\w]+)*)?(?:\:(\d+))?(\/[^?#]*)?(?:\?([^#]*))?(?:#(.*))?$/);
    if(!segment){
    	return {};
    }
    if(!segment[3]){
        segment[3] = '80';
    }
    
    var param = {};
    if(segment[5]){
        var pse = segment[5].match(/([^=&]+)=([^&]+)/g);
        if(pse){
        	for(var i = 0; i < pse.length; i++){
                param[pse[i].split('=')[0]] = pse[i].split('=')[1];
            }
        }
    }
    
    return {
        url:segment[0],
        sechme:segment[1],
        host:segment[2],
        port:segment[3],
        path:segment[4],
        queryString:segment[5],
        fregment:segment[6],
        param:param
    };
};
esp.tip = function (msg, timeout, btnClose) {
	$('#tip-proxy .alert span').html(msg);
	if (btnClose === false) {
		$('#tip-proxy .alert button').addClass("hidden");
	}
	var a = $('#tip-proxy .alert').clone().appendTo($(document.body)).fadeIn();
	if (timeout) {
		setTimeout(function () {
			a.fadeOut();
		}, timeout * 1000);
	}
	return a;
}

/**
 * 重新定义alert方法
 * 依赖layout中的html片段
 */
window.ialert = function (msg) {
    $('#alert-proxy #alert-modal-body').html(msg);
    $('#alert-proxy').modal('show');
    var a = $('#alert-proxy .btn-default'), b = $('#alert-proxy .btn-primary');
    b.addClass("hide");
    a.html('确定');
    a.unbind('click');
    b.unbind('click');
    
}

/**
 * 模拟confirm方法
 * 依赖layout中的html片段
 */
window.iconfirm = function (msg, cb) {
	$('#alert-proxy #alert-modal-body').html(msg);
    $('#alert-proxy').modal('show');
    var a = $('#alert-proxy .btn-default'), b = $('#alert-proxy .btn-primary');
    a.html('取消');
    b.html('确定');
    a.unbind('click');
    b.unbind('click');
    b.removeClass('hide');
    a.click( function () {
        cb('no');
    });
    b.click(function () {
        cb('yes');
    });
    
};  