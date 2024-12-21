<x-layout-app page-title="Departments">

    <div class="w-100 p-4">

        <h3>Departments</h3>

        <hr>

        @if (empty($departments))
            <div class="text-center my-5">
                <p>No departments found.</p>
                <a href="{{ route('departments.new-department') }}" class="btn btn-primary">Create a new department</a>
            </div>
            <hr>
        @else
            <div class="mb-3">
                <a href="{{ route('departments.new-department') }}" class="btn btn-primary">Create a new department</a>
            </div>

            <table class="table w-50" id="table">
                <thead class="table-dark">
                    <th>Department</th>
                    <th></th>
                </thead>
                <tbody>
                    @foreach ($departments as $department)
                        <tr>
                            <td>{{ $department->name }}</td>

                            <td>
                                <div class="d-flex gap-3 justify-content-end">
                                    @if (!in_array($department->id, [1,2]))
                                        <a href="{{ route('departments.edit-department', ['id' => $department->id]) }}"
                                            class="btn btn-sm btn-outline-dark"><i
                                                class="fa-regular fa-pen-to-square me-2"></i>Edit</a>
                                        <a href="{{ route('departments.delete-department', ['id' => $department->id]) }}"
                                            class="btn btn-sm btn-outline-dark"><i
                                                class="fa-regular fa-trash-can me-2"></i>Delete</a>
                                    @else
                                        <div class="d-flex align-content-center">
                                            <div class="px-2"><i class="fa-solid fa-lock text-secondary"></i></div>
                                        </div>
                                    @endif
                                </div>
                            </td>


                        </tr>
                    @endforeach
                </tbody>
            </table>

        @endif

        @if (session('success_create'))
            <div class="text-success mt-3">
                {{ session('success_create') }}
            </div>
        @endif

        @if (session('success_edit'))
            <div class="text-success mt-3">
                {{ session('success_edit') }}
            </div>
        @endif

        @if (session('success_delete'))
            <div class="text-success mt-3">
                {{ session('success_delete') }}
            </div>
        @endif

        @if (session('error'))
            <div class="text-danger mt-3">
                {{ session('error') }}
            </div>
        @endif

    </div>


</x-layout-app>
