<?php

namespace app\api\model;

use think\Model;

class BannerItem extends Model
{
    protected $hidden=['delete_time','update_time'];
    //定义关联img表模型
    public function img()
    {
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}
