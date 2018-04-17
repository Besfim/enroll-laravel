<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    protected $table = 'department';
    protected $fillable = ['id','aid','name','short','introduce','round'];
    public function association()
    {
        return $this->hasOne('App\Association','id','aid');
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
