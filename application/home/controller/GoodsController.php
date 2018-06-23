<?php 
namespace app\home\controller;
use think\Controller;
use think\Db;
use app\home\model\Goods;
use app\home\model\Category;
class GoodsController extends Controller{
	/*****商品详情页********/
	public function detail(){

		/******面包屑导航加基本信息*******/
		$goods_id = input('goods_id');
		$goodsData = Goods::find($goods_id)->toArray();
		$catModel = new Category();
		$familyData = $catModel->getFamilyCat($goodsData['cat_id']);

		/*********画廊图片显示*******/
		$goodsData['goods_img'] = json_decode($goodsData['goods_img']);
		$goodsData['goods_thumb'] = json_decode($goodsData['goods_thumb']);
		$goodsData['goods_middle'] = json_decode($goodsData['goods_middle']);

		/*********取出单选属性名称以及值 需要链表查询***********/
		$singelData = Db::name('goods_attr')
		->alias('t1')
		->field('t1.*,t2.attr_name')
		->join('sh_attribute t2','t1.attr_id = t2.attr_id','left')
		->where("t1.goods_id = $goods_id and t2.attr_type = 1")
		->select();
		$singel_data = [];
		foreach($singelData as $attr){
			$singel_data[ $attr['attr_id'] ][] = $attr;

		}
		/*******唯一属性*******/
		$onlyData = Db::name('goods_attr')
		->alias('t1')
		->field('t1.*,t2.attr_name')
		->join('sh_attribute t2','t1.attr_id = t2.attr_id','left')
		->where("t1.goods_id = $goods_id and t2.attr_type = 0")
		->select();
		// halt($singel_data);
		
		/********显示浏览记录******主要利用三个函数加入数组和弹出数组去除重复以及mysql里的field函数**/
		$goodsModel = new Goods();
		$history = $goodsModel->addGoodsToHistory($goods_id);
		$history = implode(',',$history);
		$sql = "select * from sh_goods where goods_id in ($history) order by field(goods_id,$history)";
		$historyGoods = Db::query($sql);
		
		/*************浏览过此商品的还浏览过***************/
		$guessGoods = $goodsModel->getTypeGoods('is_guess');
		// halt($guessGoods);
		return $this->fetch('',[
			'goodsData'=>$goodsData,
			'familyData'=>$familyData,
			'singel_data'=>$singel_data,
			'onlyData'=>$onlyData,
			'historyGoods'=>$historyGoods,
			'guessGoods'=>$guessGoods,
		]);
	}
	//清空历史纪录
	public function delhistory(){
		cookie('history',null);
		return json(['code'=>200,'message'=>'清除成功!']);
	}

	public function priceClick(){
		if(request()->isAjax()){

			$goodsAttr = Db::name('goods_attr')
			->field("sum(attr_price) price")
			->where("goods_id",input('goods_id'))
			->where("goods_attr_id",'in',input('goods_attr_id'))
			->find();
			return json(['code'=>200,'data'=>$goodsAttr]);
		}
	}
}	