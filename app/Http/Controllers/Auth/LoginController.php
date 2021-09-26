<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use App\Helpers\HttpHelper;
use App\Http\Requests\AuthenticationRequest;
use http\Env\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';

    private $httpHelper;

    // use Throttles;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function showLoginForm() {
        return view("auth.login");
    }

    /**
     * Authenticate against the  API
     * @param AuthenticationRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(AuthenticationRequest $request) {

        if($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }

        $remember = false;
        $credentials = ['username' => $request->get('username'), 'password' => request('password'), 'tenantCode' => $request->get('tenantCode')];

        if ($this->guard()->attempt($credentials, $remember)) {
            $this->clearLoginAttempts($request);
            return redirect()->intended('admin/dashboard');
        }

        $this->incrementLoginAttempts($request);

        $errors = $request->session()->get('error');

        foreach ($errors as $error) {
            alert()->error($error);
        }

        return redirect()->back()
        ->withInput($request->only('username', 'remember', 'tenantCode'))
        ->withErrors($errors);
    }

    /**
     * The user has been authenticated.
     * if user had role redirect to user index in admin panel
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticatedd(\Illuminate\Http\Request $request, $user)
    {
        if (auth()->user()->getRoleNames()->count()) {
            return redirect()->route('user.index');
        }
        if ($request->has('before_checkout_form')){
           return redirect()->route('front.checkout');
        }
    }

    /**
     * Validate the user login request.
     * if a bot filed input then return false
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     *
     * @throws \Illuminate\Validation\ValidationException
     */

    protected function validateLogin(\Illuminate\Http\Request $request)
    {
        if ($request->input('input')){
            return false;
        }
        $request->validate([
            $this->username() => 'required|string',
            'password' => 'required|string',
            'tenantCode' => 'required|string',
        ]);
    }

    public function username()
    {
        return 'username';
    }

    /**
     * @param AuthenticationRequest $request
     * @return string
     */
    private function generateLoginThrottleHash(AuthenticationRequest $request) {
        return md5($request->username . "_" . $request->getClientIp());
    }
}
