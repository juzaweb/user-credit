<?php
namespace Juzaweb\UserCredit\Actions;

use Exception;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Juzaweb\CMS\Abstracts\Action;
use Juzaweb\UserCredit\Models\UserCreditDailyGiveCreditHistory;

class FrontendAction extends Action
{
    public function handle(): void
    {
        $this->addAction(Action::FRONTEND_INIT, [$this, 'registerProfilePages']);
        $this->addAction(Action::FRONTEND_INIT, [$this, 'addCreditsGivenEachDay']);
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

    public function addCreditsGivenEachDay(): void
    {
        $user = request()->user();
        if (get_config('user_credit_give_credits_every_day_enable')) {
            $seconds = now()->diffInSeconds(now()->endOfDay());

            Cache::remember('user_credit_daily_give_credit_histories', $seconds, function() use ($user) {
                $exsistHistoryLog = UserCreditDailyGiveCreditHistory::where('user_id', $user->id)
                    ->whereDate('created_at', '=', now()->format('Y-m-d'))
                    ->exists();
                if (!$exsistHistoryLog) {
                    DB::beginTransaction();
                    try {
                        $userCreditDailyGiveCreditHistory = new UserCreditDailyGiveCreditHistory();
                        $userCreditDailyGiveCreditHistory->user_id = $user->id;
                        $userCreditDailyGiveCreditHistory->credit = get_config('user_credit_number_of_credits_given_each_day');
                        $userCreditDailyGiveCreditHistory->save();

                        $user->increment('credit', $userCreditDailyGiveCreditHistory->credit);
                        DB::commit();
                    } catch (Exception $e) {
                        DB::rollBack();
                        throw $e;
                    }
                }
                return true;
            });
        }
    }
}
