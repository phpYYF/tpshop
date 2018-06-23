<?php 
namespace app\home\controller;
use think\Controller;
use app\home\model\Goods;
use think\Db;
class CartController extends Controller{


	/**************购物车详情页*****************/
	public function cartList(){
		if(!session('member_id')){
			$this->error('请先登陆!',url('home/public/login'));
		}
		$cart = new \cart\Cart();
		$cartInfo = $cart->getCart();
		// 循环取出每个商品的id以及属性数量 数据id
		$cartData = [];
		foreach($cartInfo as $key => $goods_number){
			$arr = explode('-',$key);
			$cartData[] = [
				'goods_id' => $arr[0],
				'goods_attr_ids' => $arr[1],
				'goods_number' => $goods_number,
			];
		}
		/********构造需要的商品信息数组用于循环********/
		//给每个商品添加一个goodsInfo的字段和attrInfo字段
		foreach($cartData as $k => $data){
			$cartData[$k]['goodsInfo'] = Db::name('goods')->find($data['goods_id']);
			$cartData[$k]['attrInfo'] = Db::name('goods_attr')
			->alias('t1')
			->field("sum(t1.attr_price) attr_total_price ,group_concat(t2.attr_name,':',t1.attr_value,'&nbsp;&nbsp;￥',t1.attr_price separator '<br />') as singleAttr")
			->join('sh_attribute t2','t1.attr_id = t2.attr_id','left')
			->where('t1.goods_attr_id','in',$data['goods_attr_ids'])
			->find();
		}
		return $this->fetch('',[
			'cartData' => $cartData,
		]);
	}
	//添加商品到购物车ajax实现
	public function ajaxaddcart(){
		if(request()->isAjax()){
			// return json('halou');
			if(!session('member_id')){
				return json(['code'=> -1,'message'=>'亲,请先登陆!']);
			}
			$goods_id = input('goods_id');
			$goods_attr_ids = input('goods_attr_ids');
			$goods_number = input('goods_number');
			$cart = new \cart\Cart();
			$cart->addCart($goods_id,$goods_attr_ids,$goods_number);
			return json(['code'=>200,'message'=>'添加购物车成功!']);
		}
	}
	//ajax删除购物车商品
	public function ajaxDelGoods(){
		if(request()->isAjax()){
			$cart = new \cart\Cart();
			if($cart->delCart(input('goods_id'),input('goods_attr_ids'))){
 				return json(['code'=>200,'message'=>'移除成功']);
			}else{
				return json(['code'=>-1,'message'=>'移除失败']);
			}
		}
	}
	//ajax清空购物车
	public function emptyCart(){
		if(request()->isAjax()){
			$cart = new \cart\Cart();
			if($cart->clearCart()){
	 			return json(['code'=>200,'message'=>'清空成功!']);
			}else{
				return json(['code'=>-1,'message'=>'清空失败']);
			}
		}
	}
	//ajax修改购物车数量
	public function changeCartNum(){
		if(request()->isAjax()){
			$cart = new \cart\Cart();
			if($cart->changeCartNum(input('goods_id'),input('goods_attr_ids'),input('goods_number'))){
  				return json(['code'=>200,'message'=>'修改成功']);
			}else{
				return json(['code'=>-1,'message'=>'修改失败']);
			}
		}
	}
}