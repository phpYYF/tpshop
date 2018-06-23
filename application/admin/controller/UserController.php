<?php 
namespace app\admin\controller;
use app\admin\model\User;
use app\admin\model\Role;
//用户类
class UserController extends CommonController{

	public function add(){
		if(request()->isPost()){
			//接收数据
			$postData = input('post.');
			//验证数据
			$result = $this->validate($postData,'user.add',[],true);
			if($result!==true){
				$this->error(implode(',',$result));
			}
			//模型操作
			$userModel = new User();
			if($userModel->allowField(true)->save($postData)){
				$this->success('添加成功',url("admin/user/index"));
			}else{
				$this->error('添加失败');
			}
		}
		$roles = Role::select();
		return $this->fetch('',['roles'=>$roles]);
	}

	public function index(){
		$userModel= new User();
		$lists = $userModel->alias('t1')
		->field('t1.*,t2.role_name')
		->join('sh_role t2','t1.role_id = t2.role_id','left')
		->paginate(3);
		return $this->fetch('',['lists'=>$lists]);
	}
	public function upd(){
		if(request()->isPost()){
			//接收数据
			$postData = input('post.');
			//验证数据
			$result = $this->validate($postData,'user.upd',[],true);
			if($result!==true){
				$this->error(implode(',',$result));
			}
			//模型操作
			$userModel = new User();
			if($userModel->allowField(true)->isUpdate(true)->save($postData)){
				$this->success('修改成功',url("admin/user/index"));
			}else{
				$this->error('修改失败');
			}
		}

		$data = User::find(input('user_id'));
		$roles = Role::select();
		return $this->fetch('',['data'=>$data,'roles'=>$roles]);
	}
	public function ajaxActive(){
		if(request()->isAjax()){
			$user_id = input('user_id');
			$is_active = input('is_active')?0:1;
			$data = [
				'user_id'=>$user_id,
				'is_active'=>$is_active
			];
			$userModel = new User();
			if($userModel->update($data)!==false){
            	return json(['code' => 200,'is_active'=>$is_active]);
			}else{
            	return json(['code' => -1,'is_active'=>$is_active]);
			}
		}
	}
}