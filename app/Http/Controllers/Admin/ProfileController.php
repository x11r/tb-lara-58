<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App;
use App\Profile;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    //
    public function add()
    {
        return view('admin.profile.create');
    }

    public function create(Request $request)
    {
        $this->validate($request, Profile::$rules);

        $profile = new Profile;
        $form = $request->all();

        unset($form['_token']);
        unset($form['image']);

        $profile->fill($form);
        $profile->save();

        return redirect('admin/profile/create');
    }

//    public function edit2(Request $request)
//    {
//        $profile = Profile::find($request->id);
//        if (empty($profile)) {
//            abort(404);
//        }
//
//        return view('admin.profile.edit', ['profile_form' => $profile]);
//    }

    public function edit($profile_id)
    {
        $profile = Profile::find($profile_id);
        if (empty($profile)) {
            abort(404);
        }

        return view('admin.profile.edit', ['profile_form' => $profile]);
    }

    public function update(Request $request)
    {
        $this->validate($request, Profile::$rules);

        $profile_id = $request->id;
        $profile = Profile::find($profile_id);
        $profile_form = $request->all();

        unset($profile_form['_token']);
        unset($profile_form['image']);
        unset($profile_form['remove']);

        \Log::debug(__LINE__.' '.__FILE__.' '.print_r($profile_form, true));

        $profile->fill($profile_form)->save();

        return redirect('admin/profile/edit/' . $profile_id);
    }
}
