<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class WakatimeService
{
    private const WAKATIME_API_ENDPOINT = 'https://wakatime.com/api/v1';

    public function allTimeStats(): array
    {
        return $this->stats('all_time');
    }

    public function yearlyStats(): array
    {
        return $this->stats('last_year');
    }

    public function weeklyStats(): array
    {
        return $this->stats();
    }

    public function stats(string $range = 'last_7_days'): array
    {
        $name = config('services.wakatime.username');

        $res = $this->request("/users/$name/stats/$range?is_including_today=true")['data'];

        return [
            'daily_average' => $res['daily_average_including_other_language'],
            'languages' => $res['languages'],
            'editors' => $res['editors'],
            'total_seconds' => $res['total_seconds_including_other_language'],
            'range' => [
                'range' => $range,
                'start' => $res['start'],
                'end' => $res['end'],
            ],
        ];
    }

    private function request(string $endpoint): array
    {
        return Http::withToken($this->prepareToken(), 'Basic')
            ->get(self::WAKATIME_API_ENDPOINT . $endpoint)
            ->json();
    }

    private function prepareToken(): string
    {
        return base64_encode(config('services.wakatime.token'));
    }
}
