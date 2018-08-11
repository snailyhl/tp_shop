<?php 

namespace app\home\controller;

use think\Controller;
use app\home\model\MemberModel;

class PublicController extends Controller
{
	public function resetPassword($member_id, $hash, $time)
	{
		# 判断验证地址是否被篡改, 判断hash加密字符串的结果,不一样则篡改了
		if ( md5($member_id . $time . config('email_salt')) != $hash ) {
			exit('验证地址对你做了啥');
		}

		# 判断地址是否在30分钟有效期内
		if ( time() > $time + 1800 ) {
			exit('你来晚了,验证地址过期鸟');
		}
		
		if ( request()->isPost() ) {
			$postData = input('post.');
			$result = $this->validate($postData, "Member.resetPassword", [], true);
			if ( $result !== true ) {
				$this->error( implode(',', $result) );
			}
			# 更新密码
			$data = [
				'member_id' => $member_id,
				'password' => md5( $postData['password'].config('password_salt') ),
			];
			$memModel = new MemberModel();
			if ( $memModel->update($data) ) {
				$this->error('重置密码成功', url('/home/public/login'));
			}else {
				$this->success('重置密码失败');
			}
		}
		return $this->fetch('');
	}


	public function sendEmail()
	{
		if ( request()->isAjax() ) {
			$email = input('email');
			# 验证邮箱是否已注册
			$result = MemberModel::where('email', '=', $email)->find();
			if ( !result ) {
				$response = ['code' => -1, 'message' => '该邮箱尚未注册'];
				echo json_encode($response);die;
			}
			# 构造找回密码的链接地址
			$member_id = $result['member_id'];
			$time = time();
			$hash = md5($result['member_id'] . $time . config('email_salt'));
			
			# 把用户id和当前时间戳以及email地址的盐进行加密,防止用户篡改,后面验证该地址的有效性
			$href = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['HTTP_HOST'] . "/index.php/home/public/resetPassword/" . $member_id . '/' . $hash . '/' . $time;
			$content = "<a href='{$href}' target='_blank'>京西商城.找回密码</a>";
			if ( sendEmail([$email], '找回密码', $content) ) {
				$response = ['code' => '200', 'message' => '邮件发送成功,请登录邮箱查看'];
				echo json_encode($response);die;
			}else {
				$response = ['code' => '-2', 'mesage' => '发送失败,请重试'];
				echo json_encode($response);die;
			}
		}
	}

	
	public function forgetPassword()
	{
		return $this->fetch('');
	}

	public function sendSms()
	{
		if ( request()->isAjax() ) {
			$phone = input('phone');
			$result = $this->validate(['phone'=>$phone], "Member.sendSms", [], false);
			if ( $result !== true ) {
				$response = ['code' => -1, 'message' => '该手机号已注册'];
				echo json_encode($response);die;
			}
			// 发送短信
			$rand = mt_rand(1000, 9999);
			// $phone 手机号码  $rand 验证码数值  5 有效期为5分钟 
			$result = sendSms($phone, array($rand, '5'), '1');
			// halt($result);
			if ( $result->statusCode == '000000' ) {
				// 明文cookie易泄露,给cookie加盐,并将有效期设置为5分钟
				cookie('phoneCaptcha', md5($rand.config('sms_salt')), 300);
				$response = ['code' => 200, 'message' => '发送短信成功'];
				echo json_encode($response),die;
			}else {
				$response = ['code' => -2, 'message' => '网络异常'.$result->statusMsg];
				echo json_encode($response);die;
			}
		}
	}


	public function logout()
	{
		session('member_id', null);
		session('member_username', null);
		// 重定向到登录页
		$this->redirect('/home/public/login');
	}


	public function login()
	{
		$memberModel = new MemberModel();
		if ( request()->isPost() ) {
			$postData = input('post.');
			$result = $this->validate($postData, 'Member.login', [], true);
			if ( $result !== true ) {
				$this->error( implode( ',', $result ) );
			}
			// 判断用户名和密码是否匹配
			$flag = $memberModel->checkUser( $postData['username'], $postData['password'] );
			if ( $flag ) {
				# 判断是否有goods_id, 如果有,返回到对应的商品详情页
				if ( input('goods_id') ) {
					$this->redirect('/home/goods/detail', ['goods_id' => input('goods_id')]);
				}
				$this->redirect('/');
			}else {
				$this->error('用户名或密码错误');
			}
		}
		return $this->fetch();
	}


	public function register()
	{
		if ( request()->isPost() ) {
			$postData = input('post.');
			$result = $this->validate($postData, 'Member.register', [], true);
			if ( $result !== true ) {
				$this->error( implode(',', $result) );
			}
			# 判断手机验证码是否正确
			if ( md5($postData['phoneCaptcha'].config('sms_salt')) !== cookie('phoneCaptcha') ) {
				$this->error('手机验证码错误');
			}

			# 写入数据库
			$memModel = new MemberModel();
			if ( $memModel->allowField(true)->save($postData) ) {
				$this->success('注册成功', url('/'));
			}else {
				$this->error('注册失败');
			}
		}
		return $this->fetch('');
	}


}