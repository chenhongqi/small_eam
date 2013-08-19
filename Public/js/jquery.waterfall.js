/*!
 * jQuery Waterfall v1.2
 * 
 * Author		: LeoLai
 * Blog			: http://leolai.cnblogs.com/
 * Mail 		: leolai.mail@qq.com
 * QQ 			: 657448678 
 * Date 		: 2013-4-19 
 * Last Update 	: 2013-5-23
 *
 **************************************************************
 * 1. 根据页面大小自动排列
 * 2. 自定义异步请求函数（返回JSON，json格式与html模板对应即可，默认格式请看demo json.js）
 * 3. 自定义html模板
 * 4. 图片自动按比例缩放
 * 5. 是否显示分页(未完成)
 * usage: url必填，其它不传将使用默认配置
	$('#id').waterfall({
		colWidth: 235,		// 列宽
		marginLeft: 15,		// 每列的左间宽
		marginTop: 15,		// 每列的上间宽
		count: 'infinite',	// 获取的次数(int) 'infinite' 表示无限加载 
		lastId: 0,			// 最后一条数据的ID
		perNum: 10,			// 每次获取10条数据
		url: null,			// 数据来源(ajax加载，返回json格式)，传入了ajaxFunc参数，此参数可省略
		// 自定义异步函数, 第一个参数为成功回调函数，第二个参数为失败回调函数
		// 当执行成功回调函数时，传入返回的JSON数据作为参数
		ajaxFunc: null,		
		createHtml: null	// 自定义生成html字符串函数,参数为一个信息集合，返回一个html字符串
	});
 *
 */
 
