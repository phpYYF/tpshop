<?php 
namespace app\home\controller;
use think\Controller;
use app\home\model\Goods;
use app\home\model\Order;
use app\home\model\OrderGoods;
use think\Db;
class OrderController extends Controller{
	/***********列表页未付款点击付款**************/
	public function payMoney(){
		$id = input('id');
		$data = Order::find($id);
		if($data){
		   $this->_payMoney($data['order_id'],$data['total_price']);
		}else{
			$this->error('支付异常,请稍后再试');
		}
	}
	/******订单列表页********/
	public function selfOrder(){
		$member_id = session('member_id');
		if(!$member_id){
			$this->error('请先登陆',url('home/public/login'));
		}
		$lists = Order::where('member_id',$member_id)->select();
		
		return $this->fetch('',['lists'=>$lists]);
	}

	/***************提交订单页填写个人信息**************/
	public function orderInfo(){
		if(!session('member_id')){
			$this->error('请先登录',url('home/public/login'));
		}
		/**********订单入库*************/
		if(request()->isPost()){
			$postData = input('post.');
			$result = $this->validate($postData,'Order',[],true);
			if($result !== true){
				$this->error(implode(',',$result));
			}
			//订单入库
			$this->_writeOrder($postData);exit;
		}
		/*********回显商品信息***********/
		$goodsModel = new Goods();
		$cartData = $goodsModel->getCartGoodsAttr();
		return $this->fetch('',[
			'cartData' => $cartData
		]);
	}

	public function _writeOrder($postData){
		$goodsModel = new Goods();
		$cartData = $goodsModel->getCartGoodsAttr();
		//取出总价
		$total_price = 0;
		foreach($cartData as $cart){
			$total_price += ($cart['goodsInfo']['goods_price']+$cart['attrInfo']['attr_total_price'])*$cart['goods_number'];
		}
		$orderData = $postData;
		$orderData['total_price'] = $total_price;
		$orderData['member_id'] = session('member_id');
		$orderData['order_id'] = date('Ymd').time().uniqid();
		Db::startTrans();
		try{
			//入库订单表
			$order = Order::create($orderData);
			if(!$order){
				//捕获异常   成功往下执行
				throw new \Exception('订单表入库失败');
			}
			//循环入库订单商品表
			foreach($cartData as $cart){
				$orderGoods = OrderGoods::create([
					'order_id' => $orderData['order_id'],
					'goods_id' => $cart['goods_id'],
					'goods_attr_ids' => $cart['goods_attr_ids'],
					'goods_number' => $cart['goods_number'],
					'goods_price' =>($cart['goodsInfo']['goods_price']+$cart['attrInfo']['attr_total_price'])*$cart['goods_number']
				]);
				if(!$orderGoods){
					throw new \Exception('订单商品入库失败');
				}
				//减去对应的商品库存  防止超卖
				$condition = [
					'goods_id' =>$cart['goods_id'],
					'goods_number' => ['>=',$cart['goods_number']]
				];
				$number = Goods::where($condition)->setDec('goods_number',$cart['goods_number']);
				if(!$number){
					throw new \Exception('商品数量购买超过库存');
				}
			}
			//成功提交事务
			Db::commit();
			$cart = new \cart\Cart();
			$cart->clearCart();

		}catch(\Exception $e){
			Db::rollback();
			$this->error($e->getMessage());
		}
		//唤起支付宝界面付款
		$this->_payMoney($order['order_id'],$total_price);
	}
	//支付宝支付页面
	private function _payMoney($order_id,$total_price,$title='袁一凡支付宝',$content='购物'){
		$payData = [
			//商户订单号
			'WIDout_trade_no'=>$order_id,
			//订单名称
			'WIDsubject'=>$title,
			//付款金额
			'WIDtotal_amount'=>$total_price,
			//订单描述
			'WIDbody'=>$content
		];

		include "../extend/alipay/pagepay/pagepay.php";
	}
	//同步回调函数
	public function returnurl(){
		require_once("../extend/alipay/config.php");
        require_once '../extend/alipay/pagepay/service/AlipayTradeService.php';
        $arr=input('get.');
		$alipaySevice = new \AlipayTradeService($config); 
		$result = $alipaySevice->check($arr);
		if($result) {
			//商户订单号
			$out_trade_no = htmlspecialchars($arr['out_trade_no']);
			//支付宝交易号
			$trade_no = htmlspecialchars($arr['trade_no']);	
			$data = [
				'pay_status'=>1,
				'ali_order_id'=>$trade_no
			];
			//更新数据库操作改变付款状态和存入订单号
			if(Order::where('order_id',$out_trade_no)->update($data)){
				
				$this->success("支付成功",url('home/order/selforder'));
			}else{
				$this->error('支付异常',url('home/order/selforder'));
			}
		}
		else {
		    //验证失败
		    echo "验证失败";
		}
	}
	//异步回调函数为post接受
	public function notifyurl(){
		require_once("../extend/alipay/config.php");
        require_once '../extend/alipay/pagepay/service/AlipayTradeService.php';
        $arr=input('post.');
		$alipaySevice = new \AlipayTradeService($config); 
		$result = $alipaySevice->check($arr);
		if($result) {
			//商户订单号
			$out_trade_no = htmlspecialchars($arr['out_trade_no']);
			//支付宝交易号
			$trade_no = htmlspecialchars($arr['trade_no']);	
			$data = [
				'pay_status'=>1,
				'ali_order_id'=>$trade_no
			];
			//更新数据库操作改变付款状态和存入订单号
			if(Order::where('order_id',$out_trade_no)->update($data)){
				echo "success";
			}
		}
		else {
		    //验证失败
		    echo "验证失败";
		}
	}
}