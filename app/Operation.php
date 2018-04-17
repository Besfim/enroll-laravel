<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model
{
    protected $table = 'operation';
    protected $fillable = ['id','from_id','type','detail'];
}
/*
 * 操作类型type的说明
 * 1 - 调用发送验证码
 * 2 - 调用群发短信
 *
 * 注意：
 * 1 - 调用发送验证码中id可能为null，若有值则是当前session中储存的id，并且在info中储存用户的手机
 * 2 - 调用群发短信的id应该不会为null，应该是当前session中储存的id，并且在info中储存群发接受手机的数目
 */