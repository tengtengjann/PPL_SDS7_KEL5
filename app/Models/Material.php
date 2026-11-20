<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Material extends Model
{
    use HasFactory;

    public const CATEGORIES = [
        'Energi Surya',
        'Energi Angin',
        'Energi Air',
        'Hemat Energi',
        'Biomassa',
        'Geotermal',
    ];

    protected $fillable = [
        'title',
        'category',
        'description',
        'body',
        'image',
    ];

    public function quizzes(): HasMany
    {
        return $this->hasMany(Quiz::class);
    }

    public function quiz(): HasOne
    {
        return $this->hasOne(Quiz::class);
    }

    public function progresses(): HasMany
    {
        return $this->hasMany(Progress::class);
    }
}

