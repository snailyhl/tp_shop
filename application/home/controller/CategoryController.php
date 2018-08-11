<?php 

namespace app\home\controller;

use think\Controller;
use app\home\model\GoodsModel;
use app\home\model\CategoryModel;

class CategoryController extends Controller
{
	public function index()
	{
		$cat_id = input('cat_id');
		$catModel = new CategoryModel();
		$cats = $catModel->select()->toArray();
		
		# 获取当前分类的祖先分类
		$parentCats = $catModel->getParentCats($cats, $cat_id);
		// halt($parentCats);

		# 通过俩个技巧实现
		# 1. 以cat_id作为二维数组下标
		$catsdata = [];
		foreach ($cats as $v) {
			$catsData[ $v['cat_id'] ] = $v;
		}
		# 2. 通过pid对分类cat_id进行分组 
		$children = [];
		foreach ($cats as $vv) {
			$children[ $vv['pid'] ][] = $vv['cat_id'];
		}

		# 获取当前分类的所有后代分类的cat_id
		$sonCatIds = $catModel->getSonCatIds($cats, $cat_id);
		# 添加当前分类
		$sonCatIds[] = $cat_id;
		// halt($sonCatIds);
		$where = [
			'is_sale' => 1,
			'is_delete' => 0,
			'cat_id' => ['in', $sonCatIds],
		];
		$goodsData = GoodsModel::where($where)->select()->toArray();

		return $this->fetch('', [
			'parentCats' => $parentCats, 
			'catsData' => $catsData, 
			'children' => $children,
			'goodsData' => $goodsData,
		]);
	}
}