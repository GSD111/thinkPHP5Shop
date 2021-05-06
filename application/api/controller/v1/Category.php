<?php


namespace app\api\controller\v1;

use app\api\model\Category as CategoryModel;
use app\lib\exception\CategoryException;

class Category
{
    /*
     * 获取分类列表
     */
    public function getAllCategories()
    {
        $categories = CategoryModel::all([], 'img');
        if ($categories->isEmpty()) {
            throw new CategoryException();
        }

        return $categories;
    }
}