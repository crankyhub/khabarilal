<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reporter;
use App\Models\User;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReporterManagementController extends Controller
{
    public function index()
    {
        $reporters = Reporter::with(['user', 'category'])
            ->withCount('articles')
            ->get();
            
        return view('admin.reporters.index', compact('reporters'));
    }

    public function create()
    {
        $categories = Category::all();
        $roles = [
            User::ROLE_REPORTER => 'Reporter',
            User::ROLE_EDITOR => 'Editor',
            User::ROLE_SUB_EDITOR => 'Sub Editor',
            User::ROLE_SUPER_ADMIN => 'Super Admin',
            User::ROLE_GUEST => 'Guest Contributor',
        ];
        return view('admin.reporters.create', compact('categories', 'roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|string',
            'beat' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'bio' => 'nullable|string',
            'revenue_share' => 'required|numeric|min:0|max:100',
        ]);

        \Illuminate\Support\Facades\DB::transaction(function () use ($validated) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => \Illuminate\Support\Facades\Hash::make($validated['password']),
                'role' => $validated['role'],
                'status' => User::STATUS_ACTIVE,
            ]);

            Reporter::create([
                'user_id' => $user->id,
                'beat' => $validated['beat'],
                'category_id' => $validated['category_id'],
                'bio' => $validated['bio'],
                'revenue_share' => $validated['revenue_share'],
                'status' => 'active',
            ]);
        });

        return redirect()->route('admin.reporters.index')->with('success', 'Reporter created successfully.');
    }

    public function edit(Reporter $reporter)
    {
        $categories = Category::all();
        $roles = [
            User::ROLE_SUPER_ADMIN => 'Super Admin',
            User::ROLE_EDITOR => 'Editor',
            User::ROLE_SUB_EDITOR => 'Sub Editor',
            User::ROLE_REPORTER => 'Reporter',
            User::ROLE_GUEST => 'Guest Contributor',
        ];
        
        return view('admin.reporters.edit', compact('reporter', 'categories', 'roles'));
    }

    public function update(Request $request, Reporter $reporter)
    {
        $validated = $request->validate([
            'role' => 'required|string',
            'status' => 'required|string',
            'beat' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'bio' => 'nullable|string',
            'revenue_share' => 'required|numeric|min:0|max:100',
            'social_links' => 'nullable|array',
            'password' => 'nullable|string|min:8',
        ]);

        $userData = [
            'role' => $validated['role'],
            'status' => $validated['status'],
        ];

        if (!empty($validated['password'])) {
            $userData['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        }

        $reporter->user->update($userData);

        $reporter->update([
            'beat' => $validated['beat'],
            'category_id' => $validated['category_id'],
            'bio' => $validated['bio'],
            'revenue_share' => $validated['revenue_share'],
            'social_links' => $validated['social_links'],
        ]);

        return redirect()->route('admin.reporters.index')->with('success', 'Reporter updated successfully.');
    }

    public function toggleStatus(User $user)
    {
        if ($user->status === User::STATUS_ACTIVE) {
            $user->update(['status' => User::STATUS_SUSPENDED]);
            $msg = 'Reporter suspended.';
        } elseif ($user->status === User::STATUS_PENDING) {
            $user->update(['status' => User::STATUS_ACTIVE]);
            $msg = 'Reporter approved and activated.';
        } else {
            $user->update(['status' => User::STATUS_ACTIVE]);
            $msg = 'Reporter activated.';
        }

        return back()->with('success', $msg);
    }

}
