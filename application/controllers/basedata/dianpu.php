<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Dianpu extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview();
    }

    //店铺列表
	public function index(){
		$list = $this->mysql_model->get_results('dianpu','(isDelete=0) '.$this->common_model->get_location_purview(1).' order by id desc');  
		foreach ($list as $arr=>$row) {
		    $v[$arr]['dianpumc']     = $row['dianpumc'];
			$v[$arr]['delete']      = $row['disable'] > 0 ? true : false;
		    $v[$arr]['suoshuqy']     = $row['suoshuqy'];
		    $v[$arr]['zhuyaofzr']     = $row['zhuyaofzr'];
		    $v[$arr]['lianxifs']     = $row['lianxifs'];
		    $v[$arr]['paixu']     = intval($row['paixu']);
			$v[$arr]['id']          = intval($row['id']);
		}
		$json['status'] = 200;
		$json['msg']    = 'success'; 
		$json['data']['rows']       = isset($v) ? $v : array();
		$json['data']['total']      = 1;
		$json['data']['records']    = count($list);
		$json['data']['page']       = 1;
		die(json_encode($json));
	}
	
	    //仓库列表读取
		public function cangKuListGet(){
			$list = $this->mysql_model->get_results('storage','(isDelete=0) '.$this->common_model->get_location_purview(1).' order by id desc');  
			foreach ($list as $arr=>$row) {
				$v[$arr]['dianpumc']     = $row['dianpumc'];
				$v[$arr]['delete']      = $row['disable'] > 0 ? true : false;
				$v[$arr]['suoshuqy']     = $row['suoshuqy'];
				$v[$arr]['zhuyaofzr']     = $row['zhuyaofzr'];
				$v[$arr]['lianxifs']     = $row['lianxifs'];
				$v[$arr]['paixu']     = intval($row['paixu']);
				$v[$arr]['id']          = intval($row['id']);
			}
			$json['status'] = 200;
			$json['msg']    = 'success'; 
			$json['data']['rows']       = isset($v) ? $v : array();
			$json['data']['total']      = 1;
			$json['data']['records']    = count($list);
			$json['data']['page']       = 1;
			die(json_encode($json));
		}
	
	//新增
	public function add(){
		$this->common_model->checkpurview(207);
		
		$data = str_enhtml($this->input->post(NULL,TRUE));
        
		if (count($data)>0) {
			$data = $this->validform($data);
			$sql  = $this->mysql_model->insert('dianpu',elements(array('dianpumc','suoshuqy','zhuyaofzr','lianxifs','paixu'),$data));
			if ($sql) {
				$data['id'] = $sql;
				$this->common_model->logs('新增店铺:'.$data['name']);
				str_alert(200,'success',$data);
			}  
		}
		str_alert(-1,'添加失败');
	}
	
	//修改
	public function update(){
		$this->common_model->checkpurview(208);
		$data = str_enhtml($this->input->post(NULL,TRUE));
		if (count($data)>0) {
			$data = $this->validform($data);
			$sql  = $this->mysql_model->update('dianpu',elements(array('dianpumc','suoshuqy','zhuyaofzr','lianxifs','paixu'),$data),array('id'=>$data['locationId']));
			//修改用户表中店铺id对应的名称
			$this->mysql_model->update('admin',elements(array('dianpumc'),$data),array('dianpuid'=>$data['locationId']));
			
			if ($sql) {
				$data['id'] = $data['locationId'];
				$this->common_model->logs('更新店铺:'.$data['name']);
				str_alert(200,'success',$data);
			}
		}
		str_alert(-1,'更新失败');
	}
	
	//删除
	public function delete(){
		$this->common_model->checkpurview(209);
		$id   = intval($this->input->post('locationId',TRUE));
		$data = $this->mysql_model->get_rows('dianpu',array('id'=>$id,'isDelete'=>0)); 
		if (count($data) > 0) {
		    $sql = $this->mysql_model->update('dianpu',array('isDelete'=>1),array('id'=>$id));   
		    if ($sql) {
				$this->common_model->logs('删除店铺:ID='.$id.' 名称:'.$data['name']);
				str_alert(200,'success');
			}
		}
		str_alert(-1,'删除失败');
	}
	
	//启用禁用
	public function disable(){
		$this->common_model->checkpurview(206);
		$id = intval($this->input->post('locationId',TRUE));
		$data = $this->mysql_model->get_rows('dianpu',array('id'=>$id,'isDelete'=>0)); 
		if (count($data) > 0) {
			$info['disable'] = intval($this->input->post('disable',TRUE));
			$sql = $this->mysql_model->update('dianpu',$info,array('id'=>$id));
		    if ($sql) {
			    $actton = $info['disable']==0 ? '店铺启用' : '店铺禁用';
				$this->common_model->logs($actton.':ID='.$id.' 名称:'.$data['name']);
				str_alert(200,'success');
			}
		}
		str_alert(-1,'操作失败');
	}
	
	//公共验证
	private function validform($data) {
        strlen($data['dianpumc']) < 1 && str_alert(-1,'店铺名称不能为空');
		strlen($data['suoshuqy']) < 1 && str_alert(-1,'店铺地址不能为空');
		strlen($data['paixu']) < 1 && str_alert(-1,'排序值不能为空');
		$data['locationId'] = intval($data['locationId']);
		$where = $data['locationId']>0 ? ' and id<>'.$data['locationId'].'' :'';
		$this->mysql_model->get_count('dianpu','(isDelete=0) and dianpumc="'.$data['dianpumc'].'" '.$where) > 0 && str_alert(-1,'店铺名称重复');
		return $data;
	}  
}