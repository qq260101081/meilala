<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?=$title?></title>
<meta name="keywords" content="" />
<meta name="description" content="" />
<meta name="format-detection" content="telephone=no" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<link rel="stylesheet" href="./css/app.css" type="text/css" media="all">
<link rel="stylesheet" href="./css/style.css" type="text/css" media="all">
<script src="./js/jquery-1.11.2.min.js" type="text/javascript"></script><!--
<script src="./js/alert.js" type="text/javascript"></script>-->
<script src="./js/mobile.js" type="text/javascript"></script>
</head>
<body>
	<section class="st1"></section>	
	<section class="st3"><img src="./images/egg_img2.png" alt="" /></section> 		
	<section class="st5 st7">
		<div class="st5_ct1 st7_ct1" id="egg_area" >
			<img src="./images/egg.png" alt="" id="egg_img" />
			<img src="./images/egg_img6.png" alt="" id="cuizi" />
		</div>			
	</section>	
	<section class="st2 st8"><div class="st1_ct4"></div></section>
	<section class="st9">
		<div class="st9_ct1">
			<div class="st9_ct1_p1">砸蛋结果动态</div>
			<div class="st9_ct1_p2">
				<div class="st9_ct1_p2_li">
					<div class="st9_ct1_p2_li_c1"><img src="./images/touxiang.png" alt="" /></div> 
					<div class="st9_ct1_p2_li_c2 text_overflow">煤老板煤老板煤老板煤老板</div>                          
					<div class="st9_ct1_p2_li_c3">10元</div> 
					<div class="st9_ct1_p2_li_c4">2015-08-04 13:24</div> 
				</div>	
				<div class="st9_ct1_p2_li">
					<div class="st9_ct1_p2_li_c1"><img src="./images/touxiang.png" alt="" /></div> 
					<div class="st9_ct1_p2_li_c2 text_overflow">煤老板</div>                          
					<div class="st9_ct1_p2_li_c3">10元</div> 
					<div class="st9_ct1_p2_li_c4">2015-08-04 13:24</div> 
				</div>	
				<div class="st9_ct1_p2_li">
					<div class="st9_ct1_p2_li_c1"><img src="./images/touxiang.png" alt="" /></div> 
					<div class="st9_ct1_p2_li_c2 text_overflow">煤老板</div>                          
					<div class="st9_ct1_p2_li_c3">10元</div> 
					<div class="st9_ct1_p2_li_c4">2015-08-04 13:24</div> 
				</div>	
				<div class="st9_ct1_p2_li">
					<div class="st9_ct1_p2_li_c1"><img src="./images/touxiang.png" alt="" /></div> 
					<div class="st9_ct1_p2_li_c2 text_overflow">煤老板</div>                          
					<div class="st9_ct1_p2_li_c3">10元</div> 
					<div class="st9_ct1_p2_li_c4">2015-08-04 13:24</div> 
				</div>	
				<div class="st9_ct1_p2_li">
					<div class="st9_ct1_p2_li_c1"><img src="./images/touxiang.png" alt="" /></div> 
					<div class="st9_ct1_p2_li_c2 text_overflow">煤老板</div>                          
					<div class="st9_ct1_p2_li_c3">10元</div> 
					<div class="st9_ct1_p2_li_c4">2015-08-04 13:24</div> 
				</div>					
			</div>			
		</div>
		<div class="st9_ct2">
			<div class="st9_ct2_p1">活动规则：</div>
			<div class="st9_ct2_p2">
			<p>
				1、通过美啦啦公众号提示消息进入砸现金蛋页面。</p><p>			
				2、每砸蛋一次，都可砸出现金，最高1000元，现金自动存入您的微信零钱。</p><p>
				3、邀请朋友一起参与砸现金蛋活动，可获取一次砸蛋机会。</p><p>
				4、每人砸蛋机会共10次，数量有限，砸完即止。</p><p>
				5、本次活动最终解释权归美啦啦所有。</p>
			</div>
		</div>		
	</section>
<script type="text/javascript">
	var w=$(window).width();
    $('.st1').css({'height':w/640*233+'px'});

    $("#egg_area").click(function  () {
    	empty_num();
    })
    function go (money) {
    	var cuizi_t=$("#cuizi").position().top;
    	var cuizi_l=$("#cuizi").position().left;
    	//alert(cuizi_l);alert(cuizi_t);
    	$("#cuizi").css({"top":cuizi_t,"left":cuizi_l,"position":""});
    	$('.st7_ct1').attr('id','egg_area_end');
		$("#cuizi").animate({
			"top":cuizi_t  +20,
			"left":cuizi_l -40,
			},1000,function(){
				$("#cuizi").hide();
				$(".st7").addClass("st10");  	
		    	$("#egg_img").attr("src","./images/egg_2.png");	
		    	var html=	'<div class="money">恭喜你砸出<br><i>'+money+'元</i></div>'+
							'<div class="st1_ct3 re_go"><button>再砸一次</button></div>';				
		    	$(".st10 .st7_ct1").append(html);
		    	$(".money").css({"opacity":"0","fontSize":"0","top":"0"});
		    	$(".money").animate({"opacity":"1.0","fontSize":"1.3rem","top":"-3rem"},1000);
			}
		);  

    }
    function empty_num() {

        
            var cuizi_t=$("#cuizi").position().top;
            var cuizi_l=$("#cuizi").position().left;
            //alert(cuizi_l);alert(cuizi_t);
            $("#cuizi").css({"top":cuizi_t,"left":cuizi_l});
            $('.st7_ct1').attr('id','egg_area_end');
            $("#cuizi").animate({
                "top":cuizi_t  +20,
                "left":cuizi_l -40,
                },100,function(){
                    $("#cuizi").hide();
                    $(".st7").addClass("st10");     
                    $("#egg_img").attr("src","./images/egg_2.png"); 
                    var html=   '<div class="money">砸蛋机会用完啦~<br>分享朋友圈可获取砸蛋机会！</div>'+
                                '<div class="st1_ct3 re_go"><button onclick="share();">分享给好友</button></div>';               
                    $(".st10 .st7_ct1").append(html);
                    $(".money").css({"opacity":"0","fontSize":"0","top":"0"});
                    $(".money").animate({"opacity":"1.0","fontSize":"1.3rem","top":"-3rem"},1000);
                    
                }
            );
            window.location.href="share.php";
                 
    }
</script>	
</body>
</html>
