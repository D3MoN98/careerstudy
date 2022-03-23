<?php

namespace App\Models;

use App\Models\Auth\User;
use Illuminate\Database\Eloquent\Model;

class TeacherProfile extends Model
{
     /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'facebook_link',
        'twitter_link',
        'linkedin_link',
        'payment_method',
        'payment_details',
        'description',
        'subject_specialist',
        'qualification_details',
        'teaching_details',
        'profile_photo',
        'signature',
        'qualification_certificate',
        'bank_passbook',
        'resume',
        'kyc_submitted',
        'approved'
    ];

    protected $casts = [
        'kyc_submitted' => 'boolean',
        'approved' => 'boolean'
    ];

    /**
    * Get the teacher profile that owns the user.
    */
    public function teacher(){
        return $this->belongsTo(User::class);
    }
}
