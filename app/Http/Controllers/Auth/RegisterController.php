<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\UserActivation;
use App\User;
use DB;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Mail;
use Validator;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function register(Request $request)
    {
        $validator = $this->validator($request->all());
        if($validator->passes())
        {
            $user = $this->create($request->all());
            $token = str_random(30);

            DB::table('activation_user')->insert(array(
                    'user_id' => $user->id,
                    'token' => $token,
                    'sent' => date('Y-m-d')
                ));

            $mail = new UserActivation($token);
            Mail::to($user->email)->send($mail);

            return redirect()->to('login')
                ->with('success', 'Activation link sent to your email');

        }

        return redirect()->back()->with('errors', $validator->errors());
    }

    public function activation($token)
    {
        $activation = DB::table('activation_user')
            ->where('token', $token)
            ->first();

        if(!is_null($activation))
        {
            $user = User::find($activation->user_id);

            if($user->activated == 1){
                return redirect()->to('login')
                    ->with('success', 'Your account is already activated');
            }

            DB::table('activation_user')->where('token', $token)->delete();
            
            $user->activated = 1;
            $user->save();

            return redirect()->to('login')
                ->with('success', 'Your account succesfully activated');

        }

        return redirect()->to('login')
            ->with('errors', 'Your token is invalid');
    }

}
