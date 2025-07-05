<?php

namespace App\Services;

use GuzzleHttp\Client;

class MapService
{
    public static function resolve(string $url): ?array
    {
        try {
            $client = new Client([
                'allow_redirects' => ['track_redirects' => true],
                'headers' => ['User-Agent' => 'Mozilla/5.0'],
            ]);

            $response = $client->get($url);

            // Try to extract from redirect history first
            $finalUrl = $response->getHeaderLine('X-Guzzle-Redirect-History');

            if (preg_match('/@(-?\d+\.\d+),(-?\d+\.\d+)/', $finalUrl, $matches)) {
                return [
                    'lat' => $matches[1],
                    'lng' => $matches[2],
                ];
            }

            // Fallback: Try to extract from body HTML
            $body = (string) $response->getBody();
            if (preg_match('/https:\/\/www\.google\.com\/maps\/place\/.*?@(-?\d+\.\d+),(-?\d+\.\d+)/', $body, $matches)) {
                return [
                    'lat' => $matches[1],
                    'lng' => $matches[2],
                ];
            }

            return null;
        } catch (\Throwable $e) {
            return null;
        }
    }
}
