<?php 

namespace app\home\model;
use think\Model;

class GoodsModel extends Model{
	protected $table = 'sh_goods';
	protected $pk = 'goods_id';

	# 将商品id加入浏览历史cookie中
	public function addGoodsToHistory($goods_id)
	{
		# 判断cookie('history')是否已经存在
		$history = cookie('history') ? cookie('history') : [];
		if ( $history ) {
			# 浏览历史已经有数据的时候
			# 1. 把商品浏览历史加入$history头部
			array_unshift($history, $goods_id);
			# 2. 去除$history中重复的商品
			$history = array_unique($history);
			# 3. 判断$history是否超过指定长度
			if ( count($history) > 5 ) {
				# 移除$history最后一个元素
				array_pop($history);
			}
		}else {
			# $history中没有数据
			$history[] = $goods_id;
		}

		# 把浏览历史写入cookie
		cookie('history', $history, 3600*24*7);
		# 返回数据
		return $history;
	}	

	public function getGoods($type, $limit)
	{	
		// 定义初始查询条件
		$condition = [ 'is_sale' => 1 ];
		switch( $type ){
			case 'is_crazy':
				// 按照价格升序取出数据
				$data = $this->where( $condition )->order('goods_price asc')->limit( $limit )->select();
				break;
			default:
				$condition[ $type ] = ['=', 1]; // $condition = [$type => '1'];
				$data = $this->where( $condition )->limit( $limit )->select();
				break;
		}
		// halt($data);
		return $data;
	}
}