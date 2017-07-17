<?php

namespace App\Http\Controllers\FbAuth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Socialite;
use Redirect;
use App\User;
use App\Models\FbUser;
use Auth;

class LoginController extends Controller
{
	/**
     * Redirect the user to the GitHub authentication page.
     *
     * @return Response
     */
    public function redirectToProvider($group_id)
    {
        return Socialite::driver('facebook')->scopes(['user_posts', 'publish_actions', 'user_managed_groups', 'manage_pages', 'publish_pages', 'pages_manage_cta'])->with(['state' => $group_id])->redirect();
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleProviderCallback(Request $request)
    {
        $userAuth = Socialite::driver('facebook')->stateless()->user();

        $group_id = $request->get('state');

        if ($user = User::where('email', $userAuth->email)->first()) {
            Auth::loginUsingId($user->id);
            return Redirect::to('/home');
        }

        $user = new User;
        $user->name = $userAuth->name;
        $user->email = $userAuth->email;
        $user->avatar = $userAuth->avatar;
        $user->group_id = $group_id;
        $user->fb_id = $userAuth->id;
        $user->save();

        if ($group_id == 1) {
            $fbUser = new FbUser;
            $fbUser->user_id = $user->id;
            $fbUser->access_token = $userAuth->token;
            $fbUser->expires_in = $userAuth->expiresIn;

            $fbUser->save();
        }
        Auth::loginUsingId($user->id);
        return Redirect::to('/home');

        return Redirect::to('https://graph.facebook.com/oauth/client_code?access_token='.$user->token.'&client_secret=18aca32d0218e63828e38cafd15fdb94&redirect_uri=http://localhost:8000/callback&client_id=1804782059851995');





        // $user->token;
    }

    /**
     * Obtain the user information from GitHub.
     *
     * @return Response
     */
    public function handleCallback()
    {
        // $user = Socialite::driver('facebook')->user();
        echo strtotime("2017-03-15T10:02:01+0000");
        echo date('M d Y h i s', strtotime("2017-03-15T10:02:01+0000"));
        echo "\n";
        echo (time()-2592000);
        echo date('M d Y', (time()-2592000));
        return date('M d Y', time());
        // return time()+5142383;
        // return $first_date_changed = date('M d Y', (time() + 5144438));

        return Redirect::to('https://graph.facebook.com/oauth/access_token?code=AQAsIAwEZZzf9S097jeyZoPmiALYEueGMUSHkWKm4jg7QxyV1mV3NKtOEpJhIflxkPhSCuBKKaxyQGNdm3GkOuw_wns9-BxSj1dOsgI_49J8i0Vm2ZYI8Q8PNQNI67XORPcSrGldf9Zwo4WA3Y3CxhjVAl0gTzfpBALCvxG7EgJo0DJhxEZ9SVBZR4rYBuhMpoF6oPjFLcx1_Y-uRIRjEmrdWZSZsC_pJU8tPEjWkq7RbO7nCr1yUZdgcz9oRTeMr_UjCCI_k97JnYCK0xThiArv-_oPvocIeLiPI23PKzKc3UffuvRAwV14e-nMEOrFDOMZXnZXq3FsDiGECHiWo7rA&client_id=1804782059851995&redirect_uri=http://localhost:8000/callback');

        return dd($user);
        // $user->token;
    }
}


