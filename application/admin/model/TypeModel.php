<?php 

namespace app\admin\model;

use think\Model;

class TypeModel extends Model 
{
	protected $table = 'sh_type';

	protected $pk = 'type_id';

	protected $autoWriteTimestamp = true;
}