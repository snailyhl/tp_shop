<?php 

namespace app\home\controller;

use think\Db;
use think\Controller;

class CartController extends Controller
{
	# 订单结算
	public function orderAccount()
	{
		# 判断是否登录
		if ( !session('member_id') ) {
			$this->error('滚去登录');
		}
		# 判断购物车是否有数据
		$cart = new \cart\Cart();
		if ( !$cart->getCart() ) {
			$this->error('购物车没有数据', url('/'));
		}
		# 取出购物车的数据
		$cartData = $this->getCartGoods();
		return $this->fetch('', ['cartData' => $cartData]);
	}


	# 获取购物车商品数据
	public function getCartGoods()
	{
		# 1. 通过购物车类的getCart方法获取购物车的数据
		$cart = new \cart\Cart();
		$carts = $cart->getCart();

		# 构造便于输出到模板的数组结构
		$cartData = [];
		foreach ($carts as $key => $goods_number) {
			$arr = explode('-', $key);
			$goods_id = $arr[0];
			$goods_attr_ids = $arr[1];
			$cartData[] = [
				'goods_id' => $goods_id,
				'goods_attr_ids' => $goods_attr_ids,
				'goods_number' => $goods_number,
				'goodsInfo' => Db::name('goods')->find($goods_id),
				'attr' => Db::name('goods_attr')
					->alias('t1')
					->field("sum(t1.attr_price) attrTotalPrice, GROUP_CONCAT(t2.attr_name, ':', t1.attr_value SEPARATOR '<br/>') as attrInfo")
					->join('sh_attribute t2', 't1.attr_id = t2.attr_id', 'left')
					->where("t1.goods_id = " . $goods_id . ' and t2.attr_type = 1 and t1.goods_attr_id in ' . "(" . $goods_attr_ids . ")" )
					->find(),
			];
		}
		// halt($cartData);
		return $cartData;
	}


	# 更新购物车商品数量
	public function updateCartGood()
	{
		if ( request()->isAjax() ) {
			# 判断是否登录
			if ( !session('member_id') ) {
				$response = ['code' => 1, 'message' => '滚去登录'];
				echo json_encode($response);die;
			}
			# 1. 接受参数
			$goods_id = input('goods_id');
			$goods_number = input('goods_number');
			$goods_attr_ids = input('goods_attr_ids');
			# 2. 调用购物车方法更新数据库购物车表数据
			$cart = new \cart\Cart();
			$result = $cart->changeCartNum($goods_id, $goods_attr_ids, $goods_number);
			# 3. 判断是否成功,返回json数据
			if ( $result ) {
				$response = ['code' => 200, 'message' => 'success'];
				return json($response);
			}else {
				$response = ['code' => -2, 'message' => 'error'];
				return json($response);
			}
		}
	}


	# 清空购物车
	public function clearCartGood()
	{
		if ( request()->isAjax() ) {
			$cart = new \cart\Cart();
			// halt($cart);
			if ( $cart->clearCart() ) {
				$response = ['code' => 200, 'message' => '清空成功'];
				// halt($response);
				echo json_encode($response);die;
			}else {
				$response = ['code' => -3, 'message' => '清空失败'];
				echo json_encode($response);exit;
			}
		}
	}


	#  购物车删除商品
	public function delCartGood()
	{
		if ( request()->isAjax() ) {
			$goods_id = input('goods_id');
			$goods_attr_ids = input('goods_attr_ids');

			$cart = new \cart\Cart();
			if ( $cart->delCart($goods_id, $goods_attr_ids) ) {
				$response = [
					'code' => 200,
					'message' => '删除成功',
				];
				echo json_encode($response);die;
			}else {
				$reponse = [
					'code' => -1,
					'message' => '删除失败',
				];
				echo json_encode($response);die;
			}
		}
	}

	# 购物车列表
	public function cartList()
	{
		$member_id = session('member_id');
		if ( !$member_id ) {
			$this->error('', url('home/public/login'));
		}
		# 1. 通过购物车类的getCart方法获取购物车的数据
		$cart = new \cart\Cart();
		$carts = $cart->getCart();
		# 2. 构造需要的数组结构
		$cartData = [];
		foreach ( $carts as $k => $goods_number ) {
			$arr = explode('-', $k);
			$goods_id = $arr[0];
			$goods_attr_ids = $arr[1];
			$cartData[] = [
				'goods_id' => $goods_id,
				'goods_attr_ids' => $goods_attr_ids,
				'goods_number' => $goods_number,
				'goodsInfo' => Db::name('goods')->find( $arr[0] ),
				'attr' => Db::name('goods_attr')
					->alias('t1')
					->field("sum(t1.attr_price) attrTotalPrice, GROUP_CONCAT(t2.attr_name, ':', t1.attr_value SEPARATOR '<br/>') as attrInfo")
					->join('sh_attribute t2', 't1.attr_id = t2.attr_id', 'left')
					->where("t1.goods_id = " . $goods_id . ' and t2.attr_type = 1 and t1.goods_attr_id in ' . "(" . $goods_attr_ids . ")" )
					->find(),
			];
		}
		// halt($cartData);
		return $this->fetch('', ['cartData' => $cartData]);
	}

	# 添加商品到购物车
	public function addGoodsToCart()
	{
		if ( request()->isAjax() ) {
			# 1. 判断是否登录
			$member_id = session('member_id');
			if ( !$member_id ) {
				$response = ['code' => -1, 'message' => '滚去登录'];
				echo json_encode($response);die;
			}
			# 2. 接受参数
			$goods_id = input('goods_id');
			$goods_number = input('goods_number');
			$goods_attr_ids = input('goods_attr_ids');

			# 3. 调用购物车类的方法进行商品的入库
			$cart = new \cart\Cart();
			$result = $cart->addCart($goods_id, $goods_attr_ids, $goods_number);
			if ( $result ) {
				$response = ['code' => 200, 'message' => '加入购物车成功,赶紧交钱'];
				echo json_encode($response);die;
			}else {
				$response = ['code' => -2, 'message' => '加入购物车失败'];
				echo json_encode($response);die;
			}
		}
	}
}