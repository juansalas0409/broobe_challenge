<?php

namespace App\Requests\Google;

use App\Models\Category;
use App\Models\Strategy;
use App\Requests\BaseRequest;
use App\Responses\PageSpeed;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Throwable;

class PageSpeedRequest extends BaseRequest
{
    /**
     * @param string                $url
     * @param Strategy|null         $strategy
     * @param Collection|Category[] $categories
     * @return PageSpeed
     * @throws Exception
     */
    public function request(string $url, Strategy|null $strategy, array|Collection $categories): PageSpeed
    {
        $api_url           = 'https://pagespeedonline.googleapis.com/pagespeedonline/v5/runPagespeed';
        $base_query_params = [
            'url' => $url,
            'key' => 'AIzaSyDCrPAzhzWxZbJxPYIEURODTvBFVVRNHbY'
        ];

        $query_params = http_build_query($base_query_params);

        if (!is_null($strategy)) {
            $query_params .= "&strategy=$strategy->name";
        }

        if ($categories->isNotEmpty()) {
            foreach ($categories as $category) {
                $query_params .= "&category=$category->name";
            }
        }

        try {
            $response = $this->makeRequest($api_url, $query_params);
        } catch (Throwable $e) {
            throw new Exception("Error getting metrics", $e->getCode(), $e);
        }

        return $this->serializer->deserialize($response, PageSpeed::class, 'json');
    }
}
