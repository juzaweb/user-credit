<?php

namespace Juzaweb\UserCredit\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Juzaweb\CMS\Models\Model;

/**
 * Juzaweb\UserCredit\Models\UserCreditDailyGiveCreditHistory
 *
 * @property int $id
 * @property int $user_id
 * @property int $credit
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|UserCreditDailyGiveCreditHistory newModelQuery()
 * @method static Builder|UserCreditDailyGiveCreditHistory newQuery()
 * @method static Builder|UserCreditDailyGiveCreditHistory query()
 */
class UserCreditDailyGiveCreditHistory extends Model
{

    protected $table = 'user_credit_daily_give_credit_histories';

    protected $fillable = [
        'user_id',
        'credit',
    ];
}
