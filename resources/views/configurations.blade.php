<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Reporting') }}
        </h2>
    </x-slot>


    
    @if (session('status'))
        <div class="alert alert-success" id="status-message" style="text-align:center;">
            {{ session('status') }}
        </div>
    @endif

    <form method="POST" action="{{ route('configuration.update') }}">
        @csrf
        @method('patch')

        
        
            <div class="modal-dialog modal-md" role="document">
                
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabel" style="text-transform:uppercase;color:blueviolet">Add Configuration Settings </h5>
                        
                    </div>
                    <div class="modal-body">
                        <div class="img-container">

                            <div class="row">
                                <div class="col-sm-12">  
                                    <div class="form-group">
                                        <label for="movie_repeat">Movie Repetion Duration</label>
                                        <input type="number" name="movie_repeat" id="movie_repeat" class="form-control" placeholder="Enter Movie repeat duration" required>
                                    </div>
                                </div>    
                            </div>

                            <div class="row">
                                <div class="col-sm-12">  
                                    <div class="form-group">
                                        <label for="music_repeat">Music Repetion Duration</label>
                                        <input type="number" name="music_repeat" id="music_repeat" class="form-control" placeholder="Enter Music repeat duration" required>
                                    </div>
                                </div>    
                            </div>
                            
                        </div>
                    <div class="modal-footer">
                        <!-- <button type="button" class="btn btn-danger" id ="dismiss_modal_button" data-dismiss="modal" >
                            Cancel
                        </button> -->
                        <button type="submit" class="btn btn-primary" id="save_configuration_button">Save</button>
                        
                    </div>
                </div>
            </div>
            
        </div>
    </form>


    <!-- Add a script to hide the status message after 5 seconds -->
    <script>
        window.onload = function() {
            setTimeout(function() {
                var statusMessage = document.getElementById('status-message');
                if (statusMessage) statusMessage.style.display = 'none';
            }, 2000);
        }
    </script>

</x-app-layout>


