<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Import extends CI_Controller {

    public function __construct(){
        parent::__construct();
		$this->common_model->checkpurview();
		$this->load->helper('download'); 
		$this->load->library('excel/excel_reader2');
		$this->load->library('pinyin/getpinyin');
    }
	
	public function index() {
		$dir = './data/upfile/' . date('Ymd') . '/';
		//$path = '/data/upfile/' . date('Ymd') . '/';
		$err = json_encode(array('url' => '', 'title' => '', 'state' => '请登录'));
		$info = upload('resume_file', $dir);
		if (is_array($info) && count($info) > 0) {
			//$array = array('url' => $path . $info['file'], 'title' => $path . $info['file'], 'state' => 'SUCCESS');
			print_r($info);
			die();
		} else {
			die($err);
		}
	}
	
	//客户
	public function downloadtemplate1() {
		$info = read_file('./data/download/customer.xls');
		$this->common_model->logs('下载文件名:客户导入_'.date("YmdHis").'.xls');
		force_download('客户导入_'.date("YmdHis").'.xls', $info); 
	}
	
	//供应商
	public function downloadtemplate2() {
		$info = read_file('./data/download/vendor.xls');
		$this->common_model->logs('下载文件名:供应商导入_'.date("YmdHis").'.xls');
		force_download('供应商导入_'.date("YmdHis").'.xls', $info); 
	}
	
	//商品
	public function downloadtemplate3() {
		$info = read_file('./data/download/goods.xls');
		$this->common_model->logs('下载文件名:商品导入_'.date("YmdHis").'.xls');
		force_download('商品导入_'.date("YmdHis").'.xls', $info);  
	}
	
	//客户导入
	public function findDataImporter() {
	    $fn = (isset($_SERVER['HTTP_X_FILENAME']) ? $_SERVER['HTTP_X_FILENAME'] : false);
		 print_r($fn);
		die();
		if ($fn) {
			file_put_contents(
				'upload/' . $fn,
				file_get_contents('php://input')
			);
			exit();
		}
	    print_r($_FILES);
		die();
//	    $dir = './data/upfile/' . date('Ymd') . '/';
//		//$path = '/data/upfile/' . date('Ymd') . '/';
//		$err = json_encode(array('url' => '', 'title' => '', 'state' => '请登录'));
//		$info = upload('resume_file', $dir);
//		if (is_array($info) && count($info) > 0) {
//			//$array = array('url' => $path . $info['file'], 'title' => $path . $info['file'], 'state' => 'SUCCESS');
//			print_r($info);
//			die();
//		} else {
//			die($err);
//		}
        die('{"status":200,"msg":"success","data":{"items":[{"id":1294598139109696,"date":"2015-04-25 14:41:35","uploadPath"
:"customer_20150425024011.xls","uploadName":"customer_20150425024011.xls","resultPath":"uploadfiles/88887901
/customer_20150425024011.xls","resultName":"customer_20150425024011.xls","resultInfo":"商品导入完毕。<br/>商
品一共：0条数据，成功导入：0条数据，失败：0条数据。<br/>供应商导入完毕。<br/>供应商一共：0条数据，成功导入：0条数据，失败：0条数据。<br/>客户导入完毕。<br/>客户一共：10条数
据，成功导入：10条数据，失败：0条数据。<br/>","status":2,"spendTime":0},{"id":1294598139109659,"date":"2015-04-25 14:40
:49","uploadPath":"customer_20150425024011.xls","uploadName":"customer_20150425024011.xls","resultPath"
:"uploadfiles/88887901/customer_20150425024011.xls","resultName":"customer_20150425024011.xls","resultInfo"
:"商品导入完毕。<br/>商品一共：0条数据，成功导入：0条数据，失败：0条数据。<br/>供应商导入完毕。<br/>供应商一共：0条数据，成功导入：0条数据，失败：0条数据。<br/>客户导入完毕
。<br/>客户一共：10条数据，成功导入：10条数据，失败：0条数据。<br/>","status":2,"spendTime":0},{"id":1294597559113847,"date":"2015-04-17
 16:54:39","uploadPath":"蓝港新系统xls.xls","uploadName":"蓝港新系统xls.xls","resultPath":"uploadfiles/88887901
/蓝港新系统xls.xls","resultName":"蓝港新系统xls.xls","resultInfo":"商品导入完毕。<br/>商品一共：557条数据，成功导入：0条数据，失败：557条数据
。<br/>(请检查模板是否匹配，建议重新下载模板导入)<br/>供应商导入完毕。<br/>供应商一共：0条数据，成功导入：0条数据，失败：0条数据。<br/>客户导入完毕。<br/>客户一共：0条数
据，成功导入：0条数据，失败：0条数据。<br/>","status":2,"spendTime":0}],"totalsize":3}}');  
	    die('{"status":200,"msg":"success"}');  
	}
	
	//上传文件
	public function upload() {
		die('{"status":200,"msg":"success","data":{"items":[{"id":1294598139109696,"date":"2015-04-25 14:41:35","uploadPath"
:"customer_20150425024011.xls","uploadName":"customer_20150425024011.xls","resultPath":"uploadfiles/88887901
/customer_20150425024011.xls","resultName":"customer_20150425024011.xls","resultInfo":"商品导入完毕。<br/>商
品一共：0条数据，成功导入：0条数据，失败：0条数据。<br/>供应商导入完毕。<br/>供应商一共：0条数据，成功导入：0条数据，失败：0条数据。<br/>客户导入完毕。<br/>客户一共：10条数
据，成功导入：10条数据，失败：0条数据。<br/>","status":2,"spendTime":0},{"id":1294598139109659,"date":"2015-04-25 14:40
:49","uploadPath":"customer_20150425024011.xls","uploadName":"customer_20150425024011.xls","resultPath"
:"uploadfiles/88887901/customer_20150425024011.xls","resultName":"customer_20150425024011.xls","resultInfo"
:"商品导入完毕。<br/>商品一共：0条数据，成功导入：0条数据，失败：0条数据。<br/>供应商导入完毕。<br/>供应商一共：0条数据，成功导入：0条数据，失败：0条数据。<br/>客户导入完毕
。<br/>客户一共：10条数据，成功导入：10条数据，失败：0条数据。<br/>","status":2,"spendTime":0},{"id":1294597559113847,"date":"2015-04-17
 16:54:39","uploadPath":"蓝港新系统xls.xls","uploadName":"蓝港新系统xls.xls","resultPath":"uploadfiles/88887901
/蓝港新系统xls.xls","resultName":"蓝港新系统xls.xls","resultInfo":"商品导入完毕。<br/>商品一共：557条数据，成功导入：0条数据，失败：557条数据
。<br/>(请检查模板是否匹配，建议重新下载模板导入)<br/>供应商导入完毕。<br/>供应商一共：0条数据，成功导入：0条数据，失败：0条数据。<br/>客户导入完毕。<br/>客户一共：0条数
据，成功导入：0条数据，失败：0条数据。<br/>","status":2,"spendTime":0}],"totalsize":3}}');  
	}
	
//获取当前时间毫秒
public static function getMillisecond(){

    list($msec, $sec) = explode(' ', microtime());

    $msectime =  (float)sprintf('%.0f', (floatval($msec) + floatval($sec)) * 1000);

    return $msectimes = substr($msectime,0,13);

}

	public function uploadExcel() {
        
        //php 错误屏蔽等级设置 huangchao - 2022-06-08
        error_reporting(E_ALL& ~E_DEPRECATED& ~E_NOTICE);
        //php  内存大小设置 huangchao - 2022-06-08
        ini_set('memory_limit', '1024M');

	    $path=$_FILES['file'];
	    if($path['error']!= 0)
	        str_alert(-1,'文件上传失败！');
	    if($path['size']>20*1024*1024)
	        str_alert(-1,'上传文件大小超过限制！');
	    if($path['type']!='application/vnd.ms-excel' || strrchr($path['name'],'xls')!='xls')
	        str_alert(200,'上传的文件不是excel类型！');

        //$filePath = "data/upload/".$path["name"];
        //move_uploaded_file($path["tmp_name"],$filePath);
        
        //$reader = new Excel_reader2(); // 实例化解析类Spreadsheet_Excel_Reader
	    $reader = $this->excel_reader2;
        $reader->setOutputEncoding("utf-8");    // 设置编码方式
        $reader->read("{$path['tmp_name']}");
        $data = $reader->sheets[0]['cells'];
        if(!isset($data[2])||!isset($data[2][1]))
            str_alert(-1,'无可导入的数据！');
        $first = array_shift($data);
        $itype = "";

        $this->db->trans_begin();

        if($first[1]=='商品编号'){
            $itype = "商品";
        
            foreach ($data as $arr=>$row) {
                empty($row[1])&&str_alert(-1,'商品【'.$row[2].'】编号不能为空！');
                empty($row[2])&&str_alert(-1,'商品【'.$row[2].'】名称不能为空！');
                empty($row[5])&&str_alert(-1,'商品【'.$row[2].'】类别不能为空！');
                empty($row[9])&&str_alert(-1,'商品【'.$row[2].'】计量单位不能为空！');
                
                $_t  = self::getMillisecond();
                $good['cid'] = $_t;
                //商品编号
                $good['number'] = $row[1];
                //商品名称
                $good['name'] = $row[2];
                //商品条码
                $good['barCode'] = $row[3];
                //规格型号
                $good['spec'] = $row[4];
                //商品类别
                $list = $this->mysql_model->get_rows('category',array('name'=>$row[5],'typeNumber'=>'trade'));
                if (count($list) > 0) {
                    $good['categoryId']= $list['id'];
                    $good['categoryName']= $row[5];
                }else{
                    str_alert(-1,'商品类别【'.$row[5].'】不存在,请先添加商品类别再导入！');
                }        
                //首选仓库
                if($row[6]!="")
                {
                    $list = $this->mysql_model->get_rows('storage',array('isDelete'=>0,'name'=>$row[6]));
                    if (count($list) > 0) {
                        $good['locationId']= $list['id'];
                        $good['locationName']= $row[6];
                    }else 
                    {   str_alert(-1,'首选仓库【'.$row[6].'】不存在,请先添加仓库后再导入！');}
                }
                //最低库存
                $good['lowQty'] = $row[7];
                //最高库存
                $good['highQty'] = $row[8];
                //计量单位
                $list = $this->mysql_model->get_rows('unit',array('name'=>$row[9]));
                if (count($list) > 0) {
                    $good['baseUnitId']= $list['id'];
                    $good['unitName']= $row[9];
                }else{
                    str_alert(-1,'计量单位【'.$row[9].'】不存在,请先添加计量单位再导入！');
                }
                //预计采购价
                $good['purPrice'] = $row[10];
                //零售价
                $good['salePrice'] = $row[11];
                //批发价
                $good['wholesalePrice'] = $row[12];
                //会员价
                $good['vipPrice'] = $row[13];
                //折扣率一
                $good['discountRate1'] = $row[14];
                //折扣率二
                $good['discountRate2'] = $row[15];
                //备注
                $good['remark'] = $row[16];

                //期初设置 

                //仓库
                if($row[18]!="")
                {
                    $list = $this->mysql_model->get_rows('storage',array('isDelete'=>0,'name'=>$row[18]));
                    if (count($list) > 0) {
                        $property['locationId']= $list['id'];
                    }else 
                    {   str_alert(-1,'仓库【'.$row[18].'】不存在,请先添加仓库后再导入！');}
                    //期初数量
                    $property['quantity'] = $row[19];
                    //单位成本
                    $property['unitCost'] = $row[20];
                    //期初总价
                    $property['amount'] = $row[21];
                    $property['batch'] = "";
                    $property['prodDate'] = "";
                    $property['safeDays'] = "";
                    $property['validDate'] = "";
                    $property['id'] = "";
                    $propertys[0] = $property;
                    $good['propertys'] = json_encode($propertys);
                }

                $good['pinYin'] = '';
                $good['sonGoods'] = '[]';
                $good['dopey'] = 0;
                
                $info = array(
                    'cid','number','name','barCode','spec','categoryId','categoryName','locationId','locationName','lowQty','highQty','baseUnitId','unitName','purPrice','salePrice','wholesalePrice','vipPrice','discountRate1','discountRate2','remark','propertys','pinYin','sonGoods','dopey'
                );
                $info = elements($info,$good,NULL);
                
		        //先删除已有信息，再添加
                $this->mysql_model->delete('goods',array('isDelete'=>0,'number'=>$good['number']));  
                $rtn['id'] = $this->mysql_model->insert('goods',$info);

                if (strlen($good['propertys'])>0) {                            
                    $list = (array)json_decode($good['propertys'],true);
                    foreach ($list as $arr=>$row) {
                        $v[$arr]['invId']         = $rtn['id'];
                        $v[$arr]['locationId']    = intval($property['locationId']);
                        $v[$arr]['qty']           = (float)$property['quantity']; 
                        $v[$arr]['price']         = (float)$property['unitCost']; 
                        $v[$arr]['amount']        = (float)$property['amount']; 
                        $v[$arr]['skuId']         = 0;  
                        $v[$arr]['billDate']      = date('Y-m-d');;
                        $v[$arr]['billNo']        = '期初数量';
                        $v[$arr]['billType']      = 'INI';
                        $v[$arr]['transTypeName'] = '期初数量';
                    } 
                    if (isset($v)) {
                        $this->mysql_model->insert('invoice_info',$v);
                    }
                }
            }
        }else if($first[1]=='客户编号'){

            $itype = "客户";
            try
            {
            foreach ($data as $arr=>$row) {
                //数据左右字符空格去掉
                $row[1] = trim($row[1]);
                $row[2] = trim($row[2]);
                $row[3] = trim($row[3]);
                $row[4] = trim($row[4]);
                $row[5] = trim($row[5]);
                $row[6] = trim($row[6]);
                $row[7] = trim($row[7]);
                $row[8] = trim($row[8]);
                $row[9] = trim($row[9]);
                $row[10] = trim($row[10]);
                $row[11] = trim($row[11]);
                $row[12] = trim($row[12]);
                $row[13] = trim($row[13]);
                $row[14] = trim($row[14]);

                $arr = $arr+1;
                if(!empty($row[1]) || !empty($row[2]) || !empty($row[3]) || !empty($row[4]))
                {
                //判断所有必填项
                empty($row[1])&&str_alert(-1,'第'.$arr.'行客户编号不能为空！');
                empty($row[2])&&str_alert(-1,'第'.$arr.'行客户名称不能为空！');
                empty($row[3])&&str_alert(-1,'第'.$arr.'行客户类别不能为空！');
                empty($row[4])&&str_alert(-1,'第'.$arr.'行客户等级不能为空！');

                //客户编号不能超过20个字
                $cust['number'] = $row[1];

                //判断字符串中是否有中文
                if (preg_match("/[\x7f-\xff]/", $cust['number'])) {  
                    str_alert(-1,'第'.$arr.'行客户编号中不能包含中文，请仔细核对后重新提交！');
                } 

                //客户名称不能超过20个字
                $cust['name'] = $row[2];

                $cust['cLevel'] = 0;

                //余额日期
                if($row[5]!="")
                {
                    $beginDate = date('Y-m-d',strtotime($row[5]));
                    $cust['beginDate'] = $beginDate;
                    if(substr($cust['beginDate'],0,4)=="1970")
                    {
                        str_alert(-1,'第'.$arr.'行余额日期格式不正确，请仔细核对后重新提交！');
                    }
                }
                else
                {
                    $cust['beginDate'] = "1970-01-01";
                }

                //判断金额类型
                if($row[6] !="")
                {
                    if(!is_numeric($row[6]))
                    str_alert(-1,'第'.$arr.'行期初应收款请填写金额类型，请仔细核对后重新提交！');
                    else
                    $cust['amount'] = $row[6];
                }
                else
                {
                    $cust['amount'] = 0;
                }
                if($row[7] !="")
                {
                    if(!is_numeric($row[7]))
                    str_alert(-1,'第'.$arr.'行期初预收款请填写金额类型，请仔细核对后重新提交！');
                    else
                    $cust['periodMoney'] = $row[7];  
                }
                else
                {
                    $cust['periodMoney'] = 0;  
                }
                //初期往来余额
                try{
                    $cust['difMoney'] = (double)$cust['amount'] - (double)$cust['periodMoney'];
                }
                catch(Exception $e){
                    $cust['difMoney'] = 0;
                }

                $cust['remark'] = $row[8];
                $linkMan['linkName'] = $row[9];
                $linkMan['linkMobile'] = $row[10];
                $linkMan['linkPhone'] = $row[11];
                $linkMan['linkPlace'] = $row[12];
                $linkMan['linkIm'] = $row[13];
                $linkMan['address'] = $row[14];
                //首要联系人
                $linkMan['linkFirst'] = 1;

                $linkMan['id'] = 0;
                $linkMans[0] = $linkMan;
                $cust['linkMans'] = json_encode($linkMans);
                $cust['type'] = -10;

                //客户类别 
                $list = $this->mysql_model->get_rows('category',array('name'=>$row[3],'typeNumber'=>'customertype'));
                if (count($list) > 0) {
                    $cust['cCategory']= $list['id'];
                    $cust['cCategoryName']= $row[3];
                }else{
                    str_alert(-1,'第'.$arr.'行客户类别【'.$row[3].'】不存在,请仔细核对后重新提交！');
                }

                //客户等级
                if($row[4]=="零售客户"||$row[4]=="批发客户"||$row[4]=="VIP客户"||$row[4]=="折扣等级一"||$row[4]=="折扣等级二")
                {
                    if($row[4]=="零售客户")
                    {
                        $cust['cLevel']= 0;
                        $cust['cLevelName']= "零售客户";
                    }
                    else if($row[4]=="批发客户")
                    {
                        $cust['cLevel']= 1;
                        $cust['cLevelName']= "批发客户";
                    }
                    else if($row[4]=="VIP客户")
                    {
                        $cust['cLevel']= 2;
                        $cust['cLevelName']= "VIP客户";
                    }
                    else if($row[4]=="折扣等级一")
                    {
                        $cust['cLevel']= 3;
                        $cust['cLevelName']= "折扣等级一";
                    }
                    else if($row[4]=="折扣等级二")
                    {
                        $cust['cLevel']= 4;
                        $cust['cLevelName']= "折扣等级二";
                    }
                }
                else
                {
                    str_alert(-1,'第'.$arr.'行客户等级【'.$row[4].'】不存在,请仔细核对后重新提交！');
                }

                $info = array(
                    'number','name','cLevel','amount','periodMoney','difMoney','beginDate','remark','cCategory','cCategoryName','linkMans','type'
                );
                $info = elements($info,$cust,NULL);
            
                if($this->mysql_model->get_count('contact',array('isDelete'=>0,'number'=>$cust['number'])) <= 0){
                    $rtn['id'] = $this->mysql_model->insert('contact',$info);
                 }else {
                     $this->mysql_model->update('contact',$info,array('number'=>$cust['number']));
                }
            }
        }
        }
        catch(Exception $e){
            str_alert(-1,'客户信息导入失败！');
        }
        }else if($first[1]=='供应商编号'){
            $itype = "供应商";
            foreach ($data as $arr=>$row) {

                //数据左右字符空格去掉
                $row[1] = trim($row[1]);
                $row[2] = trim($row[2]);
                $row[3] = trim($row[3]);
                $row[4] = trim($row[4]);
                $row[5] = trim($row[5]);
                $row[6] = trim($row[6]);
                $row[7] = trim($row[7]);
                $row[8] = trim($row[8]);
                $row[9] = trim($row[9]);
                $row[10] = trim($row[10]);
                $row[11] = trim($row[11]);
                $row[12] = trim($row[12]);
                $row[13] = trim($row[13]);

                $arr = $arr+1;
                if(!empty($row[1]) || !empty($row[2]) || !empty($row[3]))
                {
                    //判断所有必填项
                    empty($row[1])&&str_alert(-1,'第'.$arr.'行供应商编号不能为空！');
                    empty($row[2])&&str_alert(-1,'第'.$arr.'行供应商名称不能为空！');
                    empty($row[3])&&str_alert(-1,'第'.$arr.'行供应商类别不能为空！');

                //客户编号不能超过20个字
                $sup['number'] = $row[1];
                if(strlen($sup['number'])>20)
                {$sup['number'] = substr($sup['number'],0,20);}
                
                //判断字符串中是否有中文
                if (preg_match("/[\x7f-\xff]/", $sup['number'])) { 
                   str_alert(-1,'第'.$arr.'行客户编号中不能包含中文，请仔细核对后重新提交！');
                }
                //客户名称不能超过20个字
                $sup['name'] = $row[2];

                $sup['cLevel'] = 0;
                 //余额日期
                 if($row[4]!="")
                 {
                     $beginDate = date('Y-m-d',strtotime($row[4]));
                     $sup['beginDate'] = $beginDate;
                     if(substr($sup['beginDate'],0,4)=="1970")
                     {
                         str_alert(-1,'第'.$arr.'行余额日期格式不正确，请仔细核对后重新提交！');
                    }
                 }
                 else
                 {
                     $sup['beginDate'] = "1970-01-01";
                 }

                //判断金额类型
                 if($row[5] !="")
                 {
                    if(!is_numeric($row[5]))
                        str_alert(-1,'第'.$arr.'行期初应付款请填写金额类型，请仔细核对后重新提交！');
                    else
                        $sup['amount'] = $row[5];
                 }
                 else
                 {
                    $sup['amount'] = 0;
                 }
                 if($row[6] !="")
                 {
                    if(!is_numeric($row[6]))
                        str_alert(-1,'第'.$arr.'行期初预付款请填写金额类型，请仔细核对后重新提交！');
                    else
                        $sup['periodMoney'] = $row[6]; 
                 }
                 else
                 {
                    $sup['periodMoney'] = 0; 
                 }
                
                //初期往来余额
                $sup['difMoney'] = (double)$sup['amount'] - (double)$sup['periodMoney'];

                $sup['remark'] = $row[7];
                $linkMan['linkName'] = $row[8];
                $linkMan['linkMobile'] = $row[9];
                $linkMan['linkPhone'] = $row[10];
                $linkMan['linkPlace'] = $row[11];
                $linkMan['linkIm'] = $row[12];
                $linkMan['address'] = $row[13];

                //首要联系人
                $linkMan['linkFirst'] = 1;

                $linkMan['id'] = 0;
                $linkMans[0] = $linkMan;
                $sup['linkMans'] = json_encode($linkMans);
                $sup['type'] = 10;
                $list = $this->mysql_model->get_rows('category',array('name'=>$row[3],'typeNumber'=>'supplytype'));
                if (count($list) > 0) {
                    $sup['cCategory']= $list['id'];
                    $sup['cCategoryName']= $row[3];
                }else{
                    str_alert(-1,'第'.$arr.'行供应商类别【'.$row[3].'】不存在,请先添加供应商类别再导入！');
                }
            
                $info = array(
                    'number','name','cLevel','amount','periodMoney','difMoney','beginDate','remark','cCategory','cCategoryName','linkMans','type'
                );

                $info = elements($info,$sup,NULL);
            
                if($this->mysql_model->get_count('contact',array('isDelete'=>0,'number'=>$sup['number'])) <= 0){
                    $rtn['id'] = $this->mysql_model->insert('contact',$info);
                }else {
                    $this->mysql_model->update('contact',$info,array('number'=>$sup['number']));
                }

             }
            }
        }
	    if ($this->db->trans_status() === FALSE) {
		    $this->db->trans_rollback();
			str_alert(-1,'SQL错误回滚');
		} else {
		    $this->db->trans_commit();
			str_alert(200,'恭喜您，导入'.$itype.'信息成功！');
		}
	}

    /**
     * 检查指定字符串是否为日期格式 年-月-日
     * @param $date  日期字符串
     * @return bool  true 是日期格式     false 不是日期格式
     */
    function valid_date($date)
    {
        //匹配日期格式
        if (preg_match("/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/", $date, $parts))
        {
            //检测是否为日期,checkdate为月日年
            if(checkdate($parts[2],$parts[3],$parts[1]))
                return true;
            else
                return false;
        }
        else
            return false;
    }
}