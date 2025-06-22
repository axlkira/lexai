<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use App\Models\LegalCase;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * Get the legal cases for the user.
     */
    public function legalCases()
    {
        return $this->hasMany(LegalCase::class);
    }

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'password',
        'phone',
        'document_type',
        'document_number',
        'bar_association_number',
        'user_type',
        'is_active'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function casesAsLawyer()
    {
        return $this->hasMany(LegalCase::class, 'user_id');
    }

    /**
     * Obtiene los clientes asociados a este usuario (abogado).
     */
    public function clients(): HasMany
    {
        return $this->hasMany(Client::class);
    }

    public function casesAsClient()
    {
        return $this->hasMany(LegalCase::class, 'client_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}