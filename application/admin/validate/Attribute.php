<?php 
namespace app\admin\validate;
use think\Validate;
class Attribute extends Validate{
	protected $rule = [
		'attr_name' => 'require',
		'type_id' => 'require',
		'attr_values' => 'require'
	];
	protected $message = [
		'attr_name.require' => '属性名必填',
		'type_id.require' => '商品类别必填',
		'attr_values.require' => '属性值必填'
	];
	protected $scene = [
		'add'=>['attr_name','type_id'],
		'attr_values' => ['attr_name','type_id','attr_values']
	];
}