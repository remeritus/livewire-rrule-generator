<?php

namespace Remeritus\LivewireRruleGenerator\Http\Livewire;

use Livewire\Component;
use RRule\RRule;

class RruleGenerator extends Component
{
    public $task;
    public $tasksSchedules = [];

    public $frequencies = ['DAILY', 'WEEKLY', 'MONTHLY'];

    public array $months = [
        1 => 'January',
        2 => 'February',
        3 => 'March',
        4 => 'April',
        5 => 'May',
        6 => 'June',
        7 => 'July',
        8 => 'August',
        9 => 'September',
        10=> 'October',
        11 => 'November',
        12 => 'December',];

    public $daysInMonth = [ 1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31 ];
    public $monthlyRepetition = 'BYMONTHDAY';
    protected $schedule;
    public $nextDate = '';
    public $humanReadable = '';
    public $isVisible = false;

    public $daysOfWeek = [
        'MO' => 'Monday',
        'TU' => 'Tuesday',
        'WE' => 'Wednesday',
        'TH' => 'Thursday',
        'FR' => 'Friday',
        'SA' => 'Saturday',
        'SU' => 'Sunday',];

    public $bySetPositions = [
        '1' => 'First',
        '2' => 'Second',
        '3' => 'Third',
        '4' => 'Fourth',
        '5' => 'Last'];

    public $ending = NULL;

    protected $rrule;
    public $rruleString;

    public $FREQ  = 'WEEKLY';

    // used for FREQ=WEEKLY and for FREQ=MONHTLY
    public $INTERVAL = 1;

    private $DTSTART = NULL;
    private $COUNT = NULL;
    private $UNTIL = NULL;

    public $BYSETPOS = NULL;
    public $BYDAY = NULL;
    public $BYMONTH = NULL;
    public $BYMONTHDAY = NULL;

    public $BYDAYDAILY = NULL;

    protected $rules = [
        'BYDAY'     => 'sometimes|required',
    ];

    protected $messages = [
        'BYDAY.required' => 'Please select the day.',
    ];

    public function mount(){

        $this->FREQ = 'WEEKLY';
        $this->BYDAY = NULL;
        $this->INTERVAL = 1;

    }

    // [{   "task_id":1,
    //      "amount":1,
    //      "dtstart":"2021-02-07",
    //      "rrule":"FREQ=MONTHLY;INTERVAL=2;BYDAY=TU;BYSETPOS=1",
    //\     "humanReadable":"every 2 months on Tuesday, but only the first instance of this set"}]


    public function updated()
    {
        $rruleObject = new RRule([
            'FREQ' => 'WEEKLY',
            'BYDAY' => 'MO',
            'INTERVAL' => 1,
        ]);

        $rruleArray = $rruleObject->getRule();

        if(isset($this->FREQ)) {

            $rruleArray['FREQ'] = $this->FREQ;

            if($this->FREQ == 'WEEKLY') {

                $rruleArray['INTERVAL'] = $this->INTERVAL;

                if(isset($this->BYDAY)) {
                    $rruleArray['BYDAY'] = $this->BYDAY;
                }

                $rruleArray['BYMONTHDAY'] = NULL;
                $rruleArray['BYSETPOS'] = NULL;


            } elseif($this->FREQ === 'DAILY') {
                $this->INTERVAL = 1;

                if($this->BYDAY !== NULL) {
                    $this->BYDAYDAILY = implode(', ', $this->BYDAY);

                    $rruleArray['BYDAY'] = $this->BYDAYDAILY;
                }

            } elseif($this->FREQ === 'MONTHLY') {

                if(isset($this->INTERVAL)) {
                    $rruleArray['INTERVAL'] = $this->INTERVAL;
                }

                if($this->monthlyRepetition === 'BYMONTHDAY'){

                    // FREQ=MONTHLY eg. on day (1-31)
                    if(isset($this->BYMONTHDAY)) {
                        $rruleArray['BYMONTHDAY'] = $this->BYMONTHDAY;
                    }

                    $rruleArray['BYSETPOS'] = NULL;
                    $rruleArray['BYDAY'] = NULL;

                } else {
                    // FREQ=MONTHLY eg on (first, second,..., last)
                    if(isset($this->BYSETPOS)) {
                        $rruleArray['BYSETPOS'] = $this->BYSETPOS;
                    }

                    if(isset($this->BYDAY)) {
                        $rruleArray['BYDAY'] = $this->BYDAY;
                    }
                }
            }
        }

        $rrule = new RRule($rruleArray);
        $this->rruleString = $rrule->rfcString();
    }

    public function updatedMonthlyRepetition()
    {
        if($this->monthlyRepetition == 'BYMONTHDAY') {
            $this->BYSETPOS = NULL;
            $this->BYDAY = NULL;
        } else {
            $this->BYMONTHDAY = NULL;
        }
    }

    public function render()
    {
         return view('livewire-rrule-generator::livewire.rrule-generator');
    }

    public function resetForm(){
        $this->reset();
    }

    public function closeModal() {
        $this->emitUp('closeRrule');
    }

    public function submitForm(){
        $this->updated();
        $this->validate();

        $this->emitUp('rruleCreated', $this->rruleString);
    }
}
