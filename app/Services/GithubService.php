<?php

namespace App\Services;

use Illuminate\Support\Carbon;

class GithubService
{
    const API_ENDPOINT = 'https://api.github.com/graphql';

    /**
     * Returns the number of issues I authored
     */
    public function issues(): int
    {
        $response = $this->request('issues { totalCount }');

        return $response['user']['issues']['totalCount'];
    }

    /**
     * Returns the number of pull requests I authored
     */
    public function pullRequests(): int
    {
        $response = $this->request('pullRequests(first: 1) { totalCount }');

        return $response['user']['pullRequests']['totalCount'];
    }

    /**
     * Returns the number of repos I starred, excluding my own
     */
    public function starred(): int
    {
        $response = $this->request('starredRepositories(ownedByViewer: false) { totalCount }');

        return $response['user']['starredRepositories']['totalCount'];
    }

    public function commits(): int
    {
        $count = 0;

        // Automatically fetch all commits starting from 2020, until now
        for ($i = 2020; $i <= Carbon::now()->year; $i++) {
            $response = $this->request('contributionsCollection(from: $from, to: $to) { totalCommitContributions }', [
                'from' => "$i-01-01T00:00:00", 'to' => "$i-12-31T00:00:00",
            ], ['$from: DateTime', '$to: DateTime']);
            $count += $response['user']['contributionsCollection']['totalCommitContributions'];
        }

        return $count;
    }

    /**
     * Make a GraphQL request to GitHub's GraphQL API
     */
    private function request(string $query, array $variables = [], array $parameters = []): array
    {
        $parameters = implode(', ', ['$login: String!', ...$parameters]);

        $query = 'query userInfo(' . $parameters . ') { user(login: $login) { ' . $query . '} }';

        return GraphQLService::make($query, array_merge($variables, ['login' => 'moon250']))
            ->request(self::API_ENDPOINT, [
                'Authorization' => 'Bearer ' . config('services.github.token'),
                'Content-Type' => 'application/json',
            ])
            ->parse();
    }
}
