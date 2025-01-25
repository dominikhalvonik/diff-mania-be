<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AdminLogTable extends Model
{
    protected $fillable = ['log_info', 'user_id', 'value'];
}
