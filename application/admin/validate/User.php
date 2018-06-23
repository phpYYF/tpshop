<?php 
namespace app\admin\validate;
use think\Validate;
class User extends Validate{
	protected $rule = [
		'username' => 'require|unique:user',
		'password' => 'require',
		'role_id' => 'require',
		'repassword' =>'require|confirm:password',
		'captcha' => 'require|captcha'
	];
	protected $message = [
		'username.require' => '用户名必填',
		'username.unique' => '用户名重复',
		'password.require' => '密码必填',
		'role_id.require' => '所属角色必填',
		'repassword.confirm' => '两次密码不一致',
		'repassword.require' => '必须确认密码',
		'captcha.require' => '验证码必填',
		'captcha.captcha' => '验证码错误',
	];
	protected $scene = [
		'add'=>['username','password','repassword','role_id'],
		'upd'=>['username','role_id'],
		'login'=>['username'=>'require','password','captcha'],
	];
}