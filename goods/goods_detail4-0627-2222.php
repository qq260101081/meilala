<?php
	require_once '../inc/common.php';
	$gid = isset($_GET['gid']) ? (int)$_GET['gid'] : 0;
	if(!$gid) {
		header("Location:goods.php");
		exit;
	}
	$sql = "SELECT c.cid, c.imgs, c.content, c.ctime, c.nice, m.headimgurl, m.name
			FROM m_comment as c LEFT JOIN m_member as m ON c.mid = m.mid 
			WHERE c.type = '0' AND c.pid = '$gid' ORDER BY c.cid DESC";
	
	$res = $db->query($sql);
	$res->setFetchMode(PDO::FETCH_ASSOC);
	$data = $res->fetchAll();
	//print_r($data);die;
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>项目详情</title>
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
	<div class="header_back"><a href="/goods/goods.php"></a></div>
    <h2>项目详情</h2>
</header>
<!--header end-->
<!--content-->
<div class="content">
	<?php include_once ('../template/item_detail_top_new.php');?> 
    <div class="detail mt10 mb10">
		<ul class="detail_ul">
        	<?php if($data):;?>
        	<?php foreach($data as $v):;?>
            <?php $v['headimgurl']=empty($v['headimgurl'])?'../images/touxiang.png':$v['headimgurl']; ?>
        	<li class="detail_comment pt10 pb10 white">
            	<div class="person">
                    <div class="person_img"><img src="<?php echo $v['headimgurl'];?>" /></div>
                    <div class="person_name"><?php echo $v['name'];?></div>
                    <div class="time"><?php date('Y-m-d H:i:s', $v['ctime']);?></div>
                    <?php if($v['nice']):?><div class="best"></div><?php endif;?>
                </div>
                <div class="cm">
                	<div class="cm_img">
                    	<?php $imgs =  explode('|', $v['imgs']);?>
                        <?php foreach($imgs as $vv):;?>
                        <?php if(!empty($vv)) :?>
                        <div class="img" style="background-image:url(../plus/get_img.php?i=<?php echo '../uploads/image/'. $vv;?>&w=180);" onclick="look_comment_img(<?php echo $v['cid']; ?>);">
                        	
                        </div>
						<?php endif; ?>
                        <?php endforeach;?>
                    </div>
                    <div class="cl"></div>
                    <div class="cm_ct">
                		<?php echo $v['content'];?>
                	</div>
                </div>
                <div class="cl"></div>
            </li>
           <?php endforeach;?> 
           <?php else:?>
           <div class="detail_comment pt10 pb10 white">该项目暂无评论，快抢沙发！</div>
           <?php endif;?>	
        </ul>    
    </div>
    <div class="comment_button"><button onclick="window.location.href='act_comment.php?gid=<?php echo $gid;?>'">我要评论</button>
  </div>
</div>
<!--content end-->
<!--footer-->
<?php include_once ('../template/footer.php');?>
<!--footer end-->
<script type="text/javascript"> 
window.onscroll = function(){ 
	var h=$(".header").height();
	var h1=$(".detail_top").height();
    var t = document.documentElement.scrollTop || document.body.scrollTop;  
	if(t<=h){
		$('.detail_top').css({"position":"relative","margin":"0.1rem"});
	}
    else { 
		$('.detail_top').css({"position":"fixed","width":"100%","top":"0px","max-width":"640px","margin-top":"0px","margin-left":"auto","margin-right":"auto"});
    }
} 

</script> 
</body>
</html>
