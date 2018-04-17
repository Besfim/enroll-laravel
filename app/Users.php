<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    protected $table = 'user';
    public $fillable = ['id','wx_id','phone','password','name','gender','birth','school','major','class'];
    public function getGender()
    {
        switch($this->gender)
        {
            case 1:
                return '男';break;
            case 2:
                return '女';break;
            default:
                return '未知';break;
        }
    }
}
