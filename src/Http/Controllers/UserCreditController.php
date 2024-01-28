<?php

namespace Juzaweb\UserCredit\Http\Controllers;

use Juzaweb\CMS\Http\Controllers\BackendController;

class UserCreditController extends BackendController
{
    public function index()
    {
        //

        return view(
            'user_credit::index',
            [
                'title' => 'Title Page',
            ]
        );
    }
}
