<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stichoza\GoogleTranslate\GoogleTranslate;
use Illuminate\Support\Facades\Cache;


class TranslationController extends Controller
{
    private $translator;

    public function __construct()
    {
        // Initialize GoogleTranslate once
        $this->translator = new GoogleTranslate();
    }

    public function index()
    {
        return view('translate');
    }

    public function translate(Request $request)
    {
        try {
            $languages = ['en', 'hi', 'fr', 'gu', 'es', 'de', 'it', 'ja', 'pt', 'ru', 'zh', 'ar', 'ko', 'pl', 'nl', 'bn', 'mr', 'ta', 'te', 'ml', 'kn', 'pa', 'sw', 'tr', 'cs', 'da', 'el', 'th', 'vi', 'he', 'id', 'ms', 'ro', 'sr', 'sk', 'uk', 'bn', 'hu', 'ca', 'hr', 'lt', 'lv', 'et', 'sq', 'sl', 'no', 'bg', 'mt', 'fa', 'ne', 'sw', 'hi'];
    
            $request->validate([
                'text' => 'required|string',
                'source_lang' => 'required|string|in:' . implode(',', $languages),
                'target_lang' => 'required|string|in:' . implode(',', $languages),
            ]);
    
            $text = $request->input('text');
            $sourceLang = $request->input('source_lang');
            $targetLang = $request->input('target_lang');
    
            $cacheKey = "{$sourceLang}_{$targetLang}_" . md5($text);
    
            $translatedText = Cache::remember($cacheKey, 60 * 60, function () use ($text, $sourceLang, $targetLang) {
                $this->translator->setSource($sourceLang);
                $this->translator->setTarget($targetLang);
                return $this->translator->translate($text);
            });
    
            return response()->json([
                'success' => true,
                'translated' => $translatedText,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Translation failed. ' . $e->getMessage(),
            ], 500);
        }
    }
    
    
}
