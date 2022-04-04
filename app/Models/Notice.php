<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'title', 'content', 'type', 'view_by', 'opened_at', 'closed_at', 'is_active', 'category'];

    protected $casts = [
        'is_active' => 'boolean',
        'opened_at' => 'date',
        'closed_at' => 'date',
    ];

    public function getRelevantNotices()
    {
        if (auth()->user()->hasRole('student')) {
            $view_by = ['all', 'student'];
        } else if(auth()->user()->hasRole('teacher')){
            $view_by = ['all', 'teacher'];
        } else {
            $view_by = ['all', 'teacher', 'student'];
        }
        return $notice = Notice::whereDate('closed_at' , '>=', date('Y-m-d'))->whereIn('view_by', $view_by)->where('category', 'notice')->get();
    }

    public function getRelevantJobs()
    {
        if (auth()->user()->hasRole('student')) {
            $view_by = ['all', 'student'];
        } else if(auth()->user()->hasRole('teacher')){
            $view_by = ['all', 'teacher'];
        } else {
            $view_by = ['all', 'teacher', 'student'];
        }
        return $notice = Notice::whereDate('closed_at' , '>=', date('Y-m-d'))->whereIn('view_by', $view_by)->where('category', 'job')->get();
    }

}
