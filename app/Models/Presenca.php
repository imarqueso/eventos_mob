<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presenca extends Model
{
    use HasFactory;

    protected $fillable = ['data', 'participante_id'];

    public function participante()
    {
        return $this->belongsTo(Participante::class);
    }
}
