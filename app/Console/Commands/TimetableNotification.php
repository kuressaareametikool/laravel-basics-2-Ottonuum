<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http; // Import the Http facade
use Illuminate\Support\Facades\Mail; // Import the Mail facade
use App\Mail\Timetable;
use Carbon\Carbon;

class TimetableNotification extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timetable:notification';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetches timetable data and processes it';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Command is running');

        $startDate = Carbon::parse('2023-10-30');
        $endDate = Carbon::parse('2023-11-05');

        $url = 'https://tahvel.edu.ee/hois_back/timetableevents/timetableByGroup/38?from=2023-10-30T00:00:00Z&studentGroups=5901&thru=2023-11-05T00:00:00Z';
        $params = [
            'from' => $startDate->toIsoString(),
            'studentGroups' => '5901',
            'thru' => $endDate->toIsoString(),
        ];

        $response = Http::get($url, $params);

        $this->info('API request made');
        $this->info('Response: ' . print_r($response->json(), true));

        if ($response->successful()) {
            $data = $response->json();

            if (!empty($data['timetableEvents'])) {
                $timetableEvents = collect($data['timetableEvents'])
                    ->sortBy(['date', 'timeStart'])
                    ->groupBy(function ($event) {
                        return Carbon::parse($event['date'])->locale('et_EE')->dayName;
                    });

                $this->info('Timetable events found');
                $this->info('Response: ' . print_r($timetableEvents->toArray(), true));

                Mail::to('Nuumotto1@gmail.com')->send(new Timetable($timetableEvents, $startDate, $endDate));
                $this->info('Email sent successfully.');
            } else {
                $this->info('No timetable events found.');
            }
        } else {
            $this->error('Failed to fetch data from API');
        }
    }
}
