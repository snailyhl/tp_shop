<?php 

namespace app\admin\controller;

// use think\Controller;
use app\admin\model\CategoryModel;
use app\admin\model\GoodsModel;
use app\admin\model\TypeModel;
use app\admin\model\AttributeModel;

class GoodsController extends CommonController
{
	public function index()
	{
		$goods = GoodsModel::alias('t1')
			->field('t1.*, t2.cat_name')
			->join('sh_category t2', 't1.cat_id = t2.cat_id', 'left')
			->select();
		return $this->fetch('', ['goods' => $goods]);
	}

	public function add()
	{
		$goodsModel = new GoodsModel();
		if(request()->isPost()){
			//接收参数
			$postData = input('post.');
			// halt($postData);
			// 验证器验证
			$result = $this->validate($postData,'Goods.add',[],true);
			if($result !== true){
				$this->error(implode(',',$result));
			}

			# 文件上传 
			$goods_img = $goodsModel->uploadImg();	# 调用模型的自定义uploadImg()方法
			if ( $goods_img ) {
				# 调用goodsModel的自定义方法thumb()生成缩略图
				$thumb = $goodsModel->thumb($goods_img);
				$postData['goods_img'] = json_encode( $goods_img );
				$postData['goods_middle'] = json_encode( $thumb['goods_middle'] );
				$postData['goods_thumb'] = json_encode( $thumb['goods_thumb'] );
			}
			// halt($postData);
			//入库
			if($goodsModel->allowField(true)->save($postData)){
				$this->success("入库成功",url("admin/goods/index"));
			}else{
				$this->error("入库失败");
			}
		}
		# 取出所有无限级分类的数据
		$categoryModel = new CategoryModel();
		$categorys = $categoryModel->getSonCats( $categoryModel->select() );
		# 取出商品的类型分配到模板
		$types = TypeModel::select();
		return $this->fetch('', [
			'categorys' => $categorys,
			'types' => $types,
		]);
	}

	public function getTypeAttr()
	{
		if ( request()->isAjax() ) {
			$type_id = input('type_id');
			$attributes = AttributeModel::where('type_id', $type_id)->select();
			echo json_encode( $attributes );exit;
		}
	}
}