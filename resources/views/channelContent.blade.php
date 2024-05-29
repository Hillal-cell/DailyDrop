<x-app-layout>
    <x-slot name="header">
        
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Calendar for ') . $channelName }}
        </h2>
    </x-slot>

   
    <div class="row m-2">
        <div class="col-2">
            <div class="list-group">
                @foreach ($channels as $channel)
                    <a href="{{ route('channel.calendar', ['channelName' => $channel]) }}" class="list-group-item list-group-item-action {{ $channel == $channelName ? 'active' : '' }}">{{ $channel }}</a>
                @endforeach
                <a href="{{ route('dashboard') }}" class="list-group-item list-group-item-action"> All Channels</a>
            </div>
        </div>

        <div class="col">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div id="calendars"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Scripts at the end of the body -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" />

    <script>
        $(document).ready(function() {
            var channelName = '{{ $channelName }}'; 

            $('#calendars').fullCalendar({ 
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay,listMonth'
                },

                events: '/channel/' + channelName + '/events'
                
            });
            
        });
    </script>
</x-app-layout>
