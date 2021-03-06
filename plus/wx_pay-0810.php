<?php
/**
 * JS_API支付demo
 * ====================================================
 * 在微信浏览器里面打开H5网页中执行JS调起支付。接口输入输出数据格式为JSON。
 * 成功调起支付需要三个步骤：
 * 步骤1：网页授权获取用户openid
 * 步骤2：使用统一支付接口，获取prepay_id
 * 步骤3：使用jsapi调起支付
*/

	include_once("../weixin/wxpay/WxPayPubHelper/WxPayPubHelper.php");
	
	//使用jsapi接口
	$jsApi = new JsApi_pub();

	//=========步骤1：网页授权获取用户openid============
	//通过code获得openid

	include_once '../weixin/weixin.auth.php';

	$openid 		=	$_SESSION['wx_user']['openid'];
	$wxpay_name		=	$_SESSION['wxpay_name'];
	$order_no 		=	$_SESSION['order_no'];
	$price 			=	$_SESSION['price'] *100	;
	$wxpay_tel		=	$_SESSION['wxpay_tel'];
	//$price 			=	$_SESSION['price']	;


	//=========步骤2：使用统一支付接口，获取prepay_id============
	//使用统一支付接口
	$unifiedOrder = new UnifiedOrder_pub();
	
	//var_dump($unifiedOrder);
	//设置统一支付接口参数
	//设置必填参数
	//appid已填,商户无需重复填写
	//mch_id已填,商户无需重复填写
	//noncestr已填,商户无需重复填写
	//spbill_create_ip已填,商户无需重复填写
	//sign已填,商户无需重复填写
	$unifiedOrder->setParameter("openid","$openid");//商品描述
	$unifiedOrder->setParameter("body","立即支付");//商品描述
	//自定义订单号，此处仅作举例
	$timeStamp = time();
	$out_trade_no = WxPayConf_pub::APPID."$timeStamp";
	$unifiedOrder->setParameter("out_trade_no","$order_no");//商户订单号 
	$unifiedOrder->setParameter("total_fee","$price");//总金额
	$unifiedOrder->setParameter("notify_url",WxPayConf_pub::NOTIFY_URL);//通知地址 
	$unifiedOrder->setParameter("trade_type","JSAPI");//交易类型
	//非必填参数，商户可根据实际情况选填
	//$unifiedOrder->setParameter("sub_mch_id","XXXX");//子商户号  
	//$unifiedOrder->setParameter("device_info","XXXX");//设备号 
	//$unifiedOrder->setParameter("attach","XXXX");//附加数据 
	//$unifiedOrder->setParameter("time_start","XXXX");//交易起始时间
	//$unifiedOrder->setParameter("time_expire","XXXX");//交易结束时间 
	//$unifiedOrder->setParameter("goods_tag","XXXX");//商品标记 
	//$unifiedOrder->setParameter("openid","XXXX");//用户标识
	//$unifiedOrder->setParameter("product_id","XXXX");//商品ID


	$prepay_id = $unifiedOrder->getPrepayId();
	
	//echo 'prepay_id:';
	//var_dump($prepay_id);
		
	//=========步骤3：使用jsapi调起支付============
	$jsApi->setPrepayId($prepay_id);

	$jsApiParameters = $jsApi->getParameters();
	//echo $jsApiParameters;

?>

<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <title>微信安全支付</title>
	<meta name="keywords" content="美啦啦，整形美容" />
	<meta name="description" content="美啦啦，整形美容" />
	<meta name="format-detection" content="telephone=no" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
	<link rel="stylesheet" href="../css/common.css" type="text/css" media="all">
	<script type="text/javascript">

		//调用微信JS api 支付
		function jsApiCall(){
			WeixinJSBridge.invoke('getBrandWCPayRequest',<?php echo $jsApiParameters;?>,function(res){
					WeixinJSBridge.log(res.err_msg);
					//alert(res.err_code+'sss'+res.err_desc+res.err_msg);
					if(res.err_msg == "get_brand_wcpay_request:ok"){
                   //alert(res.err_code+res.err_desc+res.err_msg);
                       window.location.href="../plus/update_order.php";
                   }else{
                       //返回跳转到订单详情页面
                       alert(支付失败);
                                                
                   }					
				});
		}

		function callpay()
		{
			if (typeof WeixinJSBridge == "undefined"){
			    if( document.addEventListener ){
			        document.addEventListener('WeixinJSBridgeReady', jsApiCall, false);
			    }else if (document.attachEvent){
			        document.attachEvent('WeixinJSBridgeReady', jsApiCall); 
			        document.attachEvent('onWeixinJSBridgeReady', jsApiCall);
			    }
			}else{
			    jsApiCall();
			}
		}
	</script>
</head>
<body>
	<header class="header header_wxpay">
		<div class="header_back"><a href="javascript:window.history.go(-1)"></a></div>
	    <h2>微信支付</h2>
	    <div class="header_right" onclick="window.location.href='/'">首页</div>
	</header>
	<div class="wxpay">
		<p class="wxpay_li">
			<span class="left">名称：</span><span class="span right"><?php echo $wxpay_name ?></span>
		</p>
		<p class="wxpay_li">
			<span class="left">订单号：</span><span class="span right"><?php echo $order_no ?></span>
		</p>
		<p class="wxpay_li">
			<span class="left">金额：</span><span class="span right">￥<?php echo $_SESSION['price'] ?></span>
		</p>		
		<p class="wxpay_li">
			<span class="left">手机号：</span><span class="span right"><?php echo $wxpay_tel ?></span>
		</p>	
	</div>
	<div class="wxpay_notice" style=" " ><!--支付完成，请联系美啦啦客服，提醒客服及时审核--> <br><br></div>
	<div align="center" class="wxpay_button">
		<button style="" type="button" onClick="callpay()" >立即支付</button>
	</div>
	
</body>
</html>