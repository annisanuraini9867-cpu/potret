<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OnboardingController extends Controller
{
    /**
     * Langkah 1: Buat Akun Admin (Profil)
     */
    public function step1()
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        $saved = session('onboarding_data', []);
        return view('auth.register_step1', compact('saved'));
    }

    public function postStep1(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
        ], [
            'name.required'     => 'Nama lengkap wajib diisi.',
            'email.required'    => 'Alamat email wajib diisi.',
            'email.unique'      => 'Email ini sudah terdaftar di sistem.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min'      => 'Kata sandi minimal 6 karakter.',
        ]);

        session(['onboarding_data' => $validated]);

        return redirect()->route('onboarding.step2');
    }

    /**
     * Langkah 2: Informasi Studio
     */
    public function step2()
    {
        if (!session()->has('onboarding_data.email')) {
            return redirect()->route('register');
        }

        $saved = session('onboarding_data', []);
        return view('auth.register_step2', compact('saved'));
    }

    public function postStep2(Request $request)
    {
        $validated = $request->validate([
            'studio_name'    => 'required|string|max:255',
            'studio_address' => 'required|string|max:500',
            'studio_city'    => 'required|string|max:100',
            'booth_type'     => 'required|string',
        ], [
            'studio_name.required'    => 'Nama studio wajib diisi.',
            'studio_address.required' => 'Alamat lengkap studio wajib diisi.',
            'studio_city.required'    => 'Kota lokasi studio wajib diisi.',
            'booth_type.required'     => 'Silakan pilih tipe booth Anda.',
        ]);

        $currentData = session('onboarding_data', []);
        session(['onboarding_data' => array_merge($currentData, $validated)]);

        return redirect()->route('onboarding.step3');
    }

    /**
     * Langkah 3: Metode Pembayaran & Ringkasan Pesanan
     */
    public function step3()
    {
        if (!session()->has('onboarding_data.studio_name')) {
            return redirect()->route('onboarding.step2');
        }

        $data = session('onboarding_data', []);
        $packageName = 'Studio Pro - Bulanan';
        $packagePrice = 250000;
        $tax = 27500; // 11% PPN
        $total = 277500;

        return view('auth.register_step3', compact('data', 'packageName', 'packagePrice', 'tax', 'total'));
    }

    public function postStep3(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|string',
        ], [
            'payment_method.required' => 'Silakan pilih metode pembayaran.',
        ]);

        $data = session('onboarding_data');
        if (!$data) {
            return redirect()->route('register');
        }

        // 1. Buat User Admin di Database
        $user = User::create([
            'name'           => $data['name'],
            'email'          => $data['email'],
            'password'       => $data['password'],
            'role'           => 'admin',
            'phone'          => '08' . rand(100000000, 999999999),
            'studio_name'    => $data['studio_name'],
            'studio_address' => $data['studio_address'],
            'studio_city'    => $data['studio_city'],
            'booth_type'     => $data['booth_type'],
            'admin_pin'      => '123456',
        ]);

        // 2. Login User
        Auth::login($user);

        // 3. Simpan Rincian Pembayaran di Session
        $txnId = 'TXN-PD-' . date('Ymd') . '-' . sprintf('%03d', rand(1, 999));
        session([
            'onboarding_receipt' => [
                'txn_id'         => $txnId,
                'package_name'   => 'Studio Pro - Bulanan',
                'payment_method' => $request->payment_method,
                'total_amount'   => 277500,
            ]
        ]);

        return redirect()->route('onboarding.success');
    }

    /**
     * Langkah 4: Pembayaran Berhasil (Receipt)
     */
    public function success()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $receipt = session('onboarding_receipt', [
            'txn_id'         => 'TXN-PD-' . date('Ymd') . '-089',
            'package_name'   => 'Studio Pro - Bulanan',
            'payment_method' => 'Virtual Account BCA',
            'total_amount'   => 277500,
        ]);

        return view('auth.register_success', compact('receipt'));
    }

    /**
     * Langkah 5: Buat PIN Keamanan Admin (6 Digit Keypad)
     */
    public function setPin()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        return view('auth.register_pin');
    }

    public function postSetPin(Request $request)
    {
        $request->validate([
            'pin' => 'required|string|size:6|regex:/^[0-9]{6}$/',
        ], [
            'pin.required' => 'PIN wajib diisi.',
            'pin.size'     => 'PIN harus tepat 6 digit angka.',
            'pin.regex'    => 'PIN hanya boleh berisi angka.',
        ]);

        $user = Auth::user();
        /** @var \App\Models\User $user */
        $user->update([
            'admin_pin' => $request->pin,
        ]);

        // Bersihkan session onboarding
        session()->forget(['onboarding_data', 'onboarding_receipt']);

        return redirect()->route('admin.dashboard')->with('success', 'Selamat datang! Akun studio dan PIN keamanan Anda berhasil diatur.');
    }
}
