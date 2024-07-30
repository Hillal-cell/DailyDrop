<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-800 leading-tight">
            {{ __('Channels Guide') }}
        </h2>
        </h2>
    </x-slot>

    <div class="table-responsive">

        <table class="table align-middle">
            <thead>
               
                
                <tr>
                    <th style="color: blueviolet; font-weight: bold;text-transform: uppercase;">Channel</th>
                    <!-- <th> </th> -->
                    <?php for ($hour = 0; $hour <= 24; $hour++) { ?>
                        <th class="table-primary" style="font-wegth : bold;"><?php echo $hour % 12; ?><?php echo $hour >= 12 ? 'pm' : 'am'; ?></th>
                    <?php } ?>
                </tr>
                
            </thead>
            <tbody style="text-transform:uppercase">
        @foreach ($channels as $channel)
            <tr>
                <td class="table-secondary">{{$channel}}</td>
                <?php for ($hour = 0; $hour < 24; $hour++) { ?>
                    <td class="table-success" style="margin: 12px;">
                        @foreach ($events as $event)
                            @if ($event['channel_name'] == $channel && 
                                \Carbon\Carbon::parse($event['start_time'])->hour == $hour)
                                <div style="white-space: nowrap;">{{$event['cast_name']}}</div>
                                <div style="white-space: nowrap;">START : {{$event['start_time']}}</div>
                                <div style="white-space: nowrap;">END : {{$event['end_time']}}</div>
                            @endif

                        @endforeach
                    </td>
                <?php } ?>
            </tr>
        @endforeach
    </tbody>
        </table>
    </div>

</x-app-layout>






