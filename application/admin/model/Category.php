<?php 
namespace app\admin\model;
use think\Model;
class Category extends Model{
	protected $pk = 'cat_id';
	protected $autoWriteTimestamp = true;

	public function getSonsCat(){
	 	$data = $this->select();
	 	return $this->_getSonsCat($data);
	}	

	private function _getSonsCat($data,$pid = 0,$deep=1){
		static $result = [];
		foreach($data as $v){
			if($v['pid'] == $pid){
				$v['deep'] = $deep;
				$result[$v['cat_id']] = $v;
				$this->_getSonsCat($data,$v['cat_id'],$deep+1);
			}
		}
		return $result;
	}
}