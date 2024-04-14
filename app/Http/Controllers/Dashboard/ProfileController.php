<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use LaravelLang\LocaleList\Locale;
use Nnjeim\World\Models\Country;
use Nnjeim\World\Models\State;
use Nnjeim\World\World;


class ProfileController extends Controller
{
    public function edit(Request $request)
    {
        $user=Auth::user();
        $countries = Country::all();
        $locales = Locale::names();
        $countryNames = $countries->pluck('name')->toArray();

        return view('dashboard.profile.edit',['user'=>$user,
            'countries'=>$countryNames,
            'locales'=>$locales,
  ]);
    }
    public function update(Request $request)
    {
        $request->validate([
            'first_name' => ['required', 'string', 'max:255'],
            'last_name' => ['required', 'string', 'max:255'],
            'birthday' => ['nullable', 'date', 'before:today'],
            'gender' => ['in:male,female'],
            'state'=>['nullable'],
            'country' => ['required', 'string'],
            'locale' => ['required', 'string'],
        ]);

        $user = $request->user();

        $adjustedLocale = $request['locale'];

            $user->profile->fill([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'birthday' => $request->birthday,
                'gender' => $request->gender,
                'street_address'=>$request->street_address,
                'city'=>$request->city,
                'state'=>$request->state,
                'postal_code'=>$request->postal_code,
                'country' => $request->country,
                'locale' => $request['locale'],
            ])->save();

            return redirect()->route('dashboard.profile.edit')
                ->with('success', 'Profile updated!');
        }




//        $profile=$user->profile;
//        if($profile->first_name){
//            $user->profile->update($request->all());
//        }
//        else {
////            $request->merge(['user_id'=>$user->id]);
////            Profile::create($request->all());
//            $user->profile()->create($request->all());
//        }


}
