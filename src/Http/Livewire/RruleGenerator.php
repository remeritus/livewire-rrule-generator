<?php

namespace Remeritus\LivewireRruleGenerator\Http\Livewire;

use Illuminate\Support\Facades\Validator;
use RRule\RRule;
use Livewire\Component;
use Illuminate\View\View;
use Remeritus\LivewireRruleGenerator\Services\CalendarService;
use function Livewire\str;

class RruleGenerator extends Component
{
    // config
    public bool $editable = true;
    public bool $includeWeekend = true;
    public bool $includeStarts = true;
    public bool $includeEnds = true;
    public string $defaultView = 'WEEKLY';

    // defaults
    public array $frequencies = [];
    public array $daysOfWeek = [];
    public array $months = [];
    public int $daysInMonth = 31;

    public string $STARTS = 'NOT-SPECIFIED';
    public ?string $ENDS = 'NEVER';

    public $monthlyRepetition = 'BYMONTHDAY';
    public ?string $monthlyRepetitionFrequency = "";
    public ?string $monthlyRepetitionDay = "";

    public array $BYDAYLIST = [];

    public array $frequencyLookup = [
        'DAILY' => 'day',
        'WEEKLY' => 'week',
        'MONTHLY' => 'month',
        'YEARLY' => 'year',
    ];

    public array $rruleArray = [
        'DTSTART' => NULL,
        'FREQ' => 'WEEKLY',
        'UNTIL' => NULL,
        'COUNT' => 1,
        'INTERVAL' => 1,
        "BYSECOND" => NULL,
        "BYMINUTE" => NULL,
        "BYHOUR" => NULL,
        'BYDAY' => NULL,
        'BYMONTHDAY' => NULL,
        "BYYEARDAY" => NULL,
        "BYWEEKNO" => NULL,
        'BYMONTH' => NULL,
        'BYSETPOS' => NULL,
        "WKST" => "MO",
    ];

    public ?string $humanReadable = '';
    public ?string $rruleString = '';

    protected $listeners = [
        'showRruleGenerator'
    ];

    public function mount(string $rruleString = ''): void
    {
        if (!empty($rruleString)) {
            $rrule = new RRule($rruleString);
            $this->rruleArray = $rrule->getRule();
            $this->editable = false;
        }
        $this->getConfigDefaults();
        $this->getCalendarDefaults();
    }

    private function getConfigDefaults(): void
    {
        $this->setDaysOfWeek();
        $this->setWeekStarts();
        $this->setFrequencies();
        $this->setView();
    }

    private function getCalendarDefaults(): void
    {
        $this->setMonths();
    }

    private function setDaysOfWeek(): void
    {
        $this->includeWeekend = $this->includeWeekend ??
            config('livewire-rrule-generator.includeWeekend');
        $this->daysOfWeek = (new CalendarService())->getDaysOfTheWeek($this->includeWeekend);
    }

    private function setWeekStarts(): void
    {
        $this->rruleArray['WKST'] = config('livewire-rrule-generator.weekStarts') ?? 'MO';
    }

    private function setView(): void
    {
        $this->defaultView = $this->defaultView ?? config('livewire-rrule-generator.defaultView');
        $this->rruleArray['FREQ'] = $this->defaultView;
    }

    private function setMonths(): void
    {
        $this->months = (new CalendarService())->getListOfMonths();
    }

    private function getConfigFrequencies(): array
    {
        $frequencies = [];
        $configFrequencies = config('livewire-rrule-generator.frequencies');

        foreach ($configFrequencies as $frequency => $allowed) {
            if ($allowed) {
                $frequencies[] = $frequency;
            }
        }

        return $frequencies;
    }

    private function setFrequencies(): void
    {
        $this->frequencies = $this->getConfigFrequencies();
    }

