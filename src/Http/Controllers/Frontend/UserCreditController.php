<?php
/**
 * JUZAWEB CMS - Laravel CMS for Your Project
 *
 * @package    juzaweb/juzacms
 * @author     The Anh Dangz
 * @link       https://juzaweb.com
 * @license    GNU V2
 */

namespace Juzaweb\UserCredit\Http\Controllers\Frontend;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Juzaweb\CMS\Http\Controllers\FrontendController;
use Juzaweb\UserCredit\Models\UserCreditDailyGiveCreditHistory;

class UserCreditController extends FrontendController
{
    public function attendance(Request $request): JsonResponse|RedirectResponse
    {
        $user = $request->user();
        $receiveCreditToday = UserCreditDailyGiveCreditHistory::where('user_id', $user->id)
            ->whereDate('created_at', '=', now()->format('Y-m-d'))
            ->exists();
        $countReceiveCreditHistories = UserCreditDailyGiveCreditHistory::where('user_id', $user->id)->count();

        if ($receiveCreditToday) {
            return $this->error(['message' => __('You attended today.')]);
        }

        if ($countReceiveCreditHistories >= (int) get_config('user_credit_maximum_number_receive_of_credits')) {
            return $this->error(['message' => __('The number of times attended exceeds the allowed number of times.')]);
        }

        DB::transaction(
            function () use ($user) {
                UserCreditDailyGiveCreditHistory::create([
                    'user_id' => $user->id,
                    'credit' => (int) get_config('user_credit_number_of_credits_given_each_day'),
                ]);

                $user->increment('credit', (int) get_config('user_credit_number_of_credits_given_each_day'));
            }
        );

        return $this->success(['message' => __('Attended successful.')]);
    }
}
