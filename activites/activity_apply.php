<?php
	require_once '../inc/common.php';    
	$aid = isset($_REQUEST['aid']) ? (int)$_REQUEST['aid'] : 0;
	if(!$aid){
		header("Location:activity.php");
		exit;	
	}
	$user = getUser('mid,name,phone,openid,sex');
	$local_url=$_SERVER['PHP_SELF'];
	$is_enroll = 0;
	$enroll    = 0;
	//检查是否已报名
	$sql = "SELECT count(1) as count FROM m_atv_enroll WHERE mid = '$user[mid]' AND aid = '$aid'";
	if ($db->query($sql)->fetchColumn())
	{
		$is_enroll = 1;
	}
	else
	{
		//检查是否已经满额
		$sql = "SELECT aid FROM m_activites WHERE aid = '$aid' AND enroll >= quota";
		if ($db->query($sql)->fetchColumn())
		{
			$enroll = 1;
		}	
	}
	//获取用户是否支付成功
	$my_order=get_order($user['mid'],$aid);
	//print_r($my_order);


	if($_POST)
	{
		$res = array('flag'=>0);
		$time = time();
		if($_POST['phone'] && ($_POST['phone'] != $user['phone']))	
		{
			$sql = "UPDATE m_member SET phone = :phone WHERE mid = :mid";
			$param = array(':phone' => $_POST['phone'], ':mid' => $user['mid']);
			$sth = $db->prepare($sql);
			$sth->execute($param);
		}
		
		//该活动报名人数更新
		$sql = "UPDATE m_activites SET enroll = enroll+1 WHERE aid = '$aid' AND enroll < quota";
		if($db->exec($sql))
		{
			$sql = "INSERT INTO m_atv_enroll SET mid = :mid, aid = :aid, addtime = '$time'";
			$param = array(':aid' => $_POST['aid'], ':mid' => $user['mid']);
			$sth = $db->prepare($sql);
			$sth->execute($param); 	
			if($db->lastInsertId()) $res['flag'] = 1;
		}
		die(json_encode($res));	
	}

	//获取活动支付价格
	$sql = "SELECT ispay, price, name ,aimg FROM m_activites WHERE aid = '$aid'";
	$sth = $db->prepare($sql);
	$sth->execute();
	$data = $sth->fetch(PDO::FETCH_ASSOC);
	$data['order_no']='MLL'.date('Ymdhis').randStr(4);
	//print_r($data);

	//生成随机数
	function randStr($len=4) {   
		$chars='ABDEFGHJKLMNPQRSTVWXY'; // characters to build the password from   
		mt_srand((double)microtime()*1000000*getmypid()); // seed the random number generater (must be done)   
		$str='';   
		while(strlen($str)<$len)   
			$str.=substr($chars,(mt_rand()%strlen($chars)),1);   
		return $str;   
	} 

	//判断用户是否已经支付
	function get_order($mid,$aid)
	{
		global $db;	
		$sql = "SELECT *  FROM m_order WHERE pid = '$aid' AND mid='$mid' AND type= 1 order by stat desc ";
		$sth = $db->prepare($sql);
		$param = array(':aid' => $aid, ':mid' => $mid);
		$sth->execute($param);	
		return $sth->fetch(PDO::FETCH_ASSOC);
	} 			
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $data['name'] ?></title>
<meta name="keywords" content="美啦啦，整形美容" />
<meta name="description" content="美啦啦，整形美容" />
<meta name="format-detection" content="telephone=no" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<link rel="stylesheet" href="../css/common.css" type="text/css" media="all">
	<script src="../js/jquery-1.11.2.min.js" type="text/javascript"></script>
    <script src="../js/mobile.js" type="text/javascript"></script>
</head>

<body>
<!--header-->
<header class="header">
	<div class="header_back"><a href="/activites/activity.php"></a></div>
    <h2>活动报名</h2>
