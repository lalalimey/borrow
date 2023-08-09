<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kuru_logModal extends Model
{
    protected $table = 'kuru_log';
    protected $primaryKey = 'id';
    protected $fillable = ['user_id','item_list','purpose','place','status','borrow_date','due_date','created_at','update_at'];
}
