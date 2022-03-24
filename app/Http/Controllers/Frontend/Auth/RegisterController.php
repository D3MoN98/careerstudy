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
        $res = $this->sendOtp('6291839827');
        return response(['success' => $res]);

        $validator = Validator::make($request->all(), [
            'first_name' => 'required|max:255',
            'last_name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
            'g-recaptcha-response' => (config('access.captcha.registration') ? ['required',new CaptchaRule] : ''),
        ],[
            'g-recaptcha-response.required' => __('validation.attributes.frontend.captcha'),
        ]);

        if ($validator->passes()) {
            // Store your user in database
            event(new Registered($user = $this->create($request->all())));
            return response(['success' => true]);

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
        $userForRole->confirmed = 1;
        $userForRole->save();
        $userForRole->assignRole('student');

        // send otp
        $this->sendOtp($user->phone);

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
            'user_id' => $user->id,
            'college_id' => $data['college_id'],
            'college_stream_id' => $data['college_stream_id'],
            'semester' => $data['semester'],
        ]);

        if(config('access.users.registration_mail')) {
            $this->sendAdminMail($user);
        }

        return $user;
    }

    private function sendOtp($contact_number)
    {
        $api_key = '3623BECF18E37F';
        $contacts = '6291839827,8420304842';
        $from = 'CSAOTP';
        $sms_text = urlencode('Dear, Sudipta Your OTP for login to the Career Study portal is 5432. Valid for 10 minutes. Please do not share this OTP.-Regards,Career Study Team');
        $template_id = '1207164328742664768';
        
        //Submit to server
        
        // $ch = curl_init();
        // curl_setopt($ch,CURLOPT_URL, "http://sms.xhost.co.in/app/smsapi/index.php");
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, "key=" . $api_key . "&routeid=37&type=flash&contacts=" . $contacts . "&senderid=" . $from . "&msg=" .$sms_text . "&template_id=1207164328742664768");
        // $response = curl_exec($ch);
        // curl_close($ch);

        // $ch = curl_init();
        // curl_setopt($ch,CURLOPT_URL, "http://sms.xhost.co.in/app/smsapi/index.php");
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_POST, 1);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, "key=" . $api_key . "&campaign=11396&routeid=37&type=text&contacts=".$contacts."&senderid=".$from."&msg=". $sms_text . "&template_id=1207164328742664768");
        // $response = curl_exec($ch);
        // curl_close($ch);

        $api_url = "http://sms.xhost.co.in/app/smsapi/index.php?key=".$api_key."&campaign=11396&routeid=37&type=text&contacts=". $contacts . "&senderid=" . $from . "&msg=" . $sms_text;

        //Submit to server
        $response = file_get_contents( $api_url);

        return $response;
    }

    private function sendAdminMail($user)
    {
        $admins = User::role('administrator')->get();

        foreach ($admins as $admin){
            \Mail::to($admin->email)->send(new AdminRegistered($user));
        }
    }



}
