<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Badge extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'icon',
        'criteria_type',
        'criteria_value',
    ];

    /**
     * User yang telah meraih badge ini.
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_badges')
                    ->withPivot('earned_at')
                    ->withTimestamps();
    }

    /**
     * Dapatkan label kriteria yang ramah tampilan.
     */
    public function getCriteriaLabelAttribute(): string
    {
        return match ($this->criteria_type) {
            'modules_completed'  => "Selesaikan {$this->criteria_value} modul materi",
            'quizzes_passed'     => "Lulus {$this->criteria_value} kuis",
            'xp_reached'         => "Kumpulkan {$this->criteria_value} XP",
            'missions_completed' => "Selesaikan {$this->criteria_value} misi",
            default              => "{$this->criteria_type}: {$this->criteria_value}",
        };
    }
}
