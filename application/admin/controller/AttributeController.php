<?php 

namespace app\admin\controller;

use think\Controller;

use app\admin\model\AttributeModel;
use app\admin\model\TypeModel;

class AttributeController extends CommonController
{
	# 商品属性的删除
	public function del()
	{
		$attr_id = input('attr_id');
		if ( AttributeModel::destroy($attr_id) ) {
			$this->success('删除成功', url('admin/attribute/index'));
		}else {
			$this->error('删除失败');
		}
	}

	# 商品属性编辑
	public function upd()
	{
		if(request()->isPost()){
			//接收参数
			$postData = input('post.');
			//验证器验证
			if ( $postData['attr_input_type'] == 1 ) {
				$result = $this->validate($postData,'Attribute.upd',[],true);
			}else {
				$result = $this->validate($postData,'Attribute.except_attr_values',[],true);
			}
			if($result !== true){
				$this->error(implode(',',$result));
			}
			//入库
			$attributeModel = new AttributeModel();
			if( $attributeModel->allowField(true)->isUpdate(true)->save($postData) ){
				$this->success("入库成功",url("admin/attribute/index"));
			}else{
				$this->error("入库失败");
			}
		}
		$attr_id = input('attr_id');
		$attr = AttributeModel::find($attr_id);
		# 取出商品类型
		$types = TypeModel::select();
		return $this->fetch('', [
			'attr' => $attr, 
			'types' => $types,
		]);
	}

	# 商品属性列表
	public function index()
	{
		$attrs = AttributeModel::alias('attr')
			->field('attr.*, type.type_name')
			->join('sh_type type', 'attr.type_id = type.type_id', 'left')
			->select();

		return $this->fetch('', ['attrs' => $attrs]);
	}

	# 添加商品属性
	public function add()
	{
		if ( request()->isPost() ) {
			$postData = input('post.');
			if ( $postData['attr_input_type'] == 1 ) {
				# 如果录入方式为列表选择
				$result = $this->validate($postData, 'Attribute.add', [], true);
			}else {
				$result = $this->validate($postData, 'Attribute.except_attr_values', [], true);
			}
			if ( $result !== true ) {
				$this->error( implode(',', $result) );
			}

			# 实例化模型写入数据库
			$attributeModel = new AttributeModel();
			if ( $attributeModel->allowField(true)->save($postData) ) {
				$this->success('添加成功', url('admin/attribute/index'));
			}else {
				$this->error('添加失败');
			}
		}
		# 查询所有商品类型并分配到模板
		$types = TypeModel::select();
		return $this->fetch('', ['types' => $types]);
	}
}