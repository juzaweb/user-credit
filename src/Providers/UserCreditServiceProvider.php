<?php

namespace Juzaweb\UserCredit\Providers;

use Juzaweb\CMS\Support\ServiceProvider;
use Juzaweb\UserCredit\Actions\ConfigAction;
use Juzaweb\UserCredit\Actions\FrontendAction;

class UserCreditServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $this->registerHookActions([ConfigAction::class, FrontendAction::class]);
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register(): void
    {
        //
    }
}
