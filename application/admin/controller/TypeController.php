<?php 

namespace app\admin\controller;

use think\Controller;
use app\admin\model\TypeModel;
use app\admin\model\AttributeModel;

class TypeController extends CommonController
{
	# 查看商品类型属性
	public function getAttr()
	{
		$type_id = input('type_id');
		$type_name = TypeModel::where('type_id', '=', $type_id)->value('type_name');
		$attrs = AttributeModel::where('type_id', '=', $type_id)->select();
		return $this->fetch('', ['attrs' => $attrs, 'type_name' => $type_name]);
	}

	public function del()
	{
		$type_id = input('type_id');
		if ( TypeModel::destroy($type_id) ) {
			$this->success('删除成功', url('admin/type/index'));
		}else {
			$this->error('删除失败');
		}
	}

	# 商品类型编辑
	public function upd()
	{
		if ( request()->isPost() ) {
			$postData = input('post.');
			// halt($postData);
			$result = $this->validate($postData, 'Type.upd', [], true);
			if ( $result !== true ) {
				$this->error( implode(',', $result) );
			}
			$typeModel = new TypeModel();
			if ( $typeModel->update($postData) ) {
				$this->success('编辑商品类型成功', url('admin/type/index'));
			}else {
				$this->error('编辑商品类型失败');
			}
		}
		$type_id = input('type_id');
		$typeInfo = TypeModel::find($type_id);
		return $this->fetch('', ['typeInfo' => $typeInfo]);
	}

	# 商品类型列表
	public function index()
	{
		$types = TypeModel::select();
		// halt($types);
		return $this->fetch('type/index', ['types' => $types]);
	}

	# 添加商品类型
	public function add()
	{
		$typeModel = new TypeModel();
		if ( request()->isPost() ) {
			$postData = input('post.');
			$result = $this->validate($postData, 'Type.add', [], true);
			if ( $result !== true ) {
				$this->error( implode(',', $result) );
			}
			if ( $typeModel->allowField(true)->save($postData) ) {
				$this->success('添加成功', url('admin/type/index'));
			}else {
				$this->error('添加失败');
			}
		}
		return $this->fetch();
	}
}