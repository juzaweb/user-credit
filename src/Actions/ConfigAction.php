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
            __('User Credit'),
            'user-credits',
            [
                'icon' => 'fa fa-plus',
                'position' => 99,
            ]
        );
        $this->hookAction->registerSettingPage(
            'user-credit-submission',
            [
                'label' => __('Settings'),
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
                'name' => __('User Credit'),
                'priority' => 1,
                'page' => 'user-credit-submission',
            ]
        );


        $this->hookAction->registerConfig(
            [
                'user_credit_min_prepaid_credit' => [
                    'label' => __('Min Prepaid Credit'),
                    'form' => 'user-credit-submission',
                ],
                'user_credit_convert' => [
                    'label' => __('Convert'),
                    'form' => 'user-credit-submission',
                ],
                'user_credit_give_credits_every_day_enable' => [
                    'type' => 'select',
                    'label' => __('Give Credits Every Day Enable'),
                    'form' => 'user-credit-submission',
                    'data' => [
                        'options' => [
                            0 => trans('cms::app.disabled'),
                            1 => trans('cms::app.enable')
                        ],
                        'validators' => [
                            'required',
                            'in:0,1'
                        ],
                    ]
                ],
                'user_credit_number_of_credits_given_each_day' => [
                    'label' => __('Number of credits given each day'),
                    'form' => 'user-credit-submission',
                ],
            ]
        );
    }
}
