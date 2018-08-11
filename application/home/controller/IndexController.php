<?php 

namespace app\home\controller;

use think\Controller;
use app\home\model\CategoryModel;
use app\home\model\GoodsModel;

class IndexController extends Controller 
{


	public function index()
	{
		# 取出导航栏分类数据
		$categoryModel = new CategoryModel();
		$navDatas = $categoryModel->getNavData(5);
			
		# 取出所有分类数据
		$oldCats = CategoryModel::select();
		# 俩个技巧
		# 1. 以cat_id主键作为cats二维数组的下标
		$cats = [];
		foreach ($oldCats as $cat) {
			$cats[ $cat['cat_id'] ] = $cat;
		}

		# 2. 根据pid进行分组
		$children = [];
		foreach ($oldCats as $cat) {
			$children[ $cat['pid'] ][] = $cat['cat_id'];
		}

		// 取出前台推荐位的商品数据
		$goodsModel = new GoodsModel();
		$crazyDatas = $goodsModel->getGoods('is_crazy', 5);
		$hotDatas = $goodsModel->getGoods('is_hot', 5);
		$bestDatas = $goodsModel->getGoods('is_best', 5);
		$newDatas = $goodsModel->getGoods('is_new', 5);

		return $this->fetch('', [
			'navDatas' => $navDatas,
			'cats' => $cats, 
			'children' => $children,
			'crazyDatas' => $crazyDatas, 
			'hotDatas' => $hotDatas, 
			'bestDatas' => $bestDatas, 
			'newDatas' => $newDatas
		]);
	}


}