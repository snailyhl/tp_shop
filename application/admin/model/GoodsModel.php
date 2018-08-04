<?php 

namespace app\admin\model;
use think\Model;
use think\Db;


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

		# 入库后事件,完成商品属性入库到商品属性表
		GoodsModel::event('after_insert', function ($goods)
		{
			# $goods 表单数据入库后返回表的记录数据对象
			$goods_id = $goods['goods_id'];
			$postData = input('post.');
			$goodsAttrValue = $postData['goodAttrValue'];
			$goodsAttrPrice = $postData['goodsAttrPrice'];
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
			$image->thumb(350, 350, 2)->save('./static/upload/' . $thumb_path);
		}
		return ['goods_middle' => $goods_middle, 'goods_thumb' => $goods_thumb];
	}
}