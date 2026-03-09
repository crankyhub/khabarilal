<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NewsletterController extends Controller
{
    public function index()
    {
        $subscribers = DB::table('newsletter_subscribers')->latest()->paginate(20);
        return view('admin.newsletter.index', compact('subscribers'));
    }

    public function destroy($id)
    {
        DB::table('newsletter_subscribers')->where('id', $id)->delete();
        return back()->with('success', 'Subscriber removed.');
    }
}
