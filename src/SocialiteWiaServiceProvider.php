<?php

namespace ShahzadaSaeed\SocializeWia;

use Illuminate\Support\ServiceProvider;
use Laravel\Socialite\Contracts\Factory;
use Illuminate\Contracts\Container\BindingResolutionException;

class SocialiteWiaServiceProvider extends ServiceProvider
{
    /**
     * @throws BindingResolutionException
     */
    public function boot()
    {
        $socialite = $this->app->make(Factory::class);

        $socialite->extend('cognito', function () use ($socialite) {
            $config = config('services.wia');

            return $socialite->buildProvider(SocialiteWiaProvider::class, $config);
        });
    }
}
