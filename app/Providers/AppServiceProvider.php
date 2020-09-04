<?php

namespace App\Providers;

use App\Models\Category;
use App\Models\Matter;
use App\Models\News;
use App\Models\User;
use App\Observers\CategoryObserver;
use App\Observers\MatterObserver;
use App\Observers\NewsObserver;
use App\Observers\UserObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);
//        Category::observe(CategoryObserver::class);
        //Matter::observe(MatterObserver::class);
        //News::observe(NewsObserver::class);
        //User::observe(UserObserver::class);
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
