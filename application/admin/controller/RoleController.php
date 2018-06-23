<?php 
namespace app\admin\controller;
use app\admin\model\Auth;
use app\admin\model\Role;
use think\Db;
//角色类
class RoleController extends CommonController{
	public function add(){
		if(request()->isPost()){
			//接收数据
			$postData = input('post.');
			//验证数据
			$result = $this->validate($postData,'role.add',[],true);
			if($result!==true){
				$this->error(implode(',',$result));
			}
			//模型操作
			$roleModel = new Role();
			// halt($postData);
			if($roleModel->save($postData)){
				$this->success('添加成功',url("admin/role/index"));
			}else{
				$this->error('添加失败');
			}
		}

		//取出所有权限
		$authAuth = new Auth();
		$authsData = $authAuth->select()->toArray();
		// halt($authsData);
		$auths = [];
		//把每个元素的auth_id作为下标
		foreach($authsData as $auth){
			$auths[$auth['auth_id']]=$auth;
		}
		// halt($auths);
		//循环所有的权限进行分组  通过pid进行分组
		$children = [];
		foreach($authsData as $auth){
			$children[$auth['pid']][]=$auth['auth_id'];
		}
		// halt($children);
		return $this->fetch('',[
			'auths' => $auths,
			'children'=>$children
		]);
	}

	public function index(){
		$sql="select t1.*,GROUP_CONCAT(t2.auth_name SEPARATOR '|') all_auth from sh_role as t1 left join sh_auth t2 on FIND_IN_SET(t2.auth_id,t1.auth_id_list) group by t1.role_id;";
		$lists = Db::query($sql);
		// halt($lists);
		return $this->fetch('',[
			'lists'=> $lists,
		]);
	}

	public function upd(){
		$roleModel = new Role();
		$authModel = new Auth();
		if(request()->isPost()){
			$postData = input('post.');
			// halt($postData);

			$result = $this->validate($postData,'role.upd',[],true);
			if($result!==true){
				$this->error(implode(',', $result));
			}
			//更新前注意加前钩子进行拆分
			if($roleModel->isUpdate(true)->save($postData)){
				$this->success("修改成功",url("admin/role/index"));
			}else{
				$this->error('修改失败');
			}

		}

		$role_id = input('role_id');
		$authsData = $authModel->select()->toArray();
		
		$auths = [];
		foreach($authsData as $auth){
			$auths[$auth['auth_id']]=$auth;
		} 

		$children = [];
		foreach($authsData as $auth){
			$children[$auth['pid']][]=$auth['auth_id'];
		}
		// halt($auths);
		
		
		$data = $roleModel->find($role_id);
		// dump($children[0]);
		// halt($auths);
		return $this->fetch('',[
			'auths' => $auths,
			'children'=>$children,
			'data' => $data
		]);
	}

	public function ajaxDel(){
		$roleModel = new Role();
		$role_id = input('role_id');
		if($roleModel->destroy($role_id)){
			return json(['code'=>200,'message'=>'删除成功']);
		}else{
			return json(['code'=>-1,'message'=>'删除失败']);
		}
	}
}
