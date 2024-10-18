<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Participante extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'email', 'cpf', 'evento_id'];

    public function evento()
    {
        return $this->belongsTo(Evento::class);
    }

    public function presencas()
    {
        return $this->hasMany(Presenca::class);
    }
}
