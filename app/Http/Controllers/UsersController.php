<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Hash;
use Session;

class UsersController extends Controller
{

    public function users(Request $request)
    {

        /* $type = User::find($request->id);
        if($type->user_type==1)
        {
            $datas = array();
            if (Session::has('loginId')) {
                $datas = User::all();
            }
            return view('pages.users',compact('datas'));
        }
*/
        $datas = User::where('user_type', null)->get();

        return view('pages.users', compact('datas'));
        // else echo "not allowed";


    }

    function updatedelete(Request $req)
    {
        $id = $req->get('id');
        $name = $req->get('name');
        $price = $req->get('email');
        $price = $req->get('phone');



        $prod = User::find($id);
        $prod->delete();

        return redirect('/users');
    }
}
