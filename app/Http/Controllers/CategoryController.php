<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\User;
use Hash;
use Session;

class CategoryController extends Controller
{
   public function index()
   {
      $infos = array();
      if (Session::has('loginId')) {
         $infos = User::where('id', '=', Session::get('loginId'))->first();
      }

      return view('pages.profile', compact('infos'));
   }



   public function init(Request $request)
   {
      $infos = array();
      if (Session::has('loginId')) {
         $infos = User::where('id', '=', Session::get('loginId'))->first();
      }


      return view('pages.settings', compact('infos'));
   }



   public function update(User $user, Request $request)
   {

      $user = User::find($request->id);
      // dd($user);
      $update = $user->name = $request->name;
      $user->email = $request->email;
      $user->designation = $request->designation;
      $user->phone = $request->phone;
      $user->password = $request->password;

      $user->save();

      if ($update) {

         return redirect('/profile');
      }
   }


}