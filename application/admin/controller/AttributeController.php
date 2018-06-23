<?php 
namespace app\admin\controller;
use app\admin\model\Type;
use app\admin\model\Attribute;
//属性类
class AttributeController extends CommonController{
	public function add(){
		if(request()->isPost()){
			//接收数据
			$postData = input('post.');
			$postData['attr_values']=trim($postData['attr_values']);
			//验证数据
			if($postData['attr_input_type'] == 0){
				$result = $this->validate($postData,'Attribute.add',[],true);
			}else{
				$result = $this->validate($postData,'Attribute.attr_values',[],true);
			}
			if($result!==true){
				$this->error(implode(',',$result));
			}
			//模型操作
			$attributeModel = new Attribute();
			if($attributeModel->allowField(true)->save($postData)){
				$this->success('添加成功',url("admin/attribute/index"));
			}else{
				$this->error('添加失败');
			}
		}
		$types = Type::select();
		return $this->fetch('',['types'=>$types]);
	}

	public function index(){
		$lists = Attribute::select();
		$typess = Type::select();
		$types = [];
		foreach($typess as $type){
			$types[$type['type_id']] = $type;
		}
		return $this->fetch('',['lists'=>$lists,'types'=>$types]);
	}

	public function upd(){
		$typeModel = new Type();
		$attrModel = new Attribute();
		if(request()->isPost()){
			//接收数据
			$postData = input('post.');
			$postData['attr_values']=trim($postData['attr_values']);
			//验证数据
			if($postData['attr_input_type'] == 0){
				$result = $this->validate($postData,'Attribute.add',[],true);
			}else{
				$result = $this->validate($postData,'Attribute.attr_values',[],true);
			}
			if($result!==true){
				$this->error(implode(',',$result));
			}
			//模型操作
			if($attrModel->allowField(true)->isUpdate(true)->save($postData)){
				$this->success('修改成功',url("admin/attribute/index"));
			}else{
				$this->error('修改失败');
			}
		}
		$attr_id = input('attr_id');
		$data = $attrModel->find($attr_id);
		$types = Type::select();
		return $this->fetch('',['data'=>$data,'types'=>$types]);
	}

	public function ajaxDel(){
		if(request()->isAjax()){
			$attr_id = input("attr_id");
			$attrModel = new Attribute();
			if($attrModel->destroy($attr_id)){
				return json(['code'=>200,'message'=>"删除成功"]);
			}else{
				return json(['code'=>-1,'message'=>"删除失败"]);
			}
		}
	}
}