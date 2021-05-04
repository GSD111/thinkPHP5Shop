<?php

namespace app\api\model;

class BannerItem extends BaseModel
{
    protected $hidden=['delete_time','update_time'];
    //定义关联img表模型
    public function img()
    {
        return $this->belongsTo('Image', 'img_id', 'id');
    }
}
