<?php
	require_once '../inc/common.php';
	require_once '../inc/uploads.php';
	$user = getUser('mid, jifen');
	$mid = $user['mid'];
	$time = TIME;
	$imgs = '';
	
	$aid = isset($_REQUEST['aid']) ? (int)$_REQUEST['aid'] : 0;
	if(!$aid) {
		header("Location: activity.php");
		die;	
	}
	
	$sql = "SELECT count(1) as count FROM m_comment WHERE `pid` = '$aid' AND `type` = '1' AND `mid` = '$mid'";
	$sth = $db->prepare($sql);
	$sth->execute();
	$firstCommon = $sth->fetchColumn();
	
	
	if($_POST){
		$res = array('msg'=>'');
		if($_FILES)
		{
			$res = uploadImg($_FILES);
			if($res['msg']) die(json_encode($res));
			if(isset($res['imgs'])) $imgs = implode('|', $res['imgs']);
		}
		$content = htmlspecialchars($_POST['content']);
		$sql = "INSERT INTO m_comment SET pid=:aid, mid='$mid',ctime='$time',type='1',imgs='$imgs',content=:content";
		$sth = $db->prepare($sql);
		$sth->bindParam(':aid', $aid);
		$sth->bindParam(':content', $content);
		$sth->execute();
		if(!$db->lastInsertId())
		{
			$res['msg'] = '评论失败！';
		}
		else
		{
			if(!$firstCommon)
			{
				$addJf = $user['jifen']+5;
				$sql = "INSERT INTO m_jifen_logs SET mid = '$mid', gtime = '$time', point = '5', income='1', remark='活动评论', count = '$addJf'";
				$sth = $db->prepare($sql);
				$sth->execute();
				if($db->lastInsertId())
				{
					$sql = "UPDATE m_member SET jifen = jifen + 5 WHERE mid = '$mid' LIMIT 1";
					$sth = $db->prepare($sql);
					$sth->execute();
				}
				
			}
			
			//同步项目表评论数
			$sql = "UPDATE m_activites SET pl = pl+1 WHERE aid = '$aid' LIMIT 1";
			$sth = $db->prepare($sql);
			$sth->execute();
		}
		die(json_encode($res));
	}
	
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>活动详情</title>
<meta name="keywords" content="美啦啦，整形美容" />
<meta name="description" content="美啦啦，整形美容" />
<meta name="format-detection" content="telephone=no" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<link rel="stylesheet" href="../css/common2.css" type="text/css" media="all">
	<script src="../js/jquery-1.11.2.min.js" type="text/javascript"></script>
    <script src="../js/mobile.js" type="text/javascript"></script>
</head>

<body>
<!--header-->
<header class="header">
	<div class="header_back"><a href="/activites/activity.php"></a></div>
    <h2>活动详情</h2>
</header>
<!--header end-->
<!--content-->
<div class="content mb10">
	<div class="act_dtl_top  mb10">
        <button class="button1 " onclick="window.location.href='activity_detail.php?aid=<?php echo $aid;?>'">活动介绍</button>
        <button class="button2 on" onclick="window.location.href='comment_list.php?aid=<?php echo $aid;?>'">活动评价</button>
    </div>
    <div class="comment_body">
    <div class=" act_comment white pt10 pb10 wd90 border mt10 mb10">
        <div class="detail">
            <form name="form1" id="uploadForm" class="form1 comment_operate wd90"  >
                <div class="comment_operate1">
                    <div class="input1"><textarea name="input1"  id="content" ></textarea></div>
                </div>
                <div class="comment_operate2">
                	<div id="img_view_list">
                    <div class="input2 mt10">
                        <input name="files[]" type="file" unat="server" accept="image/*" capture="camera" id="files"  multiple/>
                        
                        <!--<canvas id="canvas" style="display:none;"></canvas> -->
                    </div>                    
                    </div>
                    <div class="cl"></div>
                   
                </div>
                <div class="comment_operate3">
                 	<div class="submit"><button id="submit" name="submit">提交</button></div>
                </div>
                <div class="cl"></div>
            </form>
        </div>
    </div>
	</div>
