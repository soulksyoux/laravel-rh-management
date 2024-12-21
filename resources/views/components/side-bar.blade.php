<div class="d-flex flex-column sidebar pt-4">
    <a href="{{ route('home') }}" class=""><i class="fas fa-home me-3"></i>Home</a>
    @can('admin')
        <a href="{{ route('colaborators.all-colaborators') }}" class=""><i class="fas fa-users me-3"></i>Colaborators</a>
        <a href="{{ route('colaborators.rh-users') }}" class=""><i class="fas fa-user-gear me-3"></i>RH Colaborators</a>
        <a href="{{ route('departments') }}" class=""><i class="fas fa-industry me-3"></i>Departments</a>
        
    @endcan
    <hr>
    <a href="{{ route('user.profile') }}" class=""><i class="fas fa-cog me-3"></i>User Profile</a>
    <hr>
    <div class="text-center mt-3">
        <form action="{{ route('logout') }}" method="post">
            @csrf
            <button type="submit" class="btn btn-sm btn-outline-dark">
                <i class="fas fa-sign-out me-3">  Logout</i>
            </button>
        </form>
    </div>
</div>