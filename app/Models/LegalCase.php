<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LegalCase extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'case_number',
        'title',
        'description',
        'status',
        'user_id',
        'client_id',
        'start_date',
        'end_date',
        'judicial_process_number',
        'court',
        'judge',
        'jurisdiction'
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function lawyer()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function client()
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}