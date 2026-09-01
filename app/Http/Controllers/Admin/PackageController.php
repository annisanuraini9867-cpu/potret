<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Package;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class PackageController extends Controller
{
    public function index()
    {
        $packages = Package::withCount('bookings')->latest()->get();
        return view('admin.packages.index', compact('packages'));
    }

    public function create()
    {
        return view('admin.packages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'price'            => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:5|max:240',
            'max_persons'      => 'required|integer|min:1|max:50',
            'description'      => 'nullable|string',
            'is_active'        => 'boolean',
        ]);

        $slug = Str::slug($request->name);
        $originalSlug = $slug;
        $count = 1;
        while (Package::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$count}";
            $count++;
        }

        Package::create([
            'name'             => $request->name,
            'slug'             => $slug,
            'price'            => $request->price,
            'duration_minutes' => $request->duration_minutes,
            'max_persons'      => $request->max_persons,
            'description'      => $request->description,
            'is_active'        => $request->has('is_active'),
        ]);

        return redirect()->route('admin.packages.index')->with('success', 'Paket foto baru berhasil ditambahkan.');
    }

    public function edit(Package $package)
    {
        return view('admin.packages.edit', compact('package'));
    }

    public function update(Request $request, Package $package)
    {
        $request->validate([
            'name'             => 'required|string|max:255',
            'price'            => 'required|numeric|min:0',
            'duration_minutes' => 'required|integer|min:5|max:240',
            'max_persons'      => 'required|integer|min:1|max:50',
            'description'      => 'nullable|string',
        ]);

        $package->update([
            'name'             => $request->name,
            'price'            => $request->price,
            'duration_minutes' => $request->duration_minutes,
            'max_persons'      => $request->max_persons,
            'description'      => $request->description,
            'is_active'        => $request->has('is_active'),
        ]);

        return redirect()->route('admin.packages.index')->with('success', "Paket {$package->name} berhasil diperbarui.");
    }

    public function destroy(Package $package)
    {
        if ($package->bookings()->exists()) {
            return back()->with('error', 'Paket tidak dapat dihapus karena sudah memiliki riwayat pemesanan. Anda dapat menonaktifkannya.');
        }

        $package->delete();
        return redirect()->route('admin.packages.index')->with('success', 'Paket foto berhasil dihapus.');
    }
}
