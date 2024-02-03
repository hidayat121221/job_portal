<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AccountController extends Controller
{
   public function registration(){
    return view('front.account.registration');
   }

   public function processRegistration(Request $request){
      $validator = validator::make($request->all(),[
         'name'=>'required',
         'email'=>'required|email|unique:users,email',
         'password'=>'required|min:5',
         'confrim_password'=>'required|same:password'
      ]);
      if($validator->passes()){

         $user = new User();
         $user->name = $request->name;
         $user->email = $request->email;
         $user->password = Hash::make($request->password);
         $user->save();

         session()->flash('success','you have registered successfuly');
         return response()->json([
            'status'=>true,
            'errors'=> []
         ]);
      }else{
         return response()->json([
            'status'=>false,
            'errors'=> $validator->errors()
         ]);
      }
   }

   public function login(){
    return  view('front.account.login');
   }
   public function authenticate(Request $request){
      $validator = validator::make($request->all(),[
         'email'=>'required|email',
         'password'=>'required'
      ]);
      if($validator->passes()){
         if(Auth::attempt(['email' => $request->email, 'password' => $request->password])){
            return redirect()->route('account.profile');
         }else{
            return redirect()->route('account.login')->with('error','Either Email/password is incorrect');
         }
      }else{
        return redirect()->route('account.login')->withErrors($validator)->withInput($request->only('email'));
      }
   }
   public function profile(){
      $id = Auth::user()->id;
      $user = User::where('id',$id)->first();
      return view('front.account.profile',[
         'user'=> $user
      ]);
   }

   public function updateProfile(Request $request){
      $id = Auth::user()->id;
      $validator = validator::make($request->all(),[
         'name'=>'required|min:5|max:20',
         'email'=>'required|email|unique:users,email,'. $id .',id'
      ]);

      if($validator->passes()){
         $user = User::find($id);
         $user->name = $request->name;
         $user->email = $request->email;
         $user->designation = $request->designation;
         $user->mobile = $request->mobile;
         $user->save();

         session()->flash('success','profile updated successful');

         return response()->json([
            'status'=>true,
            'errors'=>  []
            ]);

      }else{
         return response()->json([
         'status'=>false,
         'errors'=>  $validator->errors()
         ]);
      }
   }

   public function logout(){
      Auth::logout();
      return redirect()->route('account.logout');
   }
}
