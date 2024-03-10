<?php

namespace Juzaweb\UserCredit\Actions;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Juzaweb\CMS\Abstracts\Action;
use Juzaweb\CMS\Models\User;
use Juzaweb\PaymentMethod\Http\Resources\PaymentMethodResource;
use Juzaweb\PaymentMethod\Models\PaymentMethod;
use Juzaweb\UserCredit\Models\UserCreditDailyGiveCreditHistory;

class FrontendAction extends Action
{
    public function handle(): void
    {
        $this->addAction(Action::FRONTEND_INIT, [$this, 'registerProfilePages']);
        $this->addAction(Action::FRONTEND_INIT, [$this, 'addCreditsGivenEachDay']);
        $this->addFilter('user.resouce_data', [$this, 'addCreditToTheme'], 20, 2);
    }

    public function registerProfilePages(): void
    {
        $user = request()->user();
        $this->hookAction->registerProfilePage(
            'buy-credit',
            [
                'title' => __('Buy Credit'),
                'icon' => 'fa fa-credit-card',
                'contents' => 'user_credit::frontend.profile.buy_credit',
                'data' => [
                    'paymentMethods' => fn () => PaymentMethodResource::collection(PaymentMethod::query()
                        ->active()
                        ->get())->toArray(request()),
                ]
            ]
        );

        if (get_config('receive_of_credits_each_day') == 1) {
            $this->hookAction->registerProfilePage(
                'attendance',
                [
                    'title' => __('Attendance'),
                    'icon' => 'fa fa-credit-card',
                    'contents' => 'user_credit::frontend.profile.attendance',
                    'data' => [
                        'showBtnAttendance' => function () use ($user) {
                            $checkReceiveCreditToday = UserCreditDailyGiveCreditHistory::where('user_id', $user->id)
                                ->whereDate('created_at', '=', now()->format('Y-m-d'))
                                ->exists();
                            $countReceiveCreditHistories = UserCreditDailyGiveCreditHistory::where('user_id', $user->id)
                                ->count();

                            if (!$checkReceiveCreditToday
                                && $countReceiveCreditHistories < (int) get_config('maximum_number_receive_of_credits')
                            ) {
                                return true;
                            }

                            return false;
                        },
                    ]
                ]
            );
        }
    }

    public function addCreditsGivenEachDay(): void
    {
        $user = request()?->user();

        if ($user && get_config('user_credit_give_credits_every_day_enable') && get_config('receive_of_credits_each_day') == 0) {
            $seconds = now()->diffInSeconds(now()->endOfDay());

            Cache::remember("user_credit_daily_give_credit_histories_{$user->id}", $seconds, function () use ($user) {
                $exsistHistoryLog = UserCreditDailyGiveCreditHistory::where('user_id', $user->id)
                    ->whereDate('created_at', '=', now()->format('Y-m-d'))
                    ->exists();

                if (!$exsistHistoryLog) {
                    DB::transaction(
                        function () use ($user) {
                            $newCreditHistory = new UserCreditDailyGiveCreditHistory();
                            $newCreditHistory->user_id = $user->id;
                            $newCreditHistory->credit = get_config('user_credit_number_of_credits_given_each_day', 0);
                            $newCreditHistory->save();

                            $user->increment('credit', $newCreditHistory->credit);
                        }
                    );
                }

                return true;
            });
        }
    }

    public function addCreditToTheme(array $data, User $user): array
    {
        $data['credit'] = $user->credit + $user->credit_holdback;

        return $data;
    }
}
