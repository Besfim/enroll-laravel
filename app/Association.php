<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Association extends Model
{
    protected $table = 'association';
    public $fillable = ['id','name','short','introduce','require_info'];
}
