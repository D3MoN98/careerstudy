<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureTeacherIsApproved
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if(!auth()->user()->hasRole('teacher')){
            return $next($request);
        } else {
            $user = auth()->user();
            if(!$user->teacherProfile->approved && !$user->teacherProfile->kyc_submitted){
                return redirect()->route('frontend.auth.teacher.register.kyc');
            } else if(!$user->teacherProfile->approved && $user->teacherProfile->kyc_submitted) {
                return redirect()->route('frontend.auth.teacher.register.approve');
            }
            return $next($request);
        }
    }
}
