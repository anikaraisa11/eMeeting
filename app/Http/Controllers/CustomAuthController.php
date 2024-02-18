<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Meeting;
use App\Models\Attendee;
use Hash;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CustomAuthController extends Controller
{
    public function login()
    {
        // dd("hi");
        return view('pages.login');
    }

    public function registration()
    {

        return view('pages.registration');
    }
    public function registerUser(Request $request)
    {

        $request->validate([
            'name' => 'required',
            'email' => 'required|unique:users',
            'designation' => 'required',
            'phone' => 'required',
            'password' => 'required|min:8|max:12'

        ]);

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->designation = $request->designation;
        $user->phone = $request->phone;
        $user->password = Hash::make($request->password);
        $res = $user->save();

        if ($res) {
            return redirect('login')->with('success', 'Registered Successfully! Sign In');
        } else {

            return back()->with('fail', 'Something went wrong');
        }
    }


    public function loginUser(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required'
        ]);

        $user = User::where('email', '=', $request->email)->first();
        if ($user) {
            // if (Hash::check($request->password, $user->password)) {
            if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
                $request->session()->put('loginId', $user->id);
                return redirect('welcome');
            } else {
                return back()->with('fail', 'Credentials do not match!');
            }
        } else {
            return back()->with('fail', 'This E-mail is not registered!');
        }
    }

    public function welcome()
    {

        $data['up_attendees'] = Meeting::whereHas('attendees', function ($q) {
            $q->where('member_id', auth()->user()->id);
        })->with(['attendees'])->where('date', '>=', date('Y-m-d'))->where('timeend', '>=', date('H:i:s'))->get();


        // dd($data['up_attendees']->toArray());

        $data['com_attendees'] = Meeting::whereHas('attendees', function ($q) {
            $q->where('member_id', auth()->user()->id);
        })->with(['attendees'])->get();


        return view('pages.welcome', $data);
    }


    public function logout()
    {
        if (Session::has('loginId')) {
            Session::pull('loginId');
            return redirect('/');
        }
    }
   
}
