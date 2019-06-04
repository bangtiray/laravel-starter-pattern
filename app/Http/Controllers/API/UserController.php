<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use  App\Http\Controllers\API\ApiController;
use App\Models\User;
use Notification;
use App\Exceptions\GeneralException;
use App\Notifications\JastanNotif;
use Illuminate\Support\Facades\Auth;
use Validator;

use App\Repositories\User\UserRepository;

class UserController extends ApiController
{
    protected $repository;
    public $successStatus = 200;
    const USER = User::class;

  
    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }
  

    public function login(){
        if(Auth::attempt(['email' => request('email'), 'password' => request('password')])){
            $user = Auth::user();
            if($user->confirmed != 0){
                $success['token'] =  $user->createToken('nApp')->accessToken;
             //   return response()->json(['success' => $success], $this->successStatus);
                return $this->respond([
                    'message'   => $success,
                ]);
            }else{
                
                return $this->respondWithError("Mohon aktivasi akun anda");
            }
        }
        else{
            return $this->respondWithError("Tidak ada otoritas untuk akun anda");
        }
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
            'c_password' => 'required|same:password',
            'is_term_accept'=> 'required'
        ]);

        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()->first()], 401);            
        }

        $input = $request->all();
        // $input['password'] = bcrypt($input['password']);
        $user = self::USER;
        $user = new $user();
        $user->first_name = $input['first_name'];
        $user->last_name = $input['last_name'];
        $user->name = $input['first_name']." ". $input['last_name'];
        $user->email = $input['email'];
        $user->password =  bcrypt($input['password']);
        $user->confirmation_code = md5(uniqid(mt_rand(), true));
        $user->status = 1;
        $user->is_term_accept = $input['is_term_accept'];
        $user = $user->save();

        $user = User::first();
        $success['token'] =  $user->createToken('nApp')->accessToken;
        $success['name'] =  $user->name;


        $user = User::first();
  
        $details = [
            'greeting' => 'Hi ' . $user->name,
            'body' => 'Please Confirmation your account',
            'thanks' => 'Thank you for using jastan.co.id',
            'actionText' => 'Confirmation',
            'actionURL' => url('/api/v1/auth/activation/'.$user->confirmation_code),
            'order_id' => 101
        ];
  
        Notification::send($user, new JastanNotif($details));

        return response()->json(['success'=>$success], $this->successStatus);
    }

    public function activation($param)
    {
        $user = $this->repository->confirmAccount($param);
        if($user){
            return $this->respond(['message'=>"Berhasil Melakukan konfirmasi"]);
        }
    }
        
    public function details()
    {
       
        // $user = Auth::user()->profile;
        $user =User::with('profile')->find(Auth::id());
        return $this->respond($user);
    }
}