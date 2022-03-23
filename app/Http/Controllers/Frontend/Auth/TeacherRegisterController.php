<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Exceptions\GeneralException;
use App\Models\Auth\User;
use Illuminate\Http\Request;
use App\Models\TeacherProfile;
use App\Http\Controllers\Controller;
use App\Http\Requests\Frontend\Auth\TeacherRegisterRequest;
use App\Notifications\Frontend\Auth\TeacherNeedsConfirmation;
use App\Notifications\Frontend\Auth\UserNeedsConfirmation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Mail;

class TeacherRegisterController extends Controller
{
    /**
     * Show the application teacher registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showTeacherRegistrationForm()
    {
        return view('frontend.auth.registerTeacher');
    }

    /**
     * Show the application teacher kyc registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showTeacherKycRegistrationForm()
    {
        if (!auth()->user()->confirmed) {
            return redirect()->route('frontend.auth.teacher.register.confirm_email');
        }

        if (auth()->user()->teacherProfile->kyc_submitted) {
            return redirect()->route('frontend.auth.teacher.register.approve');
        }

        return view('frontend.auth.kycRegisterTeacher');
    }


    /**
     * show approve step of teacher registration form
     * @return View
     */
    public function showTeacherApproveRegistration()
    {
        if (!auth()->user()->teacherProfile->kyc_submitted) {
            return redirect()->route('frontend.auth.teacher.register.kyc');
        }
        return view('frontend.auth.approveRegisterTeacher');
    }


    /**
     * show confirm email step of teacher registration form
     * @return View
     */
    public function showTeacherConfirmEmailRegistration()
    {
        if (auth()->user()->confirmed) {
            return redirect()->route('frontend.auth.teacher.register.kyc');
        }
        return view('frontend.auth.confirmEmailRegisterTeacher');
    }

    /**
     * Register new teacher
     *
     * @param \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     **/
    public function register(TeacherRegisterRequest $request)
    {
        $user = $request->all();
        $user['confirmation_code'] = md5(md5(uniqid(mt_rand(), true)));
        $user = User::create($user);

        $user->notify(new TeacherNeedsConfirmation($user->confirmation_code));

        // teacher profile create
        if ($request->has('image')) {
            $user->avatar_type = 'storage';
            $user->avatar_location = $request->image->store('/avatars', 'public');
        }
        $user->active = 0;
        $user->save();
        $user->assignRole('teacher');
        $payment_details = [
            'bank_name' => $request->bank_name,
            'ifsc_code' => $request->ifsc_code,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'paypal_email' => $request->paypal_email,
            'upi_id' => $request->upi_id,
        ];

        $data = [
            'user_id' => $user->id,
            'facebook_link' => $request->facebook_link,
            'twitter_link' => $request->twitter_link,
            'linkedin_link' => $request->linkedin_link,
            'payment_method' => 'bank',
            'payment_details' => json_encode($payment_details),
            'description'       => $request->description,
        ];
        $teacher = TeacherProfile::create($data);


        // for teacher after successfully register teacher it will go to ky verification with pogin session
        $credentials = [
            'email' => $request->email,
            'password' => $request->password,
        ];

        if (Auth::attempt($credentials, true)) {
            $request->session()->regenerate();

            auth()->user()->update([
                'last_login_at' => Carbon::now()->toDateTimeString(),
                'last_login_ip' => $request->getClientIp()
            ]);

            return redirect()->route('frontend.auth.teacher.register.confirm_email');
        } else {
            return redirect()->back()->withErrors(['msg' => 'Something went wrong']);
        }

        // return redirect()->route('frontend.index')->withFlashSuccess(trans('labels.frontend.modal.registration_message'))->with(['openModel' => true]);
    }

    public function registerTeacher(Request $request)
    {
        // dd($request->all());

        $user = Auth::user();
        $teacher_id = $user->teacherProfile->id;

        $payment_details = [
            'bank_name' => $request->bank_name,
            'ifsc_code' => $request->ifsc_code,
            'account_number' => $request->account_number,
            'account_name' => $request->account_name,
            'paypal_email' => $request->paypal_email,
            'upi_id' => $request->upi_id,
        ];

        // teacher kyc uplaod 
        $kyc_files = ['profile_photo' => null, 'signature' => null, 'qualification_certificate' => null, 'bank_passbook' => null, 'resume' => null];

        foreach ($kyc_files as $key => $value) {
            if ($request->has($key)) {
                $kyc_files[$key] = $request->$key->store("/teacher/$key", 'public');
            }
        }

        // update teacher profile
        TeacherProfile::find($teacher_id)->update([
            'payment_method' => $request->payment_method,
            'payment_details' => json_encode($payment_details),
            'subject_specialist' => $request->subject_specialist,
            'teaching_details' => $request->teaching_details,
            'qualification_details' => $request->qualification_details,
            'profile_photo' => $kyc_files['profile_photo'],
            'signature' => $kyc_files['signature'],
            'qualification_certificate' => $kyc_files['qualification_certificate'],
            'bank_passbook' => $kyc_files['bank_passbook'],
            'resume' => $kyc_files['resume'],
            'kyc_submitted' => true
        ]);

        return redirect()->route('frontend.auth.teacher.register.approve')->withSuccess(['message' => 'successfully submitted']);
    }

}
