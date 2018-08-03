<?php 

namespace app\admin\model;

use think\Model;

class AttributeModel extends Model 
{
	protected $table = 'sh_attribute';

	protected $pk = 'attr_id';

	protected $autoWriteTimestamp = true;
}