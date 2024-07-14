<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMetricHistoryRun;
use App\Models\MetricHistoryRun;
use App\Models\Strategy;
use Throwable;

class MetricHistoryRunController extends Controller
{
    public function index()
    {
        $history_runs = MetricHistoryRun::query()->with('strategy')->get();

        return response()->json(['data' => $history_runs->toArray()]);
    }

    public function store(StoreMetricHistoryRun $request)
    {
        $attributes = $request->validated();

        if (!isset($attributes['strategy_id'])) {
            /** @var Strategy $strategy */
            $strategy = Strategy::query()->where('name', 'DESKTOP')->first();

            $attributes['strategy_id'] = $strategy->id;
        }

        try {
            MetricHistoryRun::create($attributes);
        } catch (Throwable) {
            $request->session()->flash('errors', [
                'message' => 'There was an error saving data in database, try again',
                'data'    => $attributes
            ]);
        }

        return redirect()->route('home');
    }
}
