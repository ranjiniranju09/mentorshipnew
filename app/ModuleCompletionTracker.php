<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ModuleCompletionTracker extends Model
{
    use HasFactory;

    // Define which attributes are mass assignable
    protected $fillable = ['mentee_id', 'module_id', 'chapter_id', 'completed_at'];

    // This will automatically manage created_at and updated_at timestamps
    public $timestamps = true;
}
