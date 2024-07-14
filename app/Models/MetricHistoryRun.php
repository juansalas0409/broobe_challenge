<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Database columns
 * @property int         id
 * @property string      url
 * @property float|null  accessibility_metric
 * @property float|null  pwa_metric
 * @property float|null  performance_metric
 * @property float|null  seo_metric
 * @property float|null  best_practices_metric
 * @property int         strategy_id
 * @property string|null created_at
 * @property string|null updated_at
 *
 * Relations
 * @property Strategy    strategy
 */
class MetricHistoryRun extends Model
{
    use HasFactory;

    protected $table = 'metric_history_runs';

    protected $fillable = [
        'url',
        'accessibility_metric',
        'pwa_metric',
        'performance_metric',
        'seo_metric',
        'best_practices_metric',
        'strategy_id',
    ];

    public function strategy(): BelongsTo
    {
        return $this->belongsTo(Strategy::class, 'strategy_id', 'id');
    }
}
