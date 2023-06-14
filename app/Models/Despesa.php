<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Despesa extends Model
{
    protected $table = 'despesas';

    protected $fillable = [
        'titulo',
        'valor',
        'tag',
        'check',
        'data',
        'wallet',
        'id_user',
    ];
}

