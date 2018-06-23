<?php 
namespace app\admin\validate;
use think\Validate;
class Order extends Validate{
	protected $rule = [
		'company' => 'require',
		'number' => 'require'
	];
	protected $message = [
		'company.require' => '物流公司必填',
		'number.require' => '订单号必填'
	];
	protected $scene = [
		
	];
}