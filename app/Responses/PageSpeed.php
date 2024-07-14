<?php

namespace App\Responses;

use App\Responses\PageSpeed\LightHouseResult;

class PageSpeed
{
    public $id;
    public $kind;
    public $captchaResult;

    /** @var LightHouseResult */
    public $lighthouseResult;
}
