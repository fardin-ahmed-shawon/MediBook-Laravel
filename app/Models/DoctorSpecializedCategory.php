<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DoctorSpecializedCategory extends Model
{
    use HasFactory;

    protected $table = 'doctors_specialized_categories';

    protected $fillable = [
        'name',
    ];

    public $timestamps = false;

    /**
     * Get the doctors in this category.
     */
    public function doctors(): HasMany
    {
        return $this->hasMany(Doctor::class, 'specialized_area');
    }
}
