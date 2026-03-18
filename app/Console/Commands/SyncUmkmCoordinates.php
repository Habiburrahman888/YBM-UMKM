<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Umkm;

class SyncUmkmCoordinates extends Command
{
    protected $signature = 'umkm:sync-coords';
    protected $description = 'Sync UMKM coordinates from their address using Nominatim';

    public function handle()
    {
        $umkms = Umkm::whereNull('latitude')->orWhereNull('longitude')->get();
        $this->info("Found " . $umkms->count() . " UMKMs without coordinates.");

        foreach ($umkms as $umkm) {
            $this->info("Geocoding: {$umkm->nama_usaha}...");
            
            $districtName = $umkm->district ? $umkm->district->name : null;
            $cityName     = $umkm->city ? $umkm->city->name : null;
            $villageName  = $umkm->village ? $umkm->village->name : null;

            $coords = null;

            // Try Village + District + City
            if ($villageName && $districtName && $cityName) {
                $coords = $this->geocodeByNominatim($villageName . ', Kecamatan ' . $districtName, $cityName);
            }

            // Try District + City
            if (!$coords && $districtName && $cityName) {
                $coords = $this->geocodeByNominatim('Kecamatan ' . $districtName, $cityName);
            }

            // Try City only
            if (!$coords && $cityName) {
                $coords = $this->geocodeByNominatim(null, $cityName);
            }

            if ($coords) {
                $umkm->update([
                    'latitude' => $coords[0],
                    'longitude' => $coords[1]
                ]);
                $this->info("  Success: {$coords[0]}, {$coords[1]}");
            } else {
                $this->error("  Failed to geocode.");
            }

            // Sleep to respect Nominatim rate limits (1 request per second)
            sleep(1);
        }

        $this->info("Done.");
    }

    private function geocodeByNominatim(?string $queryPart, string $cityName): ?array
    {
        try {
            $query = $queryPart
                ? "{$queryPart}, {$cityName}, Indonesia"
                : "{$cityName}, Indonesia";

            $url = 'https://nominatim.openstreetmap.org/search?' . http_build_query([
                'q'              => $query,
                'format'         => 'json',
                'limit'          => 1,
                'countrycodes'   => 'id',
                'addressdetails' => 0,
            ]);

            $opts = [
                'http' => [
                    'method'  => 'GET',
                    'header'  => "User-Agent: YBM-UMKM-App/1.0\r\n",
                    'timeout' => 5,
                ],
            ];

            $context  = stream_context_create($opts);
            $response = @file_get_contents($url, false, $context);

            if ($response === false) {
                return null;
            }

            $data = json_decode($response, true);

            if (!empty($data[0]['lat']) && !empty($data[0]['lon'])) {
                return [(float) $data[0]['lat'], (float) $data[0]['lon']];
            }
        } catch (\Throwable $e) {
            // Fails silently
        }

        return null;
    }
}
