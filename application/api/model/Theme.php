<?php


namespace app\api\model;


class Theme extends BaseModel
{

    protected $hidden = ['update_time', 'delete_time', 'topic_img_id', 'head|_img_id'];

    /*
     * 获取主题展示图
     */
    public function topicImg()
    {
        return $this->belongsTo('Image', 'topic_img_id', 'id');
    }

    /*
     * 获取主题内页head图
     */
    public function headImg()
    {
        return $this->belongsTo('Image', 'head_img_id', 'id');
    }

    /*
     * 主题跟产品多对多模型关联
     */
    public function products(){

        return $this->belongsToMany('Product','theme_product','product_id','theme_id');
    }

    /*
     * 根据传入的主题ID值获取对应主题下所有产品信息
     * @param string $id
     */
    public static function getThemeWithProduct($id){

        $theme = self::with('products,topicImg,headImg')->find($id);

        return $theme;
    }
}