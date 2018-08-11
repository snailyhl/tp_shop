<?php 

namespace app\home\model;
use think\Model;

class OrderModel extends Model
{
	protected $table = 'sh_order';
	protected $pk = 'id';
	protected $autoWriteTimestamp = true;
	
}