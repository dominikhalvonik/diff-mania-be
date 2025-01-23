<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LogTable extends Model
{
    protected $table = 'log';
    protected $fillable = ['user_id', 'log_info', 'value'];
    public $timestamps = false;
}
