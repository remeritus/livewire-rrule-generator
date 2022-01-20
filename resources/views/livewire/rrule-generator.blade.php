<div class="p-4">
    @if($editable)
    <form wire:submit.prevent="processRrule" class="space-y-4">
        <section>
            <h4 class="font-bold text-2xl mb-2">
                {{ config('livewire-rrule-generator.title') ?? 'Define Schedule' }}
            </h4>
            <select wire:model="FREQ">
                @foreach($frequencies as $frequency)
                    <option value="{{$frequency}}">{{$frequency}}</option>
                @endforeach
            </select>

            @if($FREQ === 'DAILY')
                @error('BYDAY')<p class="text-red-900 text-sm font-bold">{{$message}}</p>@enderror
                <div class="space-x-2">
                    @foreach($daysOfWeek as $abbrevation => $label)
                        <label>
                            <input type="checkbox"
                                   wire:model="BYDAY.{{$abbrevation}}"
                                   value="{{$abbrevation}}">
                            {{ $abbrevation }}</label>
                    @endforeach
                </div>
            @endif

            @if($FREQ === 'WEEKLY')

                @error('BYDAY')<p class="text-red-900 text-sm font-bold">{{$message}}</p>@enderror
                <label> on </label>
                <select wire:model="BYDAY">
                    <option value="NULL">-- Select day of the week --</option>
                    @foreach($daysOfWeek as $value => $label)
                        <option value="{{$value}}">{{$label}}</option>
                    @endforeach
                </select>

                <div class="mt-2">
                    <label>
                        Every
                        <input type="number" wire:model="INTERVAL" min="1" class="w-20">
                        week(s)
                    </label>
                </div>

            @endif

            @if($FREQ == 'MONTHLY')
                <label>
                    Every <input type="number" step="1" min="1" wire:model="INTERVAL"> month(s)
                </label>
                <label class="block mb-4 {{$monthlyRepetition == 'BYSET' ? 'opacity-30' : ''}} hover:opacity-100">
                    <input type="radio" wire:model="monthlyRepetition" value="BYMONTHDAY"> on day
                    <select wire:model="BYMONTHDAY" {{$monthlyRepetition == 'BYSET' ? 'disabled' : ''}}>
                        <option value="NULL">Select</option>
                        @for($day = 1; $day <= $daysInMonth; $day++)
                            <option value="{{$day}}">{{$day}}</option>
                        @endfor
                    </select>
                </label>
                <label class="{{$monthlyRepetition == 'BYMONTHDAY' ? 'opacity-30' : ''}} hover:opacity-100">
                    <input type="radio" wire:model="monthlyRepetition" value="BYSET"> on the
                    <select wire:model="BYSETPOS" {{$monthlyRepetition == 'BYMONTHDAY' ? 'disabled' : ''}}>
                        <option value="NULL">Select</option>
                        @foreach($bySetPositions as $value => $label)
                            <option value="{{$value}}">{{$label}}</option>
                        @endforeach
                    </select>
                    <select wire:model="BYDAY" {{$monthlyRepetition == 'BYMONTHDAY' ? 'disabled' : ''}}>
                        <option value="NULL">Select</option>
                        @foreach($daysOfWeek as $value => $label)
                            <option value="{{$value}}">{{$label}}</option>
                        @endforeach
                    </select>
                </label>
            @endif
        </section>
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
                    @error('DTSTART')<span class="text-red-900 text-sm font-bold"></span>@enderror
                    <input type="date"
                           {{ $STARTS !== 'CUSTOM' ? 'disabled' : '' }}
                           wire:model="DTSTART">
                </label>
            </div>


        </section>
        <section>
            <h5 class="font-bold">Ends</h5>
            <div class="space-y-2">
                <label>
                    <input type="radio"
                           wire:model="ENDS"
                           value="NEVER"> <span>Never</span>
                </label>

                <div>
                    <label>
                        <input type="radio"
                               wire:model="ENDS"
                               value="ON"><span>On</span>
                    </label>

                    <input type="date"
                           wire:model="UNTIL"
                        {{ $ENDS !== 'ON' ? 'disabled' : '' }}>
                </div>

                <div>

                    <label>
                        <input type="radio"
                               wire:model="ENDS"
                               value="AFTER"><span>After</span>
                    </label>

                    <label>
                        <input type="number"
                               wire:model="COUNT"
                               step="1"
                               min="1"
                            {{ $ENDS !== 'AFTER' ? 'disabled' : '' }}>
                        <span>occurences</span>
                    </label>

                </div>
            </div>
        </section>
        <section class="flex justify-end ">
            <button class="p-2 px-4 font-bold bg-blue-500 text-white rounded">Confirm</button>
        </section>
    </form>
    @else
        <div class="flex items-center justify-between">
            <p>{{ $humanReadable }}</p>
            <button wire:click="$toggle('editable')" class="p-4 text-blue-500">Edit</button>
        </div>
    @endif
</div>
