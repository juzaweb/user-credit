<?php
namespace Juzaweb\UserCredit\Actions;

use DB;
use Exception;
use Juzaweb\CMS\Abstracts\Action;
use Juzaweb\UserCredit\Models\UserCreditHistoryLog;

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
            $exsistHistoryLog = UserCreditHistoryLog::where('user_id', $user->id)
            ->whereDate('created_at', '=', now()->format('Y-m-d'))
            ->exists();
            if (!$exsistHistoryLog) {
                DB::beginTransaction();
                try {
                    $userCreditHistoryLog = new UserCreditHistoryLog();
                    $userCreditHistoryLog->user_id = $user->id;
                    $userCreditHistoryLog->number = get_config('user_credit_number_of_credits_given_each_day');
                    $userCreditHistoryLog->save();

                    $user->increment('credit', $userCreditHistoryLog->number);
                    DB::commit();
                } catch (Exception $e) {
                    DB::rollBack();
                    throw $e;
                }
            }
        }
    }
}
