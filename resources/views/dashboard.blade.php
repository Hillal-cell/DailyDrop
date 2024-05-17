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
                    {{ __("Welcome to Daily Drop  ") }}
                </div>
                
            </div>
            
        </div>
        
    </div>

    <!-- Separate div for the calendar -->
   
    <div class="row m-2">
        <div class="col-2" style="border-collapse: collapse">
          
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
                    <h5 class="modal-title" id="modalLabel">Add Programme Name </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="img-container">
                        <div class="row">
                            <div class="col-sm-12">  
                                <div class="form-group">
                                  <label for="cast_name">Programme name</label>
                                  <input type="text" name="cast_name" id="cast_name" class="form-control" placeholder="Enter your cast name" required>
                                </div>
                            </div>
                        </div>
                         <div class="row">
                            <div class="col-sm-12">  
                                <div class="form-group">
                                  <label for="MainCast_name">Main Cast</label>
                                  <input type="text" name="MainCast_name" id="maincast_name" class="form-control" placeholder="Enter main cast's name" required>
                                </div>
                            </div>
                        </div>

                            <div class="row">
                            <div class="col-sm-12">  
                                <div class="form-group">
                                    <label>Is Translated ?</label><br>
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
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="save_event_button">Save Event</button>
                </div>
            </div>
        </div>
    </div>
    <!-- End popup dialog box -->



</x-app-layout>



