<?php
error_reporting(0);
	
	/**************************************************************
 *
*  使用特定function对数组中所有元素做处理
*  @param  string  &$array     要处理的字符串
*  @param  string  $function   要执行的函数
*  @return boolean $apply_to_keys_also     是否也应用到key上
*  @access public
*
*************************************************************/
function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
{
	static $recursive_counter = 0;
	if (++$recursive_counter > 1000) {
		die('possible deep recursion attack');
	}
	foreach ($array as $key => $value) {
		if (is_array($value)) {
			arrayRecursive($array[$key], $function, $apply_to_keys_also);
		} else {
			$array[$key] = $function($value);
		}		 
		if ($apply_to_keys_also && is_string($key)) {
			$new_key = $function($key);
			if ($new_key != $key) {
				$array[$new_key] = $array[$key];
				unset($array[$key]);
			}			
		}
	}
	$recursive_counter--;
}
 
/**************************************************************
*
*  将数组转换为JSON字符串（兼容中文）
*  @param  array   $array      要转换的数组
*  @return string      转换得到的json字符串
*  @access public
*
*************************************************************/

function JSON($array) {
	arrayRecursive($array, 'urlencode', true);
	$json = json_encode($array);
	return urldecode($json);
}
/**
*手机号验证
 * @param string $mobile 需要验证的手机号
**/
function verifyMobile($mobile){
	$mobile = trim($mobile);
	//print_r($mobile);exit;
	if(strlen($mobile)==11){
		if(preg_match('/^(1(([358][0-9])|[47][01256789]))\d{8}$/', $mobile)){
			return 1;//匹配成功
		}else{
			return -1;//匹配失败
		}
	}else{
		return -2;//手机号长度不符
	}	
}

/** Json数据格式化 
* @param  Mixed  $data   数据 
* @param  String $indent 缩进字符，默认4个空格 
* @return JSON 
*/  
function jsonFormat($data, $indent=null){  
  
    // 对数组中每个元素递归进行urlencode操作，保护中文字符  
    array_walk_recursive($data, 'jsonFormatProtect');  
  
    // json encode  
    $data = json_encode($data);  
  
    // 将urlencode的内容进行urldecode  
    $data = urldecode($data);  
  
    // 缩进处理  
    $ret = '';  
    $pos = 0;  
    $length = strlen($data);  
    $indent = isset($indent)? $indent : '    ';  
    $newline = "\n";  
    $prevchar = '';  
    $outofquotes = true;  
  
    for($i=0; $i<=$length; $i++){  
  
        $char = substr($data, $i, 1);  
  
        if($char=='"' && $prevchar!='\\'){  
            $outofquotes = !$outofquotes;  
        }elseif(($char=='}' || $char==']') && $outofquotes){  
            $ret .= $newline;  
            $pos --;  
            for($j=0; $j<$pos; $j++){  
                $ret .= $indent;  
            }  
        }  
  
        $ret .= $char;  
          
        if(($char==',' || $char=='{' || $char=='[') && $outofquotes){  
            $ret .= $newline;  
            if($char=='{' || $char=='['){  
                $pos ++;  
            }  
  
            for($j=0; $j<$pos; $j++){  
                $ret .= $indent;  
            }  
        }  
  
        $prevchar = $char;  
    }  
  
    return $ret;  
}  
  
/** 将数组元素进行urlencode 
* @param String $val 
*/  
function jsonFormatProtect(&$val){  
    if($val!==true && $val!==false && $val!==null){  
        $val = urlencode($val);  
    }  
}  

/** 获取访问地址 
* return $ip 
*/ 
function get_clientip(){
	if ($HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"])  
	{  
		$ip = $HTTP_SERVER_VARS["HTTP_X_FORWARDED_FOR"];  
	}  
	elseif ($HTTP_SERVER_VARS["HTTP_CLIENT_IP"])  
	{  
		$ip = $HTTP_SERVER_VARS["HTTP_CLIENT_IP"];  
	}  
	elseif ($HTTP_SERVER_VARS["REMOTE_ADDR"])  
	{  
		$ip = $HTTP_SERVER_VARS["REMOTE_ADDR"];  
	}  
	elseif (getenv("HTTP_X_FORWARDED_FOR"))  
	{  
		$ip = getenv("HTTP_X_FORWARDED_FOR");  
	}  
	elseif (getenv("HTTP_CLIENT_IP"))  
	{  
		$ip = getenv("HTTP_CLIENT_IP");  
	}  
	elseif (getenv("REMOTE_ADDR"))  
	{  
		$ip = getenv("REMOTE_ADDR");  
	}  
	else  
	{  
		$ip = "Unknown";  
	}  
	return $ip;
}

?>