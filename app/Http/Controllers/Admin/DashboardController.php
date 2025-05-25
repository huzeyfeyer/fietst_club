<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\User;
use App\Models\News;
use App\Models\ContactMessage;
use App\Models\FaqItem;

class DashboardController extends Controller
{
    /**
     * Toon het admin dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $totalUsers = User::count();
        $totalNewsItems = News::count();
        $unreadContactMessages = ContactMessage::where('is_read', false)->whereNull('archived_at')->count();
        $totalFaqItems = FaqItem::count();

        return view('admin.dashboard', compact(
            'totalUsers',
            'totalNewsItems',
            'unreadContactMessages',
            'totalFaqItems'
        ));
    }
}
