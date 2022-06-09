<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Right extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview(82);
    }
	

	public function isMaxShareUser() {
		die('{"status":200,"data":{"totalUserNum":10000000,"shareTotal":1},"msg":"success"}');	
	}

	public function queryAllUser() {
		$list = $this->mysql_model->get_results('admin','(1=1)','roleid');  
		foreach ($list as $arr=>$row) {
		    $v[$arr]['share']         = intval($row['status']) > 0 ? true : false;
			$v[$arr]['admin']         = $row['roleid'] > 0 ? false : true;
		    $v[$arr]['userId']        = intval($row['uid']);
			$v[$arr]['isCom']         = intval($row['status']);
			$v[$arr]['role']          = intval($row['roleid']);
			$v[$arr]['userName']      = $row['username'];
			$v[$arr]['realName']      = $row['name'];
			$v[$arr]['dianpumc']      = $row['dianpumc'];
			$v[$arr]['dianpuid']      = intval($row['dianpuid']);
			$v[$arr]['shareType']     = intval($row['status']);
			$v[$arr]['mobile']        = $row['mobile'];
		}
		$json['status'] = 200;
		$json['msg']    = 'success'; 
		$json['data']['items']        = isset($v) ? $v : array();
		$json['data']['shareTotal']   = count($list);
		$json['data']['totalsize']    = $json['data']['shareTotal'];
		$json['data']['corpID']       = 0;
		$json['data']['totalUserNum'] = 1000;
		die(json_encode($json));
	}
	
	public function findDianPu()
	{
		$data = $this->mysql_model->get_results('dianpu','(isDelete=0)');
		if (count($data)>0) {
			foreach ($data as $arr=>$row) {
				$v[$arr]['id']      = intval($row["id"]);
				$v[$arr]['name']      = $row['dianpumc'];
			}
			str_alert(200,'success',isset($v) ? $v : array());  
		}
	}

	public function queryUserByName() {
	    $userName = str_enhtml($this->input->get_post('userName',TRUE));
		$data = $this->mysql_model->get_rows('admin',array('username'=>$userName));
		if (count($data)>0) {
			$json['share']      = true;
			$json['email']      = '';
			$json['userId']     = $data['uid'];
			$json['userMobile'] = $data['mobile'];
			$json['userName']   = $data['username'];
			$json['name']   = $data['name'];
			$json['dianpuid']   = $data['dianpuid'];
			$json['dianpumc']   = $data['dianpumc'];
			str_alert(200,'success',$json);  
		}
        str_alert(502,'用户名不存在');   
	}

	public function adduser() {
	    $data = str_enhtml($this->input->post(NULL,TRUE));
		if (is_array($data)&&count($data)>0) {
			strlen($data['userNumber'])<1 && str_alert(-1,'用户名不能为空');  
			strlen($data['password'])<1 && str_alert(-1,'密码不能为空');  
			$this->mysql_model->get_count('admin',array('username'=>$data['userNumber']))>0 && str_alert(-1,'用户名已经存在');   
			$this->mysql_model->get_count('admin',array('mobile'=>$data['userMobile'])) >0 && str_alert(-1,'该手机号已被使用'); 
			$info = array(
				 'username' => $data['userNumber'],
				 'userpwd'  => md5($data['password']),
				 'name'     => $data['userName'],
				 'mobile'   => $data['userMobile'],
				 'dianpuid' => $data['dianpuid'],
				 'dianpumc' => $data['dianpumc']
			);
		    $sql = $this->mysql_model->insert('admin',$info);
			if ($sql) {
			    $this->common_model->logs('新增用户:'.$data['userNumber']);
				die('{"status":200,"msg":"注册成功","userNumber":"'.$data['userNumber'].'"}');
			}
		}	
		str_alert(-1,'添加失败'); 
	}

	public function editUser() {
	    $data = str_enhtml($this->input->post(NULL,TRUE));
		if (is_array($data)&&count($data)>0) {
			strlen($data['userNumber'])<1 && str_alert(-1,'用户名不能为空');

			$where1['username'] = $data['userNumber'];
			$where1['uid !='] = $data['uid'];
			$this->mysql_model->get_count('admin',array_filter($where1))>0 && str_alert(-1,'用户名已经存在');
			$where2['mobile'] = $data['userMobile'];
			$where2['uid !='] = $data['uid'];
			$this->mysql_model->get_count('admin',array_filter($where2))>0 && str_alert(-1,'该手机号已被使用');

			$data["username"]=$data["userNumber"];
			$data["name"]=$data["userName"];
			$data["mobile"]=$data["userMobile"];

			$sql  = $this->mysql_model->update('admin',elements(array('username','name','mobile','dianpuid','dianpumc'),$data),array('uid'=>$data['uid']));

			if ($sql) {
			    $this->common_model->logs('更新用户信息:'.$data['userNumber']);
				die('{"status":200,"msg":"用户信息更新成功","userNumber":"'.$data['userNumber'].'"}');
			}
		}
		//str_alert(-1,'添加失败'); 
	}

	public function addrights2Outuser() {
	    $userName = str_enhtml($this->input->get_post('userName',TRUE));
		$rightid  = str_enhtml($this->input->get_post('rightid',TRUE));
		$data = $this->mysql_model->get_rows('admin',array('username'=>$userName));
		if (count($data)>0) {
		    $sql = $this->mysql_model->update('admin',array('lever'=>$rightid),array('username'=>$userName));  
			if ($sql) {
			    $this->common_model->logs('更新权限:'. $userName);
				str_alert(200,'操作成功'); 
			}
		}	
		str_alert(-1,'操作失败'); 
	}
	 

	public function queryalluserright() {
	    $userName = str_enhtml($this->input->get_post('userName',TRUE));
		$data = $this->mysql_model->get_rows('admin',array('username'=>$userName));
		if (count($data)>0) {
			$lever = explode(',',$data['lever']);
			$list  = $this->mysql_model->get_results('menu',array('isDelete'=>0),'sortIndex,parentid'); 
			$menu  = array_column($list,'name','id'); 
			foreach ($list as $arr=>$row) {
				$v[$arr]['fobjectid']  = $row['parentId']>0 ? $row['parentId'] : $row['id']; 
				$v[$arr]['fobject']    = $row['parentId']>0 ? @$menu[$row['parentId']] : $row['name'];
				$v[$arr]['faction']    = $row['level'] > 1 ? $row['name'] : '查询';
				$v[$arr]['fright']     = in_array($row['id'],$lever) ? 1 : 0;
				$v[$arr]['frightid']   = intval($row['id']);
			}
			$json['status'] = 200;
			$json['msg']    = 'success';  
			$json['data']['totalsize'] = count($list);  
			$json['data']['items']     = isset($v) ? $v : array();
			die(json_encode($json));
		}
	}

	public function auth2UserCancel(){
	    $userName = str_enhtml($this->input->get_post('userName',TRUE));
		$data = $this->mysql_model->get_rows('admin',array('username'=>$userName));
		if (count($data)>0) {
		    $userName == 'admin' && str_alert(-1,'管理员不可操作');   
			$sql = $this->mysql_model->update('admin',array('status'=>0),array('username'=>$userName));		
			if ($sql) {
			    $this->common_model->logs('用户停用:'.$userName);
				str_alert(200,'success',$data); 
			}
		}	
		str_alert(-1,'停用失败'); 
	}

	//重置用户密码
	public function reSetUserPassword(){
	    $userName = str_enhtml($this->input->get_post('userName',TRUE));
		//生成密码
		$strs = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ#$*@";
		$s = substr(str_shuffle($strs),mt_rand(0,strlen($strs)-11),10);
		//用户信息获取
		$data = $this->mysql_model->get_rows('admin',array('username'=>$userName));
		if (count($data)>0) {
			//系统管理员不允许密码初始化操作
		    $userName == 'admin' && str_alert(-1,'管理员不可操作');   
			//密码修改
			$sql = $this->mysql_model->update('admin',array('userpwd'=>md5($s)),array('username'=>$userName));		
			if ($sql) {
			    $this->common_model->logs('用户密码重置:'.$userName);
				//修改成功，返回修改密码
				str_alert(200,'success',$s);
			}
		}
		str_alert(-1,'用户密码重置失败！'); 
	}

	public function auth2User(){
	    $userName = str_enhtml($this->input->get_post('userName',TRUE));
		$data = $this->mysql_model->get_rows('admin',array('username'=>$userName));
		if (count($data)>0) {
			$userName == 'admin' && str_alert(-1,'管理员不可操作');    
			$sql = $this->mysql_model->update('admin',array('status'=>1),array('username'=>$userName));		
			if ($sql) {
			    $this->common_model->logs('用户启用:'.$userName);
				str_alert(200,'success',$data); 
			}
		}	
		str_alert(-1,'启用失败'); 
	}
}