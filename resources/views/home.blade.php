<x-layout-app page-title="Home">
    <div class="w-100 p-4">
        <h3>Admin Home</h3>
        <hr>
        <div class="d-flex w-100">
            <x-info-title-value item-title="Total colaborators" :item-value="$data['total_colaborators']" c="flex-grow-0"></x-info-title-value>
            <x-info-title-value item-title="Total deleted colaborators" :item-value="$data['total_colaborators_deleted']" c="flex-grow-0"></x-info-title-value>
            <x-info-title-value item-title="Total salaries" :item-value="$data['total_salaries']" c="flex-grow-1"></x-info-title-value>
        </div>
        <hr>
        <div class="d-flex">
            <x-info-title-collection item-title="Colaborators by department" :collection="$data['total_colaborators_per_department']"></x-info-title-collection>
            <x-info-title-collection item-title="Total salaries by department" :collection="$data['total_salaries_per_department']"></x-info-title-collection>

        </div>

    </div>
</x-layout-app>
