<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-red-800 leading-tight">
            {{ __('User edit pannel') }}
        </h2>
        </h2>
    </x-slot>


    
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
    
    
     <!-- Form to edit the data -->

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <form action="{{ route('updateUser', $user->id) }}" method="post">
                    @csrf
                    @method('PATCH')
                    <div class="form-group">
                        <label for="name">Name:</label>
                        <input type="text" class="form-control" name="name" value="{{ $user->name }}" />
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="text" class="form-control" name="email" value="{{ $user->email }}" />
                    </div>
                    <div class="form-group">
                        <label for="role">Role:</label>
                        <select name="role" class="form-control">
                            <option value="user" {{ $user->role == 'user' ? 'selected' : '' }}>user</option>
                            <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>admin</option>
                        </select>
                    </div>

                    
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-danger" onclick="window.location.href='{{ route('getUsers') }}'">Cancel</button>
                    
                    
                </form>

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


