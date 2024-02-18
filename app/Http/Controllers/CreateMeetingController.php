<?php

namespace App\Http\Controllers;

use App\Models\Attendee;
use Illuminate\Http\Request;
use App\Models\Meeting;
use App\Models\User;
use Hash;
use Session;
use Str;
use Mail;

class CreateMeetingController extends Controller
{





    public function attended(Request $request)

    {







        $datas = array();
        if (Session::has('loginId')) {
            $datas = User::all();
        }

        $infos = array();
        if (Session::has('loginId')) {
            $infos = User::where('id', '=', Session::get('loginId'))->first();
        }
        return view('pages.attended', compact('datas', 'infos'));
    }


    public function upcoming(Request $request)

    {

        $data['meetings'] = Meeting::whereHas('attendees', function ($q) {
            $q->where('member_id', auth()->user()->id);
        })->with(['attendees'])->where('date', '>=', date('Y-m-d'))->where('timeend', '>=', date('H:i:s'))->get();



        return view('pages.upcoming', $data);
    }

    public function completedMeeting(Request $request)

    {



        $data['meetings'] = Meeting::whereHas('attendees', function ($q) {
            $q->where('member_id', auth()->user()->id);
        })->with(['attendees'])->get();


        return view('pages.upcoming', $data);
    }



    public function meetinv(Request $request)

    {
          
        $datas = array();
        if (Session::has('loginId')) {
            $datas = User::all();
        }

        $infos = array();
        if (Session::has('loginId')) {
            $infos = User::where('id', '=', Session::get('loginId'))->first();
        }
        return view('pages.meetinv', compact('datas', 'infos'));
    }







    public function create()

    {
        $datas = array();
        if (Session::has('loginId')) {
            $datas = User::all();
        }

        $infos = array();
        if (Session::has('loginId')) {
            $infos = User::where('id', '=', Session::get('loginId'))->first();
        }
        return view('pages.create_meeting', compact('datas', 'infos'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category' => 'required',
            'agenda' => 'required',
            'date' => 'required',
            'time' => 'required',
            'timeend' => 'required',
            'address' => '',
            'platform' => '',
            'link' => '',
        ]);

        $meeting = new Meeting();
        $meeting->category = $request->category;
        $meeting->agenda = $request->agenda;
        $meeting->date = $request->date;
        $meeting->time = $request->time;
        $meeting->timeend = $request->timeend;
        $meeting->address = $request->address;
        $meeting->platform = $request->platform;
        $meeting->link = $request->link;
        $meeting->save();

        foreach ($request->attendee as $atten) {
            $metu = new Attendee();
            $metu->meeting_id = $meeting->id;
            $metu->member_id = $atten;
            $metu->token = (string) Str::uuid();
            $metu->save();
            $user = User::find($atten);

            Mail::send('attendee.mail', $data = ['metu' => $metu, 'user' => $user, 'meeting' => $meeting], function ($m) use ($user) {
                $m->from('emeeting@la360host.com', 'eMeeting');
                $m->to($user->email, $user->name)->subject('Meeting Invitation');
            });
        }

        if ($meeting) {
            return redirect('welcome')->with('success', 'Invitation sent successfully!');
        } else {
            return back()->with('fail', 'Something went wrong');
        }
    }

    public function attendeeApproved(Request $request)
    {
        $exist_attandee = Attendee::where('token', $request->token)->first();
        if ($exist_attandee->approval_status == '1') {
            return redirect()->route('landing-page')->with('e_aprroval_status', 'Already Accepted');
        }

        if ($exist_attandee) {
            $exist_attandee->approval_status = 1;
            $exist_attandee->save();
            return redirect()->route('landing-page')->with('s_aprroval_status', 'Invitation accepted');
        } else if ($exist_attandee) {
            $exist_attandee->approval_status = 2;
            $exist_attandee->save();
            return redirect()->route('landing-page')->with('s_aprroval_status', 'successful approval');
        } else {
            return redirect()->route('landing-page')->with('e_aprroval_status', 'Sorry unsuccessful approval');
        }
    }
}
