<x-layout-app page-title="Delete Colaborator">
    <div class="w-25 p-4">

        <h3>Delete colaborator</h3>

        <hr>

        <p>Are you sure you want to delete this colaborator?</p>
    
        <div>
            <h3 class="my-5">Name: {{ $colaborator->name }}</h3>
            <p>Email: {{ $colaborator->email }}</p>
            <p>Role: {{ $colaborator->role }}</p>
            <a href="{{ route('colaborators.rh-users') }}" class="btn btn-secondary px-5">No</a>
            <a href="{{ route('colaborators.rh.delete-colaborator-confirm', ['id' => $colaborator->id]) }}" class="btn btn-danger px-5">Yes</a>
        </div>

    </div>

</x-layout-app>
