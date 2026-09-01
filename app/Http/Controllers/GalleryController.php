<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;
use ZipArchive;

class GalleryController extends Controller
{
    public function index()
    {
        return view('gallery.index');
    }

    public function search(Request $request)
    {
        $request->validate([
            'booking_code' => 'required|string|max:50',
        ]);

        $code = trim($request->booking_code);
        $booking = Booking::where('booking_code', $code)->first();

        if (!$booking) {
            return back()->withInput()->withErrors([
                'booking_code' => 'Kode booking "' . $code . '" tidak ditemukan. Silakan periksa kembali kode Anda.',
            ]);
        }

        return redirect()->route('gallery.show', ['booking_code' => $code]);
    }

    public function show($booking_code)
    {
        $booking = Booking::with(['package', 'photos'])
            ->where('booking_code', $booking_code)
            ->firstOrFail();

        return view('gallery.show', compact('booking'));
    }

    public function downloadZip($booking_code)
    {
        $booking = Booking::with('photos')->where('booking_code', $booking_code)->firstOrFail();

        if ($booking->photos->isEmpty()) {
            return back()->with('error', 'Belum ada foto yang tersedia untuk diunduh pada sesi ini.');
        }

        $zipFileName = "PotretDiri-{$booking->booking_code}.zip";
        $tempDir = storage_path('app/temp');

        if (!file_exists($tempDir)) {
            mkdir($tempDir, 0755, true);
        }

        $zipPath = $tempDir . DIRECTORY_SEPARATOR . $zipFileName;

        $zip = new ZipArchive;
        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($booking->photos as $index => $photo) {
                $fullPath = storage_path("app/public/{$photo->file_path}");
                if (file_exists($fullPath)) {
                    $ext = pathinfo($photo->file_name, PATHINFO_EXTENSION);
                    $cleanName = "Foto-" . ($index + 1) . ($ext ? ".{$ext}" : "");
                    $zip->addFile($fullPath, $cleanName);
                }
            }
            $zip->close();
        }

        if (!file_exists($zipPath)) {
            return back()->with('error', 'Gagal membuat file arsip ZIP.');
        }

        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
