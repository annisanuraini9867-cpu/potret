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
    public function step1(Request $request)
    {
        if (Auth::check() && Auth::user()->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        if ($request->filled('plan')) {
            session(['onboarding_plan' => strtolower($request->plan)]);
        }
        if ($request->filled('billing')) {
            session(['onboarding_billing' => strtolower($request->billing)]);
        }

        $saved = session('onboarding_data', []);
        $selectedPlan = session('onboarding_plan', 'pro');
        $selectedBilling = session('onboarding_billing', 'monthly');

        return view('auth.register_step1', compact('saved', 'selectedPlan', 'selectedBilling'));
    }

    public function postStep1(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255', 'regex:/^[a-zA-Z\s\.\']+$/'],
            'email'    => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:6',
            'plan'     => 'nullable|string|in:starter,pro,business',
            'billing'  => 'nullable|string|in:monthly,yearly',
        ], [
            'name.required'     => 'Nama lengkap wajib diisi.',
            'name.regex'        => 'Nama lengkap wajib diisi huruf dan spasi (tidak boleh mengandung angka atau simbol).',
            'email.required'    => 'Alamat email wajib diisi.',
            'email.unique'      => 'Email ini sudah terdaftar di sistem.',
            'password.required' => 'Kata sandi wajib diisi.',
            'password.min'      => 'Kata sandi minimal 6 karakter.',
        ]);

        if ($request->filled('plan')) {
            session(['onboarding_plan' => strtolower($request->plan)]);
        }
        if ($request->filled('billing')) {
            session(['onboarding_billing' => strtolower($request->billing)]);
        }

        session(['onboarding_data' => [
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => $validated['password'],
        ]]);

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
            'studio_phone'   => 'nullable|string|max:50',
            'studio_city'    => 'nullable|string|max:100',
            'booth_type'     => 'nullable|string',
        ], [
            'studio_name.required'    => 'Nama studio wajib diisi.',
            'studio_address.required' => 'Alamat lengkap studio wajib diisi.',
        ]);

        if (empty($validated['studio_phone']) && empty($validated['studio_city'])) {
            $request->validate([
                'studio_phone' => 'required|string|max:50',
            ], [
                'studio_phone.required' => 'Nomor WhatsApp pengelola wajib diisi.',
            ]);
        }

        $validated['booth_type'] = $validated['booth_type'] ?? 'Standard Self-Photo Kiosk';
        if (!empty($validated['studio_phone'])) {
            $validated['phone'] = $validated['studio_phone'];
            $validated['studio_city'] = $validated['studio_city'] ?? $validated['studio_phone'];
        }

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
        $plan = session('onboarding_plan', 'pro');
        $billing = session('onboarding_billing', 'monthly');

        $plans = [
            'starter' => [
                'monthly' => ['name' => 'Starter Booth - Bulanan', 'price' => 149000],
                'yearly'  => ['name' => 'Starter Booth - Tahunan', 'price' => 1341000],
            ],
            'pro' => [
                'monthly' => ['name' => 'Studio Pro - Bulanan', 'price' => 250000],
                'yearly'  => ['name' => 'Studio Pro - Tahunan', 'price' => 2250000],
            ],
            'business' => [
                'monthly' => ['name' => 'Business Multi-Booth - Bulanan', 'price' => 499000],
                'yearly'  => ['name' => 'Business Multi-Booth - Tahunan', 'price' => 4491000],
            ],
        ];

        $selected = $plans[$plan][$billing] ?? $plans['pro']['monthly'];
        $packageName = $selected['name'];
        $packagePrice = $selected['price'];
        $tax = (int) round($packagePrice * 0.11);
        $total = $packagePrice + $tax;

        return view('auth.register_step3', compact('data', 'packageName', 'packagePrice', 'tax', 'total', 'plan', 'billing'));
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
            'phone'          => $data['studio_phone'] ?? $data['phone'] ?? ('08' . rand(100000000, 999999999)),
            'studio_name'    => $data['studio_name'],
            'studio_address' => $data['studio_address'],
            'studio_city'    => $data['studio_city'] ?? 'Indonesia',
            'booth_type'     => $data['booth_type'] ?? 'Standard Self-Photo Kiosk',
            'admin_pin'      => '123456',
        ]);

        // 2. Login User
        Auth::login($user);

        // 3. Hitung harga paket
        $plan = session('onboarding_plan', 'pro');
        $billing = session('onboarding_billing', 'monthly');
        $plans = [
            'starter' => [
                'monthly' => ['name' => 'Starter Booth - Bulanan', 'price' => 149000],
                'yearly'  => ['name' => 'Starter Booth - Tahunan', 'price' => 1341000],
            ],
            'pro' => [
                'monthly' => ['name' => 'Studio Pro - Bulanan', 'price' => 250000],
                'yearly'  => ['name' => 'Studio Pro - Tahunan', 'price' => 2250000],
            ],
            'business' => [
                'monthly' => ['name' => 'Business Multi-Booth - Bulanan', 'price' => 499000],
                'yearly'  => ['name' => 'Business Multi-Booth - Tahunan', 'price' => 4491000],
            ],
        ];
        $selected = $plans[$plan][$billing] ?? $plans['pro']['monthly'];
        $packageName = $selected['name'];
        $packagePrice = $selected['price'];
        $tax = (int) round($packagePrice * 0.11);
        $total = $packagePrice + $tax;

        // 4. Simpan Rincian Pembayaran di Session
        $txnId = 'TXN-PD-' . date('Ymd') . '-' . sprintf('%03d', rand(1, 999));
        session([
            'onboarding_receipt' => [
                'txn_id'         => $txnId,
                'package_name'   => $packageName,
                'payment_method' => $request->payment_method,
                'total_amount'   => $total,
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
