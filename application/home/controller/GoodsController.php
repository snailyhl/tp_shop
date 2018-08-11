<?php 

namespace app\home\controller;

use think\Controller;
use think\Db;
use app\home\model\GoodsModel;
use app\home\model\CategoryModel;

class GoodsController extends Controller
{
	public function detail()
	{
		$goods_id = input('goods_id');
		$goodsInfo = GoodsModel::find($goods_id)->toArray();
		// halt($goodsInfo);
		# 面包屑导航
		$catModel = new CategoryModel();
		$cats = $catModel->select();
		$parentCats = $catModel->getParentCats($cats, $goodsInfo['cat_id']);
		# 商品图片
		$goodsInfo['goods_img'] = json_decode($goodsInfo['goods_img']);
		$goodsInfo['goods_middle'] = json_decode($goodsInfo['goods_middle']);
		$goodsInfo['goods_thumb'] = json_decode($goodsInfo['goods_thumb']);

		# 取出商品的单选属性
		$_singleAttrs = Db::name('goods_attr')
			->alias('t1')
			->field('t1.*, t2.attr_name')
			->join('sh_attribute t2', 't1.attr_id = t2.attr_id', 'left')
			->where('t1.goods_id =' . $goods_id . ' and t2.attr_type = 1')
			->select();
		# 通过attr_id把单选属性进行分组,方便后续再模板中遍历
		$singleAttrs = [];
		foreach($_singleAttrs as $v) {
			$singleAttrs[ $v['attr_id'] ][] = $v;
		}
		// halt($singleAttrs);
		# 取出商品的唯一属性
		$onlyAttrs = Db::name('goods_attr')
			->field('t1.*, t2.attr_name')
			->alias('t1')
			->join('sh_attribute t2', 't1.attr_id = t2.attr_id', 'left')
			->where('t1.goods_id = ' . $goods_id . ' and t2.attr_type = 0')
			->select();

		# 把访问过的商品goods_id加入到浏览历史cookie中
		$goodsModel = new GoodsModel();
		$history = $goodsModel->addGoodsToHistory($goods_id);
		# 取出浏览器历史中的商品信息
		$where = [
			'is_delete' => 0,
			'is_sale' => 1,
			'goods_id' => ['in', $history],
		];
		# 数组转换为字符串
		$goods_str_ids = implode(',', $history);
		$historyDatas = GoodsModel::where($where)->order("field(goods_id, $goods_str_ids)")->select()->toArray();
		// halt($historyDatas);

		return $this->fetch('', [
			'parentCats' => $parentCats,
			'goodsInfo' => $goodsInfo,
			'singleAttrs' => $singleAttrs,
			'onlyAttrs' => $onlyAttrs,
			'historyDatas' => $historyDatas,
		]);
	}
}