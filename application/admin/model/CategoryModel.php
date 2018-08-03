<?php 

namespace app\admin\model;

use think\Model;

class CategoryModel extends Model 
{
	protected $table = 'sh_category';

	protected $pk = 'cat_id';

	protected $autoWriteTimestamp = true;

	public function getSonCats($data, $pid = 0, $level = 1)
	{
		static $result = [];

		foreach ($data as $k => $v) {
			if ( $v['pid'] == $pid ) {
				$v['level'] = $level;
				$result[ $v['cat_id'] ] = $v;
				unset( $data[ $k ] );
				$this->getSonCats($data, $v['cat_id'], $level + 1);
			}
		}
		return $result;
	}
}
