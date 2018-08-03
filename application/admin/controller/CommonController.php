<?php

namespace app\admin\controller;

use think\Controller;

class CommonController extends Controller
{
	public function _initialize()
	{
		if ( !session('user_id') ) {
			$this->error('请先登录', url('admin/public/login'));
		}else {
			# 获取session中的权限
			$visitorAuth = session('visitorAuth');

			# 拼接获取到当前访问的控制器名和方法名,转为小写
			$now_ca = strtolower( request()->controller() . '/' . request() ->action());
			# 判断访问的权限在session所记录的权限中是否存在 超级管理员和首页控制器不做限制
			if ( $visitorAuth == '*' || strtolower( request()->controller() ) == 'index' ) {
				return;  // 不在执行后面的代码
			}
			if ( !in_array( $now_ca, $visitorAuth ) ) {
				$this->error('权限不足!');
			}
		}
	}
}

