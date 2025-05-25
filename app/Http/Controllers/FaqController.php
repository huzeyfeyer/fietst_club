<?php

namespace App\Http\Controllers;

use App\Models\FaqCategory;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FaqController extends Controller
{
    /**
     * Toon de publieke FAQ pagina.
     *
     * @return \Illuminate\View\View
     */
    public function index(): View
    {
        $faqCategories = FaqCategory::with([
            'faqItems' => function ($query) {
                $query->orderBy('question');
            }
        ])
        ->orderBy('name')
        ->get();

        return view('faq.index', compact('faqCategories'));
    }
}
