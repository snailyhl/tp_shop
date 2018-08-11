<?php 

namespace app\home\controller;

use think\Controller;
use think\Db;
use app\home\model\OrderModel;
use app\home\model\GoodsModel;
use app\home\model\OrderGoodsModel;

class OrderController extends Controller
{
	# 我的订单
	public function selfOrder()
	{
		$orderData = OrderModel::where('member_id', session('member_id'))->select();
		return $this->fetch('', ['orderData' => $orderData]);
	}

	# 支付订单
	public function orderPay()
	{
		// halt('123');
		$member_id = session('member_id');
		if ( !$member_id ) {
			$this->error('滚去登录', url('/home/public/login'));
		}

		$postData = input('post.');
		$result = $this->validate($postData, 'Order.pay', [], true);
		if ( $result !== true ) {
			$this->error( implode(',', $result) );
		}

		$cartData = $this->getCartGood();
		if ( !cartData ) {
			$this->error('购物车为空,无需结算', url('/'));
		}

		# 生成唯一的订单号
		$order_id = date('ymdhis').time().uniqid();
		$total_price = 0;
		foreach ($cartData as $v) {
			$total_price += ($v['goodsInfo']['goods_price'] + $v['attr']['attrTotalPrice']) * $v['goods_number'];
		}

		# 开启事务,先入库到订单表
		$postData['order_id'] = $order_id;
		$postData['total_price'] = $total_price;
		$postData['member_id'] = $member_id;
		Db::startTrans();
		try{
			# 更新订单表
			$order_result = OrderModel::create($postData);
			if ( !order_result ) {
				throw new \Exception('订单表入库失败');
			}
			# 入库到订单表成功之后,把订单商品入库到订单商品表
			$goodsModel = new GoodsModel();
			foreach ($cartData as $v) {
				$goods_price = ($v['goodsInfo']['goods_price'] + $v['attr']['attrTotalPrice']) * $v['goods_number'];
				$order_goods_result = OrderGoodsModel::create([
					'order_id' => $order_id,
					'goods_id' => $v['goods_id'],
					'goods_attr_ids' => $v['goods_attr_ids'],
					'goods_number' => $v['goods_number'],
					'goods_price' => $goods_price
				]);
				# 减少商品的库存(购买数量小于库存数量才执行操作)
				$where = [
					'goods_id' => $v['goods_id'],
					# 商品库存数量大于购买数量
					'goods_number' => ['>=', $v['goods_number']]
				];
				# 更新商品表库存数
				$goods_result = $goodsModel->where($where)->setDec('goods_number', $v['goods_number']);
				if ( !$order_goods_result || !$goods_result ) {
					throw new \Excetption('库存不足,无法购买');
				}
			}
			# 上面三张表更新成功后,提交事务
			Db::commit();
		}catch( \Exception $e ) {
			Db::rollback();
		}
		# 清空购物车
		$cart = new \cart\Cart();
		$cart->clearCart();
		// echo '支付宝';exit;
		$this->_payMoney($order_id, '测试订单的名称', $total_price, '测试商品的描述');
	}

	# 订单付款
	public function payMoney()
	{
		$order_id = input('order_id');
		$total_price = OrderModel::where('order_id', $order_id)->value('total_price');
		$this->_payMoney($order_id, '订单付款测试', $total_price, '订单付款测试的描述');
	}

	/**
	 * 支付宝付款
	 * @param  num    $total_price 付款金额
	 * @param  string $order_id    订单号
	 * @param  string $title       订单名称
	 * @param  string $body        商品描述
	 * @return [type]              [description]
	 */
	private function _payMoney($order_id, $title, $total_price, $body = '')
	{
		// halt('123');
		$payData = [
			'WIDout_trade_no' => $order_id,
			'WIDsubject' => $title,
			'WIDtotal_amount' => $total_price,
			'WIDbody' => $body,
		];

		include "../extend/alipay/pagepay/pagepay.php";
	}

	# 支付宝get同步方式通知支付结果的跳转地址
	public function returnUrl()
	{
		require_once("../extend/alipay/config.php");
        require '../extend/alipay/pagepay/service/AlipayTradeService.php';
        //会以get方式携带支付的结果参数到此页面
        $arr=input('get.');
        $alipaySevice = new \AlipayTradeService($config);
        $result = $alipaySevice->check($arr);
        //halt($result);

      if($result){//验证成功
            //商户订单号
            $out_trade_no = htmlspecialchars($arr['out_trade_no']);
            //支付宝交易号
            $trade_no = htmlspecialchars($arr['trade_no']);
                
            //更新订单的状态为已支付
            $orderModel = new OrderModel();
            $result = $orderModel->where("order_id", $out_trade_no)->update(['pay_status'=>1,'ali_order_id'=>$trade_no]);
            if($result){
                //支付成功
                $this->error('支付成功',url('/home/order/orderDone'));
            }else{
                //支付失败（跳转到个人订单列表）
                $this->error('支付失败',url('/'));
            }
        }
        else {
            //验证失败
            echo "验证失败";
        }
	}

	#支付宝支付完成页面
	public function orderDone()
	{
		return $this->fetch('');
	}

	# 支付宝get异步方式通知支付结果的跳转地址	
	public function notifyUrl()
	{
		require_once('../extend/alipay/config.php');
		require_once('../extend/alipay/pagepay/service/AlipayTradeService.php');
		// 接受通知携带的支付结果参数
		$arr = input('post.');
		// halt($arr);
		$alipaySevice = new \AlipayTradeService($config);
		$result = $alipaySevice->check($arr);

		/* 实际验证过程建议商户添加以下校验。
		1、商户需要验证该通知数据中的out_trade_no是否为商户系统中创建的订单号，
		2、判断total_amount是否确实为该订单的实际金额（即商户订单创建时的金额），
		3、校验通知中的seller_id（或者seller_email) 是否为out_trade_no这笔单据的对应的操作方（有的时候，一个商户可能有多个seller_id/seller_email）
		4、验证app_id是否为该商户本身。
		*/
		if($result) {//验证成功
			//请在这里加上商户的业务逻辑程序代码
			
			//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
		    //获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

			//商户订单号
			$out_trade_no = htmlspecialchars($arr['out_trade_no']);

			//支付宝交易号
			$trade_no = htmlspecialchars($arr['trade_no']);
				
			// 更新订单的状态为已支付
			$orderModel = new OrderModel();
			$result = $orderModel->where('order_id', $out_trade_no)->update(['pay_status' => 1, 'ali_order_id' => $trade_no]);
			if ( $result ) {
				echo 'success';
			}
		}
		else {
		    //验证失败
		    echo "验证失败";
		}
	}

	# 获得购物车详细的商品数据
	public function getCartGood()
	{
		$cart = new \cart\Cart();
		$carts = $cart->getCart();
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
					->join('sh_attribute t2', 't1.attr_id = t2.attr_id', 'left')
					->where('t1.goods_attr_id', 'in', $goods_attr_ids)
					->find(),
			];
		}
		return $cartData;
	}
}