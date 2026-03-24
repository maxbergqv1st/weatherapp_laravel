<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Schema;

class WeatherController extends Controller
{
    public function show(Request $request): JsonResponse
    {
        $city = trim((string) $request->string('city', 'Halmstad, Sweden'));

        if ($city === '') {
            $city = 'Halmstad, Sweden';
        }

        $apiKey = env('WEATHER_API_KEY');

        abort_unless($apiKey, 500, 'WEATHER_API_KEY is not configured.');

        $response = Http::timeout(10)
            ->get('https://api.weatherapi.com/v1/current.json', [
                'key' => $apiKey,
                'q' => $city,
                'aqi' => 'no',
            ]);

        if ($response->failed()) {
            return response()->json([
                'message' => 'Unable to fetch weather data.',
            ], $response->status());
        }

        $weather = $response->json();
        $weather['location_id'] = data_get($weather, 'location.name');

        return response()->json($weather);
    }

    public function toggleFavorite(Request $request): JsonResponse
    {
        $request->validate([
            'location_id' => ['required', 'string'],
        ]);

        if (! Schema::hasTable('favorite_locations')) {
            return response()->json([
                'starred' => false,
                'message' => 'Favorites table is not available yet.',
            ], 409);
        }

        $userId = $request->user()->id;
        $locationId = $request->string('location_id')->toString();

        $exists = DB::table('favorite_locations')
            ->where('user_id', $userId)
            ->where('location_id', $locationId)
            ->exists();

        if ($exists) {
            DB::table('favorite_locations')
                ->where('user_id', $userId)
                ->where('location_id', $locationId)
                ->delete();
        } else {
            DB::table('favorite_locations')->insert([
                'user_id' => $userId,
                'location_id' => $locationId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return response()->json([
            'starred' => ! $exists,
        ]);
    }

    public function favorites(Request $request): JsonResponse
    {
        if (! Schema::hasTable('favorite_locations')) {
            return response()->json([]);
        }

        $favorites = DB::table('favorite_locations')
            ->where('user_id', $request->user()->id)
            ->pluck('location_id');

        return response()->json($favorites);
    }
}
