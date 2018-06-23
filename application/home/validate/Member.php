<?php 
namespace app\home\validate;
use think\Validate;
class Member extends Validate{
	protected $rule = [
		'username' => 'require|unique:member',
		'password' => 'require',
		'repassword' => 'require|confirm:password',
		'email' => 'require|email',
		'phone'=>'require|unique:member',
		'regcaptcha'=>'require|captcha:2',
		'login_captcha'=>'require|captcha:3',
		'email_captcha'=>'require',
	];
	protected $message = [
		'regcaptcha.require' => "验证码必填", 
		'regcaptcha.captcha' => "验证码错误", 
		'login_captcha.require' => "验证码必填", 
		'login_captcha.captcha' => "验证码错误", 
		'username.require' => '用户名必填',
		'username.unique' => '用户名占用',
		'phone.require' => '手机号必填',
		'phone.unique' => '手机号占用',
		'password.require' => '密码必填',
		'repassword.require' => '确认密码必填',
		'repassword.confirm' => '两次密码不一致',
		'email.require' => '邮箱必填',
		'email.email' => '邮箱格式不正确',
		'email_captcha.require'=>'邮箱验证码必填'
	];
	protected $scene = [
		'register'=>['username','password','email','repassword','phone','regcaptcha'],
		'login' =>['username'=>'require','password','login_captcha'],
		'sms'=>['phone'],
		'find'=>['email'],
		'email'=>['email','email_captcha'],
		'updpassword'=>['password','repassword']
	];
}