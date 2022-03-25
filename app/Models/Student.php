<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'college_id', 'college_stream_id', 'semester'];

    /**
     * Get the College details
     */
    public function college()
    {
        return $this->belongsTo(College::class);
    }
    
    /**
     * Get the streams details
     */
    public function college_stream()
    {
        return $this->belongsTo(CollegeStream::class);
    }
}
