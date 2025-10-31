<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

// --- IMPORT YANG DIPERLUKAN DARI GAMBAR ---
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Role;
// -------------------------------------------

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // Removed $redirectTo = '/home' as we handle redirects manually in login method

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    // --- FUNGSI DARI GAMBAR: image_d7e8c0.png ---
    /**
     * Menampilkan form login.
     */
    public function showLoginForm()
    {
        return view('auth.login');
    }

   public function login(Request $request)
{
    $validator = Validator::make($request->all(), [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ]);

    if ($validator->fails()) {
        return redirect()->back()
            ->withErrors($validator)
            ->withInput();
    }

    $user = User::with(['roles' => function ($query) {
        $query->wherePivot('status', '1');
    }])
        ->where('email', $request->input('email'))
        ->first();

    if (!$user) {
        return redirect()->back()
            ->withErrors(['email' => 'Email tidak ditemukan.'])
            ->withInput();
    }

    // Pastikan user memiliki role yang aktif
    if (!$user->roles || $user->roles->isEmpty()) {
        return redirect()->back()
            ->withErrors(['email' => 'User tidak memiliki role yang aktif.'])
            ->withInput();
    }

    if (!$user) {
        return redirect()->back()
            ->withErrors(['email' => 'Email tidak ditemukan.'])
            ->withInput();
    }

    // Cek password
    if (!Hash::check($request->password, $user->password)) {
        return redirect()->back()
            ->withErrors(['password' => 'Password salah.'])
            ->withInput();
    }

    // Login user ke session
    Auth::login($user);

    // Simpan session user
    $request->session()->put([
        'user_id' => $user->iduser,
        'user_name' => $user->nama,
        'user_email' => $user->email,
        'user_role' => $user->roles[0]->idrole ?? 'user',
        'user_status' => $user->roles[0]->pivot->status ?? 'active'
    ]);

    $userRole = $user->roles[0]->idrole ?? null;

    switch ($userRole) {
        case 1:
            return redirect()->route('admin.dashboard')->with('success', 'Login Berhasil!');
            break;
        case 2:
            return redirect()->route('dokter.dashboard')->with('success', 'Login Berhasil!');
            break;
        case 3:
            return redirect()->route('perawat.dashboard')->with('success', 'Login Berhasil!');
            break;
        case 4:
            return redirect()->route('resepsionis.dashboard')->with('success', 'Login Berhasil!');
            break;
        default:
            return redirect()->route('pemilik.dashboard')->with('success', 'Login Berhasil!');
            break;
    }
}

    // --- FUNGSI DARI GAMBAR: image_d7ebe0.png ---
    /**
     * Menangani proses logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logout berhasil!');
    }
}