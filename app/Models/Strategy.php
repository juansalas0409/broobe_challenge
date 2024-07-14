<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Database columns
 * @property int                           id
 * @property string                        name
 * @property string|null                   created_at
 * @property string|null                   updated_at
 *
 * Relations
 * @property MetricHistoryRun[]|Collection metricHistoryRuns
 */
class Strategy extends Model
{
    use HasFactory;

    protected $table = 'strategies';

    public function metricHistoryRuns(): HasMany
    {
        return $this->hasMany(MetricHistoryRun::class, 'strategy_id', 'id');
    }
}
