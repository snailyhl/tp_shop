<?php 

namespace app\home\model;
use think\Model;

class MemberModel extends Model
{
	protected $table = 'sh_member';
	protected $pk = 'member_id';
	protected $autoWriteTimestamp = true;

	protected static function init()
	{
		MemberModel::event('before_insert', function ($member)
		{
			# 入库前密码加盐
			$member['password'] = md5($member['password'].config('password_salt'));
		});
	}

	public function checkUser($username, $password)
	{
		$where = [
			'username' => $username,
			'password' => md5($password.config('password_salt')),
		];
		$userInfo = $this->where($where)->find();
		if ( $userInfo ) {
			session('member_username', $userInfo['username']);
			session('member_id', $userInfo['member_id']);
			return true;
		}else {
			return false;
		}
	}

}