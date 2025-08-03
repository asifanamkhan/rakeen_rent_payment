<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Carbon\Carbon;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\View\View;
use Jenssegers\Agent\Agent;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $agent = new Agent();

        $session = time().'-'.mt_rand(1, 1000);
        Session::put('log_session', $session);

        $data = [
            'user_name' => Auth::user()->name,
            'user_id' => Auth::user()->id,
            'device_type' => $this->device($agent),
            'browser_info' => $agent->browser(),
            'ip_address' => request()->ip(),
            'access_time' => Carbon::now(),
            'log_session' => $session,
            'platform' => $agent->platform(),
        ];

        DB::table('USR_AUDIT_TRAIL')->insert($data);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function device($agent){
        if ($agent->isDesktop()) {
            return 'Desktop';
        } elseif ($agent->isTablet()) {
            return 'Tablet';
        } elseif ($agent->isMobile()) {
            return 'Mobile';
        } else {
            return $agent->device();
        }
    }


    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $session = session('log_session');
        $agent = new Agent();
        $data = [
            'user_name' => Auth::user()->name,
            'user_id' => Auth::user()->id,
            'device_type' => $this->device($agent),
            'browser_info' => $agent->browser(),
            'ip_address' => request()->ip(),
            'exit_time' => Carbon::now(),
            'platform' => $agent->platform(),
        ];
        if($session){
            DB::table('USR_AUDIT_TRAIL')->where('log_session', $session)
                ->update($data);
            session()->forget('log_session');
        }else{
            DB::table('USR_AUDIT_TRAIL')->insert($data);
        }
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}