<?php 
namespace app\admin\controller;
use app\admin\model\Auth;
//权限类
class AuthController extends CommonController{
	public function add(){
		if(request()->isPost()){
			//接收数据
			$postData = input('post.');
			//验证数据 //判断用那种场景
			if($postData['pid']==0){
				$result = $this->validate($postData,'auth.add',[],true);
			}else{
				$result = $this->validate($postData,'auth.ziAdd',[],true);
			}
			
			
			if($result!==true){
				$this->error(implode(',',$result));
			}
			//模型操作
			$Auth = new Auth();
			if($Auth->allowField(true)->save($postData)){
				$this->success('添加成功',url("admin/auth/index"));
			}else{
				$this->error('添加失败');
			}
		}
		$Auth = new Auth();
		$lists = $Auth->getAuthsSon();

		return $this->fetch('',['lists'=>$lists]);
	}

	public function index(){
		$authModel = new Auth();
		$lists = $authModel->getAuthsSon();
		// halt($lists);
		return $this->fetch('',['lists'=>$lists]);
	}

	public function upd(){
		$authModel = new Auth();
		if(request()->isPost()){
			//接收数据
			$postData = input('post.');
			//验证数据
			if($postData['pid']==0){
				$result = $this->validate($postData,'auth.upd',[],true);
			}else{
				$result = $this->validate($postData,'auth.ziuod',[],true);
			}
			if($result!==true){
				$this->error(implode(',',$result));
			}
			//模型操作
			if($authModel->allowField(true)->isUpdate(true)->save($postData)){
				$this->success('修改成功',url("admin/auth/index"));
			}else{
				$this->error('修改失败');
			}
		}

		$auth_id = input('auth_id');
		$data = $authModel->find($auth_id);
		$lists = $authModel->getAuthsSon();
		return $this->fetch('',[
			'data'=>$data,
			'lists'=>$lists
		]);
	}

	public function ajaxDel(){
		$auth_id = input('auth_id');
		$authModel = new Auth();
		if($authModel->where('pid',$auth_id)->find()){
			return json(['code' => -1,'message'=>'有子级权限不能删除']);
		}
		if($authModel->destroy($auth_id)){
			return json(['code'=>200,'message'=>'删除成功']);
		}else{
			return json(['code'=>-1,'message'=>'删除失败']);
		}

	}
}

