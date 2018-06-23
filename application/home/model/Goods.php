<?php 
namespace app\home\model;
use think\Model;
use think\Db;
class Goods extends Model{
	protected $pk = 'goods_id';
	protected $autoWriteTimestamp = true;

	public function getTypeGoods($type,$limit = 5){
		$condition = [
			'is_sale' => 1,
			'is_delete' => 0
		];
		switch ($type) {
			//疯狂抢购
			case 'is_crazy':
				$goodsData = $this->where($condition)->order('goods_price asc')->limit($limit)->select(); 
				break;
			//猜你喜欢
			case 'is_guess':
				$goodsData = $this->where($condition)->order('rand()')->limit($limit)->select(); 
				break;
			
			default:
			//热卖.新品,推荐
				$condition[$type] = 1;
				$goodsData = $this->where($condition)->limit($limit)->select();
				break;
		}
		return $goodsData;
	}

	/******历史纪录*******/
	public function addGoodsToHistory($goods_id){
		$history = cookie('history')?cookie('history'):[];
		if($history){
			//当为空的时候说名里面是有商品的
			//添加到数组前部
			array_unshift($history,$goods_id);
			//去掉重复的内容
			$history = array_unique($history);
			//数量大于5移除最后一个
			if(count($history)>5){
				array_pop($history);
			}
		}else{
			array_unshift($history,$goods_id);
		}
		//写入cookie中保存起来 设置过期时间为一天
		cookie('history',$history,3600*24);
		return $history;
	}

	//封装获取购物车商品以及属性的方法
	public function getCartGoodsAttr(){
		$cart = new \cart\Cart();
		$cartInfo = $cart->getCart();
		// halt($cartInfo);
		$cartData = [];
		foreach($cartInfo as $key => $goods_number){
			$arr = explode('-',$key);
			$cartData[] = [
				'goods_id' => $arr[0],
				'goods_attr_ids' => $arr[1],
				'goods_number' => $goods_number,
			];
		}
		foreach($cartData as $k => $data){
			$cartData[$k]['goodsInfo'] = Db::name('goods')->find($data['goods_id']);
			$cartData[$k]['attrInfo'] = Db::name('goods_attr')
			->alias('t1')
			->field("sum(t1.attr_price) attr_total_price ,group_concat(t2.attr_name,':',t1.attr_value,'&nbsp;&nbsp;￥',t1.attr_price separator '<br />') as singleAttr")
			->join('sh_attribute t2','t1.attr_id = t2.attr_id','left')
			->where('t1.goods_attr_id','in',$data['goods_attr_ids'])
			->find();
		}
		return $cartData;
	}
}