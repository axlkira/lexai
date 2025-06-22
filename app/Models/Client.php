<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Client extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'document_type',
        'document_number',
        'phone',
        'address',
        'user_id'
    ];

    /**
     * Obtiene el usuario (abogado) dueño de este cliente.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    
    /**
     * Obtener los casos legales asociados a este cliente.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function legalCases()
    {
        return $this->hasMany(LegalCase::class);
    }
}
