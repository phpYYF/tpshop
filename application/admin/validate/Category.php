<?php 
namespace app\admin\validate;
use think\Validate;
class Category extends Validate{
	protected $rule = [
		'cat_name' => 'require|unique:category',
		'pid' => 'require'
	];
	protected $message = [
		'cat_name.require' => '分类名必填',
		'cat_name.unique' => '分类名重复',
		'pid.require' => '父分类必填'
	];
	protected $scene = [
		'add'=>['cat_name','pid'],
		'upd'=>['cat_name','pid'],
	];
}