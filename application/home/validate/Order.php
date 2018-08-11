<?php  

namespace app\home\validate;

use think\Validate;

class Order extends Validate 
{
	# 验证规则
	protected $rule = [
		'receiver' => 'require',
		'address' => 'require',
		'phone' => ['/^\d{11}$/'],
		'zcode' => ['/^\d{6}$/'],
	];

	protected $message = [
		'receiver.require' => '收货人不能为空', 
		'address.require' => '收货地址不能为空', 
		'phone' => '请输入正确的手机号', 
		'zcode' => '请输入正确的邮编', 
	];

	protected $scene = [
		'pay' => ['receiver', 'address', 'phone', 'zcode'],
	];
}