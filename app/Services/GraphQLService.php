<?php

namespace App\Services;

use App\Exceptions\GraphQLRequestException;
use GuzzleHttp\Client;

final class GraphQLService
{
    private string $response;

    public function __construct(
        private readonly string $query,
        private readonly array $variables,
    ) {
    }

    public static function make(string $query, array $variables): self
    {
        return new GraphQLService($query, $variables);
    }

    public function request(string $endpoint, array $headers): self
    {
        $client = new Client();

        $this->response = $client->request('post', $endpoint, [
            'headers' => $headers,
            'body' => json_encode([
                'query' => $this->query,
                'variables' => $this->variables,
            ]),
        ])->getBody()->getContents();

        return $this;
    }

    public function parse(): array
    {
        $parsed = json_decode($this->response, true);

        if (array_key_exists('errors', $parsed)) {
            throw new GraphQLRequestException($parsed['errors'][0]['message']);
        }

        return $parsed['data'];
    }
}
