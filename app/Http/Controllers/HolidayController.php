<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

class HolidayController extends Controller
{
    public function index(Request $request)
    {
        $country = $request->input('country', 'Ireland');
        $selectedYear = $request->input('year', Carbon::now()->year);
        $years = $this->getYears();

        // Determine financial year dates
        if ($country === 'UK') {
            $startDate = Carbon::create($selectedYear, 4, 6)->startOfDay();
            $endDate = Carbon::create($selectedYear + 1, 4, 5)->endOfDay();
            $yearDisplay = "$selectedYear-" . ($selectedYear + 1);
        } else {
            $startDate = Carbon::create($selectedYear, 1, 1)->startOfDay();
            $endDate = Carbon::create($selectedYear, 12, 31)->endOfDay();
            $yearDisplay = $selectedYear;
        }

        // Adjust start and end dates to exclude weekends
        while ($startDate->isWeekend()) {
            $startDate->addDay();
        }
        while ($endDate->isWeekend()) {
            $endDate->subDay();
        }

        // Fetch holidays from API
        $holidays = $this->fetchHolidays($country, $selectedYear);

        return view('holidays', compact('country', 'years', 'selectedYear', 'startDate', 'endDate', 'holidays', 'yearDisplay'));
    }

    private function getYears()
    {
        $currentYear = Carbon::now()->year;
        return range($currentYear - 10, $currentYear);
    }

    private function fetchHolidays($country, $year)
    {
        $apiKey = env('ABSTRACT_API_KEY');
        $countryCode = $country === 'UK' ? 'GB' : 'IE';

        $holiday_api = new \HolidayAPI\Client(['key' => $apiKey]);
        $holidays = $holiday_api->holidays([
                        'country' => $countryCode,
                        'year' => $year-1,
                        ]);

      
        $holidays = $holidays['holidays'];

        $formattedHolidays = collect($holidays)->map(function ($holiday) {
            return [
                'name' => $holiday['name'],
                'date' => Carbon::parse($holiday['date'])->format('jS F Y'),
            ];
        })->values()->all();

        return $formattedHolidays ;
    }
}