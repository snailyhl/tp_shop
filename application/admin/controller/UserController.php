<?php
namespace app\admin\controller;

// use think\Controller;
use app\admin\model\UserModel;
use app\admin\model\RoleModel;

class UserController extends CommonController
{
	# 编辑用户
	public function upd()
	{
		if ( request()->isPost() ) {
			$userModel = new UserModel();
			$postData = input('post.');
			if ( $postData['password'] == '' && $postData['repassword'] == '' ) {
				$result = $this->validate($postData, 'User.onlyUsername', [], true);
				// halt($result);
				if ( $result !== true ) {
					$this->error( implode(',', $result) );
				}
			}else {
				$result = $this->validate($postData, 'User.UsernamePassword', [], true);
				if ( $result !== true ) {
					$this->error( implode(',', $result) );
				}
			}
			// 判断编辑是否成功
			halt($postData);
			if ( $userModel->allowField(true)->isUpdate(true)->save($postData) ) {
				$this->success('编辑成功', url('admin/user/index'));
			}else {
				$this->error('编辑失败');
			}
		}
		$user_id = input('user_id');
		$userInfo = UserModel::find($user_id);
		return $this->fetch('', ['userInfo' => $userInfo]);
	}

	# 删除用户
	public function del()
	{
		$user_id = input('user_id');
		if ( UserModel::destroy($user_id) ) {
			$this->success('删除成功', url('admin/user/index'));
		}else {
			$this->error();
		}
	}

	# 用户首页
	public function index()
	{
		# 关联角色表
		$users = UserModel::alias('ut')
			->field('ut.*, rt.role_name')
			->join('sh_role rt', 'ut.role_id = rt.role_id', 'left')
			->paginate(3);

		return $this->fetch('', ['users' => $users]);
	}

	# 添加用户
	public function add()
	{
		if ( request()->isPost() ) {
			$userModel = new UserModel();

			$postData = input('post.');

			$result = $this->validate($postData, 'User.add', [], true);
			if ( $result !== true ) {
				// halt($result);
				$this->error( implode(',', $result) );
			}
			if ( $userModel->allowField(true)->save($postData) ) {
				$this->success('入库成功', url('admin/user/index'));
			}else {
				$this->error('入库失败');
			}
		}

		# 取出角色数据分配到模板中
		$roles = RoleModel::select();

		return $this->fetch('', ['roles' => $roles]);
	}
}