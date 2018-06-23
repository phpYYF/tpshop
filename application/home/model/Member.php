<?php 
namespace app\home\model;
use think\Model;
class Member extends Model{
	protected $pk = 'member_id';
	protected $autoWriteTimestamp = true;
	protected static function init(){
		Member::event('before_insert',function($member){
			$member['password'] = md5($member['password'].config('password_salt'));
		});
		Member::event('before_update',function($member){
			$member['password'] = md5($member['password'].config('password_salt'));
		});
	}
	public function checkUser($username,$password){
		$data = [
			'username'=>$username,
			'password'=>md5($password.config('password_salt'))
		];
		$info = $this->where($data)->find();
		if($info){
			session('home_username',$info['username']);
			session('member_id',$info['member_id']);
			return true;
		}else{
			return false;
		}
	}
}