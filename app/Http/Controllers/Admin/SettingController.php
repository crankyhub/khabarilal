<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    public function index()
    {
        $settings = [
            'site_logo' => Setting::get('site_logo'),
            'site_logo_height' => Setting::get('site_logo_height', '45'),
            'site_favicon' => Setting::get('site_favicon'),
            'site_name' => Setting::get('site_name', 'Khabar-i-Lal'),
            'header_bg_color' => Setting::get('header_bg_color', '#f9c80e'),
            'ticker_speed' => Setting::get('ticker_speed', '15'),
        ];
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'site_name' => 'required|string|max:255',
            'site_logo' => 'nullable|image|max:2048',
            'site_logo_height' => 'required|integer|min:20|max:200',
            'site_favicon' => 'nullable|image|mimes:ico,png,jpg,jpeg|max:512',
            'header_bg_color' => 'required|string|regex:/^#[a-fA-F0-9]{6}$/',
            'ticker_speed' => 'required|integer|min:5|max:100',
        ]);

        Setting::set('site_name', $request->site_name);
        Setting::set('site_logo_height', $request->site_logo_height);
        Setting::set('header_bg_color', $request->header_bg_color);
        Setting::set('ticker_speed', $request->ticker_speed);

        if ($request->hasFile('site_logo')) {
            // Delete old logo
            $oldLogo = Setting::get('site_logo');
            if ($oldLogo) {
                Storage::disk('public')->delete($oldLogo);
            }
            $path = $request->file('site_logo')->store('branding', 'public');
            Setting::set('site_logo', $path);
        }

        if ($request->hasFile('site_favicon')) {
            // Delete old favicon
            $oldFavicon = Setting::get('site_favicon');
            if ($oldFavicon) {
                Storage::disk('public')->delete($oldFavicon);
            }
            $path = $request->file('site_favicon')->store('branding', 'public');
            Setting::set('site_favicon', $path);
        }

        return back()->with('success', 'Settings updated successfully!');
    }
}
