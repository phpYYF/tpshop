<?php 
namespace app\admin\model;
use think\Model;
use think\Db;
class Goods extends Model{
	protected $pk = 'goods_id';
	protected $autoWriteTimestamp = true;
	protected static function init(){
		Goods::event('before_insert',function($goods){
			$goods['goods_sn'] = date('ymdhis').uniqid();
		});
		Goods::event('after_insert',function($goods){
			$goodsAttrValue = $goods['goodsAttrValue'];
			$goodsAttrPrice = $goods['goodsAttrPrice'];
			$goods_id = $goods['goods_id'];
			foreach($goodsAttrValue as $attr_id=>$attr_values){
				if(is_array($attr_values)){
					foreach($attr_values as $k=>$single_attr_value){
						$data=[
							'goods_id'=>$goods_id,
							'attr_id'=>$attr_id,
							'attr_value'=>$single_attr_value,
							'attr_price'=>$goodsAttrPrice[$attr_id][$k],
							'create_time'=>time(),
							'update_time'=>time(),
						];
						Db::name('goods_attr')->insert($data);
					}
				}else{
					$data=[
							'goods_id'=>$goods_id,
							'attr_id'=>$attr_id,
							'attr_value'=>$attr_values,
							'create_time'=>time(),
							'update_time'=>time(),
						];
						Db::name('goods_attr')->insert($data);
				}
			}
		});
	}

	public function uploadImg(){
		//建立一个空数组保存接下来要保存的路径可能是多个图片路径 用数组保存
		$goods_img = [];
		//接受上传上来的图片资源
		$files = request()->file('img');
		//判断是否有图片上传来
		if( $files ){
			//设置验证规则
			$condition = ['size'=>5*1024*1024,'ext'=>'jpg,jpeg,png,gif'];
			//设置保存路径
			$uploadDir = "./upload/";
			//可能是多个图片 用foreach循环保存
			foreach($files as $file){
				//把图片移动到设置的地址上面
				$info = $file->validate($condition)->move($uploadDir);
				//判断是否成功
				if($info){
					//成功后把日期目录和文件名保存到数组当中
					$goods_img[]=str_replace('\\','/',$info->getSaveName());
				}
			}
		}
		//返回数组
		return $goods_img;
	}

	public function thumbImg($goodsImg){
		//@$goodsImg  原图的地址路径 是一个一维数组
		//设置空数组保存中图路径//中图350*350
		$middle = []; 
		//设置空数组保存小图路径//小图50*50
		$small = []; 
		//循环$goodsImg 取出具体的路径 中图
		foreach($goodsImg as $path){
			//把路径拆分为数组 通过\ 拆分 
			$path_arr = explode('/',$path);
			//生成保存的路径
			$middle_path = $path_arr[0].'/middle_'.$path_arr[1];
			// 通过路径打开图片
			$image = \think\Image::open('./upload/'.$path);
			// 通过打开的图片资源生成缩略图 设置大小以是否补白  save 设置保存路径
			$image->thumb(350,350,2)->save('./upload/'.$middle_path);
			//将路径数组当中
			$middle[] = $middle_path;
		}
		//小图
		foreach($goodsImg as $path){
			$path_arr = explode('/',$path);
			$small_path = $path_arr[0].'/small_'.$path_arr[1];
			$image = \think\Image::open('./upload/'.$path);
			$image->thumb(50,50,2)->save('./upload/'.$small_path);
			$small[] = $small_path;
		}
		//返回中图和小图的路径  设置对应的键名用于取值
		return ['middle'=>$middle,'small'=>$small];
	}
}