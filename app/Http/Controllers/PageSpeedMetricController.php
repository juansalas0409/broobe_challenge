<?php

namespace App\Http\Controllers;

use App\Services\Google\PageSpeed;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class PageSpeedMetricController extends Controller
{
    public function getMetrics(Request $request): JsonResponse
    {
        $url            = $request->get('url');
        $strategy_id    = $request->get('strategy_id');
        $categories_ids = $request->get('categories_ids');

        try {
            $metrics = (new PageSpeed())->getMetricsFromUrl(
                url           : $url,
                strategy_id   : $strategy_id,
                categories_ids: $categories_ids ?? []
            );
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], $e->getCode());
        }

        return response()->json($metrics);
    }
}
