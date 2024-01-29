<?php
namespace Juzaweb\UserCredit\Actions;

use Cache;
use Carbon\Carbon;
use DB;
use Exception;
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
            $now = Carbon::now();
            $endOfDay = Carbon::now()->endOfDay();
            $seconds = $now->diffInSeconds($endOfDay);
            Cache::remember('users', $seconds, function() use ($user) {
                $exsistHistoryLog = UserCreditDailyGiveCreditHistory::where('user_id', $user->id)
                    ->whereDate('created_at', '=', now()->format('Y-m-d'))
                    ->exists();
                if (!$exsistHistoryLog) {
                    DB::beginTransaction();
                    try {
                        $UserCreditDailyGiveCreditHistory = new UserCreditDailyGiveCreditHistory();
                        $UserCreditDailyGiveCreditHistory->user_id = $user->id;
                        $UserCreditDailyGiveCreditHistory->credit = get_config('user_credit_number_of_credits_given_each_day');
                        $UserCreditDailyGiveCreditHistory->save();

                        $user->increment('credit', $UserCreditDailyGiveCreditHistory->credit);
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
