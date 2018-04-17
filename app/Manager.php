<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Manager extends Model
{
    protected $table = 'manager';
    public $fillable = ['id','wx_id','phone','name','password','type','aid','did'];
    public function department()
    {
        return $this->hasOne('App\Department','id','did');
    }
    public function association()
    {
        return $this->hasOne('App\Association','id','aid');
    }
    public function getType()
    {
        return $this->type == 3 ? '主任' : '部长';
    }
}
