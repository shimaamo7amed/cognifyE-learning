<?php

// app/Http/Middleware/SetLocale.php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        $locale = Session::get('locale');
        if (!$locale) {
            $locale = $request->header('Accept-Language', 'en');
        }
        if (!in_array($locale, ['en', 'ar'])) {
            $locale = 'en';
        }
        Session::put('locale', $locale);
        App::setLocale($locale);

        return $next($request);
    }
}
