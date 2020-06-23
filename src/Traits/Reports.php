<?php


namespace fredbradley\CranleighGoogleApi\Traits;


use fredbradley\CranleighGoogleApi\Maps\GoogleActivity;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

trait Reports
{
    public function getLatestActivity(string $email, string $application): Collection
    {
        try {
            $results = $this->reports->activities->listActivities($email, $application);
        } catch (\Google_Service_Exception $exception) {
            Log::error("Could not get Google Activity for user: " . $email);
            return collect([]);
        }
        $collection = collect($results);
        $return = $collection->mapInto(GoogleActivity::class);
        return $return;
    }
}
