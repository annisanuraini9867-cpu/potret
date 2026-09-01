<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Package;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    public function create(Request $request)
    {
        $packages = Package::where('is_active', true)->get();
        $selectedPackageId = $request->query('package_id', $packages->first()?->id);

        return view('bookings.create', compact('packages', 'selectedPackageId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'package_id'     => 'required|exists:packages,id',
            'customer_name'  => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'booking_date'   => 'required|date|after_or_equal:today',
            'start_time'     => 'required|date_format:H:i',
            'notes'          => 'nullable|string|max:1000',
        ]);

        $package = Package::findOrFail($request->package_id);

        // Kalkulasi waktu selesai sesi foto berdasarkan durasi paket
        $startTime = Carbon::createFromFormat('H:i', $request->start_time);
        $endTime = (clone $startTime)->addMinutes($package->duration_minutes);

        $dateString = $request->booking_date;
        $startTimeString = $startTime->format('H:i:s');
        $endTimeString = $endTime->format('H:i:s');

        // Pengecekan overlap / bentrok jadwal pada tanggal yang sama
        $isOverlap = Booking::whereDate('booking_date', $dateString)
            ->whereIn('status', ['pending', 'confirmed'])
            ->where('start_time', '<', $endTimeString)
            ->where('end_time', '>', $startTimeString)
            ->exists();

        if ($isOverlap) {
            return back()
                ->withInput()
                ->withErrors(['start_time' => 'Mohon maaf, slot jam ' . $request->start_time . ' - ' . $endTime->format('H:i') . ' pada tanggal tersebut sudah terisi atau bertabrakan dengan sesi lain. Silakan pilih waktu lain.']);
        }

        $booking = DB::transaction(function () use ($request, $package, $startTimeString, $endTimeString) {
            return Booking::create([
                'user_id'        => Auth::id(), // Nullable jika pelanggan memesan tanpa akun
                'customer_name'  => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'package_id'     => $package->id,
                'booking_date'   => $request->booking_date,
                'start_time'     => $startTimeString,
                'end_time'       => $endTimeString,
                'status'         => 'pending',
                'total_amount'   => $package->price,
                'notes'          => $request->notes,
            ]);
        });

        return redirect()->route('bookings.success', $booking->booking_code)
            ->with('success', 'Pemesanan sesi foto Anda berhasil dibuat! Simpan kode booking Anda.');
    }

    public function success($booking_code)
    {
        $booking = Booking::with('package')->where('booking_code', $booking_code)->firstOrFail();
        return view('bookings.success', compact('booking'));
    }

    public function myBookings()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $bookings = Booking::with(['package', 'photos'])
            ->where('user_id', Auth::id())
            ->latest()
            ->paginate(10);

        return view('bookings.my_bookings', compact('bookings'));
    }
}
