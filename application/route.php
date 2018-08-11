<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------

// return [
//     '__pattern__' => [
//         'name' => '\w+',
//     ],
//     '[hello]'     => [
//         ':id'   => ['index/hello', ['method' => 'get'], ['id' => '\d+']],
//         ':name' => ['index/hello', ['method' => 'post']],
//     ],

// ];

use think\Route;

// 前台首页
Route::get('/', 'home/index/index');


// 前台路由群组
Route::group('home', function() {

	# 订单提交路由
	Route::any('order/orderpay', 'home/order/orderpay');
	# 我的订单
	Route::any('order/selforder', 'home/order/selforder');
	# 付款路由
	Route::any('order/payMoney', 'home/order/payMoney');

	# 支付宝同步通知路由
	Route::any('order/returnurl', 'home/order/returnurl');
	# 支付宝异步通知路由
	Route::any('order/notifyurl', 'home/order/notifyurl');
	# 支付宝支付完成路由
	Route::any('order/orderdone', 'home/order/orderdone');

	# 分类列表路由
	Route::any('category/index', 'home/category/index');

	# 购物车商品数量更新路由
	Route::any('cart/updatecartgood', 'home/cart/updatecartgood');
	# 购物车加入商品路由
	Route::any('cart/addgoodstocart', 'home/cart/addgoodstocart');
	# 购物车商品列表路由
	Route::any('cart/cartlist', 'home/cart/cartlist');
	# 购物车删除商品路由
	Route::any('cart/delcartgood', 'home/cart/delcartgood');
	# 清空购物车
	Route::any('cart/clearcartgood', 'home/cart/clearcartgood');
	# 购物车结算
	Route::any('cart/orderaccount', 'home/cart/orderaccount');

	# 商品详情路由
	Route::any('goods/detail', 'home/goods/detail');

	// 前台注册路由
	Route::any('public/register', 'home/public/register');
	// 前台登录退出路由
	Route::any('public/login', 'home/public/login');
	Route::any('public/logout', 'home/public/logout');
	// 忘记密码路由
	Route::any('public/forgetPassword', 'home/public/forgetPassword');
	// 重置密码路由
	Route::any('public/resetpassword/:member_id/:hash/:time', 'home/public/resetPassword');

	# 短信发送路由
	Route::any('public/sendSms', 'home/public/sendSms');
	# 邮件发送路由
	Route::any('public/sendEmail', 'home/public/sendEmail');
});


// --------------------- 后台路由群组 ------------------------------- //
Route::group('admin', function (){

	// 后台订单管理
	Route::any('order/index', 'admin/order/index');
	// 订单分配物流
	Route::any('order/setwuliu', 'admin/order/setwuliu');
	// 查询物流
	Route::any('order/getwuliu', 'admin/order/getwuliu');

	// 后台首页路由
	Route::get('index/index', 'admin/index/index');
	Route::get('index/left', 'admin/index/left');
	Route::get('index/top', 'admin/index/top');
	Route::get('index/main', 'admin/index/main');

	// 后台用户路由
	Route::get('/user/index', 'admin/user/index');
	Route::any('/user/add', 'admin/user/add');
	Route::get('/user/del', 'admin/user/del');
	Route::any('/user/upd', 'admin/user/upd');

	// 后台登录 注销路由
	Route::any('public/login', 'admin/public/login');
	Route::get('public/logout', 'admin/public/logout');

	// 后台权限管理路由
	Route::get('auth/index', 'admin/auth/index');
	Route::any('auth/add', 'admin/auth/add');
	Route::any('auth/upd', 'admin/auth/upd');
	Route::get('auth/del', 'admin/auth/del');

	// 后台角色管理路由
	Route::any('role/add', 'admin/role/add');
	Route::get('role/index', 'admin/role/index');
	Route::any('role/upd', 'admin/role/upd');
	Route::get('role/del', 'admin/role/del');

	// 后台商品类型管理路由
	Route::any('type/add', 'admin/type/add');
	Route::get('type/index', 'admin/type/index');
	Route::any('type/upd', 'admin/type/upd');
	Route::get('type/del', 'admin/type/del');
	Route::get('type/getAttr', 'admin/type/getAttr');

	// 后台商品属性管理路由
	Route::any('attribute/add', 'admin/attribute/add');
	Route::get('attribute/index', 'admin/attribute/index');
	Route::any('attribute/upd', 'admin/attribute/upd');
	Route::get('attribute/del', 'admin/attribute/del');

	// 后台商品分类管理路由
	Route::any('category/add', 'admin/category/add');
	Route::get('category/index', 'admin/category/index');
	Route::any('category/upd', 'admin/category/upd');
	Route::get('category/del', 'admin/category/del');

	// 后台商品管理路由
	Route::any('goods/add', 'admin/goods/add');
	Route::get('goods/index', 'admin/goods/index');
	Route::any('goods/upd', 'admin/goods/upd');
	Route::get('goods/del', 'admin/goods/del');
	# ajax获取指定类型商品的属性的路由
	Route::any('goods/getTypeAttr', 'admin/goods/getTypeAttr');

});