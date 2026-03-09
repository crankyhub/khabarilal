<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Reporter;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ReporterController extends Controller
{
    public function index()
    {
        $reporters = Reporter::with('user')->paginate(15);
        return view('admin.reporters.index', compact('reporters'));
    }

    public function create()
    {
        return view('admin.reporters.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'bio' => 'nullable|string',
            'beat' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('reporters', 'public');
        }

        DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
            ]);

            Reporter::create([
                'user_id' => $user->id,
                'bio' => $validated['bio'] ?? null,
                'beat' => $validated['beat'] ?? null,
                'photo_path' => $validated['photo_path'] ?? null,
            ]);
        });

        return redirect()->route('admin.reporters.index')->with('success', 'Reporter added successfully.');
    }

    public function edit(Reporter $reporter)
    {
        return view('admin.reporters.edit', compact('reporter'));
    }

    public function update(Request $request, Reporter $reporter)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'beat' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:1024',
        ]);

        if ($request->hasFile('photo')) {
            if ($reporter->photo_path) {
                Storage::disk('public')->delete($reporter->photo_path);
            }
            $validated['photo_path'] = $request->file('photo')->store('reporters', 'public');
        }

        DB::transaction(function () use ($validated, $reporter) {
            $reporter->user->update(['name' => $validated['name']]);
            $reporter->update([
                'bio' => $validated['bio'] ?? null,
                'beat' => $validated['beat'] ?? null,
                'photo_path' => $validated['photo_path'] ?? null,
            ]);
        });

        return redirect()->route('admin.reporters.index')->with('success', 'Reporter profile updated.');
    }

    public function destroy(Reporter $reporter)
    {
        DB::transaction(function () use ($reporter) {
            $user = $reporter->user;
            $reporter->delete();
            $user->delete();
        });

        return redirect()->route('admin.reporters.index')->with('success', 'Reporter removed.');
    }
}
