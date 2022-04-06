<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'college_id', 'college_stream_id', 'semester', 'college_type', 'programme_class_id', 'honour_passcourse', 'category_id'];

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
    
    /**
     * Get the streams details
     */
    public function programme_class()
    {
        return $this->belongsTo(ProgrammeClass::class);
    }

    public function categories()
    {
        if (is_null($this->category_id)) {
            return null;
        }
        
        return $categories = Category::whereIn('id', explode(',', $this->category_id))->get();
    }

    public function category_names()
    {
        if (is_null($this->category_id)) {
            return null;
        }
        return $categories = implode(',', array_column($this->categories()->toArray(), 'name'));
    }
}
