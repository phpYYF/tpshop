<?php 
namespace app\home\controller;
use think\Controller;
use app\home\model\Member;
class PublicController extends Controller{
	//登陆页
	public function login(){
		if(request()->isPost()){
			$postData = input('post.');
			$result = $this->validate($postData,'Member.login',[],true);
			if($result !== true){
				$this->error(implode(',', $result));
			}
			$memModel = new Member();
			if($memModel->checkUser($postData['username'],$postData['password'])){
				if(input("return_url")){
					$this->redirect('home/goods/detail?goods_id='.input('return_url'));
				}
				$this->redirect('home/index/index');
			}else{
				$this->error('用户名或密码错误');
			}
		}
		return $this->fetch('');
	}
	//注册页
	public function register(){
		if(request()->isPost()){
			//接收数据
			$postData = input('post.');
			//验证数据
			$result = $this->validate($postData,'Member.register',[],true);
			if($result!==true){
				$this->error(implode(',',$result));
			}
			if(md5($postData['phoneCaptcha'].config('sms_salt')) !== cookie('sms') ){
				$this->error('手机短信验证码输入错误');
			}
			//模型操作
			$memberModel = new Member();
			if($memberModel->allowField(true)->save($postData)){
				$this->success('注册成功',url("home/index/index"));
			}else{
				$this->error('注册失败');
			}
		}
		return $this->fetch();
	}
	//发送短信
	public function ajaxSendSms(){
		if(request()->isAjax()){
			$phone = input('phone');
			$result = $this->validate(['phone'=>$phone],'Member.sms',[]);
			if($result !== true){
				return json(['code'=>-1,'message'=>$result]);
			}
			$rand = mt_rand(1000,9999);
			//防止暴露被篡改
			$sms = md5($rand.config('sms_salt'));
			cookie('sms',$sms,180);
			return sendSms($phone,array($rand,3),'1');
		}
	}
	//退出
	public function logOut(){
		session('home_username',null);
		session('member_id',null);
		$this->redirect("home/index/index");
	}
	//发送邮件
	public function find(){
		if(request()->isAjax()){
			$email = input('email');
			$result = $this->validate(['email'=>$email],'Member.find',[],true);
			if($result!==true){
				return json(['code'=>-1,'message'=>implode(',',$result)]);
			}
			//判断是否有此邮箱
			$memModel = new Member();
			$info = $memModel->where('email',$email)->find();
			if($info){
				//设置发送内容 $_SERVER['REQUEST_SCHEME'] 获取协议  $_SERVER['SERVER_NAME']域名
				$title = "京西商城找回密码";
				//设置加盐防止地址被篡改
				$time = time();
				$member_id = $info['member_id'];
				$hash = md5($member_id.$time.config('email_salt'));
				$href = $_SERVER['REQUEST_SCHEME']."://".$_SERVER['SERVER_NAME']."/home/public/updpassword/member_id/".$info['member_id']."/time/".$time."/hash/".$hash;
				$content = "京西商城<a href='{$href}'>点我设置新的密码</a>";
				//发送邮件
				if(sendEmail($email,$title,$content)){
					return json(['code'=>200,'message'=>'发送成功,请查看邮箱!']);
				}else{
					return json(['code'=>-4,'message'=>'发送失败']);
				}
				
			}else{
				return json(['code'=>-2,'message'=>'邮箱不存在!']);
			}
		}
		return $this->fetch('');
	}
	//修改新密码
	public function updpassword(){
		if(request()->isAjax()){
			$data = input('post.');
			//验证器验证
			$result = $this->validate($data,"Member.updpassword",[],true);
			if($result!==true){
				return json(['code'=>30,'message'=>implode(',',$result)]);
			}
			//更新密码
			$memModel = new Member();
			if($memModel->allowField(true)->isUpdate(true)->save($data)){
				return json(['code'=>200,'message'=>'修改成功,进去登陆页面']);
			}else{
				return json(['code'=>2,'message'=>'修改失败']);
			}
		}
		//判断地址是否被篡改
		$member_id = input('member_id');
		$oldtime = input('time');
		$hash = input('hash');
		//判断有没有被篡改
		if(md5($member_id.$oldtime.config('email_salt')) !== $hash){
			exit('无效的链接地址,你对链接干啥了?');
		}
		//判断有没有过期
		if($oldtime+200<time()){
			exit('链接已过期');
		}
		return $this->fetch('');
	}





	/*//验证邮箱验证码   验证码方法
	public function find(){
		if(request()->isPost()){
			//接收数据
			$postData = input('post.');
			//验证数据
			$result = $this->validate($postData,'Member.email',[],true);
			if($result!==true){
				$this->error(implode(',',$result));
			}
			//判断输入的验证码是否正确
			if(md5($postData['email_captcha'].config('email_salt')) === cookie('email')){
				$this->success("验证成功,请重新设置密码",url('home/public/findPassword'));
			}else{
				$this->error('邮箱验证码错误');
			}
			
		}	
		return $this->fetch('');
	}
	//设置密码页
	public function findPassword(){
		if(request()->isPost()){
			//接收数据
			$postData = input('post.');
			$postData['member_id'] = session('member_id');
			//验证数据
			$result = $this->validate($postData,'Member.add',[],true);
			if($result!==true){
				$this->error(implode(',',$result));
			}
			//模型操作
			$memberModel = new Member();
			if($memberModel->allowField(true)->isUpdate(true)->save($postData)){
				$this->success('修改密码成功,请登陆',url("admin/public/login"));
			}else{
				$this->error('修改失败');
			}
		}
		return $this->fetch('');
	}
	//发送邮件的ajax
	public function ajaxEmail(){
		// sendEmail('455835178@qq.com','哈喽!',"今天的太阳很大啊");
		if(request()->isAjax()){
			$email = input('email');
			$result = $this->validate(['email'=>$email],'Member.find',[],true);
			if($result !== true){
				return json(['code'=>-1,'message'=>implode(',',$result)]);
			}
			if($info = Member::where('email',$email)->find()){
				$rand = mt_rand(10000,99999);
				$email_cookie = md5($rand.config("email_salt"));
				cookie('email',$email_cookie,180);
				session('member_id',$info['member_id']);
			    sendEmail($email,'京西商城找回密码邮箱验证',"您的验证码为<h4>{$rand}</h4>");
			    return json(['code'=>200,'message'=>'发送成功,请查看邮箱!']);

			}else{
				return json(['code'=>-2,'message'=>'邮箱不存在']);
			}
		}
	}*/
}