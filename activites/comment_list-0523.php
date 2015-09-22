<?php
	require_once '../inc/common.php';
	//$typeid = isset($_GET['typeid']) ? (int)$_GET['typeid'] : 0;
	$aid = isset($_GET['aid']) ? (int)$_GET['aid'] : 0;
	if(!$aid) {
		header("location:activity.php");
		exit;
	}
	$sql = "SELECT c.cid, c.imgs, c.content, c.ctime, m.headimgurl, m.name
			FROM m_comment as c LEFT JOIN m_member as m ON c.mid = m.mid 
			WHERE c.type = '1' AND c.pid = :aid ORDER BY c.cid DESC";
	
	$sth = $db->prepare($sql);
	$sth->bindParam(':aid', $aid);
	$sth->execute();
	$comments = $sth->fetchAll(PDO::FETCH_ASSOC);
	
	
	$sql = "SELECT aid,name,aimg FROM m_activites WHERE aid = :aid";
	$sth = $db->prepare($sql);
	$sth->bindParam(':aid', $aid);
	$sth->execute();
	$data = $sth->fetch(PDO::FETCH_ASSOC);
	
	//赞，收藏，评价
	$sql = "SELECT count(1) as count FROM m_house_zan WHERE `pid` = :gid AND `type` = '1' AND `acttype` = '0'";
	$sth = $db->prepare($sql);
	$sth->bindParam(':gid', $gid);
	$sth->execute();
	$house = $sth->rowCount();
	

	$sql = "SELECT count(1) as count FROM m_house_zan WHERE pid = :gid AND type = '1' AND acttype = '1'";
	$sth = $db->prepare($sql);
	$sth->bindParam(':gid', $gid);
	$sth->execute();
	$zan = $sth->rowCount();
	
	$sql = "SELECT count(1) as count FROM m_comment WHERE pid = :gid AND type = '1'";
	$sth = $db->prepare($sql);
	$sth->bindParam(':gid', $gid);
	$sth->execute();
	$comment = $sth->rowCount();
	
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
	<div class="header_back"><a href="#"></a></div>
    <h2>活动详情</h2>
</header>
<!--header end-->
<!--content-->
<div class="content mb10">
	<div class="act_dtl_top  mb10">
        <button class="button1" onclick="window.location.href='activity_detail.php?aid=<?php echo $data['aid'];?>'">活动介绍</button>
        <button class="button2 on ">活动评价</button>
    </div>
    <div class="act_dtl_ct">
    	<div class="act_img"><img src="<?php echo IMG_PATH . $data['aimg'];?>" /></div>
        <div class="ct">
        	<div class="title mt10"><?php echo $data['name'];?></div>
            <div class="operate">
                <a href="java:" class="comment">评论<b><?php echo number_format($comment);?></b></a>
                <a href="java:" class="collect">收藏<b><?php echo number_format($house);?></b></a>
                <a href="java:" class="best">赞<b><?php echo number_format($zan);?></b></a>
            </div>
        </div>
    </div>
    <div class="act_dtl_ct border mt10 act_comment">
		<ul>
        	<?php if($comments):?>
        	<?php foreach($comments as $v):;?>
        	<li class="detail_comment mt10 mb10">
            	<div class="person">
                    <div class="person_img"><img src="<?php echo $v['headimgurl'];?>" /></div>
                    <div class="person_name"><?php echo $v['name'];?></div>
                    <div class="time"><?php date('Y-m-d H:i:s', $v['ctime']);?></div>
                    <!--精华<div class="best"></div>-->
                </div>
                <div class="cm">
                	<div class="cm_img">
                    	<?php $imgs =  explode('|', $v['imgs']);?>
                        <?php foreach($imgs as $v):;?>
                        <img src="<?php echo IMG_PATH . $v;?>" width="50" />
                        <?php endforeach;?>
                    </div>
                    <div class="cm_ct">
                		<?php echo $v['content'];?>
                	</div>
                </div>
                <div class="cl"></div>
            </li>
           <?php endforeach;?> 
           <?php else:?>
           	<div>该活动暂无评论，快抢沙发！</div>
            <?php endif;?>
        </ul>    
    </div>
  <div class="comment_button"><button onclick="window.location.href='act_comment.php?aid=<?php echo $aid;?>'">我要评论</button>
  </div>
</div>

<!--content end-->
<!--footer-->
<?php include_once ('../template/footer.php');?>
<!--footer end-->
</body>
</html>
