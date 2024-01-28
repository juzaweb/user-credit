<?php
namespace Juzaweb\UserCredit\Actions;

use Juzaweb\CMS\Abstracts\Action;
use Juzaweb\Subscription\Http\Resources\PaymentMethodResource;
use Juzaweb\Subscription\Models\PaymentMethod;

class FrontendAction extends Action
{
    public function handle(): void
    {
        $this->addAction(Action::FRONTEND_CALL_ACTION, [$this, 'enqueueStyles']);
        $this->addAction(Action::FRONTEND_INIT, [$this, 'registerProfilePages']);
    }

     public function enqueueStyles(): void
    {
        $this->hookAction->enqueueFrontendStyle(
            'subs-css',
            plugin_asset('css/frontend/user_credit.min.css', 'juzaweb/user-credit')
        );
    }

    public function registerProfilePages(): void
    {
        $this->hookAction->registerProfilePage(
            'user-credit',
            [
               'title' => __('User credit'),
               'contents' => 'user_credit::frontend.profile.user_credit',
            ]
        );
    }
}
