<?php
//require("include/conn.php");
//$url="123.htm"; //Ҫ�ɼ��ĵ�ַ953501
$url="http://www6.53kf.com/m.php?cid=72035610&arg=10035610&style=1";
//$ft["a"]["begin"]='<a'; //��ȡ�Ŀ�ʼ��<br />
//$ft["a"]["end"]='>'; //��ȡ�Ľ�����
//$rs=pick($url,$ft,$th); //��ʼ�ɼ�
//print_r($rs["a"]);
$rs=fetch_urlpage_contents($url);
$rs=get_replace($rs);
print_r($rs);



/*
//��ȡ�˿�����
$myid=get_str_content('myid = "','";',$rs);
$myinfo=get_guest($myid);

$obj_id=get_str_content('obj_id =',';',$rs);//��ȡ�ͷ�����


//��ȡ��������¼
$last_msg=get_row('wgid,fromid,toid,msg,time','zx_msg',' wgid='.$myid.' order by time desc');
if(empty($last_msg)){
    $last_msg['time']=0;
}


//��ȡ�����¼
$ft["a"]["begin"]='<li class="msg-time" name="msg-time">'; 
$ft["a"]["end"]='<\/p><\/li>'; 
$rsc=pick($url,$ft); 
if(!empty($rsc)){
    //print_r($rsc);
    if(!empty($rsc["a"])){
        $all_msg=$rsc["a"][1];
        //print_r($all_msg);
    
        foreach($all_msg as $k => $v){
            $sub_arr_time=get_str_content('','<\/li>',$v);
            //echo $k.'=>'.$sub_arr_time.'<br>';
            $time=str2time($sub_arr_time);
            if($time>$last_msg['time']){
                $sub_arr_msg=get_str_content('<p><i><\/i>','',$v);
                echo  $sub_arr_msg;
                if(strstr($v,'msg-visitor')){
                    $fromid=$myid;
                    $toid=$obj_id;
                }else{
                    $fromid=$obj_id;
                    $toid=$myid;
                }
                $result=get_insert('zx_msg','type,fromid,fromname,toid,toname,msg,time,wgid',"1,'$fromid','','$toid','','$sub_arr_msg',$time,'$myid'");
            }    
            //str2time($sub_arr_time);
            //print_r($sub_arr_time);
        }
    }
}
*/






//��ȡ����
function get_str_content($start,$end,$rs){
   $p='/'.$start.'(.*)'.$end.'/'; 
   preg_match($p,$rs,$return_arr); 
   return $return_arr[1];
}


//ת����ʱ���
function str2time($str){
    
    $year=date('Y',time());//ȡ�����
    $month=((int)substr($str,0,2));//ȡ���·�   
    $day=((int)substr($str,4,2));//ȡ�ü���    
    $hour=((int)substr($str,10,2));//ȡ��Сʱ
    $min=((int)substr($str,13,2));//ȡ�÷���
    //echo $month.'|'.$day.'|'.$hour.'|'.$min.'|';
    $time=mktime($hour,$min,0,$month,$day,$year);
    //echo $time;
    return $time;
}



//�жϿͻ��Ƿ����,����ȡ�û�����
function get_guest($myid){
    $myinfo=get_row('gid','zx_guest','wgid='.$myid);
    $ip= GetIP();
    $browser=get_userAgent($_SERVER['HTTP_USER_AGENT']);
    $time=time();    
    if(empty($myinfo)){
        $result=get_insert('zx_guest','wgid,logins,last,lastip,browser',"'$myid',1,$time,'$ip','$browser'");
        
    }else{
        $result=get_update('zx_guest',"last=$time,lastip='$ip',browser='$browser',logins=logins+1",'wgid='.$myid);
    }
    $myinfo=get_row('*','zx_guest','wgid='.$myid);    
    return $myinfo;  
}

//��ȡĿ���ļ�
function fetch_urlpage_contents($url){
    $c=file_get_contents($url);
    return $c;
}

//��ȡƥ������
function fetch_match_contents($begin,$end,$c)
{
    $begin=empty($begin)?'':change_match_string($begin);
    $end=empty($end)?'':change_match_string($end);

    $p = "#{$begin}(.*){$end}#iU";//i��ʾ���Դ�Сд��U��ֹ̰��ƥ��
    
    
    if(preg_match_all($p,$c,$rs))
    {
        return $rs;
    }
    else { return "";}
}//ת��������ʽ�ַ���

function change_match_string($str){
//ע�⣬����ֻ�Ǽ�ת��
    $old=array("/","$",'?');
    $new=array("/","$",'?');
    $str=str_replace($old,$new,$str);
    return $str;
}

//�ɼ���ҳ
function pick($url,$ft)
{
    $c=fetch_urlpage_contents($url);
    foreach($ft as $key => $value)
    {
        $rs[$key]=fetch_match_contents($value["begin"],$value["end"],$c);
    }
return $rs;
}

//�滻�ַ���
function get_replace($rs){
    $rs=str_replace('<a href="http://www.53kf.com/">53KF.com</a>','taom.com.cn',$rs);
    $rs=str_replace('</head>','<link href="style/mobile_new.css" rel="stylesheet" /><script src="style/jquery.js" type="text/javascript"></script><script src="style/mobile_new.js" type="text/javascript"></script></head>',$rs);
    $rs=str_replace('"mobile/','"http://www6.53kf.com/mobile/',$rs);
    $rs=str_replace('"min/','"http://www6.53kf.com/min/',$rs);
    $rs=str_replace('"img/','"http://www6.53kf.com/img/',$rs);
    $rs=str_replace('"js/','"http://www6.53kf.com/js/',$rs);
    $rs=str_replace('"include/','"http://www6.53kf.com/include/',$rs);
    $rs=str_replace('"impl/','"http://www6.53kf.com/impl/',$rs);
    $rs=str_replace('class="comm-btn-focus btn-send"','class="comm-btn-focus btn-send mbtn_send" onclick="mbt_send();"',$rs);
    $rs=str_replace('"img/face','"img/faces',$rs);
    return $rs;
}

?>