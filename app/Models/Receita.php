<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Receita extends Model
{
    protected $table = 'receitas';

    protected $fillable = [
        'titulo',
        'valor',
        'tag',
        'check',
        'data',
        'wallet',
        'id_user',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
