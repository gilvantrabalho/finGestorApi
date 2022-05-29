<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Release extends Model
{
    use HasFactory;

    public $table = 'gilvan_santos.user_releases';
    protected $fillable = ['user_id', 'transaction_id', 'operation_type', 'created_at'];
    public $timestamps = false;
}
