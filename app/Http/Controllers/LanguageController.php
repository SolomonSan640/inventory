<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LanguageController extends Controller
{
    public function changeLanguage(Request $request)
    {
        $language = $request->input('code');

        if (in_array($language, config('app.available_locales'))) {
            app()->setLocale($language);
            session(['locale' => $language]);
            return response()->json(['success' => true]);
        }
        return response()->json(['success' => false]);
    }
}
