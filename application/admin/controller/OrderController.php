<?php 

namespace app\admin\controller;
use think\Db;

class OrderController extends CommonController
{
	# 查询物流
	public function getwuliu()
	{
		if ( request()->isAjax() ) {
			$company = input('company');
			$number = input('number');
			$url = "http://www.kuaidi100.com/applyurl?key=9d37bc6b0a41e6fe&com={$company}&nu={$number}&show=0";
			echo file_get_contents($url);
		}
	}


	# 分配物流
	public function setWuLiu()
	{
		if ( request()->isPost() ) {
			$postData = input('post.');
			$postData['update_time'] = time();
			$postData['send_status'] = 1;
			// 验证
			// 验证代码

			// 入库
			if ( Db::name('order')->update($postData) ) {
				$this->success('配置物流成功', url('admin/order/index'));
			}else {
				$this->error('配置物流失败');
			}
		}

		$order_id = input('order_id');
		$orderData = Db::name('order')->where('order_id', $order_id)->find();
		return $this->fetch('', ['orderData' => $orderData]);
	}


	public function index()
	{
		$orderData = Db::name('order')->select();
		// halt($orderData);
		return $this->fetch('', ['orderData' => $orderData]);
	}
}