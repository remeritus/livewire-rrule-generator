<?php

namespace Remeritus\LivewireRruleGenerator\Services;

class CalendarService
{
    public array $months;
    public array $maximumNumberOfDaysInMonths = [];

    public function __contructor(){
        $this->months = $this->getListOfMonths();

        foreach ($this->months as $monthNumber => $monthName){
            $maximumNumberOfDaysInMonths[$monthNumber] = cal_days_in_month(CAL_GREGORIAN, $monthNumber, date('Y'));
        }
    }

    public function getDaysOfTheWeek(bool $includeWeekend = true): array
    {
        $daysOfTheWeek = [
            'MO' => 'Monday',
            'TU' => 'Tuesday',
            'WE' => 'Wednesday',
            'TH' => 'Thursday',
            'FR' => 'Friday'
        ];

        if ($includeWeekend){
            $daysOfTheWeek['SA'] = 'Saturday';
            $daysOfTheWeek['SU'] = 'Sunday';
        }

        return $daysOfTheWeek;
    }

    public function getListOfMonths(): array
    {
        $calendar = cal_info(0);
        return $calendar['months'];
    }

    public function getNumberOfDayInAMonth(int $monthNumber): int
    {
        return $this->maximumNumberOfDaysInMonths[$monthNumber];
    }
}
