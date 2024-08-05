<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-800 leading-tight">
            {{ __('AuditLog OverView') }}
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
    

    <div class="container" style="background:white" >
        <form action="{{ route('getLogs') }}" method="GET" style="margin-bottom: 20px;">
            <div class="input-group">
            
            
            <input type="text" class="bi bi-search" name="search" placeholder="Search for ...">
                <button type="submit" class="btn btn-primary" style="margin-left: 3px;">Search</button>
                <a href="{{ route('getLogs') }}" class="btn btn-info" style="margin-left: 3px;">Clear</a>
            
            </div>
        </form>
        <table  class="table table-stripped table-hover" style="text-align:left">
            <thead style ="color:blue">
                <th>USER ID</th>
                <th>ROLE</th>
                <th>ACTION</th>
                <th>PATH</th>
                <th>CREATED_AT</th>
                <th>UPDATED_AT</th>
            </thead>
            <tbody>
                @foreach($logs as $log)
                    <tr>
                        <td>{{$log->user_id}}</td>
                        <td>{{$log->role}}</td>
                        <td>{{$log->action}}</td>
                        <td>{{$log->path}}</td>
                        <td>{{$log->created_at}}</td>
                        <td>{{$log->updated_at}}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="table table-info">
            {{ $logs->links() }}
        </div>
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


