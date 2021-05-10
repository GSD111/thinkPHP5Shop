<?php


namespace app\api\controller\v1;

use app\api\controller\BaseController;
use app\api\model\User as UserModel;
use app\api\service\Token as TokenService;
use app\api\validate\AddressNew;
use app\lib\exception\SuccessMessage;
use app\lib\exception\UserException;

class Address extends BaseController
{

    /*
     * 前置方法
     */
    protected $beforeActionList = [
        'checkPrimaryScope' => ['only' => 'createOrUpdateAddress']
    ];

//    /*
//     * 验证用户的权限值
//     */
//    protected function checkPrimaryScope()
//    {
//        $scope = TokenService::getCurrentTokenVal('scope');
//        if ($scope) {
//            if ($scope >= ScopeEnum::User) {
//                return true;
//            } else {
//                throw new ForbiddenException();
//            }
//        } else {
//            throw new TokenException();
//        }
//
//    }


    /*
     * 创建或者修改用户地址
     */
    public function createOrUpdateAddress()
    {
//        (new AddressNew())->goCheck();
        $validate = new AddressNew();
//        $validate->goCheck();
        //根据Token获取用户的uid
        //根据获取到uid判断该用户是否存在，如不存在则抛出异常
        //获取客户端传递过来的数据参数
        //根据用户的地址信息是否存在来进行对应的添加及更新操作
        $uid = TokenService::getCurrentUid();
        $user = UserModel::get($uid);
        if (!$user) {
            throw new UserException();
        }

        $dataArray = $validate->getDataByRule(input('post.'));
        $userAddress = $user->address;
        if (!$userAddress) {
//            AddressModel::create($dataArray);      //通过地址模型进行添加
            $user->address()->save($dataArray);   //通过关联模型来进行添加操作
        } else {
            $user->address->save($dataArray);
//          AddressModel::update($dataArray);
        }

        return json(new SuccessMessage(), 201);
    }
}