</div>
<!--content end-->
<!--footer-->
<?php include_once ('../template/footer.php');?>
<!--footer end-->

<script>	
var storedFiles = [];
$("body").on("click", ".selFile", removeFile);
$("#submit").on("click", handleForm);
/*$('#submit').click(function(){
	
	$('#uploadForm').ajaxSubmit(function(responseResult){
		console.log(responseResult);
	});
});*/

function handleForm(e) {
   e.preventDefault(); 
   var data = new FormData();
   for(var i=0, len=storedFiles.length; i<len; i++) {
		data.append('files[]', storedFiles[i]); 
   }
   if(!$('#content').val()){
		alert('内容写点什么吧！');   
		return;
   }
   //禁止过快提交
   $('#submit').html('提交中...');
   $('#submit').attr("disabled",true);
   
   data.append('content', $('#content').val()); 
   data.append('aid', '<?php echo $aid;?>');
	var xhr = new XMLHttpRequest(); 
	xhr.open('POST', 'act_comment.php', true); 
	xhr.onload = function(e) { 
		if(this.status == 200) {
			var msg = $.parseJSON(e.currentTarget.responseText).msg;
			if(!msg){
				alert('评论成功！');	
				window.location.href='comment_list.php?aid=<?php echo $aid;?>';
			}else {
				alert(msg);	
				$('#submit').html('提交');
				$('#submit').attr("disabled",false);	
			}
			//console.log(e.currentTarget.responseText); 
			//alert(e.currentTarget.responseText + ' items uploaded.'); 
		} 
	} 
	//console.log(data);return;
	xhr.send(data);
} 
				
				

$("#files").change(function(){

	var allowFile = ['jpeg','gif','png'];
	//console.log(this.files);
	$.each(this.files, function(k){
		
		//console.log(this);
		
		if($.inArray(this.type.split('/')[1], allowFile))
		{
			alert('不是图片');	
		}
		else 
		{
			var objUrl = getObjectURL(this);
			if(objUrl)
			{
				storedFiles.push(this); 
				//$('#img_view_list').append('<img onclick="del('+k+')" src="'+objUrl+'">');	
				$('#img_view_list').prepend("<div><img src=\"" + objUrl + "\" data-file='"+this.name+"' class='selFile' title='Click to remove'>" + "<br clear=\"left\"/></div>"); 
			}
		}
		
	});
});
//建立一個可存取到該file的url
function getObjectURL(file) {
	var url = null ; 
	if (window.createObjectURL!=undefined) { // basic
		url = window.createObjectURL(file) ;
	} else if (window.URL!=undefined) { // mozilla(firefox)
		url = window.URL.createObjectURL(file) ;
	} else if (window.webkitURL!=undefined) { // webkit or chrome
		url = window.webkitURL.createObjectURL(file) ;
	}
	return url;
}


function removeFile(e) {
						  var file = $(this).data("file"); 
						  for(var i=0;i<storedFiles.length;i++) { 
						  	if(storedFiles[i].name === file) {
								
							  storedFiles.splice(i,1); 
							  break; 
						  	} 
						  } 
						  //console.log(storedFiles);
						  $(this).parent().remove(); 
					} 
					
					
					
$('#content').click(function(){
	var w=$(window).width();
	if(w<480){
		$('#content').css({'height':'4rem'});
		$('.comment_operate3').css({  'position': 'absolute','width':'30%','right':'0rem','top':'4rem','margin-top':'0.5rem'});
	}
	else if(w<640){
		$('#content').css({'height':'6rem'});
		$('.comment_operate3').css({  'position': 'absolute','width':'30%','right':'0rem','top':'6rem','margin-top':'0.75rem'});
	}
	else {
		$('#content').css({'height':'8rem'});
		$('.comment_operate3').css({  'position': 'absolute','width':'30%','right':'0rem','top':'8rem','margin-top':'1rem'});
	}
});


					
</script>
</body>
</html>
