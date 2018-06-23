<?php 
namespace app\home\controller;
use think\Controller;
use app\home\model\Category;
use app\home\model\Goods;
class CategoryController extends Controller{
	//商品分类列表页
	public function index(){
		/*********面包屑导航*********/
		$cat_id = input('cat_id');
		$catModel = new Category;
		$FamilyCats = $catModel->getFamilyCat($cat_id);
		/**********侧边栏分类**********/
		$catsData = Category::select()->toArray();
		$cats = [];
		foreach($catsData as $cat){
			$cats[$cat['cat_id']] = $cat;
		}
		$children = [];
		foreach($catsData as $cat){
			$children[$cat['pid']][] = $cat['cat_id'];
		}
		/********分类列表展示商品页********/
		//查询出所有的子类id
		$sonsCatsId = $catModel->getSonsCatsId($cat_id);
		$sonsCatsId[] = $cat_id;
		$goodsModel = new Goods();
		$condition = [
			'is_delete' => 0,
			'is_sale' => 1,
			'cat_id' =>['in',$sonsCatsId]
		];
		$catsGoods = $goodsModel->where($condition)->select();
		return $this->fetch('',[
			'FamilyCats' =>$FamilyCats,
			'cats' =>$cats,
			'children' =>$children,
			'catsGoods'=>$catsGoods
		]);
	}
}