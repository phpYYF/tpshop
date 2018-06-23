<?php 
namespace app\admin\controller;
use app\admin\model\Type;
use app\admin\model\Attribute;
//商品类型类
class TypeController extends CommonController{
	public function add(){
		$typeModel = new Type();
		if(request()->isPost()){
			//接收数据
			$postData = input('post.');
			$postData['mark']=trim($postData['mark']);
			//验证数据
			$result = $this->validate($postData,'Type.add',[],true);
			if($result!==true){
				$this->error(implode(',',$result));
			}
			//模型操作
			$typeModel = new Type();
			if($typeModel->allowField(true)->save($postData)){
				$this->success('添加成功',url("admin/type/index"));
			}else{
				$this->error('添加失败');
			}
		}
		return $this->fetch();
	}

	public function index(){
		$typeModel = new Type();
		$lists = $typeModel->select();
		return $this->fetch('',['lists'=>$lists]);
	}

	public function upd(){
		$typeModel = new Type();
		if(request()->isPost()){
			//接收数据
			$postData = input('post.');
			$postData['mark'] = trim($postData['mark']);
			//验证数据
			$result = $this->validate($postData,'Type.upd',[],true);
			if($result!==true){
				$this->error(implode(',',$result));
			}
			//模型操作
			$typeModel = new Type();
			if($typeModel->allowField(true)->isUpdate(true)->save($postData)){
				$this->success('编辑成功',url("admin/type/index"));
			}else{
				$this->error('编辑失败');
			}
		}
		$type_id = input('type_id');
		$data = $typeModel->find($type_id);
		return $this->fetch('',['data'=>$data]);
	}

	public function ajaxDel(){
		if(request()->isAjax()){
			$type_id = input('type_id');
			$typeModel = new Type();
			if($typeModel->destroy($type_id)){
				return json(['code'=>200,'message'=>'删除成功']);
			}else{
				return json(['code'=>-1,'message'=>'删除失败']);
			}
		}
	}

	public function getattr(){
		$type_id = input('type_id');
		$lists = Attribute::alias('t1')
		->field('t1.*,t2.type_name')
		->join('sh_type t2','t1.type_id = t2.type_id','left')
		->where('t1.type_id',$type_id)
		->select();
		return $this->fetch('',['lists'=>$lists]);
	}
}