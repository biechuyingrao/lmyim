<?php
/**
 * 用户验证器
 */
namespace app\admin\validate;
use think\Validate;
class User extends Validate
{
    protected $rule = [
        'username'  =>  'require|max:25',
        'username' => 'unique:ucenter_member',
        'email' =>  'email',
    ];

    protected $message = [
        'username.require'  =>  '用户名必须',
        'username.unique'  =>  '用户名已存在',
        'email' =>  '邮箱格式错误',
    ];

    protected $scene = [
        'add'   =>  ['username','email'],
        'edit'  =>  ['email'],
    ];    
}