</header>
<!--header end-->
<!--content-->
<div class="content activity_applay mt10">

	<div class="wd activity_apply_ct" id="atv_<?php echo $aid;?>">
    	<div class="act_img"><img src="<?php echo IMG_PATH . $data['aimg'];?>" /></div>
        
        <div class="ct">
        	<div class="title mt10"><?php echo $data['name'];?></div>    


			<?php if($is_enroll):?>
		    	
		    	<?php if ($data['ispay']): ?>
		    		<?php if ($my_order['stat']==1): ?>
		    			<p class="applyed_button"><a href="javascript:" >已经支付</a></p>
		    		<?php else: ?>
		    			<div class="wd90 white mt10 pt10 pb10" style="text-align:center;">您已经报过名了，请继续支付</div>	
		    			<p class="applyed_button"><a href="../plus/go_pay.php?order_no=<?php echo $my_order['order_no'];?>" >请继续支付</a></p>
		    		<?php endif ?>    		
		    	<?php else: ?>
		    		<div class="wd90 white pt10 pb10" style="text-align:center;">您已经报过名了，无需重复报名！</div>	
		    	<?php endif; ?>    	
		    <?php elseif($enroll):?>
		    	<div class="wd90 white pt10 pb10">名额已满！</div>
		    <?php else:?>
		    	<p><span>用户：</span><?php echo $user['name'];?></p>
		    	<p><span>手机：</span><input type="text" name="phone" id="phone" value="<?php echo $user['phone'];?>" /></p>
		        <p class="applying_button"><button>提交报名</button></p>
		    <?php endif;?>
		    <?php if($is_enroll + $enroll)?>
        </div>       
    </div>   
</div>
<!--content end-->
<!--footer-->
<?php include_once ('../template/footer.php');?>
<!--footer end-->
<script>
	var form = $('.activity_applay');
	form.find('button').click(function(){
		var aid = <?php echo $aid;?>;
		var phone = form.find('#phone');
		var ispay=<?php echo $data['ispay'];?>;
		if(!phone.val())
		{
			alert('请填写手机号！');
			phone.focus();
			return false;
		}
		
		form.find('button').html('提交中...');
		form.find('button').attr("disabled",true);
		$.post('activity_apply.php', {aid:aid,phone:phone.val()}, function(res){
			if(res.flag == 1){
				hhr_customers();
				form.find('button').html('恭喜成功报名');
				if (ispay==1) {
					//alert('恭喜成功报名');
					var pid 	=	<?php echo $aid; ?>;
					var price 	=	<?php echo $data['price'];?>;
					var mid 	=	<?php echo $user['mid'];?>;
					var order_no= 	"<?php echo $data['order_no'];?>";
					var tel 	=   $("#phone").val();
					var order_name= "<?php echo $data['name'] ?>";
					$.post('../plus/place_order.php', {type:1,pid:pid,price:price,mid:mid,order_no:order_no,tel:tel,order_name:order_name}, function(res2){
						if(res2.flag == 1){							
							window.location.href='../plus/wx_pay.php';
							alert('正在跳转')
						}else{
							alert('跳转出错');	
						}

					},'json');							
				}else{
					delay_alert('恭喜成功报名','activity_detail.php?aid=<?php echo $aid;?>');
				}
				//window.location.href='activity_detail.php?aid=<?php echo $aid;?>';
					
			}else {
				alert('报名失败，请再次尝试！');	
				form.find('button').html('确认报名');	
				form.find('button').attr("disabled",false);
			}
		},'json');
	});

	$("#go_pay").click(function(){
		var pid 	=	<?php echo $aid; ?>;
		var price 	=	<?php echo $data['price'];?>;
		var mid 	=	<?php echo $user['mid'];?>;
		var order_no= 	"<?php echo $data['order_no'];?>";
		var tel 	=   "<?php echo $user['phone'];?>";
		var order_name= "<?php echo $data['name'] ?>";
		//alert(order_no);
		$.post('../plus/place_order.php', {type:1,pid:pid,price:price,mid:mid,order_no:order_no,tel:tel,order_name:order_name}, function(res){
			if(res.flag == 1){
				alert('正在跳转');
				window.location.href='../plus/wx_pay.php';
			}else{
				alert('页面出错');	
			}

		},'json');		
	});

	function hhr_customers()
	{
		var openid 	="<?php echo $user['openid'] ?>";
		var realname = "<?php echo $user['name'];?>";
		var mobile 	= $('#phone').val();
		var sex 	= "<?php echo $user['sex'] ?>";	
		var back_url= "<?php echo $local_url ?>"
		$.post('../plus/hhr_customers.php',{openid:openid,realname:realname,sex:sex,mobile:mobile,back_url:back_url}, function(res){
			if(res.flag)
			{
				//delay_alert('修改成功','person.php');
				return 1;
			}
			else 
			{
				//alert('修改失败');	
				return 0;
			}
		}, 'json');
	}

</script>
</body>
</html>
