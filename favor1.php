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

	$favor_json = "http://$_SERVER[HTTP_HOST]/favor.json";

	if(empty($_POST['favor_flag'])){

		$content = file_get_contents($favor_json);
		$json_arr = json_decode($content,true);
		$favor_count = $json_arr['favor_count'];
		$output = array('status'=>'200','error_code'=>'0','msg'=>'ok','favor_count'=>$favor_count);
		exit(jsonFormat($output));
	}else{
		$favor_flag = $_POST['favor_flag'] ? $_POST['favor_flag'] : 0;

		if($favor_flag == 1){

			if(isset($_COOKIE[$session_id])){

				$content = file_get_contents($favor_json);
				$json_arr = json_decode($content,true);
				$favor_count = $json_arr['favor_count'];
				$output = array('status'=>'200','error_code'=>'201','msg'=>'add','favor_count'=>$favor_count);
				exit(jsonFormat($output));

			}else{

				$content = file_get_contents($favor_json);
				$json_arr = json_decode($content,true);
				$json_arr['favor_count'] = $json_arr['favor_count'] + 1;

				$dest_file = $_SERVER["DOCUMENT_ROOT"].'military/json/favor.json';
				$result = file_put_contents($dest_file, jsonFormat($json_arr));

				if($result){
					$output = array('status'=>'200','error_code'=>'203','msg'=>'cok','favor_count'=>$json_arr['favor_count']);
					exit(jsonFormat($output));
				}else{
					$json_arr['favor_count'] = $json_arr['favor_count']-1;
					$output = array('status'=>'400','error_code'=>'401','msg'=>'cfalse','favor_count'=>$json_arr['favor_count']);
					exit(jsonFormat($output));
			}
			}
		}else{

			$content = file_get_contents($favor_json);
			$json_arr = json_decode($content,true);
			$favor_count = $json_arr['favor_count'];
			$output = array('status'=>'200','error_code'=>'202','msg'=>'lok','favor_count'=>$favor_count);
			exit(jsonFormat($output));
		}
	}
