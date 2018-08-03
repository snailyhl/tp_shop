<?php 

namespace app\admin\controller;

use think\Controller;
use app\admin\model\CategoryModel;

class CategoryController extends CommonController
{
	public function del()
	{
		$cat_id = input('cat_id');
		// halt($cat_id);
		// halt(CategoryModel::destroy($cat_id));
		$cats = CategoryModel::where('pid', $cat_id)->select();

		if ( $cats ) {
			$this->error('该分类含有子分类,无法删除');
		}else {
			if ( CategoryModel::destroy($cat_id) ) {
				$this->success('删除成功', url('admin/category/index'));
			}else {
				$this->error('删除失败');
			}
		} 
	}


	public function upd()
	{
		if(request()->isPost()){
			//接收参数
			$postData = input('post.');
			//验证器验证
			$result = $this->validate($postData,'Category.upd',[],true);
			if($result !== true){
				$this->error(implode(',',$result));
			}
			//入库
			$categoryModel = new CategoryModel();
			if($categoryModel->update($postData)){
				$this->success("编辑分类成功",url("admin/category/index"));
			}else{
				$this->error("编辑分类失败");
			}
		}
		# 回显数据到表单
		$cat_id = input('cat_id');
		$categoryModel = new CategoryModel();
		$cat = $categoryModel->find($cat_id);
		$categorys= $categoryModel->getSonCats( $categoryModel->select() );
		// halt($catInfo);
		return $this->fetch('', [
			'cat' => $cat, 
			'categorys' => $categorys
		]);
	}


	public function index()
	{
		$categoryModel = new CategoryModel();
		$cats = $categoryModel->getSonCats( $categoryModel->select()->toArray() );
		return $this->fetch('', [ 'cats' => $cats ]);
	}



	public function add()
	{
		$catgoryModel = new CategoryModel();
		if(request()->isPost()){

			//接收参数
			$postData = input('post.');
			//验证器验证
			$result = $this->validate($postData, 'Category.add', [], true);
			if($result !== true){
				$this->error(implode(',', $result));
			}
			//入库
			if($catgoryModel->allowField(true)->save($postData)){
				$this->success("入库成功",url("admin/category/index"));
			}else{
				$this->error("入库失败");
			}
		}
		# 取出无限级分类的数据分配到模板
		
		$cats = $catgoryModel->getSonCats( $catgoryModel->select() );
		return $this->fetch('', [
			'cats' => $cats,
		]);
	}
}