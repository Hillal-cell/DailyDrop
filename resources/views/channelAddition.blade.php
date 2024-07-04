<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-800 leading-tight">
            {{ __('Channel Management Page') }}
        </h2>
        </h2>
    </x-slot>


    
    @if (session('status'))
        <div class="alert alert-success" id="status-message" style="text-align:center;">
            {{ session('status') }}
        </div>
    @elseif (session('error'))
       <div class="alert alert-warning" id="status-message" style="text-align:center;">
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
    

    <div class="container" >
         
        
    </div>
    
    
    

    <!-- script to hide the status message after 5 seconds -->
    <script>
        window.onload = function() {
            setTimeout(function() {
                var statusMessage = document.getElementById('status-message');
                if (statusMessage) statusMessage.style.display = 'none';
            }, 2000);
        }
    </script>

</x-app-layout>


