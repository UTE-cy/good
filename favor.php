<?php
	session_start();
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept, Connection, User-Agent, Cookie');
	header('Access-Control-Allow-Methods: GET, POST, PUT');
	header('Content-type:text/html;charset=gb2312');
	
	error_reporting(0);
	require_once 'utils.php';
	
	$client_id  = $_COOKIE['PHPSESSID'];
	$session_id =  session_id();
	//点赞json文件
	$favor_json = "http://$_SERVER[HTTP_HOST]/military/json/favor.json";
	
	//1.如果不提交点赞数，则直接返回点赞数给客户端
	if(empty($_POST['favor_flag'])){
		//读取json文件，返回点赞数
		$content = file_get_contents($favor_json);
		$json_arr = json_decode($content,true);
		$favor_count = $json_arr['favor_count'];
		$output = array('status'=>'200','error_code'=>'0','msg'=>'请求成功!','favor_count'=>$favor_count);
		exit(jsonFormat($output)); 
	}else{
		$favor_flag = $_POST['favor_flag'] ? $_POST['favor_flag'] : 0;
		//接收到用户的点赞请求
		if($favor_flag == 1){
			//判断一下用户是否已点赞
			//未点赞
			if(isset($_COOKIE[$session_id])){
				//存在，已点赞
				$content = file_get_contents($favor_json);
				$json_arr = json_decode($content,true);
				$favor_count = $json_arr['favor_count'];
				$output = array('status'=>'200','error_code'=>'201','msg'=>'已点赞!','favor_count'=>$favor_count);
				exit(jsonFormat($output)); 
			}else{
				//未存在，未点赞
				setCookie($session_id,$favor_flag,time()+60*60*24*365);
				$content = file_get_contents($favor_json);
				$json_arr = json_decode($content,true);
				$json_arr['favor_count'] = $json_arr['favor_count'] + 1;
				//点赞数+1
				$dest_file = $_SERVER["DOCUMENT_ROOT"].'military/json/favor.json';
				$result = file_put_contents($dest_file, jsonFormat($json_arr));
				if($result){
					$output = array('status'=>'200','error_code'=>'203','msg'=>'点赞成功!','favor_count'=>$json_arr['favor_count']);
					exit(jsonFormat($output)); 
				}else{
					$json_arr['favor_count'] = $json_arr['favor_count']-1;
					$output = array('status'=>'400','error_code'=>'401','msg'=>'点赞失败!','favor_count'=>$json_arr['favor_count']);
					exit(jsonFormat($output));
			}
			}
		}else{
			//读取json文件，返回点赞数
			$content = file_get_contents($favor_json);
			$json_arr = json_decode($content,true);
			$favor_count = $json_arr['favor_count'];
			$output = array('status'=>'200','error_code'=>'202','msg'=>'请求成功','favor_count'=>$favor_count);
			exit(jsonFormat($output)); 
		}
	}
	