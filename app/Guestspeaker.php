<?php

namespace App;

use App\Traits\Auditable;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Guestspeaker extends Model
{
    use SoftDeletes, Auditable, HasFactory;

    public $table = 'guestspeakers';

    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $fillable = [
        'speakername',
        'speaker_name',
        'speakermobile',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }

    
    public function speakerNameGuestLectures()
    {
        return $this->hasMany(GuestLecture::class, 'speaker_name_id', 'id');
    }
}
