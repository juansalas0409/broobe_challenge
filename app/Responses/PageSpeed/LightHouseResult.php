<?php

namespace App\Responses\PageSpeed;

use App\Responses\PageSpeed\LightHouseResult\Category;

class LightHouseResult
{
    public $requestedUrl;
    public $finalUrl;
    public $mainDocumentUrl;
    public $finalDisplayedUrl;
    public $lighthouseVersion;
    public $userAgent;
    public $fetchTime;

    /** @var Category[] */
    public $categories;
}
