<?php 
namespace app\admin\validate;
use think\Validate;
class Goods extends Validate{
	protected $rule = [
		'goods_name' => 'require|unique:goods',
		'goods_price' => 'require',
		'goods_number' => 'require|regex:\d+',
		'cat_id' =>'require'
	];
	protected $message = [
		'goods_name.require' => '商品名称必填',
		'goods_name.unique' => '商品名称重复',
		'goods_price.require' => '商品价格必填',
		'goods_number.require' => '库存必填',
		'goods_number.regex' => '必须填写数字',
		'cat_id' =>'商品分类必填'
	];
	protected $scene = [
		'add'=>['goods_name','goods_price','goods_number','cat_id']
	];
}