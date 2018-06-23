<?php 
namespace app\home\controller;
use think\Controller;
use app\home\model\Category;
use app\home\model\Goods;
class IndexController extends Controller{
	public function index(){
		//推荐商品取出
		$goodsModel = new Goods;
		$hotGoods = $goodsModel->getTypeGoods('is_hot');
		$newGoods = $goodsModel->getTypeGoods('is_new',2);
		$bestGoods = $goodsModel->getTypeGoods('is_best',3);
		$crazyGoods = $goodsModel->getTypeGoods('is_crazy');
		$guessGoods = $goodsModel->getTypeGoods('is_guess');
		//三级分类
		$catsData = Category::select()->toArray();
		$cats = [];
		foreach($catsData as $cat){
			$cats[$cat['cat_id']] = $cat;
		}

		$children = [];
		foreach($catsData as $cat){
			$children[$cat['pid']][] = $cat['cat_id'];
		}
		//导航栏取出所有分类
		$navCats = Category::where(['is_show'=>1,'pid'=>0])->select();
		return $this->fetch('',[
			'navCats'=>$navCats,
			'cats'=>$cats,
			'children'=>$children,
			'hotGoods'=>$hotGoods,
			'newGoods'=>$newGoods,
			'bestGoods'=>$bestGoods,
			'crazyGoods'=>$crazyGoods,
			'guessGoods'=>$guessGoods
		]);
	}
	
}

