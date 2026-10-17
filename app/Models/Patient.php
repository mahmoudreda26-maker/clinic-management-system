<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasUuids;

    protected $fillable = [
        'full_name',
        'gender',
        'email',
        'address',
        'phone',
        'national_id',
        'date_of_birth',
        'blood_type',
        'chronic_diseases',

    ];

    public function clinic()
    {
        return $this->belongsTo(Clinic::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
}
