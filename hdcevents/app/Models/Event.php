<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    // Converte o atributo items para array toda vez que for acessado
    protected $casts = [
        'items' => 'array',
        'date' => 'datetime'
    ];

    protected $dates = ['date'];

    // Tudo que for enviado pelo PUT pode ser atualizado sem nenhuma restrição
    protected $guarded = [];

    protected $fillabel = ['title', 'date', 'city', 'private', 'description', 'items'];

    public function user() {
        return $this->belongsTo('App\Models\User');
    }

    public function users() {
        return $this->belongsToMany('App\Models\User');
    }
}
