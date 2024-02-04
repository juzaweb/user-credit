<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/cms
 * @author     The Anh Dang
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\UserCredit\Support;

use Juzaweb\CMS\Models\User;
use Juzaweb\PaymentMethod\Support\Entities\PaymentHandlerCompleted;

class PaymentHandler
{
    public function completed(string $paymentId, float $amount): PaymentHandlerCompleted
    {
        /** @var User $user */
        $user = auth()->user();

        $user->increment('credit', $amount * get_config('user_credit_convert', 1));

        return PaymentHandlerCompleted::make($user->id, $paymentId, $amount);
    }
}
