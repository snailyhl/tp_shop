<?php
namespace app\admin\model;

use think\Model;
use app\admin\model\RoleModel;
use app\admin\model\AuthModel;

class UserModel extends Model
{

	# 设置当前模型对应的数据表的完整表名
	protected $table = 'sh_user';

	# 定义表主键
	protected $pk = 'user_id';

	# 自动维护时间戳
	protected $autoWriteTimestamp = true;

	# 模型事件 (钩子)
	protected static function init() {
		# 使用入库(模型调用save方法)前触发的模型事件(before_insert),实现密码加密
		UserModel::event('before_insert', function ($data) # $data为表单中数据对象
		{
			$data['password'] = md5( $data['password'] . config('password_salt') );
		});
		
		UserModel::event('before_update', function ($data) # $data为表单中数据对象
		{
			$data['password'] = md5( $data['password'] . config('password_salt') );
		});
	}

	# 检查用户登录是用户名和密码是否匹配
	public function checkUser($username, $password)
	{
		$where = [
			'username' => $username,
			'password' => md5( $password . config('password_salt') ),
		];
		$userInfo = $this->where( $where )->find();
		if ( $userInfo ) {
			session('user_id', $userInfo['user_id']);
			session('username', $userInfo['username']);
			# 通过用户的角色role_id,把当前用户的权限写入到session中去
			$this->getAuthWriteSession($userInfo['role_id']);
			return true;
		}else {
			return false;
		}
	}

	# 将用户权限写入session
	public function getAuthWriteSession($role_id)
	{
		# 获取角色表中auth_ids_list的值
		$auth_ids_list = RoleModel::where('role_id', $role_id)->value('auth_ids_list');
		# 超级管理员 $auth_ids_list = *
		if ( $auth_ids_list == '*' ) {
			# 超级管理员拥有权限表所有权限
			$oldAuths = AuthModel::select()->toArray();
		}else {
			# 非超级管理员只能取出自己的权限
			$oldAuths = AuthModel::where('auth_id', 'in', $auth_ids_list)->select()->toArray();
		}

		# 俩个技巧取出所有数据
		// 1. 每个数组的auth_id为二维数组的下标
		$auths = [];
		foreach ($oldAuths as $v) {
			$auths[ $v['auth_id'] ] = $v;
		}

		// 2. 通过pid进行分组
		$idsOrderByPid = [];
		foreach ($oldAuths as $vv) {
			$idsOrderByPid[ $vv['pid'] ][] = $vv['auth_id'];
		}

		// 写入到session中去
		session('auths', $auths);
		session('ids', $idsOrderByPid);

		# 将管理员可访问的权限存入session,用于权限防翻墙
		if ( $auth_ids_list == '*' ) {
			session('visitorAuth', '*');
		}else {
			$visitorAuth = [];
			foreach ($oldAuths as $v) {
				$visitorAuth[] = strtolower( $v['auth_c'] . '/' . $v['auth_a']);
			}
			session('visitorAuth', $visitorAuth);
		}

	}
}