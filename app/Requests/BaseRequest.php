<?php

namespace App\Requests;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

abstract class BaseRequest
{
    protected Serializer $serializer;

    public function __construct()
    {
        $encoders         = [new JsonEncoder()];
        $normalizers      = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);
    }

    /**
     * @param string       $uri
     * @param string|array $query_params
     * @param string       $method
     * @return string
     * @throws Exception
     */
    protected function makeRequest(string $uri, string|array $query_params = [], string $method = 'GET'): string
    {
        $client = new Client(['base_uri' => $uri, 'query' => $query_params, 'verify' => !env(('NO_VERIFY'))]);

        try {
            $response = $client->request($method);
        } catch (GuzzleException $e) {
            throw new Exception($e->getMessage(), $e->getCode(), $e);
        }

        return $response->getBody()->getContents();
    }
}
