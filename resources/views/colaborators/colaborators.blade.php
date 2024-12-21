<x-layout-app page-title="Colaborators">

    <div class="w-100 p-4">

        <h3>All Colaborators</h3>

        <hr>

        @if ($colaborators->count() === 0)
            <div class="text-center my-5">
                <p>No colaborators found.</p>
                <a href="{{ route('rh.managment.new-colaborator') }}" class="btn btn-primary">Create a new colaborator</a>
            </div>
        @else
            <div class="my-3">
                <a href="{{ route('rh.managment.new-colaborator') }}" class="btn btn-primary">Create a new colaborator</a>
            </div>
            <table class="table" id="table">
                <thead class="table-dark">
                    <th>Name</th>
                    <th>Email</th>
                    <th>Active</th>
                    <th>Department</th>
                    <th>Role</th>
                    <th>Admission date</th>
                    <th>Salary</th>
                    <th></th>
                </thead>
                <tbody>
                    @foreach ($colaborators as $colaborator)
                        <tr>
                            <td>{{ $colaborator->name }}</td>
                            <td>{{ $colaborator->email }}</td>
                            <td>
                                @empty($colaborator->email_verified_at)
                                    <span class="badge bg-danger">No</span>
                                @else
                                    <span class="badge bg-success">Yes</span>
                                @endempty
                            </td>
                            <td>{{ $colaborator->department->name }}</td>
                            <td>{{ $colaborator->role }}</td>
                            <td>{{ $colaborator->detail->admission_date }}</td>
                            <td>{{ $colaborator->detail->salary }} $</td>

                            <td>
                                <div class="d-flex gap-3 justify-content-end">


                                    @if ($colaborator->deleted_at)
                                        <a href="{{ route('rh.managment.restore-colaborator', ['id' => $colaborator->id]) }}"
                                            class="btn btn-sm btn-outline-dark"><i
                                                class="fa-solid fa-trash-arrow-up me-2"></i>Restore</a>
                                    @else
                                        <a href="{{ route('rh.managment.edit-colaborator', $colaborator->id) }}"
                                            class="btn btn-sm btn-outline-dark"><i
                                                class="fas fa-eye me-2"></i>Edit</a>
                                        <a href="{{ route('rh.managment.delete-colaborator', $colaborator->id) }}"
                                            class="btn btn-sm btn-outline-dark"><i
                                                class="fa-regular fa-trash-can me-2"></i>Delete</a>
                                        <a href="{{ route('rh.managment.details-colaborator', $colaborator->id) }}"
                                            class="btn btn-sm btn-outline-dark"><i
                                                class="fas fa-eye me-2"></i>Details</a>
                                    @endif

                                </div>
                            </td>


                        </tr>
                    @endforeach
                </tbody>
            </table>

        @endif

        @if (session('success'))
            <div class="text-success mt-3">
                {{ session('success') }}
            </div>
        @endif

        @if (session('error'))
            <div class="text-danger mt-3">
                {{ session('error') }}
            </div>
        @endif

    </div>


</x-layout-app>
