<?php

namespace App\Providers;

use App\Http\IServices\ICampaignService;
use App\Http\IServices\ICategoryService;
use App\Http\IServices\IProductService;
use App\Http\IServices\IUserService;
use App\Http\Services\CampaignService;
use App\Http\Services\CategoryService;
use App\Http\Services\ProductService;
use App\Http\Services\UserService;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(IUserService::class, UserService::class);
        $this->app->bind(ICategoryService::class, CategoryService::class);
        $this->app->bind(ICampaignService::class, CampaignService::class);
        $this->app->bind(IProductService::class, ProductService::class);


    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        ResetPassword::createUrlUsing(function (object $notifiable, string $token) {
            return config('app.frontend_url')."/password-reset/$token?email={$notifiable->getEmailForPasswordReset()}";
        });
    }
}
