<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Gmopx\LaravelOWM\LaravelOWM;
use Stevebauman\Location\Facades\Location;

class UserController extends Controller
{
    /**
     * @return View
     */
    public function index(): View
    {
        return view('user.index');
    }

    /**
     * @param UserRequest $userRequest
     * @return RedirectResponse
     * @throws \Exception
     */
    public function store(UserRequest $userRequest): RedirectResponse
    {
        $location = Location::get($userRequest->ip());

        $country = $location->cityName ?? $location->countryName;

        $owm = new LaravelOWM();
        $currentWeather = $owm->getCurrentWeather($country);

        foreach ($userRequest->get('users') as $item) {
            User::create([
                'name'    => $item->name,
                'phone'   => $item->phone,
                'country' => $country,
                'weather' => $currentWeather->temperature
            ]);
        }

        session()->flash('message', 'Successfully stored!');

        return redirect()->back();
    }
}
