<?php 

namespace app\admin\model;
use think\Model;

class RoleModel extends Model
{
	protected $table = 'sh_role';

	protected $pk = 'role_id';

	//protected $autoWriteTimestamp = true;
	# 自动维护时间戳
	protected $autoWriteTimestamp = true;

	protected static function init()
	{
		RoleModel::event('before_insert', function ($role)
		{
			// 把权限数组形式转换为字符串进行入库
			if ( isset($role['auth_ids_list']) ) {
				$role['auth_ids_list'] = implode(',', $role['auth_ids_list']);
			}
		});

		RoleModel::event('before_update', function ($role)
		{
			// 把权限数组形式转换为字符串进行入库
			if ( isset($role['auth_ids_list']) ) {
				$role['auth_ids_list'] = implode(',', $role['auth_ids_list']);
			}
		});
	}
}