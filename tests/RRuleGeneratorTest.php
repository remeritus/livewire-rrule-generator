<?php

use Livewire\Livewire;
use Remeritus\LivewireRruleGenerator\Http\Livewire\RruleGenerator;

it('has to have at least 1 day select to repeat weekly', function (){
    Livewire::test(RruleGenerator::class)
        ->set('rruleArray.FREQ', 'WEEKLY')
        ->set('rruleArray.INTERVAL', 1)
        ->set('BYDAYLIST', ['MO'])
        ->call('processRrule')
        ->assertHasNoErrors();
});

it('will fail if no day is selected for Weekly Repetition', function (){
    Livewire::test(RruleGenerator::class)
        ->set('rruleArray.FREQ', 'WEEKLY')
        ->set('rruleArray.INTERVAL', '1')
        ->call('processRrule')
        ->assertHasErrors(['rruleArray.BYDAY' => 'required_if']);
});

it('will emit rruleCreated event on rrule creation', function (){
   Livewire::test(RruleGenerator::class)
       ->set('rruleArray.FREQ', 'WEEKLY')
       ->set('rruleArray.INTERVAL', '1')
       ->set('BYDAYLIST', ['MO', 'TU'])
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
        ->assertSee('Every 3 days');
});

it('can mount itself from RruleString', function (){
   Livewire::test(RruleGenerator::class, ['rruleString' => 'FREQ=WEEKLY;COUNT=30;INTERVAL=1'])
       ->assertSee('Edit');
});

it('will pass key together with rruleCreated event if it is set', function () {
    Livewire::test(RruleGenerator::class, ['key' => 5])
        ->set('rruleArray.FREQ', 'MONTHLY')
        ->set('rruleArray.INTERVAL', '2')
        ->set('monthlyRepetition', 'onThe')
        ->set('monthlyRepetitionFrequency', '1')
        ->set('monthlyRepetitionDay', 'TU')
        ->call('processRrule')
        ->assertEmitted('rruleCreated', 'FREQ=MONTHLY;INTERVAL=2;BYDAY=1TU', 5);
});

it('will not pass key together with rruleCreated event if it is not set', function () {
    Livewire::test(RruleGenerator::class)
        ->set('rruleArray.FREQ', 'MONTHLY')
        ->set('rruleArray.INTERVAL', '2')
        ->set('monthlyRepetition', 'onThe')
        ->set('monthlyRepetitionFrequency', '1')
        ->set('monthlyRepetitionDay', 'TU')
        ->call('processRrule')
        ->assertEmitted('rruleCreated', 'FREQ=MONTHLY;INTERVAL=2;BYDAY=1TU', NULL);
});

