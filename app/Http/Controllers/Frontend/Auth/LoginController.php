<?php

namespace App\Http\Controllers\Frontend\Auth;

use App\Helpers\Auth\Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Helpers\Frontend\Auth\Socialite;
use App\Events\Frontend\Auth\UserLoggedIn;
use App\Events\Frontend\Auth\UserLoggedOut;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Repositories\Frontend\Auth\UserSessionRepository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Arcanedev\NoCaptcha\Rules\CaptchaRule;


/**
 * Class LoginController.
 */
class LoginController extends Controller
{
    use AuthenticatesUsers;

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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showLoginForm()
    {
        if(request()->ajax()){
            return ['socialLinks' => (new Socialite)->getSocialLinks()];
        }

        return redirect('/')->with('show_login', true);
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return config('access.users.username');
    }



    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|max:255',
            'password' => 'required|min:6',
            'g-recaptcha-response' => (config('access.captcha.registration') ? ['required',new CaptchaRule] : ''),
        ],[
            'g-recaptcha-response.required' => __('validation.attributes.frontend.captcha'),
        ]);

        if($validator->passes()){
            $credentials = $request->only($this->username(), 'password');
            $authSuccess = \Illuminate\Support\Facades\Auth::attempt($credentials, $request->has('remember'));
            if($authSuccess) {

                // otp check
                if (!auth()->user()->confirmed && auth()->user()->hasRole('student')) {
                    $contact_number = auth()->user()->phone;
                    $user = auth()->user();
                    
                    $this->sendOtp($contact_number, auth()->user()->first_name);

                    \Illuminate\Support\Facades\Auth::logout();

                    $otp_data = session('otp_data');

                    return response([
                        'success' => false,
                        'otp' => false,
                        'contact_number' => $user->phone,
                        'contact_number_preview' => substr($user->phone, 0, -6) . "******",
                        'user_id' => $user->id,
                        'otp_expire_time' => 10*60 - (time() - session('otp_data')['created_at'])
                    ], Response::HTTP_FORBIDDEN);
                }

                $request->session()->regenerate();
                if(auth()->user()->active > 0){
                    if(auth()->user()->isAdmin()){
                        $redirect = 'dashboard';
                    }else{
                        $redirect = 'back';
                    }
                    auth()->user()->update([
                        'last_login_at' => Carbon::now()->toDateTimeString(),
                        'last_login_ip' => $request->getClientIp()
                    ]);
                    if($request->ajax()){
                        return response(['success' => true,'redirect' => $redirect], Response::HTTP_OK);
                    }else{
                        return redirect('/user/dashboard');
                    }
                }else{
                    \Illuminate\Support\Facades\Auth::logout();

                    return
                        response([
                            'success' => false,
                            'message' => 'Login failed. Account is not active'
                        ], Response::HTTP_FORBIDDEN);
                }
            }else{
                return
                    response([
                        'success' => false,
                        'message' => 'Login failed. Account not found'
                    ], Response::HTTP_FORBIDDEN);
            }

        }


        return response(['success'=>false,'errors' => $validator->errors()]);

    }


    private function sendOtp($contact_number, $name)
    {
        $otp_session = session('otp_data');

        if (isset($otp_session) && $otp_session['contact_number'] == $contact_number && time() - $otp_session['created_at'] < 10*60) {
            return true;
        }

        $api_key = '3623BECF18E37F';
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


    /**
     * The user has been authenticated.
     *
     * @param Request $request
     * @param         $user
     *
     * @return \Illuminate\Http\RedirectResponse
     * @throws GeneralException
     */
    protected function authenticated(Request $request, $user)
    {
        /*
         * Check to see if the users account is confirmed and active
         */
        if (! $user->isConfirmed()) {
            auth()->logout();

            // If the user is pending (account approval is on)
            if ($user->isPending()) {
                throw new GeneralException(__('exceptions.frontend.auth.confirmation.pending'));
            }

            // Otherwise see if they want to resent the confirmation e-mail

            throw new GeneralException(__('exceptions.frontend.auth.confirmation.resend', ['url' => route('frontend.auth.account.confirm.resend', $user->{$user->getUuidName()})]));
        } elseif (! $user->isActive()) {
            auth()->logout();
            throw new GeneralException(__('exceptions.frontend.auth.deactivated'));
        }

        event(new UserLoggedIn($user));

        // If only allowed one session at a time
        if (config('access.users.single_login')) {
            resolve(UserSessionRepository::class)->clearSessionExceptCurrent($user);
        }

        return redirect()->intended($this->redirectPath());
    }

    /**
     * Log the user out of the application.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {
        /*
         * Remove the socialite session variable if exists
         */
        if (app('session')->has(config('access.socialite_session_name'))) {
            app('session')->forget(config('access.socialite_session_name'));
        }

        /*
         * Remove any session data from backend
         */
        app()->make(Auth::class)->flushTempSession();

        /*
         * Fire event, Log out user, Redirect
         */
        event(new UserLoggedOut($request->user()));

        /*
         * Laravel specific logic
         */
        $this->guard()->logout();
        $request->session()->invalidate();

        return redirect()->route('frontend.index');
    }

    /**
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logoutAs()
    {
        // If for some reason route is getting hit without someone already logged in
        if (! auth()->user()) {
            return redirect()->route('frontend.auth.login');
        }

        // If admin id is set, relogin
        if (session()->has('admin_user_id') && session()->has('temp_user_id')) {
            // Save admin id
            $admin_id = session()->get('admin_user_id');

            app()->make(Auth::class)->flushTempSession();

            // Re-login admin
            auth()->loginUsingId((int) $admin_id);

            // Redirect to backend user page
            return redirect()->route('admin.auth.user.index');
        } else {
            app()->make(Auth::class)->flushTempSession();

            // Otherwise logout and redirect to login
            auth()->logout();

            return redirect()->route('frontend.auth.login');
        }
    }
}
