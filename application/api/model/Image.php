<?php

namespace app\api\model;

class Image extends BaseModel
{
    protected $hidden = ['id', 'from', 'delete_time', 'update_time'];

    //url读取器
    public function getUrlAttr($value, $data)
    {
        return $this->prefixImgUrl($value,$data);
    }
}
