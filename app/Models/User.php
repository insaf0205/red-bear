<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasUlids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'subscription_activated_at' => 'datetime',
            'last_charged_at' => 'datetime',
        ];
    }

    public function user_trainings()
    {
        return $this->hasMany(Training::class, "student_id");
    }

    public function trainer_trainings()
    {
        return $this->hasMany(Training::class, "trainer_id");
    }

    protected function subscriptionEndsAt(): Attribute
    {
        return Attribute::get(fn () => $this->last_charged_at?->copy()->addMonthNoOverflow());
    }

    public function hasSubscription(): bool
    {
        return $this->subscription_ends_at?->isFuture() ?? false;
    }

    public function scopeSubscribed(Builder $query): Builder
    {
        return $query->where('last_charged_at', '>', now()->subMonthNoOverflow());
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, "trainer_id");
    }

    public function reviewsGiven()
    {
        return $this->hasMany(\App\Models\Review::class, 'user_id');
    }

}
