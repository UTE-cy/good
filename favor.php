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
	//����json�ļ�
	$favor_json = "http://$_SERVER[HTTP_HOST]/military/json/favor.json";
	
	//1.������ύ����������ֱ�ӷ��ص��������ͻ���
	if(empty($_POST['favor_flag'])){
		//��ȡjson�ļ������ص�����
		$content = file_get_contents($favor_json);
		$json_arr = json_decode($content,true);
		$favor_count = $json_arr['favor_count'];
		$output = array('status'=>'200','error_code'=>'0','msg'=>'����ɹ�!','favor_count'=>$favor_count);
		exit(jsonFormat($output)); 
	}else{
		$favor_flag = $_POST['favor_flag'] ? $_POST['favor_flag'] : 0;
		//���յ��û��ĵ�������
		if($favor_flag == 1){
			//�ж�һ���û��Ƿ��ѵ���
			//δ����
			if(isset($_COOKIE[$session_id])){
				//���ڣ��ѵ���
				$content = file_get_contents($favor_json);
				$json_arr = json_decode($content,true);
				$favor_count = $json_arr['favor_count'];
				$output = array('status'=>'200','error_code'=>'201','msg'=>'�ѵ���!','favor_count'=>$favor_count);
				exit(jsonFormat($output)); 
			}else{
				//δ���ڣ�δ����
				setCookie($session_id,$favor_flag,time()+60*60*24*365);
				$content = file_get_contents($favor_json);
				$json_arr = json_decode($content,true);
				$json_arr['favor_count'] = $json_arr['favor_count'] + 1;
				//������+1
				$dest_file = $_SERVER["DOCUMENT_ROOT"].'military/json/favor.json';
				$result = file_put_contents($dest_file, jsonFormat($json_arr));
				if($result){
					$output = array('status'=>'200','error_code'=>'203','msg'=>'���޳ɹ�!','favor_count'=>$json_arr['favor_count']);
					exit(jsonFormat($output)); 
				}else{
					$json_arr['favor_count'] = $json_arr['favor_count']-1;
					$output = array('status'=>'400','error_code'=>'401','msg'=>'����ʧ��!','favor_count'=>$json_arr['favor_count']);
					exit(jsonFormat($output));
			}
			}
		}else{
			//��ȡjson�ļ������ص�����
			$content = file_get_contents($favor_json);
			$json_arr = json_decode($content,true);
			$favor_count = $json_arr['favor_count'];
			$output = array('status'=>'200','error_code'=>'202','msg'=>'����ɹ�','favor_count'=>$favor_count);
			exit(jsonFormat($output)); 
		}
	}
	