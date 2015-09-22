function show_img(url){
	$('.admin_big_img').css({'display':'block'});
	//alert(url);
	$('#admin_big_img').html('<img class="big_img" src="'+url+'" /><div class="big_img_close" id="big_img_close" onclick="close_big_img();">关闭</div>');
}

function close_big_img(){
	$('#admin_big_img').css({'display':'none'});
}

function fileOnchage(files){
	upform.action='../plus/post.php';
	upform.submit();
}




function fileOnchage2(files){
	var file = files[0];
	if (window.navigator.userAgent.indexOf("Chrome") >= 1 || window.navigator.userAgent.indexOf("Safari") >= 1) { 
		var url = webkitURL.createObjectURL(file);
	}
	else { 
		var url = URL.createObjectURL(file);
	} 	
	/* 生成图片
	* ---------------------- */
	var $img = new Image();
	$img.onload = function() {
		var boardDiv = '<div id="loading" class="loading"><div class="load_box"><img src="images/loading.gif" /><span>正在加载</span></div></div>'; 
		$(document.body).append(boardDiv); 
		//生成比例
		var width = $img.width,
				height = $img.height,
				scale = width / height;
		width = parseInt(800);
		height = parseInt(width / scale);
		//生成canvas
		var $canvas = $('#canvas');
		var ctx = $canvas[0].getContext('2d');		
		$canvas.attr({width : width, height : height});
		ctx.drawImage($img, 0, 0, width, height);
		var base64 = $canvas[0].toDataURL('image/jpeg',0.5);
		
		//发送到服务端
		//alert(222);
		$.post('plus/upload.php',{formFile : base64.substr(22) },function(data){
			data=data.replace("../","")
			var send_str="[img:"+data+":]";
			$('#msger').val(send_str);
			welive_send();
			loadImage(data);
			//alert(data);
		});
	}
	$img.src = url;
	
}
