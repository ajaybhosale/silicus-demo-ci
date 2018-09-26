<?php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        \Schema::defaultStringLength(191); //Solved by increasing StringLength
        /* Get and make global shmart merchant site URL */
        $siteUrl = config('app.url');
        view()->share('url', $siteUrl);

        /* Get and make global CMS theme */
        $theme = config('app.theme');
        view()->share('theme', $theme);

        /* Get and make global CMS theme */
        $adminTheme = config('app.adminTheme');
        view()->share('adminTheme', $adminTheme);

        /* Get and make css cache enabled  */
        $cssCacheEnabled = config('app.css_cache_enabled');
        view()->share('cssCacheEnabled', $cssCacheEnabled);

        /* Get and make js cache enabled  */
        $jsCacheEnabled = config('app.js_cache_enabled');
        view()->share('jsCacheEnabled', $jsCacheEnabled);

        /* Set time stamp for JS  */
        view()->share('jsTimeStamp', $jsCacheEnabled ? '?t=' . time() : '');

        /* Set time stamp for CSS */
        view()->share('cssTimeStamp', $cssCacheEnabled ? '?t=' . time() : '');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
