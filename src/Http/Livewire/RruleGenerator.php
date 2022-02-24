<?php

namespace Remeritus\LivewireRruleGenerator\Http\Livewire;

use RRule\RRule;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\View\View;
use Remeritus\LivewireRruleGenerator\Services\CalendarService;

class RruleGenerator extends Component
{
    // config
    public bool $editable = true;
    public bool $includeWeekend = true;
    public bool $includeStarts = true;
    public bool $includeEnds = true;
    public string $defaultView = 'WEEKLY';
    public string $hellostring = 'Hello';

    // defaults
    public array $frequencies = [];
    public array $daysOfWeek = [];
    public array $months = [];
    public int $daysInMonth = 31;
    public array $bySetPositions = [
        '1' => 'First',
        '2' => 'Second',
        '3' => 'Third',
        '4' => 'Fourth',
        "last" => 'Last'
    ];
    public string $STARTS = 'NOT-SPECIFIED';
    public ?string $ENDS = 'NEVER';

    public $monthlyRepetition = 'BYMONTHDAY';
    public array $BYDAYLIST = [];

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

    protected $rules = [
        'rruleArray.FREQ' => 'required',
        'rruleArray.BYDAY' => 'sometimes|required'
    ];

    protected $messages = [
        'rruleArray.BYDAY.required' => 'Please select the day.',
    ];

    protected $listeners = [
        'showRruleGenerator'
    ];

    public function mount(string $rruleString = ''): void
    {
        if (!empty($rruleString)) {
            $rrule = new RRule($rruleString);
            $this->rruleArray = $rrule->getRule();
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

    public function updatedFreq()
    {
        $FREQ = $this->rruleArray['FREQ'];
        $this->rruleArray['BYDAY'] = NULL;
        $this->BYDAYLIST = [];

        if ($FREQ === 'WEEKLY') {

            $this->rruleArray['BYMONTHDAY'] = NULL;
            $this->rruleArray['BYSETPOS'] = NULL;

        } elseif ($FREQ === 'DAILY') {

            $this->rruleArray['INTERVAL'] = 1;

        } elseif ($FREQ === 'MONTHLY') {

            if ($this->monthlyRepetition === 'BYMONTHDAY') {

                $this->rruleArray['BYSETPOS'] = NULL;
                $this->rruleArray['BYDAY'] = NULL;

            } else {
                // FREQ=MONTHLY eg on (first, second,..., last)
                $this->rruleArray['BYMONTHDAY'] = NULL;
                if ($this->rruleArray['BYSETPOS'] === 'last') {
                    $this->rruleArray['BYSETPOS'] = -1;
                }
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

            $this->rruleArray['UNTIL'] = null;

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

    public function preprocess()
    {
        if ($this->ENDS == 'NEVER') {
            $this->rruleArray['UNTIL'] = null;
            $this->rruleArray['COUNT'] = null;
        } elseif ($this->rruleArray['UNTIL'] != null) {
            $this->rruleArray['COUNT'] = null;
        }
    }

    public function processRrule(): void
    {
        $this->validate();
        $this->preprocess();

        $rrule = new RRule($this->rruleArray);

        $this->rruleString = $rrule->rfcString();
        $this->humanReadable = $rrule->humanReadable();

        $this->emit('rruleCreated', (string)$this->rruleString);
        $this->editable = false;
    }

    public function updatedShowRruleGenerator()
    {
        if ($this->showRruleGenerator === false) {
            $this->reset();
        }
    }
}
