<?php 
namespace app\admin\model;
use think\Model;
// use app\admin\model\Role;
// use app\admin\model\Auth;
class User extends Model{
	protected $pk = 'user_id';
	protected $autoWriteTimestamp = true;
	public function checkUsers($username,$password){
		$where = [
			'username' => $username,
			'password'=> md5($password.config('password_salt'))
		];
		// halt($where['password']);
		$userInfo = $this->where($where)->find();
		if($userInfo){
			if($userInfo['is_active']==0){
				return false;
			}
			session('username',$userInfo['username']);
			//把权限写入session中
			$this->_writeAuthToSession($userInfo['role_id']);
			return true;
		}else{
			return false;
		}
	}

	private function _writeAuthToSession($role_id){
		//首先查出所属角色的权限信息
		$row = Role::find($role_id);
		//取出权限
		$auth_id_list = $row['auth_id_list'];
		//判断权限是否等于*号,不等于走else
		if($auth_id_list == '*'){
			//获取到所有的顶级权限
			$oneAuth = Auth::where('pid',0)->select()->toArray();
			foreach($oneAuth as $k=>$auth){
				//新建一个下标SonsAuth  取出顶级权限下面子级权限放入
				$oneAuth[$k]['sonsAuth'] = Auth::where('pid',$auth['auth_id'])->select()->toArray();
			}
			//url访问权限 为*号是超级管理员
			session('visitorAuth','*');
		}else{
			// 定义一个空数组保存角色的所有控制器方法的访问权限
			$visitorAuth = [];
			//取出满足要求角色权限的权限
			$all_auth = Auth::where('auth_id','in',$auth_id_list)->select()->toArray();
			//定义一个空数组保存权限
			$oneAuth = [];
			// halt($all_auth);
			foreach($all_auth as $auth){
				//取出所有的顶级权限放入数组
				if($auth['pid']==0){
					$oneAuth[]=$auth;
				}
				//取出所拥有权限的控制器和方法
				$visitorAuth[] = strtolower($auth['auth_c'].'/'.$auth['auth_a']);
			}
			// halt($oneAuth);
			foreach($oneAuth as $k=>$auth){
				foreach($all_auth as $kk=>$is_auth){
					//取出对应的子级id作为一个数组放入新的下标里面
					if($auth['auth_id']==$is_auth['pid']){
						$oneAuth[$k]['sonsAuth'][]=$is_auth;
					}
				}
			}
			session('visitorAuth',$visitorAuth);
		}

		// halt($oneAuth);
		session('menuAuth',$oneAuth);
	}

	//前钩子
	protected static function init(){
		User::event('before_insert',function($user){
			$user['password'] = md5($user['password'].config('password_salt'));
		});

		User::event('before_update',function($user){
			if(isset($user['password'])){
				if($user['password']==''){
					unset($user['password']);
				}else{
					$user['password'] = md5($user['password'].config('password_salt'));
				}
			}
			
		});
	}
}