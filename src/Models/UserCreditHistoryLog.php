<?php

namespace Juzaweb\UserCredit\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Juzaweb\CMS\Models\Model;

/**
 * Juzaweb\UserCredit\Models\UserCreditHistoryLog
 *
 * @property int $id
 * @property int $user_id
 * @property float $number
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @method static Builder|UserCreditHistoryLog newModelQuery()
 * @method static Builder|UserCreditHistoryLog newQuery()
 * @method static Builder|UserCreditHistoryLog query()
 */
class UserCreditHistoryLog extends Model
{

    protected $table = 'user_credit_history_logs';

    protected $fillable = [
        'user_id',
        'number',
    ];
}
