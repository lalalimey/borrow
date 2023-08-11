<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KuruModel extends Model
{
    protected $table = 'kuru';
    protected $primaryKey = 'id';
    protected $fillable = ['number','name','division','storage','budget','year','status','checkup','contact','user_id'];
}
