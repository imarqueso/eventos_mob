<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evento extends Model
{
    use HasFactory;

    protected $fillable = ['nome', 'datainicio', 'datafim'];

    public function participantes()
    {
        return $this->hasMany(Participante::class);
    }
}
