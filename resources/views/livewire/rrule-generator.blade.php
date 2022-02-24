<div class="p-4">
    @if($editable)
        <section>
            <h4 class="font-bold text-2xl mb-2">
                {{ config('livewire-rrule-generator.title') ?? __('Define Schedule') }}
            </h4>
            <select wire:model="rruleArray.FREQ" class="p-2 rounded border">
                @foreach($frequencies as $frequency)
                    <option value="{{$frequency}}">{{$frequency}}</option>
                @endforeach
            </select>

            @if($rruleArray['FREQ'] === 'DAILY')
                @error('rruleArray.BYDAY')<p class="text-red-900 text-sm font-bold">{{$message}}</p>@enderror
                <div class="space-x-2">
                    @foreach($daysOfWeek as $abbrevation => $label)
                        <label>
                            <input type="checkbox"
                                   wire:model="BYDAYLIST"
                                   value="{{ $abbrevation }}">
                            {{ $abbrevation }}</label>
                    @endforeach
                </div>
            @endif

            @if($rruleArray['FREQ'] === 'WEEKLY')

                @error('rruleArray.BYDAY')<p class="text-red-900 text-sm font-bold">{{$message}}</p>@enderror
                <label> on </label>
                <select wire:model="rruleArray.BYDAY" class="p-2 rounded border">
                    <option value="NULL" class="p-2 border rounded">-- Select day of the week --</option>
                    @foreach($daysOfWeek as $value => $label)
                        <option value="{{$value}}">{{$label}}</option>
                    @endforeach
                </select>

                <div class="mt-2">
                    <label>
                        Every
                        <input type="number"
                               wire:model="rruleArray.INTERVAL"
                               min="1"
                               class="p-2 w-20 rounded border">
                        week(s)
                    </label>
                </div>

            @endif

            @if($rruleArray['FREQ'] == 'MONTHLY')
                <label>
                    Every <input type="number"
                                 step="1"
                                 min="1"
                                 wire:model="rruleArray.INTERVAL"
                                 class="p-2 rounded border"> month(s)
                </label>
                <label class="block mb-4
                              {{ $monthlyRepetition == 'BYSET' ? 'opacity-30' : '' }}
                              hover:opacity-100">
                    <input type="radio"
                           wire:model="monthlyRepetition"
                           value="BYMONTHDAY"> on day
                    <select wire:model="rruleArray.BYMONTHDAY"
                            {{ $monthlyRepetition == 'BYSET' ? 'disabled' : '' }}
                            class="p-2 rounded border">
                        <option value="NULL">Select</option>
                        @for($day = 1; $day <= $daysInMonth; $day++)
                            <option value="{{$day}}">{{$day}}</option>
                        @endfor
                    </select>
                </label>
                <label class="{{ $monthlyRepetition == 'BYMONTHDAY' ? 'opacity-30' : '' }}
                              hover:opacity-100">
                    <input type="radio"
                           wire:model="monthlyRepetition"
                           value="BYSET"> on the
                    <select wire:model="rruleArray.BYSETPOS"
                            {{ $monthlyRepetition == 'BYMONTHDAY' ? 'disabled' : ''}}
                            class="p-2 rounded border">
                        <option value="NULL">Select</option>
                        @foreach($bySetPositions as $value => $label)
                            <option value="{{$value}}">{{$label}}</option>
                        @endforeach
                    </select>
                    <select wire:model="rruleArray.BYDAY"
                            {{ $monthlyRepetition == 'BYMONTHDAY' ? 'disabled' : '' }}
                            class="p-2 rounded border">
                        <option value="NULL">Select</option>
                        @foreach($daysOfWeek as $value => $label)
                            <option value="{{$value}}">{{$label}}</option>
                        @endforeach
                    </select>
                </label>
            @endif
        </section>
        @if($includeStarts)
            <section>
                <h5 class="font-bold">Starts</h5>
                <label>
                    <input type="radio"
                           wire:model="STARTS"
                           value="NOT-SPECIFIED"><span> Not specified</span>
                </label>
                <div>
                    <label>
                        <input type="radio"
                               wire:model="STARTS"
                               value="CUSTOM"><span> On </span>
                    </label>

                    <label>
                        @error('rruleArray.DTSTART')<span class="text-red-900 text-sm font-bold"></span>@enderror
                        <input type="date"
                               {{ $STARTS !== 'CUSTOM' ? 'disabled' : '' }}
                               wire:model="rruleArray.DTSTART"
                               class="p-2 rounded border">
                    </label>
                </div>
            </section>
        @endif
        @if($includeEnds)
            <section>
                <h5 class="font-bold">Ends</h5>
                <div class="space-y-2">
                    <label>
                        <input type="radio"
                               wire:model="ENDS"
                               value="NEVER"> <span> Never</span>
                    </label>

                    <div>
                        <label>
                            <input type="radio"
                                   wire:model="ENDS"
                                   value="ON"><span> On</span>
                        </label>

                        <input type="date"
                               wire:model="rruleArray.UNTIL"
                               class="p-2 rounded border"
                            {{ $ENDS !== 'ON' ? 'disabled' : '' }}>
                    </div>

                    <div>

                        <label>
                            <input type="radio"
                                   wire:model="ENDS"
                                   value="AFTER"><span> After </span>
                        </label>

                        <label>
                            <input type="number"
                                   wire:model="rruleArray.COUNT"
                                   step="1"
                                   min="1"
                                   class="border p-2 rounded w-16"
                                {{ $ENDS !== 'AFTER' ? 'disabled' : '' }}>
                            <span> occurences</span>
                        </label>
                    </div>
                </div>
            </section>
        @endif
        <section class="flex justify-end ">
            <button type="button"
                    class="p-2 px-4 font-bold bg-blue-500 text-white rounded"
                    wire:click="processRrule">Confirm
            </button>
        </section>
    @else
        <div class="flex items-center justify-between">
            <p>{{ $humanReadable }}</p>
            <button type="button"
                    wire:click="$toggle('editable')"
                    class="p-4 text-blue-500">Edit
            </button>
        </div>
    @endif
</div>
