<?php

namespace Juzaweb\UserCredit\Actions;

use Juzaweb\CMS\Abstracts\Action;
use Juzaweb\UserCredit\Http\Controllers\Frontend\UserCreditController;

class AjaxAction extends Action
{
    /**
     * Execute the actions.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->addAction(Action::FRONTEND_INIT, [$this, 'addFrontendAjax']);
    }

    /**
     * @throws \Exception
     */
    public function addFrontendAjax(): void
    {
        $this->hookAction->registerFrontendAjax(
            'user-credits.attendance',
            [
                'auth' => true,
                'method' => 'post',
                'callback' => [UserCreditController::class, 'attendance'],
            ]
        );
    }
}
