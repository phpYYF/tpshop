<?php 
namespace app\admin\controller;
use app\home\model\Order;
class OrderController extends CommonController{
	/*******分配物流********/
	public function upd(){
		if(request()->isPost()){
			//接收数据
			$postData = input('post.');
			//验证数据
			$result = $this->validate($postData,'Order',[],true);
			if($result!==true){
				$this->error(implode(',',$result));
			}
			//模型操作
			$modelModel = new Order();
			$postData['send_status'] = 1; 
			if($modelModel->allowField(true)->isUpdate(true)->save($postData)){
				$this->success('添加成功',url("admin/order/index"));
			}else{
				$this->error('添加失败');
			}
		}
		$id = input('id');
		$data = Order::find($id);
		return $this->fetch('',['data'=>$data]);
	}
	/***********查询物流信息**************/
	public function queryWuliu(){
		if(request()->isAjax()){
			$key = config("key_wuliu");
			$company = input('company');
			$number = input('number');
			$url = "http://www.kuaidi100.com/applyurl?key={$key}&com={$company}&nu={$number}&show=0";
			echo file_get_contents($url);
		}
		
	}
	/*******订单列表页******/
	public function index(){
		$keyword = trim(input('keyword'));
		$where = '';
		if($keyword){
			$where .= "receiver like '%{$keyword}%' or tel like '%{$keyword}%' or address like '%{$keyword}%' or order_id like '%{$keyword}%'";
		}

		$lists = Order::alias('t1')
		->field('t1.*,t2.username')
		->join('sh_member t2','t1.member_id = t2.member_id','left')
		->where($where)
		->paginate(3);

		if(request()->isAjax()){
			$result = [
				'pagelist'=>$lists->render(),
				'tbody' =>$this->fetch('order/tbody',['lists'=>$lists])
			];
			return json($result);
		}
		return $this->fetch('',['lists'=>$lists]);
	}
}