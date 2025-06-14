<?php

namespace App\Models;

// use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Caso extends Model
{
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'fecha_apertura' => 'datetime',
    ];

    // use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'nombre',
        'numero_radicado',
        'fecha_apertura',
        'descripcion',
        'estado',
    ];

    /**
     * Get the user that owns the caso.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
