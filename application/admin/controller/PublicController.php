<?php 

namespace app\admin\controller;

use think\Controller;
use app\admin\model\UserModel;

class PublicController extends controller 
{
	public function login()
	{
		if ( request()->isPost() ) {
			$postData = input( 'post.' );
			$result = $this->validate($postData, 'User.login', [], true);
			if ( $result !== true ) {
				$this->error( implode( ',', $result ) );
			}
			$userModel = new UserModel();
			# 检查用户是否登录(验证逻辑写在模型中)
			if ( $userModel->checkUser($postData['username'], $postData['password']) ) {
				$this->redirect('admin/index/index');
			}else {
				$this->error('用户名或密码错误');
			}
		}
		return $this->fetch();
	}


	public function logout()
	{
		# 清除session里面的登录数据并重定向到登录页面
		session('username', null);
		session('user_id', null);
		return redirect('admin/public/login');
	}
}