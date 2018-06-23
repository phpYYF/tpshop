<?php 
namespace app\admin\model;
use think\Model;
class Auth extends Model{
	protected $pk = 'auth_id';
	protected $autoWriteTimestamp = true;
	public function getAuthsSon(){
		$data = $this->select()->toArray();
		return $this->_getAuthsSon($data);
	}
	private function _getAuthsSon($data,$pid=0,$deep=1){
		static $lists = [];
		foreach($data as $list){
			if($list['pid']==$pid){
				$list['deep']=$deep;
				$lists[$list['auth_id']]=$list;
				$this->_getAuthsSon($data,$list['auth_id'],$deep+1);
			}
		}
		return $lists;
	}
}