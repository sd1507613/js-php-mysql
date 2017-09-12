var nameList =""

function initUpload(browse_button,max_file_size,extensions,multi_selection,url,name,type){
	var path ="../uploads/"
	var uploader = new plupload.Uploader({ //创建实例的构造方法
		runtimes: 'html5,flash,silverlight,html4', //上传插件初始化选用那种方式的优先级顺序
		browse_button: browse_button, // 上传按钮
		url: url, //远程上传地址
		flash_swf_url: 'plupload/Moxie.swf', //flash文件地址
		silverlight_xap_url: 'plupload/Moxie.xap', //silverlight文件地址
		filters: {
			max_file_size: max_file_size, //最大上传文件大小（格式100b, 10kb, 10mb, 1gb）
			mime_types: [ //允许文件上传类型
				{
					title: "files",
					extensions: extensions
				}
			]
		},
		multi_selection: multi_selection, //true:ctrl多文件上传, false 单文件上传
		init: {
			FilesAdded: function(up, files) { //文件上传前
				if ($("#ul_pics").children("li").length > 30) {
					alert("您上传的图片太多了！");
					uploader.destroy();
				} else {
					var li = '';
					plupload.each(files, function(file) { //遍历文件
						li += "<li id='" + file['id'] + "'><div class='progress'><span class='bar'></span><span class='percent'>0%</span></div></li>";
						nameList += (path+file['name'] + ";")
					});
					$("#ul_pics").append(li);
					uploader.start();
					console.log(nameList)
				}
			},
			UploadProgress: function(up, file) { //上传中，显示进度条
				var percent = file.percent;
				$("#" + file.id).find('.bar').css({
					"width": percent + "%"
				});
				$("#" + file.id).find(".percent").text(percent + "%");
			},
			FileUploaded: function(up, file, info) { //文件上传成功的时候触发
				var data = eval("(" + info.response + ")");
				var path = data.pic
/*				try{
				var path = data.pic.replace("../","./")
				}catch(e){
					alert(data.error)
				}*/
				$("#" + file.id).html("<div class='img'><img src='" + path + "'/></div><p>" + data.name + "</p>");
			},
			Error: function(up, err) { //上传出错的时候触发
				alert(err.message);
			}
		}
	});
	
	uploader.init();
	$(name).on("click",function(){  
			if($('#姓名').val()=='' || $('#性别').val()=='' || $('#身份证号').val()=='' ||
			   $('#居住地址').val()=='' || $('#民族').val()=='' || $('#是否注销').val()=='' ||
			   $('#所属派出所').val()=='' || $('#联系方式').val()=='' || $('#派出所录入人员').val()=='')
			{
				alert("信息不完整")
				return;
			}
			var postData = { //获取或设置客户端使用的 HTTP 数据传输方法（GET 或 POST）。
			requestType: "insertIntoPerson",
			para:
			{
				name:$('#姓名').val()+"",					
				sex:$('#性别').val()+"",	
				card_num:$('#身份证号').val()+"",	
				home:$('#居住地址').val()+"",	
				inhabit:$('#民族').val()+"",	
				valueful:$('#是否注销').val()+"",	
				police_local:$('#所属派出所').val()+"",	
				tel:$('#联系方式').val()+"",	
				police_person:$('#派出所录入人员').val()+"",	
				url:nameList,
				type:type,				
			}
		};
		var cbk = function(responseJson)
		{
			if(responseJson==1)
			{
				//$('#tips').show();
				alert("上传成功")
			}else{
				alert("上传失败")
			}
		}
		messageEx("../php_server/process.php",postData, cbk);
		return;
    });
}