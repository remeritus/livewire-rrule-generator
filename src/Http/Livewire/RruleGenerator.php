<?php

namespace Remeritus\LivewireRruleGenerator\Http\Livewire;

use RRule\RRule;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\View\View;
use Remeritus\LivewireRruleGenerator\Services\CalendarService;

class RruleGenerator extends Component
{
    public ?bool $includeWeekend;
    public ?string $defaultView;

    public array $frequencies;
    public array $daysOfWeek;

    public $model;

    public $BYDAYDAILY = NULL;

    public array $months = [];
    public int $daysInMonth = 31;

    public $monthlyRepetition = 'BYMONTHDAY';
    protected $schedule;
    public $nextDate = '';
    public ?string $humanReadable = '';
    public $isVisible = false;

    public array $bySetPositions = [
        '1' => 'First',
        '2' => 'Second',
        '3' => 'Third',
        '4' => 'Fourth',
        "last" => 'Last'];

    public ?string $rruleString = '';

    public ?string $FREQ;
    public $INTERVAL = 1; // used for FREQ=WEEKLY and for FREQ=MONHTLY

    public ?string $DTSTART = NULL;
    public string $STARTS = 'NOT-SPECIFIED';
    public ?string $ENDS = 'NEVER';
    public ?int $COUNT = 1;
    public ?string $UNTIL = NULL;

    public $BYSETPOS = NULL;
    public $BYDAY = NULL;
    public $BYMONTH = NULL;
    public $BYMONTHDAY = NULL;


    protected $rules = [
        'FREQ'      => 'required',
        'BYDAY'     => 'sometimes|required',
        'DTSTART'   => 'sometimes|required'
    ];

    protected $messages = [
        'BYDAY.required' => 'Please select the day.',
    ];

    public function mount(string $modelName = '', ?int $modelId = NULL): void
    {
        $this->setModel($modelName, $modelId);
        $this->getConfigDefaults();
        $this->getCalendarDefaults();
    }

    private function getConfigDefaults(): void
    {
        $this->setDaysOfWeek();
        $this->setFrequencies();
        $this->setView();
    }

    private function setModel(string $modelName, ?int $modelId): void
    {
        if(!empty($modelName) && !empty($modelId)) {
            $modelPath = config('livewire-rrule-generator.modelsLocation') . $modelName;
            $this->model = ($modelPath::where('id', $modelId)->first());
        }
    }

    private function getCalendarDefaults(): void
    {
        $this->setMonths();
    }

    private function setDaysOfWeek(): void
    {
        $this->includeWeekend = $this->includeWeekend ?? config('livewire-rrule-generator.includeWeekend');
        $this->daysOfWeek = (new CalendarService())->getDaysOfTheWeek($this->includeWeekend);
    }

    private function setView(): void
    {
        $this->defaultView = $this->defaultView ?? config('livewire-rrule-generator.defaultView');
        $this->FREQ = $this->defaultView;
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


    public function updated(): void
    {
        $rruleArray = [];

        if (isset($this->FREQ)) {

            $rruleArray['FREQ'] = $this->FREQ;

            if ($this->FREQ == 'WEEKLY') {

                $rruleArray['INTERVAL'] = $this->INTERVAL;

                if (isset($this->BYDAY)) {
                    $rruleArray['BYDAY'] = $this->BYDAY;
                }

                $rruleArray['BYMONTHDAY'] = NULL;
                $rruleArray['BYSETPOS'] = NULL;


            } elseif ($this->FREQ === 'DAILY') {
                $this->INTERVAL = 1;

                if ($this->BYDAY !== NULL) {
                    $this->BYDAYDAILY = implode(', ', $this->BYDAY);

                    $rruleArray['BYDAY'] = $this->BYDAYDAILY;
                }

            } elseif ($this->FREQ === 'MONTHLY') {

                if (isset($this->INTERVAL)) {
                    $rruleArray['INTERVAL'] = $this->INTERVAL;
                }

                if ($this->monthlyRepetition === 'BYMONTHDAY') {

                    // FREQ=MONTHLY eg. on day (1-31)
                    if (isset($this->BYMONTHDAY)) {
                        $rruleArray['BYMONTHDAY'] = $this->BYMONTHDAY;
                    }

                    $rruleArray['BYSETPOS'] = NULL;
                    $rruleArray['BYDAY'] = NULL;

                }  else {
                    // FREQ=MONTHLY eg on (first, second,..., last)
                    if (isset($this->BYSETPOS)) {
                        if ($this->BYSETPOS === 'last') {
                            $rruleArray['BYSETPOS'] = -1;
                        } else {
                            $rruleArray['BYSETPOS'] = $this->BYSETPOS;
                        }
                    }

                    if (isset($this->BYDAY)) {
                        $rruleArray['BYDAY'] = $this->BYDAY;
                    }
                }
            }
        }

        if ($this->ENDS !== 'NEVER') {

            if ($this->ENDS === 'AFTER') {
                $rruleArray['COUNT'] = $this->COUNT;
            }

            if ($this->ENDS === 'ON') {
                $rruleArray['UNTIL'] = $this->UNTIL;
            }
        }

        if ($this->STARTS !== 'NOT SPECIFIED') {
            $rruleArray['DTSTART'] = $this->DTSTART;
        }

        $rrule = new RRule($rruleArray);
        $this->rruleString = $rrule->rfcString();
        $this->humanReadable = $rrule->humanReadable([
            'include_start' => true,
        ]);
    }

    public function updatedMonthlyRepetition(): void
    {
        if ($this->monthlyRepetition == 'BYMONTHDAY') {
            $this->BYSETPOS = NULL;
            $this->BYDAY = NULL;
        } else {
            $this->BYMONTHDAY = NULL;
        }
    }

    public function render(): View
    {
        return view('livewire-rrule-generator::livewire.rrule-generator');
    }

    public function processRrule(): void
    {
        $this->validate();

        if (!empty($this->model)){
            // update existing model
        } else {
            // emit event so child other components can catch it
            $this->emit('rruleCreated', [$this->rruleString, $key]);
        }
    }
}
