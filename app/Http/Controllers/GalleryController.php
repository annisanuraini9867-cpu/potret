<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

    /**
     * Menghapus satu file foto tertentu dari galeri sesi
     */
    public function destroyPhoto(string $booking_code, Photo $photo)
    {
        $booking = Booking::where('booking_code', $booking_code)->firstOrFail();

        // Validasi kepemilikan foto
        if ($photo->booking_id !== $booking->id) {
            return back()->with('error', 'Foto ini tidak termasuk dalam sesi foto yang valid.');
        }

        if (Storage::disk('public')->exists($photo->file_path)) {
            Storage::disk('public')->delete($photo->file_path);
        }

        $photo->delete();

        return back()->with('success', 'Foto berhasil dihapus dari galeri sesi.');
    }

    /**
     * Menghapus semua file foto pada sesi galeri
     */
    public function destroyAllPhotos(string $booking_code)
    {
        $booking = Booking::with('photos')->where('booking_code', $booking_code)->firstOrFail();

        if ($booking->photos->isEmpty()) {
            return back()->with('error', 'Belum ada foto yang tersedia untuk dihapus pada sesi ini.');
        }

        $count = $booking->photos->count();

        foreach ($booking->photos as $photo) {
            if (Storage::disk('public')->exists($photo->file_path)) {
                Storage::disk('public')->delete($photo->file_path);
            }
            $photo->delete();
        }

        return back()->with('success', "Seluruh {$count} foto pada sesi {$booking->booking_code} berhasil dihapus.");
    }
}
