<?php 
namespace app\admin\controller;
use app\admin\model\AuthModel;

class AuthController extends CommonController
{
	# 编辑权限
	public function upd()
	{
		$authModel = new AuthModel();
		if(request()->isPost()){
			//接收参数
			$postData = input('post.');
			//验证器验证
			if ( $postData['pid'] == 0 ) {
				$result = $this->validate($postData, 'Auth.onlyAuthName', [], true);
			}else{
				$result = $this->validate($postData, 'Auth.upd', [], true);
			}

			if($result !== true){
				$this->error(implode(',',$result));
			}
			//入库
			if($authModel->update($postData)){
				$this->success("编辑成功",url("admin/auth/index"));
			}else{
				$this->error("编辑失败");
			}
		}
		$auth_id = input('auth_id');
		$authInfo = AuthModel::find($auth_id);
		$auths = $authModel->getSonsAuth( $authModel->select() );
		return $this->fetch('', [
			'authInfo' => $authInfo, 
			'auths' => $auths,
		]);
	}


	# 权限列表
	public function index()
	{
		$auths = AuthModel::alias('t1')
			->field( 't1.*, t2.auth_name p_name' )
			->join('sh_auth t2', 't1.pid = t2.auth_id', 'left')
			->select();
		$authModel = new AuthModel();
		$auths = $authModel->getSonsAuth($auths);
		return $this->fetch('', ['auths' => $auths]);
	}



	# 删除权限
	public function del()
	{
		
		// dump(AuthModel::destroy(11));die;
		# 接受id
		$auth_id = input('auth_id');

		# 检查该权限下是否有子权限
		$authData = AuthModel::where('pid', $auth_id)->select()->toArray();
		if ( !$authData ) {
			# 删除数据
			$result = AuthModel::destroy($auth_id);
			if ( $result ) {
				$this->error('删除权限成功');
			}else {
				$this->success('删除权限失败');
			}
		}else {
			$this->error('该权限下有子权限,无法删除');
		}
		return $this->fetch('index');
	}



	# 添加权限
	public function add(){
		//获取所有的权限分配到模板中
		$authModel = new AuthModel;
		if(request()->isPost()){
			//接收post参数
			$postData = input('post.');
			//验证器验证,如果是顶级权限即pid=0,验证onlyAuthName
			if($postData['pid'] == 0){
				$result = $this->validate($postData,"Auth.onlyAuthName",[],true);
			}else{
				$result = $this->validate($postData,"Auth.add",[],true);
			}
			//否则验证add场景
			if($result !== true){
				$this->error( implode(',',$result) );
			}
			//判断入库是否成功
			if($authModel->save($postData)){
				$this->success("添加成功",url("/admin/auth/index"));
			}else{
				$this->error("添加失败");
			}
		}
		$auths = $authModel->getSonsAuth( $authModel->select() );
		return $this->fetch('',['auths'=>$auths]);
	}

}