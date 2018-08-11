<?php 

namespace app\home\validate;

use think\Validate;

class Member extends Validate 
{
	# 验证规则
	protected $rule = [
		'username' => 'require|unique:member',
		'email' => 'require|email|unique:member',
		'password' => 'require',
		'repassword' => 'require|confirm:password',
		'captcha' => 'require|captcha',
		'login_captcha' => 'require|captcha:2',
		'phone' => 'require|unique:member',
	];

	protected $message = [
		'username.require' => '用户名必填',
		'username.unique' => '用户名占用',
		'email.require' => '邮箱必填',
		'email.email' => '邮箱格式错误',
		'email.unique' => '该邮箱已注册',
		'password.require' => '密码不能为空',
		'repassword.confirm' => '俩次密码不一致',
		'captcha.require' => '验证码不能为空',
		'captcha.captcha' => '验证码有误',
		'login_captcha.require' => '验证码不能为空',
		'login_captcha.captcha' => '验证码错误',
	];

	protected $scene = [
		'register' => ['username', 'email', 'password', 'repassword', 'captcha', 'phone'],
		'login' => ['username' => 'require', 'password', 'login_captcha'],
		'sendSms' => ['phone' => 'require|unique:member'],
		'resetPassword' => ['password', 'repassword'],
	];
}