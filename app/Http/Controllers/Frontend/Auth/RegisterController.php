<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterRequest;
use App\Helpers\Frontend\Auth\Socialite;
use App\Events\Frontend\Auth\UserRegistered;
use App\Mail\Frontend\Auth\AdminRegistered;
use App\Models\Auth\User;
use App\Models\College;
use App\Models\CollegeStream;
use App\Models\Student;
use Arcanedev\NoCaptcha\Rules\CaptchaRule;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Repositories\Frontend\Auth\UserRepository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ClosureValidationRule;

/**
 * Class RegisterController.
 */
class RegisterController extends Controller
{
    use RegistersUsers;

    /**
     * @var UserRepository
     */
    protected $userRepository;

    /**
     * RegisterController constructor.
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Where to redirect users after login.
     *
     * @return string
     */
    public function redirectPath()
    {
        return route(home_route());
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showRegistrationForm()
    {
        abort_unless(config('access.registration'), 404);

        return view('frontend.auth.register')
            ->withSocialiteLinks((new Socialite)->getSocialLinks());
    }

    /**
     * @param RegisterRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Throwable
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'phone' => 'required|max:14|unique:users',
            'password' => 'required|min:6|confirmed',
            'g-recaptcha-response' => (config('access.captcha.registration') ? ['required',new CaptchaRule] : ''),
        ],[
            'g-recaptcha-response.required' => __('validation.attributes.frontend.captcha'),
        ]);

        if ($validator->passes()) {
            // Store your user in database
            event(new Registered($user = $this->create($request->all())));
            // otp sent
            $this->sendOtp($user->phone, $user->first_name);

            return response([
                'success' => true,
                'otp' => true,
                'contact_number' => $user->phone,
                'contact_number_preview' => substr($user->phone, 0, -6) . "******",
                'user_id' => $user->id,
                'otp_expire_time' => 10*60 - (time() - session('otp_data')['created_at'])
            ]);

        }

        return response(['errors' => $validator->errors()]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
                $user->dob = isset($data['dob']) ? $data['dob'] : NULL ;
                $user->phone = isset($data['phone']) ? $data['phone'] : NULL ;
                $user->gender = isset($data['gender']) ? $data['gender'] : NULL;
                $user->address = isset($data['address']) ? $data['address'] : NULL;
                $user->city =  isset($data['city']) ? $data['city'] : NULL;
                $user->pincode = isset($data['pincode']) ? $data['pincode'] : NULL;
                $user->state = isset($data['state']) ? $data['state'] : NULL;
                $user->country = isset($data['country']) ? $data['country'] : NULL;
                $user->save();

        $userForRole = User::find($user->id);
        $userForRole->confirmed = 0;
        $userForRole->save();
        $userForRole->assignRole('student');

        if(config('access.users.registration_mail')) {
            $this->sendAdminMail($user);
        }

        return $user;
    }

    public function studentDetailsInsert(Request $request)
    {
        $data = $request->all();
        // college details
        if (!is_int($data['college_id']) && !College::find($data['college_id'])) {
            $college = College::where('name', $data['college_id'])->first();
            if(!$college) {
                $college = College::create([
                    'name' => $data['college_id']
                ]);

            } 
            $data['college_id'] = $college->id;
        }
        
        if (!is_int($data['college_stream_id']) && !CollegeStream::find($data['college_stream_id'])) {
            $college_stream = CollegeStream::where('name', $data['college_stream_id'])->first();
            if(!$college_stream) {

                $college_stream = CollegeStream::create([
                    'name' => $data['college_stream_id']
                ]);
            } 
            $data['college_stream_id'] = $college_stream->id;
        }

        Student::create([
            'user_id' => auth()->user()->id,
            'college_id' => $data['college_id'],
            'college_stream_id' => $data['college_stream_id'],
            'semester' => $data['semester'],
        ]);

        return response([
            'success' => true,
            'otp' => $data
        ]);


    }

    private function sendOtp($contact_number, $name)
    {
        $otp_session = session('otp_data');

        // if (isset($otp_session) && $otp_session['contact_number'] == $contact_number && time() - $otp_session['created_at'] < 10*60) {
        //     return true;
        // }

        $api_key = '26242E8C1F0C88';
        $contacts = "$contact_number";
        $from = 'CSAOTP';
        $otp = rand(pow(10, 4-1), pow(10, 4)-1);
        $sms_text = urlencode("Dear, $name Your OTP for login to the Career Study portal is $otp. Valid for 10 minutes. Please do not share this OTP.-Regards,Career Study Team");
        $template_id = '1207164328742664768';
        
        $api_url = "http://sms.xhost.co.in/app/smsapi/index.php?key=".$api_key."&campaign=11396&routeid=37&type=text&contacts=". $contacts . "&senderid=" . $from . "&msg=" . $sms_text . "&template_id=1207164328742664768";

        //Submit to server
        $response = file_get_contents( $api_url);

        if (!strpos('ERR', $response)) {
            $data['otp'] = $otp;
            $data['contact_number'] = $contact_number;
            $data['created_at'] = time();

            session(['otp_data' => $data]);

            return true;
        }
        return false;

    }

    public function resendOtp(Request $request)
    {
        if ($user = User::find($request->user_id)) {
            $this->sendOtp($user->phone, $user->first_name);
            
            $otp_session = session('otp_data');
            return response([
                'success' => true,
                'otp' => true,
                'contact_number' => $user->phone,
                'contact_number_preview' => substr($user->phone, 0, -6) . "******",
                'user_id' => $user->id,
                'otp_expire_time' => 10*60 - (time() - $otp_session['created_at'])
            ]);
        }

    }

    private function sendAdminMail($user)
    {
        $admins = User::role('administrator')->get();

        foreach ($admins as $admin){
            \Mail::to($admin->email)->send(new AdminRegistered($user));
        }
    }

    public function verifyOtp(Request $request)
    {

        $otp_session = session('otp_data');

        if (isset($otp_session) && $otp_session['contact_number'] == $request->contact_number && $request->otp == $otp_session['otp'] && time() - $otp_session['created_at'] < 10*60) {
            $user = User::find($request->user_id);
            $user->confirmed = 1;
            $user->save();
            session()->forget('otp_data');
            return response(['success' => true, 'otp_confirmed' => true], Response::HTTP_OK);    
        } else {
            return response(['success' => false, 'otp_confirmed' => false], Response::HTTP_FORBIDDEN);    
        }
    }


}
