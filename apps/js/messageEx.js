function messageEx(url,jsonData, cbk)
{
	var postData = JSON.stringify(jsonData);
	//alert("postData:"+postData);
	
 	$.ajax({
 		//cache: false,//让IE不去读缓存，
		url:url,
		async:true,
		type:"POST",
		data: postData,
		contentType: "application/json",
		success:function(response)
		  {
	//alert("response="+response);
		  		response = response.replace(/(^\s*)|(\s*$)/g,"");

				//var res = JSON.parse(response);//IE8兼容性修改 JSON.parse -> eval
				var res = eval("("+response+")"); 

				if( typeof(res.requestTypeError) != "undefined")
				{
					alert("requestTypeError");
					return;
				}
				cbk(res);
		  }
		}
	); 
}

function getUrlParam(name) {
    var reg = new RegExp("(^|&)" + name + "=([^&]*)(&|$)"); //构造一个含有目标参数的正则表达式对象
    var r = window.location.search.substr(1).match(reg);  //匹配目标参数
    if (r != null) return unescape(r[2]); return null; //返回参数值
}
