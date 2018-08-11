<?php 

namespace app\admin\model;

use think\Model;
use think\Db;
use think\Validate;
use app\admin\model\CategoryModel;

class GoodsModel extends Model{
	protected $table = 'sh_goods';
	protected $pk = 'goods_id';
	protected $autoWriteTimestamp = true;

	protected static function init ()
	{
		GoodsModel::event('before_insert', function ($goods)
		{
			// 生成唯一货号
			$goods['goods_sn'] = date('ymdhis').uniqid();
		});

		# 入库后事件,完成商品属性入库到商品属性表sh_goods_attr,并将商品分类入库到分类表sh_category
		GoodsModel::event('after_insert', function ($goods)
		{
			# $goods 表单数据入库后返回表的记录数据对象
			$goods_id = $goods['goods_id'];
			$postData = input('post.');

			// halt($goods['goods_id']);
			$goodsAttrValue = $postData['goodsAttrValue'];
			$goodsAttrPrice = $postData['goodsAttrPrice'];
			// var_dump($goodsAttrPrice);echo "<hr/>";	halt($goodsAttrValue);
			foreach ($goodsAttrValue as $attr_id => $attr_value) {
				# 单选属性$attr_value是一个数组
				if ( is_array($attr_value) ) {
					foreach ($attr_value as $k => $single_attr_value) {
						$data = [
							'goods_id' => $goods_id,
							'attr_id' => $attr_id,
							'attr_value' => $single_attr_value,
							# 通过下标获取单选属性对应的价格
							'attr_price' => $goodsAttrPrice[$attr_id][$k],
							'create_time' => time(),
							'update_time' => time(),
						];
						// halt($data);
						Db::name('goods_attr')->insert($data);
					}
				}else {
					$data = [
						'goods_id' => $goods_id,
						'attr_id' => $attr_id,
						'attr_value' => $attr_value,
						'create_time' => time(),
						'update_time' => time(),
					];
					Db::name('goods_attr')->insert($data);
				}
			}

			# 将商品入库到分类表sh_category
			# 在配置文件中设置新增商品分类是否显示 (show_new_goods)
			// unique:category
			$validate = new Validate([
				'cat_name' => 'unique:category',
			]);
			$data = [
				'cat_name' => $postData['goods_name'],
				'is_show' => config('show_new_goods'),
				'pid' => $postData['cat_id']
			];
			if ( $validate->check($data) ) {
				$category = new CategoryModel();
				$category->allowField(true)->save($data);
			}
		});
	}


	# 文件上传的方法
	public function uploadImg()
	{
		$goods_img = [];
		$files = request()->file('img');
		if ( $files ) {
			$validate = [
				'size' => 3*1024*1024,
				'ext' => 'jpg,png,gif,jpeg',
			];
			$uploadDir = './static/upload/';
			foreach ($files as $file) {
				$info = $file->validate($validate)->move($uploadDir);
				if ( $info ) {
					$goods_img[] = str_replace('\\', '/', $info->getSaveName());
				}
			}
		}
		return $goods_img;
	}

	# 生成缩略图的方法
	public function thumb($goods_img)
	{
		$goods_middle = [];
		$goods_thumb = [];

		// 生成350*350的缩略图
		foreach ($goods_img as $path) {	
		// $path: "20180803/b1c8b9437849f31ebef17481a1ff8833.jpg"
			// halt($path);
			$arr_path = explode( '/', $path );
			$middle_path = $arr_path[0] . '/middle_' . $arr_path[1];
			# 存入数组
			$goods_middle[] = $middle_path;
			# 生成缩略图并保存
			$image = \think\Image::open('./static/upload/' . $path);
			# 宽350 高350 参数2表示图片不够大是用空白填充
			$image->thumb(350, 350, 2)->save('./static/upload/' . $middle_path);
		}

		foreach ($goods_img as $str_img) {
			$arr_img = explode('/', $str_img);
			$thumb_path = $arr_img[0] . '/thumb_' . $arr_img[1];
			$goods_thumb[] = $thumb_path;
			$image = \think\Image::open('./static/upload/' . $str_img);
			$image->thumb(50, 50, 2)->save('./static/upload/' . $thumb_path);
		}
		return ['goods_middle' => $goods_middle, 'goods_thumb' => $goods_thumb];
	}
}