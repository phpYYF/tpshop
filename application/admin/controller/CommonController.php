<?php 
namespace app\admin\controller;
use think\Controller;
//后台基类
class CommonController extends Controller{
	public function _initialize(){
		if(!session('username')){
			$this->error('请先登陆',url('admin/public/login'));
		}
		//获取到当前访问的控制器和方法
		$now_ca = strtolower( request()->Controller().'/'.request()->action());
		//从session中取出当前用户所用于的权限  登陆的时候就会把所拥有的权限存入session当中
		$visitorAuth = session('visitorAuth');
		// halt($visitorAuth);
		// 当用户角色权限为* 号或者访问的是index/index页面 那么继续执行接下来的代码
		if($visitorAuth == '*' || strtolower(request()->Controller()) =='index'){
			return;
		}else{
			if(request()->isAjax()){
				echo json_encode(['massage'=>'无权限访问,请联系管理员']);
				exit;
			}
			//判断当访问的控制器方法在不在用户的权限中 不在直接种植代码执行
			if(!in_array($now_ca,$visitorAuth)){
				exit('无权限访问,请联系管理员');
			}
		}
	}
}