<?php 
namespace app\admin\controller;
use app\admin\model\Category;
use app\admin\model\Type;
use app\admin\model\Goods;
use app\admin\model\Attribute;
//商品类
class GoodsController extends CommonController{
	//列表页
	public function index(){
		
		$lists = Goods::where('is_delete',0)->select();
		//核心 是把json 转变为一个二维数组在页面上展示出来
		foreach($lists as $k=>$list){
			// halt($lists);
			$img=json_decode($list['goods_thumb'],true);

			$lists[$k]['goods_thumb']=$img;
			// halt($lists);
		}

		return $this->fetch('',['lists'=>$lists]);
	}
	//添加页
	public function add(){
		if(request()->isPost()){
			$goodsModel = new Goods();
			//接收数据
			$postData = input('post.');
			// halt($postData);
			//验证数据
			$result = $this->validate($postData,'Goods.add',[],true);
			if($result!==true){
				$this->error(implode(',',$result));
			}
			//模型操作
			$goods_img = $goodsModel->uploadImg();
			//判断返回来的数组是否为空
			if($goods_img){
				$result = $goodsModel->thumbImg($goods_img);
				//将路径取出并转为json格式存入数据库
				$postData['goods_img']=json_encode($goods_img);
				$postData['goods_middle']=json_encode($result['middle']);
				$postData['goods_thumb']=json_encode($result['small']);
			}
			if($goodsModel->allowField(true)->save($postData)){
				$this->success('添加成功',url("admin/goods/index"));
			}else{
				$this->error('添加失败');
			}
		}
		$catModel = new Category();
		//取出商品分类分配到模板
		$cats = $catModel->getSonsCat();
		$types = Type::select();
		return $this->fetch('',['cats'=>$cats,'types'=>$types]);		
	}		
	//选择分类时触发事件查询商品属性
	public function ajaxGetTypeAttr(){
		if(request()->isAjax()){
			$type_id = input('type_id');
			$attrs = Attribute::where('type_id',$type_id)->select();
			return json($attrs);
		}
	}
	//弹出层的内容取出来
	public function showContent(){
		if(request()->isAjax()){
			$goods_id = input('goods_id');
			$data = Goods::find($goods_id);
			return json($data);
		}
	}
	//点击对勾不显示热卖
	public function ajaxUpdateIs_hot(){
		if(request()->isAjax()){
			$goods_id = input('goods_id');
			$is_hot = input('is_hot')==1?0:1;
			$data=['is_hot'=>$is_hot,'goods_id'=>$goods_id];
			$goodsModel = new Goods();
			if($goodsModel->isUpdate(true)->allowField(true)->save($data)){
				return json(['code'=>200,"is_hot"=>$is_hot]);
			}else{
				return json(['code'=>-1,'message'=>'修改失败']);
			}
		}
	}
	//回收站主页
	public function huishouzhan(){
		$lists = Goods::where('is_delete',1)->select();
		//核心 是把json 转变为一个二维数组在页面上展示出来
		foreach($lists as $k=>$list){
			// halt($lists);
			$img=json_decode($list['goods_thumb'],true);

			$lists[$k]['goods_thumb']=$img;
			// halt($lists);
		}

		return $this->fetch('',['lists'=>$lists]);
	}
	//加入回收站
	public function ajaxhuishouzhan(){
		if(request()->isAjax()){
			$goods_id = input('goods_id');
			$data = ['goods_id'=>$goods_id,'is_delete'=>1];
			$goodsModel = new Goods();
			if($goodsModel->isUpdate(true)->save($data)){
				return json(['code'=>200,'message'=>'操作成功']);
			}else{
				return json(['code'=>-1,'message'=>'加入回收站失败']);
			}
		}
	}
	//回收站内的恢复
	public function ajaxhuifu(){
		if(request()->isAjax()){
			$goods_id = input('goods_id');
			$data = ['goods_id'=>$goods_id,'is_delete'=>0];
			$goodsModel = new Goods();
			if($goodsModel->isUpdate(true)->save($data)){
				return json(['code'=>200,'message'=>'操作成功']);
			}else{
				return json(['code'=>-1,'message'=>'恢复失败']);
			}
		}
	}
}