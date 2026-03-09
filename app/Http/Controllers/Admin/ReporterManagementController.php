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
            ->withCount('user as articles_count')
            ->get();
            
        return view('admin.reporters.index', compact('reporters'));
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
        ]);

        $reporter->user->update([
            'role' => $validated['role'],
            'status' => $validated['status'],
        ]);

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
