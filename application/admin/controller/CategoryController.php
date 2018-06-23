<?php 
namespace app\admin\controller;
use app\admin\model\Category;
//商品分类类
class CategoryController extends CommonController{
	public function add(){
		if(request()->isPost()){
			//接收数据
			$postData = input('post.');
			//验证数据
			$result = $this->validate($postData,'Category.add',[],true);
			if($result!==true){
				$this->error(implode(',',$result));
			}
			//模型操作
			$categoryModel = new Category();
			if($categoryModel->allowField(true)->save($postData)){
				$this->success('添加成功',url("admin/category/index"));
			}else{
				$this->error('添加失败');
			}
		}

		$catModel = new Category();
		$cats = $catModel->getSonsCat();
		return $this->fetch('',['cats'=>$cats]);
	}

	public function index(){
		$catModel = new Category();
		$lists = $catModel->getSonsCat();
		return $this->fetch('',['lists'=>$lists]);
	}

	public function upd(){
		$categoryModel = new Category();
		$cat_id =input('cat_id'); 
		if(request()->isPost()){
			//接收数据
			$postData = input('post.');

			if($categoryModel->where('pid','eq',$cat_id)->find()){
				$this->error('有子级存在不允许修改');
			}
			//验证数据
			$result = $this->validate($postData,'Category.upd',[],true);
			if($result!==true){
				$this->error(implode(',',$result));
			}
			//模型操作
			
			if($categoryModel->allowField(true)->isUpdate(true)->save($postData)){
				$this->success('修改成功',url("admin/category/index"));
			}else{
				$this->error('修改失败');
			}
		}

		
		$data = $categoryModel->find($cat_id);
		$cats = $categoryModel->getSonsCat();
		return $this->fetch('',['data'=>$data,'cats'=>$cats]);
	}

	public function ajaxDel(){
		if(request()->isAjax()){
			$cat_id = input('cat_id');
			$catModel = new Category();
			if($catModel->where('pid','eq',$cat_id)->find()){
				return json(['code'=>-1,'message'=>'存在子级分类不允许删除']);
			}
			if($catModel->destroy($cat_id)){
				return json(['code'=>200,'message'=>'删除成功']);
			}else{
				return json(['code'=>-2,'message'=>'删除失败']);
			}
		}
	}
}