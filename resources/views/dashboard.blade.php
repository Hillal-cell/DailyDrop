<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div style="text-align: center;color:blueviolet;font-size: 34px" class="p-6 text-gray-900">
                    {{"Hi"}} <span style="color:gold"> {{ explode(' ', Auth::user()->name)[0] }}</span> {{ __("Welcome to") }}  {{ config('app.name') }}
                </div>
        
                @if (session('status'))
        <div class="alert alert-success" id="status-message" style="text-align:center;">
            {{ session('status') }}
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger" id="status-message" style="text-align:center;">
            {{ session('error') }}
        </div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger" id="status-message" style="text-align:center;">
            @foreach ($errors->all() as $error)
                {{ $error }}
            @endforeach
        </div>
    @endif
            </div>
        </div>
        
    </div>




    <!-- Separate div for the calendar -->

    <div class="row m-2">
        <div class="col-1.8">
            <table class="list-group" >
                <thead>
                    <tr>
                        <th style="color: blueviolet; font-weight: bold;text-transform: uppercase;">Channel Names</th>
                    </tr>
                </thead>
                <tbody style="text-transform:uppercase">
                    @foreach ($channels as $channel)
                            <tr>
                                <td>
                                    <a href="{{ route('channel.calendar', ['channelName' => $channel]) }}"  class="list-group-item list-group-item-action {{$channel==$channel}}">{{ $channel }}  </a>
                                </td>
                            </tr>
                            
                    @endforeach
                            <tr>
                                <td>
                                <a href="{{route('dashboard')}}" class="list-group-item list-group-item-action"> All Channels</a>
                                </td>
                            </tr>
                </tbody>
            </table>

            




        </div>

        <div class="col">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <div id="calendar"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

   


    <!-- Start popup dialog box -->
    <div class="modal fade" id="event_entry_modal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog modal-md" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel" style="text-transform:uppercase;color:blueviolet">Add Programme Name</h5>
                    <button type="button" class="close" data-dismiss="modal" id="dismiss_modal_button1" aria-label="Close">
                        <span aria-hidden="true">X</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="cast_name">Movie or Music name</label>
                                    <input type="text" name="cast_name" id="cast_name" class="form-control" placeholder="Enter Movie/Music name" required>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="MainCast_name">Producer or Artist Name </label>
                                    <input type="text" name="MainCast_name" id="maincast_name" class="form-control" placeholder="Enter producer/artist's name" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Type of Control </label><br>
                                    <label class="radio-inline">
                                        <input type="radio" name="type_of_control" value="Music" required onchange="handleTypeOfControlChange()"> Music
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="type_of_control" value="Movie" required onchange="handleTypeOfControlChange()"> Movie
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label>Is Translated?</label><br>
                                    <label class="radio-inline">
                                        <input type="radio" name="is_translated" value="yes" required> Yes
                                    </label>
                                    <label class="radio-inline">
                                        <input type="radio" name="is_translated" value="no" required> No
                                    </label>
                                   
                                </div>
                            </div>
                        </div>

                        


                        <div class="row">
                            <div class="col-sm-12">
                                <div class="form-group">
                                    <label for="channel_name">Channel</label>
                                    <input type="text" name="channel_name" id="channel_name" class="form-control" placeholder="Enter channel name" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="upload_date">Date uploaded</label>
                                    <input type="date" name="upload_date" id="upload_date" class="form-control onlydatepicker" placeholder="Upload date" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="play_date">Date Played</label>
                                    <input type="date" name="play_date" id="play_date" class="form-control" placeholder="Play date" required>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="start_time">Start Time</label>
                                    <input type="time" name="start_time" id="start_time" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label for="end_time">End Time</label>
                                    <input type="time" name="end_time" id="end_time" class="form-control" required>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" id="dismiss_modal_button" data-dismiss="modal">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary" id="save_event_button" >Save Event</button>
                    <button type="button" class="btn btn-info" id="update_event_button" >Update Event</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        function handleTypeOfControlChange() {
            const typeOfControl = document.querySelector('input[name="type_of_control"]:checked').value;
            const isTranslatedRadios = document.querySelectorAll('input[name="is_translated"]');
            const isTranslated = document.getElementById('is_translated');
            const isTranslatedHidden = document.getElementById('is_translated_hidden');

            if (typeOfControl === 'Music') {
                isTranslatedRadios.forEach(radio => {
                    radio.disabled = true;
                    radio.checked = false;
                   
                });

                
               
               
            } else {
                isTranslatedRadios.forEach(radio => {
                    radio.disabled = false;
                });
               
            }
        }

       
    </script>
</x-app-layout>



