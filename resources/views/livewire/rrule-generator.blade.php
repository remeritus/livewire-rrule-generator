<div class="bg-white overflow-hidden shadow-sm sm:rounded-lg w-full max-w-md absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50">
    <form wire:submit.prevent="submitForm" class="p-6 bg-white border-b border-gray-200 overflow-hidden">
        <h3>Define Schedule</h3>
        <hr class="my-4">
        <select wire:model="FREQ">
            @foreach($frequencies as $frequency)
                <option value="{{$frequency}}">{{$frequency}}</option>
            @endforeach
        </select>

        @if($FREQ === 'DAILY')
            <div>
                <hr class="my-4">
                @error('BYDAY')<p class="text-red-900 text-sm font-bold">{{$message}}</p>@enderror
                <div class="space-x-2">
                    <label><input type="checkbox" wire:model="BYDAY.MO" value="MO"> MO</label>
                    <label><input type="checkbox" wire:model="BYDAY.TU" value="TU"> TU</label>
                    <label><input type="checkbox" wire:model="BYDAY.WE" value="WE"> WE</label>
                    <label><input type="checkbox" wire:model="BYDAY.TH" value="TH"> TH</label>
                    <label><input type="checkbox" wire:model="BYDAY.FR" value="FR"> FR</label>
                    <label><input type="checkbox" wire:model="BYDAY.SA" value="SA"> SA</label>
                    <label><input type="checkbox" wire:model="BYDAY.SU" value="SU"> SU</label>
                </div>
                {{ $BYDAYDAILY }}
            </div>
        @endif

        @if($FREQ === 'WEEKLY')

            <div>
                <hr class="my-4">
                @error('BYDAY')<p class="text-red-900 text-sm font-bold">{{$message}}</p>@enderror
                <label>on </label>
                <select wire:model="BYDAY">
                    <option value="NULL">-- Select day of the week --</option>
                    @foreach($daysOfWeek as $value => $label)
                        <option value="{{$value}}">{{$label}}</option>
                    @endforeach
                </select>
                <hr class="my-4">
            </div>

            <label>
                Every
                <input type="number" wire:model="INTERVAL" min="1" class="w-20">
                week(s)
            </label>
        @endif

        @if($FREQ == 'MONTHLY')
            <label>
                Every
                <select wire:model="INTERVAL">
                    @foreach($months as $monthNumber => $monthName)
                        <option value="{{$monthNumber}}" class="w-20">{{$monthNumber}}</option>
                    @endforeach
                </select> month(s)
            </label>
            <hr class="my-4">
            <label class="block mb-4 {{$monthlyRepetition == 'BYSET' ? 'opacity-30' : ''}} hover:opacity-100">
                <input type="radio" wire:model="monthlyRepetition" value="BYMONTHDAY"> on day
                <select wire:model="BYMONTHDAY" {{$monthlyRepetition == 'BYSET' ? 'disabled' : ''}}>
                    <option value="NULL">Select</option>
                    @foreach($daysInMonth as $day)
                        <option value="{{$day}}">{{$day}}</option>
                    @endforeach
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

        <hr class="my-4">

        <div class="flex justify-between">
            <button class="btn" wire:click.prevent="closeModal">Close</button>
            <button class="btn">Confirm</button>
        </div>
    </form>
</div>
