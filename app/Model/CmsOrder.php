<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class CmsOrder extends Model
{
    //
	public $table = 'cms_order';
	public $timestamps =false;
	/**
	 * 生成订单号
	 */
	public static function GenerateOrderNumber()
	{
		return date('ymdHi') . rand(11111,99999) . rand(2222,9999);
	}
}
