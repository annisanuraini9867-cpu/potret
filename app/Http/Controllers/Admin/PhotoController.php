<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PhotoController extends Controller
{
    public function store(Request $request, Booking $booking)
    {
        $request->validate([
            'photos'   => 'required|array|min:1',
            'photos.*' => 'image|mimes:jpeg,png,jpg,webp|max:15360', // Max 15MB per foto
        ], [
            'photos.required' => 'Pilih setidaknya satu file foto untuk diunggah.',
            'photos.*.image'  => 'File harus berformat gambar valid (JPG, PNG, WEBP).',
            'photos.*.max'    => 'Ukuran foto maksimal adalah 15MB.',
        ]);

        $uploadedCount = 0;

        foreach ($request->file('photos') as $file) {
            $path = $file->store("galleries/{$booking->booking_code}", 'public');

            Photo::create([
                'booking_id' => $booking->id,
                'file_path'  => $path,
                'file_name'  => $file->getClientOriginalName(),
                'file_size'  => $file->getSize(),
            ]);

            $uploadedCount++;
        }

        return back()->with('success', "{$uploadedCount} foto berhasil diunggah untuk booking {$booking->booking_code}.");
    }

    public function destroy(Photo $photo)
    {
        if (Storage::disk('public')->exists($photo->file_path)) {
            Storage::disk('public')->delete($photo->file_path);
        }

        $photo->delete();

        return back()->with('success', 'Foto berhasil dihapus dari galeri.');
    }
}
