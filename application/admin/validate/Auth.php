<?php 
namespace app\admin\validate;
use think\Validate;
class Auth extends Validate{
	protected $rule = [
		'auth_name' => 'require|unique:Auth',
		'pid' => 'require',
		'auth_c'=>'require',
		'auth_a'=>'require',
	];
	protected $message = [
		'auth_name.require' => '权限名称必填',
		'auth_name.unique' => '权限名称不能重复',
		'auth_c.require'=> '控制器名必填',
		'auth_a.require'=> '控制器方法名必填',
		
	];
	protected $scene = [
		'add'=>['auth_name','pid'],
		'upd'=>['auth_name','pid'],
		'ziAdd'=>['auth_name','pid','auth_a','auth_c'],
		'ziupd'=>['auth_name','pid','auth_a','auth_c'],
	];
}