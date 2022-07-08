<?php

use Livewire\Livewire;
use Remeritus\LivewireRruleGenerator\Http\Livewire\RruleGenerator;

it('has to have at least 1 day select to repeat weekly', function (){
    Livewire::test(RruleGenerator::class)
        ->set('rruleArray.FREQ', 'WEEKLY')
        ->set('rruleArray.INTERVAL', 1)
        ->set('rruleArray.BYDAY', ['MO'])
        ->call('processRrule')
        ->assertHasNoErrors();
});

it('will fail if no day is selected for Weekly Repetition', function (){
    Livewire::test(RruleGenerator::class)
        ->set('rruleArray.FREQ', 'WEEKLY')
        ->set('rruleArray.INTERVAL', 1)
        ->call('processRrule')
        ->assertHasErrors('rruleArray.BYDAY');
});

it('will emit rruleCreated event on rrule creation', function (){
   Livewire::test(RruleGenerator::class)
       ->set('rruleArray.FREQ', 'WEEKLY')
       ->set('rruleArray.INTERVAL', '1')
       ->set('rruleArray.BYDAY', ['MO'])
       ->call('processRrule')
       ->assertEmitted('rruleCreated');
});

it('can repeat every 2 months on the first Tuesday of the month', function (){
    Livewire::test(RruleGenerator::class)
        ->set('rruleArray.FREQ', 'MONTHLY')
        ->set('rruleArray.INTERVAL', '2')
        ->set('monthlyRepetition', 'onThe')
        ->set('monthlyRepetitionFrequency', '1')
        ->set('monthlyRepetitionDay', 'TU')
        ->call('processRrule')
        ->assertEmitted('rruleCreated')
        ->assertSee('Every 2 months on the first Tuesday of the month');
});

it('can repeat every 3 days', function (){
    Livewire::test(RruleGenerator::class)
        ->set('rruleArray.FREQ', 'DAILY')
        ->set('rruleArray.INTERVAL', '3')
        ->call('processRrule')
        ->assertEmitted('rruleCreated')
        ->assertSee('Every 3 days');
});

it('can mount itself from RruleString', function (){
   Livewire::test(RruleGenerator::class, ['rruleString' => 'FREQ=WEEKLY;COUNT=30;INTERVAL=1'])
       ->assertSee('Edit');
});
