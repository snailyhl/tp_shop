<?php 

namespace app\admin\controller;

use app\admin\model\AuthModel;
use app\admin\model\RoleModel;
use think\Db;

class RoleController extends CommonController
{
	public function del()
	{
		# 接受参数
		$role_id = input('role_id');

		if ( RoleModel::destroy($role_id) ) {
			$this->success('删除成功', url('admin/role/index'));
		}else {
			$this->error('删除失败', url('admin/role/index'));
		}
	}


	public function upd()
	{
		if(request()->isPost()){
			//接收参数
			$postData = input('post.');
			//验证器验证
			$result = $this->validate($postData,'Role.upd',[],true);
			if($result !== true){
				$this->error(implode(',',$result));
			}
			//入库
			$roleModel = new RoleModel();
			if($roleModel->update($postData)){
				$this->success("编辑成功",url("admin/role/index"));
			}else{
				$this->error("编辑失败");
			}
		}

		# 接受参数
		$role_id = input('role_id');

		# 取出所有权限
		$oldAuths = AuthModel::select()->toArray();

		$auths = [];
		foreach ($oldAuths as $v) {
			$auths[ $v['auth_id'] ] = $v;
		}

		# 根据pid进行分组,把pid相同的pid分为同一组
		$idsOrderByPid = [];
		foreach ($oldAuths as $vv) {
			$idsOrderByPid[ $vv['pid'] ][] = $vv['auth_id'];
		}

		# 取出当前角色已有的权限
		$role = RoleModel::find($role_id);

		return $this->fetch('', [
			'auths' => $auths, 
			'ids' => $idsOrderByPid, 
			'role' => $role,
		]);
	}

	public function index()
	{
		$roles = Db::query("select t1.*, GROUP_CONCAT(t2.auth_name) as all_auth from sh_role t1 left join sh_auth t2 on FIND_IN_SET(t2.auth_id, t1.auth_ids_list) group by t1.role_id");
		// foreach ($roles as $v) {
		// 	$v['update_time'] = date('Y-m-d H:i:s', $v['update_time']);
		// 	$v['create_time'] = date('Y-m-d H:i:s', $v['create_time']);
		// }
		// halt($roles);
		return $this->fetch('', ['roles' => $roles]);
	}


	public function add()
	{	
		if(request()->isPost()){
			//接收参数
			$postData = input('post.');
			// halt($postData);
			//验证器验证
			$result = $this->validate($postData,'Role.add',[],true);
			if($result !== true){
				$this->error(implode(',',$result));
			}
			//入库
			$roleModel = new RoleModel();
			if($roleModel->allowField(true)->save($postData)){
				$this->success("添加成功",url("admin/role/index"));
			}else{
				$this->error("添加失败");
			}
		}

		$authModel = new AuthModel;
		$oldauths = $authModel->select()->toArray();

		# 以auth_id作为$auths的二维数组下标
		$auths = [];
		foreach ($oldauths as $v) {
			$auths[ $v['auth_id'] ] = $v;
		}

		# 把所有的权限以pid进行分组
		$children = [];
		foreach ($oldauths as $vv) {
			$children[ $vv['pid'] ][] = $vv['auth_id'];
		}
		
		// halt($children);
		return $this->fetch('', [
			'children' => $children, 
			'auths' => $auths,
		]);
	}
}
