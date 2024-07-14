<?php

namespace App\Services\Google;

use App\Models\Category;
use App\Models\Strategy;
use App\Requests\Google\PageSpeedRequest;
use App\Responses\PageSpeed as PageSpeedResponse;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PageSpeed
{
    /**
     * @param string|null $url
     * @param int|null    $strategy_id
     * @param array       $categories_ids
     * @return PageSpeedResponse
     * @throws Exception
     */
    public function getMetricsFromUrl(string|null $url, int|null $strategy_id = null, array $categories_ids = []): PageSpeedResponse
    {
        $validator = Validator::make([
            'url'            => $url,
            'strategy_id'    => $strategy_id,
            'categories_ids' => $categories_ids
        ], [
            'url'              => ['required', 'url'],
            'strategy_id'      => ['nullable', 'integer', 'exists:strategies,id'],
            'categories_ids'   => ['array'],
            'categories_ids.*' => ['integer', 'exists:categories,id']
        ], [
            'url.url' => 'The field must have the next structure: https://www.your-url.com'
        ]);

        if ($validator->fails()) {
            throw new Exception(json_encode($validator->errors()->getMessages()), 422);
        }

        /** @var Strategy|null $strategy */
        $strategy = null;
        /** @var Category[]|Collection $categories */
        $categories = Collection::empty();

        if (!Str::contains($url, ['http://', 'https://'])) {
            $url = ("https://$url");
        }

        if (!is_null($strategy_id)) {
            $strategy = Strategy::query()->find($strategy_id);
        }

        if (!empty($categories_ids)) {
            /** @var Category[]|Collection $categories */
            $categories = Category::query()->whereIn('id', $categories_ids)->get();
        }

        return (new PageSpeedRequest())->request($url, $strategy, $categories);
    }
}
