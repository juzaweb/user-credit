<?php

namespace Juzaweb\UserCredit\Actions;

use Juzaweb\CMS\Abstracts\Action;

class ConfigAction extends Action
{
    /**
     * Execute the actions.
     *
     * @return void
     */
    public function handle(): void
    {
        $this->addAction(Action::INIT_ACTION, [$this, 'addAdminConfigs']);
    }

    public function addAdminConfigs(): void
    {
        $this->addAdminMenu(
            trans('User Credit'),
            'user-credits',
            [
                'icon' => 'fa fa-plus',
                'position' => 99,
            ]
        );
        $this->hookAction->registerSettingPage(
            'user-credit-submission',
            [
                'label' => trans('Settings'),
                'view' => 'cms::user_credit.index',
                'menu' => [
                    'parent' => 'user-credits',
                    'position' => 99,
                    'icon' => 'fa fa-cog'
                ]
            ]
        );

        $this->hookAction->addSettingForm(
            'user-credit-submission',
            [
                'name' => trans('User Credit'),
                'priority' => 1,
                'page' => 'user-credit-submission',
            ]
        );


        $this->hookAction->registerConfig(
            [
                'min_credit' => [
                    'label' => __('min credit'),
                    'form' => 'user-credit-submission',
                ],
                'convert' => [
                    'label' => __('convert'),
                    'form' => 'user-credit-submission',
                ],
            ]
        );
    }
}