    public function updatedBydaylist()
    {
        // to prevent strange bug where on selection
        // of first checkbox it produces [0 => '1']
        // instead of [0 => 'MO']
        if (isset($this->BYDAYLIST[0]) && $this->BYDAYLIST[0] == '1') {
            $this->BYDAYLIST[0] = 'MO';
        }

        $this->rruleArray['BYDAY'] = implode(',', $this->BYDAYLIST);
    }

    public function updatedRruleArray()
    {
        $FREQ = $this->rruleArray['FREQ'];
        $this->rruleArray['BYDAY'] = NULL;
        $this->BYDAYLIST = [];

        if ($FREQ === 'WEEKLY') {

            $this->rruleArray['BYMONTHDAY'] = NULL;
            $this->rruleArray['BYSETPOS'] = NULL;

        } elseif ($FREQ === 'DAILY') {

            $this->rruleArray['BYDAY'] = NULL;

        } elseif ($FREQ === 'MONTHLY') {

            if ($this->monthlyRepetition === 'BYMONTHDAY') {

                $this->rruleArray['BYSETPOS'] = NULL;
                $this->rruleArray['BYDAY'] = NULL;

            } else {
                // FREQ=MONTHLY eg on (first, second,..., last)
                $this->rruleArray['BYMONTHDAY'] = NULL;
            }
        }
    }

    public function updatedEnds()
    {
        if ($this->ENDS !== 'NEVER') {

            $this->rruleArray['COUNT'] = NULL;
            $this->rruleArray['UNTIL'] = NULL;

        }

        if ($this->ENDS === 'AFTER') {

            $this->rruleArray['UNTIL'] = NULL;

        }

        if ($this->ENDS === 'ON') {

            $this->rruleArray['COUNT'] = NULL;

        }
    }

    public function updatedStarts()
    {
        if ($this->STARTS === 'NOT-SPECIFIED') {
            $this->rruleArray['DTSTART'] = NULL;
        }
    }

    public function updatedMonthlyRepetition(): void
    {
        if ($this->monthlyRepetition == 'BYMONTHDAY') {
            $this->rruleArray['BYSETPOS'] = NULL;
            $this->rruleArray['BYDAY'] = NULL;
        } else {
            $this->rruleArray['BYMONTHDAY'] = NULL;
        }
    }

    public function render(): View
    {
        return view('livewire-rrule-generator::livewire.rrule-generator');
    }

    private function preprocess()
    {
        if ($this->ENDS == 'NEVER') {
            $this->rruleArray['UNTIL'] = NULL;
            $this->rruleArray['COUNT'] = NULL;
        }

        if ($this->rruleArray['UNTIL'] != NULL) {
            $this->rruleArray['COUNT'] = NULL;
        }

        if (isset($this->monthlyRepetitionFrequency) && isset($this->monthlyRepetitionDay)) {
            $this->rruleArray['BYDAY'] = $this->monthlyRepetitionFrequency . $this->monthlyRepetitionDay;
        }

        if ($this->rruleArray['FREQ'] === 'WEEKLY') {
            $this->updatedBydaylist();
        }

    }

    protected function validateRruleArray(): void
    {
        $messages = [
            'rruleArray.BYDAY.required_if' => 'Please select at least 1 day.',
        ];

        Validator::make(['rruleArray' => $this->rruleArray], [
            'rruleArray.FREQ' => 'required',
            'rruleArray.BYDAY' => 'required_if:rruleArray.FREQ,WEEKLY',
        ], $messages)->validate();
    }


    public function processRrule(): void
    {
        $this->validateRruleArray();
        $this->preprocess();

        $rrule = new RRule($this->rruleArray);

        $this->rruleString = $rrule->rfcString();
        $this->humanReadable = str($rrule->humanReadable())->ucfirst();
        
        $this->dispatch('rruleCreated', [
            'rruleString'    => (string) $this->rruleString,
            'humanReadable' => (string) $this->humanReadable
        ]);

        $this->editable = false;
    }

    public function updatedShowRruleGenerator()
    {
        if ($this->showRruleGenerator === false) {
            $this->reset();
        }
    }
}
