<?php 
namespace app\home\model;
use think\Model;
class Category extends Model{
	protected $pk = 'cat_id';
	protected $autoWriteTimestamp = true;

	//查询分类面包屑导航的父级分类
	public function getFamilyCat($cat_id){
		$cats = $this->select()->toArray();
		return $this->_getFamilyCat($cats,$cat_id);
	}
	private function _getFamilyCat($cats,$cat_id){
		static $result = [];
		foreach($cats as $k=>$cat){
			if($cat['cat_id']==$cat_id){
				$result[]=$cat;
				unset($cats[$k]);
				$this->_getFamilyCat($cats,$cat['pid']);
			}
		}
		return array_reverse($result);
	}

	//查找子级分类
	public function getSonsCatsId($cat_id){
		$cats = $this->select();
		return $this->_getSonsCatsId($cats,$cat_id);
	}
	private function _getSonsCatsId($cats,$cat_id){
		static $result = [];
		foreach($cats as $k=>$cat){
			if($cat['pid'] == $cat_id){
				$result[] =$cat['cat_id'];
				unset($cats[$k]);
				$this->_getSonsCatsId($cats,$cat['cat_id']);
			}
		}
		return $result;
	}
}