;(function($, window, document){
	$.fn.waterfall = function(options){
		var // 配置信息
			opts = $.extend({}, $.fn.waterfall.defaults, options), 
			
			// 标志变量
			isIE6 = !-[1,] && !window.XMLHttpRequest,
			isScroll = false, 	// 窗口是否出现了滚动条
			isLoading = false,	// 是否正在加载图片	
			isFinish = false, 	// 是否完成(true的时候不再向服务器发送请求)
			
			// $wf_col 的相对视图的位置高度
			wf_col_top = 0,
			
			// 瀑布流块的top, left值
			wf_item_top = 0,
			wf_item_left = 0,
			
			// 下标
			index = 0,
			
			// 用于存储每列的高度
			colsHeight = [],
			
			// 一些jQ对象
			$wf_box, $wf_inner, 
			$wf_col, $wf_col_items,
			$wf_result, $backTop,
		
			// 生成html字符串函数
			createHtml = $.isFunction(opts.createHtml) ?opts.createHtml:
					function(data){
						return '<div class="wf_item_inner">' +
								  '<a href="'+ data.href +'" class="thumb" target="_blank">' +
								     '<img class="thumb_img"  src="'+ data.imgSrc +'" />' +
								  '</a>' +
								  '<h3 class="title"><a href="'+ data.href +'" target="_blank">'+ data.title +'</a></h3>' +
								  '<p class="desc">'+ data.describe +'</p>' +
							  '</div>';
					};
		
		
		
		// usage:
		// fixedPosition(elem, {top:0, left:0});
		// fixedPosition(elem, {bottom:0, right:0});
		var fixedPosition = function(){
			var html = document.getElementsByTagName('html')[0],
				dd = document.documentElement,
				db = document.body,
				doc = dd || db;
			
			// 给IE6 fixed 提供一个"不抖动的环境"
			// 只需要 html 与 body 标签其一使用背景静止定位即可让IE6下滚动条拖动元素也不会抖动
			// 注意：IE6如果 body 已经设置了背景图像静止定位后还给 html 标签设置会让 body 设置的背景静止(fixed)失效
			if (isIE6 && db.currentStyle.backgroundAttachment !== 'fixed') {
				html.style.backgroundImage = 'url(about:blank)';
				html.style.backgroundAttachment = 'fixed';
			};
			
			// pos = {top:0, right:0, bottom:0, left:0}
			return isIE6 ? 
				function(elem, pos){
					var style = elem.style,
						dom = '(document.documentElement || document.body)'; 
					
					if(typeof pos.left !== 'number'){
						pos.left = doc.clientWidth - pos.right - elem.offsetWidth; 
					}
					if(typeof pos.top !== 'number'){
						pos.top = doc.clientHeight - pos.bottom - elem.offsetHeight; 
					}
					
					elem.style.position = 'absolute';
					style.removeExpression('left');
					style.removeExpression('top');
					style.setExpression('left', 'eval(' + dom + '.scrollLeft + ' + pos.left + ') + "px"');
					style.setExpression('top', 'eval(' + dom + '.scrollTop + ' + pos.top + ') + "px"');
				} : 
				function(elem, pos){
					var style = elem.style;
						
					style.position = 'fixed';
					
					if(typeof pos.left === 'number'){
						style.left = pos.left + 'px';
					}else{
						style.left = 'auto'; 
						style.right = pos.right + 'px';
					}
					
					if(typeof pos.top === 'number'){
						style.top = pos.top + 'px';
					}else{
						style.top = 'auto'; 
						style.bottom = pos.bottom + 'px';
					}
				 
				};
		}();
		
		// 处理返回的数据
		function dealData(jsonData){
			var $wf_item, htmlStr;
			// 确保所有图片都已知宽高
			loadImg(jsonData, function(jsonData){
				$.each(jsonData, function(i, data){
					opts.lastId++;
					index = getColsIndex(colsHeight)[0];
					
					wf_item_left = index * (opts.colWidth + opts.marginLeft);
					wf_item_top = colsHeight[index] ? (colsHeight[index] + opts.marginTop) : colsHeight[index];
					
					
					$wf_item = $('<div>').addClass('wf_item');
					
					htmlStr = createHtml(data);
					
					$wf_item.html(htmlStr).css({width: opts.colWidth,left:wf_item_left, top:wf_item_top}).appendTo($wf_col);

                    //$wf_item.find('img').height($wf_item.find('img').width() / data.width * data.height);
				
					colsHeight[index] = wf_item_top + $wf_item.outerHeight();
					if( colsHeight[index] > colsHeight.maxHeight ){
						colsHeight.maxHeight = colsHeight[index];
					}
					
					$wf_col.height(colsHeight.maxHeight);
				});
				isLoading = false;
				$wf_result.hide();
				
				// 如果还没满屏出现滚动，继续获取数据
				if(!isScroll){
					getJSONData(opts.url, function(jsonData){
						dealData(jsonData);
					});
				}
				
			});
		}
		
		// 异步获取数据
		function getJSONData(url, callback){
			
			// 不再向服务器发送请求
			if(isFinish){
				showMsg('error');
				return;
			}
			
			if(!isLoading){ // 确保上一次加载完毕才发送新的请求
				getColsIndex(colsHeight);
				// 滚动条下拉时判断是否需要向服务器请求数据
				if(colsHeight.minHeight + wf_col_top < $(window).height() + $(window).scrollTop()){
					if(opts.count === 'infinite' || opts.count-- > 0){
						isLoading = true;
						showMsg('loading');
						if(!$.isFunction(opts.ajaxFunc)){
							$.ajax({
								type: 'GET',
								url: url + '?from=' + opts.lastId + '&count=' + opts.perNum,
								//crossDomain: true,
								cache: false,
								dataType:'text',
								timeout: 60000,
								success: function(jsonData){
									try{
										jsonData = $.parseJSON(jsonData);
                                        alert(jsonData);
										if($.isArray(jsonData)){
											// 返回的数据0条，说明数据已经全部加载完毕
											if(jsonData.length === 0) showMsg('finish');
											$.isFunction(callback) && callback(jsonData);
										}else{
											showMsg('error');
										}
									}
									catch(e){
										showMsg('error');
									}
								},
								error: function(){
									showMsg('error');
								}
							});
						// 如果自定义异步请求函数
						}else{
							opts.ajaxFunc(callback, function(){
								showMsg('error');
							});
						}
					}else{
						showMsg('finish');
					}
				}else{
					isScroll = true;
				}
			}
		}
		
		// 排列瀑布流的块
		function realign(){
			var colNum = 0,
				i = 0,
				backTop_left =  0;
			
			// 计算出当前屏幕可以排多少列
			colNum = Math.floor(($wf_box.width() + opts.marginLeft) / (opts.colWidth + opts.marginLeft));
			
			if(opts.colNum || colNum !== opts.colNum){
				opts.colNum = colNum;
				$wf_inner.width((opts.colWidth+opts.marginLeft) * opts.colNum - opts.marginLeft);
				
				for(i=0; i<opts.colNum; i++){
					colsHeight[i] = 0;
				}
				colsHeight.length = opts.colNum;
				
				$wf_col_items = $wf_col.children('div.wf_item');
				$wf_col_items.each(function(num, value){
					index = getColsIndex(colsHeight)[0];
					wf_item_top = colsHeight[index] ? (colsHeight[index] + opts.marginTop) : colsHeight[index];
					wf_item_left = index * (opts.colWidth + opts.marginLeft);
					
					$(this).width(opts.colWidth).stop(true).animate({
						left:wf_item_left, 
						top:wf_item_top
					}, 500);
					colsHeight[index] = wf_item_top + $(this).outerHeight();
				});
				
				getColsIndex(colsHeight);
				$wf_col.height(colsHeight.maxHeight);
				
				// 返回顶部按钮位置
				backTop_left =$wf_inner.offset().left + ($wf_box.width() + $wf_inner.width()) / 2 - $backTop.width(); 
				
				fixedPosition($backTop[0], {
					left: backTop_left,
					bottom: 0
				});
			}
			
		}
		
		// 显示结果信息
		function showMsg(type){
			switch(type){
				case 'loading':
					$wf_result.html('').addClass('wf_loading').show();
					break;
				case 'error':
					$wf_result.removeClass('wf_loading').show().html('服务器返回数据格式错误！');
					isFinish = true;
					break;
				case 'finish':
					$wf_result.removeClass('wf_loading').show().html('已加载完毕，没有更多了！');
					isFinish = true;
					break;
			}
		}
		
		return this.each(function(){
			$wf_box = $(this).addClass('waterfall');
			$wf_inner = $wf_box.children('.wf_inner');
			$backTop = $('#backTop');
			
			if($wf_inner.length === 0){
				$wf_inner = $('<div class="wf_inner">').appendTo($wf_box);
			}
			
			// 增加瀑布流列表框
			$wf_col = $wf_inner.children('.wf_col');
			if($wf_col.length === 0){
				$wf_col = $('<div class="wf_col">').appendTo($wf_inner);
			}
			wf_col_top = $wf_box.offset().top;	// 保存 $wf_col 的相对视图的位置高度
			
			// 增加loading状态
			$wf_result = $wf_inner.children('.wf_result');
			if($wf_result.length === 0){
				$wf_result = $('<div class="wf_result">').appendTo($wf_inner);
			}
			
			// 增加返回顶部按钮
			if($backTop.length === 0){
				$backTop = $('<a id="backTop" title="返回顶部"></a>').appendTo(document.body);
			}
			
			$backTop.css('opacity', 0).bind('click', function(){
				$('body,html').stop(true).animate({
					scrollTop: wf_col_top
				}, 500);
			});
			
			// 排列已经存在的瀑布流块
			$(document.body).css('overflow', 'scroll');
			realign();
			$(document.body).css('overflow', 'auto');
			
			// 第一次拉取图片时，保证图片能填满窗出现滚动
			getJSONData(opts.url, function(jsonData){
				dealData(jsonData);
				
			});
			
			// 注册滚动条事件
			$(window).bind('scroll', function(){
				if($(window).scrollTop() > wf_col_top){
					$backTop.stop(true).animate({opacity: 1}, 500);
				}else{
					$backTop.stop(true).animate({opacity: 0}, 500);
				}
				
				getJSONData(opts.url , function(jsonData){
					dealData(jsonData);
				});
				
			// 注册窗口改变大小事件
			}).bind('resize', realign);
		});
	};
	
	// 默认配置
	$.fn.waterfall.defaults = {
		colWidth: 235,		// 列宽(int)
		marginLeft: 15,		// 每列的左间宽(int)
		marginTop: 15,		// 每列的上间宽(int)
		count: 'infinite',	// 获取的次数(int) 字符串'infinite'表示无限加载 
		lastId: 0,			// 最后一条数据的ID(int)
		perNum: 10,			// 每次获取10条数据(int)
		url: null,			// 数据来源(ajax加载，返回json格式)，传入了ajaxFunc参数，此参数可省略(string)
		// 自定义异步函数, 第一个参数为成功回调函数，第二个参数为失败回调函数
		// 当执行成功回调函数时，传入返回的JSON数据作为参数
		ajaxFunc: null,		// (function)
		createHtml: null	// 自定义生成html字符串函数,参数为一个信息集合，返回一个html字符串(function)
		
	};
	
	
	/*****************一些全局函数*********************/
	/**
	 * 图片头数据加载就绪事件
	 * @参考 	http://www.planeart.cn/?p=1121
	 * @param	{String}	图片路径
	 * @param	{Function}	尺寸就绪 (参数1接收width; 参数2接收height)
	 * @param	{Function}	加载完毕 (可选. 参数1接收width; 参数2接收height)
	 * @param	{Function}	加载错误 (可选)
	 */
	var imgReady = (function(){
		var list = [], intervalId = null,
		
		// 用来执行队列
		tick = function () {
			var i = 0;
			for (; i < list.length; i++) {
				list[i].end ? list.splice(i--, 1) : list[i]();
			};
			!list.length && stop();
		},

		// 停止所有定时器队列
		stop = function () {
			clearInterval(intervalId);
			intervalId = null;
		};

		return function (url, ready, load, error) {
			var check, width, height, newWidth, newHeight,
				img = new Image();
			
			img.src = url;

			// 如果图片被缓存，则直接返回缓存数据
			if (img.complete) {
				ready(img.width, img.height);
				load && load(img.width, img.height);
				return;
			};
			
			// 检测图片大小的改变
			width = img.width;
			height = img.height;
			check = function () {
				newWidth = img.width;
				newHeight = img.height;
				if (newWidth !== width || newHeight !== height ||
					// 如果图片已经在其他地方加载可使用面积检测
					newWidth * newHeight > 1024
				) {
					ready(newWidth, newHeight);
					check.end = true;
				};
			};
			check();
			
			// 加载错误后的事件
			img.onerror = function () {
				error && error();
				check.end = true;
				img = img.onload = img.onerror = null;
			};
			
			// 完全加载完毕的事件
			img.onload = function () {
				load && load(img.width, img.height);
				!check.end && check();
				// IE gif动画会循环执行onload，置空onload即可
				img = img.onload = img.onerror = null;
			};

			// 加入队列中定期执行
			if (!check.end) {
				list.push(check);
				// 无论何时只允许出现一个定时器，减少浏览器性能损耗
				if (intervalId === null) intervalId = setInterval(tick, 40);
			};
		};
	})();
	
	// 传入json数据，全部图片头数据加载就绪后执行回调函数
	function loadImg(jsonData, callback){
		var count = i = 0,
			intervalId = null,
			data = null,
			done = function(){
				 if(count === jsonData.length) {
					 clearInterval(intervalId);
					 callback(jsonData);
				 }
			};
		for(; i<jsonData.length; i++){
			data = jsonData[i];
			if(typeof data.height !== 'number'){
				(function(data){
					imgReady(data.imgSrc, function(width,height){
						// 图片头数据加载就绪，保存宽高
						data.width = width;
						data.height = height;
						count++;
					}, null, function(){
						// 图片加载失败，替换成默认图片
						data.width = '208';
						data.height = '240';
						data.imgSrc = 'images/default.jpg';
						count++
					});
				})(data);
			}else{
				conut++;
			}
		}
		
		intervalId = setInterval(done, 40);
	}
	
	// 返回从小到大排序的数组的下标的数组
	// e.g. 传入数组[300,200,250,400] 返回[1,2,0,3]
	function getColsIndex(arr){
		var clone = arr.slice(),	// 数组副本，避免改变原数组
			ret = [], 	// 对应下标数组
			len = arr.length,
			i, j, temp;
			
		for(i=0;i<len;i++){
			ret[i] = i;
		}
		
		//外层循环(冒泡排序法：从小到大)
		for(i=0;i<len;i++){
			//内层循环
			for(j=i;j<len;j++){
				if(clone[j] < clone[i]){
					//交换两个元素的位置
					temp=clone[i];
					clone[i]=clone[j];
					clone[j]=temp;
					
					temp=ret[i];
					ret[i]=ret[j];
					ret[j]=temp;
				}
			}
		}
		arr.minHeight = arr[ret[0]];
		arr.maxHeight = arr[ret[ret.length -1]];
		return ret;
	}

})(jQuery, window, document);