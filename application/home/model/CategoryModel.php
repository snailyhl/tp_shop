<?php 

namespace app\home\model;

use think\Model;

class CategoryModel extends Model{
	protected $table = 'sh_category';
	protected $pk = 'cat_id';

	# 获取当前分类的所有后代分类的cat_id
	public function getSonCatIds($cats, $cat_id)
	{
		static $sonIds = [];
		foreach ($cats as $k => $v) {
			if ( $v['pid'] == $cat_id ) {
				$sonIds[] = $v['cat_id'];
				unset( $cats[$k] );
				$this->getSonCatIds($cats, $v['cat_id']);
			}
		}
		return $sonIds;
	}

	# 获取当前分类所有祖先分类的方法
	public function getParentCats($cats, $cat_id)
	{
		static $result = [];
		foreach ($cats as $k => $v) {
			# 第一次循环,先找到自己
			if ( $v['cat_id'] == $cat_id ) {
				$result[] = $v;
				# 删除已经判断过的分类
				unset($cats[$k]);
				# 第二个参数传当前分类的pid,下次递归找当前分类的父分类
				$this->getParentCats($cats, $v['pid']);
			}
		}
		// 获得的结果为从当前分类->父分类->.....->顶级分类的数组,所以需要反转
		return array_reverse($result);
	}

	# 获取导航栏的分类数据
	public function getNavData($limit)
	{
		return $this->where('is_show', '=', '1')->limit($limit)->select();
	}
}