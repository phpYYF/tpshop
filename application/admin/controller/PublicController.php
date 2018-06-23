<?php 
namespace app\admin\controller;
use think\Controller;
use app\admin\model\User;
//登陆类
class PublicController extends Controller{
	public function login(){
		if(request()->isPost()){
			//接收数据
			$postData = input('post.');
			//验证数据
			$result = $this->validate($postData,'user.login',[],true);
			if($result!==true){
				$this->error(implode(',',$result));
			}
			//模型操作
			$userModel = new User();

			if($userModel->checkUsers($postData['username'],$postData['password'])){
				$this->redirect('admin/index/index');
			}else{
				$this->error('用户名密码错误或已被封号');
			}
		}
		return $this->fetch('');	
	}

	public function logOut(){
		session('username',null);
		$this->redirect('admin/public/login');
	}	
}