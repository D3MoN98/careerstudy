<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollegeStream extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'college_id'];

    /**
     * Get the college that owns the CollegeStream
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function college()
    {
        return $this->belongsTo(College::class);
    }
}
