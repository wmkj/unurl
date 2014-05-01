<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<!-- 
版本：V0.1
无名科技博客：http://blog.wmkjlm.com
演示网址：http://un.wmkjlm.com
反馈邮箱：i@wmkjlm.com
-->
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>短网址还原 - 短网址分析 - 短网址真实网址</title>
<meta name="copyright" content="Blog.wmkjlm.com" />
<meta name="keywords" content="短网址程序 短网址原理 短网址源码" />
<meta name="description" content="一款在线还原绝大多数短网址的程序。" />
<link rel="stylesheet" type="text/css" href="./css.css" />
</head>
<body>
<?php

error_reporting(E_ALL^E_NOTICE^E_WARNING);

////////////信息输出，可通过CSS控制输出样式，START/////////////
    $info[1] = '亲！不是短网址或暂不支持还原。';
    $info[2] = '真实网址为：<br/><br/>';
    $info[3] = '↓<br/><a href="';
    $info[4] = '" target="_blank">';
	$info[5] = '</a><br/>';
    $info[6] = '亲！不是短网址或已失效。';
    $info[7] = '<a href="';	
////////////////////////信息输出，END/////////////////////////	

/////////////////////主函数，勿修改，START///////////////////	
function unshort($url)
{
global $info;	 
    $UrlHeader = (get_headers($url,1));	   
    $UnurlHeader1 = $UrlHeader[Location];
	$UnurlHeader2 = $UrlHeader[location];
	if (!empty($UnurlHeader1)){
	$unurl = $UnurlHeader1;
	}
	else {
	$unurl = $UnurlHeader2;
	}
    if ($unurl==''){
        echo $info[6];
        }
	else {
	   if(is_array($unurl)) {
	     $count_url = count($unurl);
         if ($count_url>10) {          //一次性还原的至多跳转数
           $count_url = 10;            //防止死循环
		   }	
		 for ($i=0;$i<$count_url;$i++){
		   echo $info[3].$unurl[$i].$info[4].$unurl[$i].$info[5];
		   }	
		 }
       else {				  
         $unurl = $info[7].$unurl.$info[4].$unurl.$info[5];				  
         echo $unurl;			   
	    }
       }	    
}

function adfunshort($url)
{
  global $info;
  $c = file_get_contents($url);
  list($version,$status_code,$msg) = explode(' ',$http_response_header[0], 3);
  if ($status_code == '302') {
  $unurl = unshort($url);
  }
  else{
  $p = "/url = '(.*)';/isU";
  preg_match($p, $c, $content);
  $unurl = $content[1];  
  $html = file_get_contents($unurl);  
  list($version,$status_code,$msg) = explode(' ',$http_response_header[0], 3);
    if ($status_code == '200') {
     $d = file_get_contents($unurl);
     $f = "/0; URL=(.*)\">/isU";
     preg_match($f, $d, $content2);
     $unurl = $content2[1];
      if ($unurl==''){
       echo $info[6];
      }
      else{
      $unurl = $info[7].$unurl.$info[4].$unurl.$info[5];
        echo $unurl;  
      }
    }   
    else {
     $unurl = unshort($unurl);
    }
  }
}
/////////////////////主函数，END///////////////////////////
?>

<!---HTML页面，START-->

<div id="all">
<div id="logo">短网址还原 - 无名科技</div>

<div id="form">
  <form action="" method="POST">
  短网址：<input type="text" name="turl" class="inurl" size="26" />
  <input type="hidden" name="url_done" value="done" />
  <input type="submit" value="还原" class="suburl" />
  </form>
</div>

<div id="trurl">

<?php
//预处理开始
$turl = trim($_POST['turl']);
$url_done= $_POST['url_done'];
global $info; 	 
if ($url_done == 'done'){
$http = substr($turl,0,7);
   if ($http != 'http://' && $http != 'https:/'){
     $turl = 'http://'.$turl;
	 	 }
   $tturl = substr($turl,0,11);
   if ($tturl == 'http://adf.'){
      echo $info[2];
	  $longurl = adfunshort($turl);
	  }
   else {
      echo $info[2];
      $longurl = unshort($turl); 
      }
	 } 
//预处理结束
?>
</div>

<div id="surported">支持还原goo.gl、adf.ly、t.co、t.cn等国内外300多种短网址。</div>
<div id="hr"><hr></div>
<div id="powered">&copy; 2012-2013 无名科技 <a href="https://github.com/wmkj/unurl" target="_blank">项目开源地址</a> <a href="http://baid.ws" target="_blank">短网址生成</a> <a href="http://blog.wmkjlm.com" target="_blank">无名科技博客</a></div>

<!--HTML，END-->

</body>
</html>
