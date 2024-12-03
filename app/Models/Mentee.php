<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mentee extends Model
{
    protected $fillable = ['name', 'email', 'password']; // Add other fields as needed

    // Define relationship with MentorMenteeMap
    public function menteeMap()
    {
        return $this->hasOne(MentorMenteeMap::class, 'mentee_id');
    }
}
