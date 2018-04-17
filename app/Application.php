<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    protected $table = 'application';
    public $fillable = ['id','uid','aid','did','require_info','round','note'];
    public function user()
    {
        return $this->hasOne('App\Users','id','uid');
    }
    public function association()
    {
        return $this->hasOne('App\Association','id','aid');
    }
    public function department()
    {
        return $this->hasOne('App\Department','id','did');
    }
    public function getRound()
    {
        return ($this->round <= 10 && $this->round > 0) ? ['零','一','二','三','四','五','六','七','八','九','十'][$this->round] : $this->round;
    }
    public function getPreRound()
    {
        return ($this->round <= 10 && $this->round > 0) ? ['零','一','二','三','四','五','六','七','八','九','十'][$this->round - 1] : $this->round;
    }
}
