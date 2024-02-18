<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contacts;
use Mail;


class ContactFormController extends Controller

{
    public function index()
    {
        return view('pages.contact');
    }
  
    /**
     * Write code on Method
     *
     * @return response()
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|digits:10|numeric',
            'subject' => 'required',
            'message' => 'required'
        ]);
  
        Contacts::create($request->all());
  
        return redirect()->back()
                         ->with(['success' => 'Thank you for contact us. we will contact you shortly.']);
    }
}
