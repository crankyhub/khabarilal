<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    public function impersonate(User $user)
    {
        // Safety check: Only Super Admin can impersonate
        if (!auth()->user()->isSuperAdmin()) {
            abort(403, 'Only super admins can impersonate users.');
        }

        // Don't impersonate self
        if (auth()->id() === $user->id) {
            return back()->with('error', 'You are already logged in as this user.');
        }

        // Store original user ID in session
        session(['impersonator_id' => auth()->id()]);

        // Login as the target user
        Auth::login($user);

        return redirect()->route('admin.dashboard')->with('success', "Now impersonating {$user->name}");
    }

    public function stop()
    {
        $impersonatorId = session('impersonator_id');

        if (!$impersonatorId) {
            return redirect()->route('admin.dashboard');
        }

        $originalUser = User::find($impersonatorId);

        if ($originalUser) {
            Auth::login($originalUser);
        }

        session()->forget('impersonator_id');

        return redirect()->route('admin.dashboard')->with('success', 'Returned to your original account.');
    }
}
