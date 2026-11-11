<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUlids;
use Illuminate\Database\Eloquent\Model;

class Training extends Model
{
    use HasUlids;

    public function trainer()
    {
        return $this->belongsTo(User::class, "trainer_id");
    }

    public function student()
    {
        return $this->belongsTo(User::class, "student_id");
    }
}
