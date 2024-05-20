<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('People') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                <div class="flex items-center justify-end">
                    <a class="bg-red-600 text-white py-2 px-3 rounded-full mr-5" href="{{ route('person.create') }}" >Add Contact</a>
                    <a class="bg-red-600 text-white py-2 px-3 rounded-full" href="{{ route('person.index') }}">Contacts</a>
                </div>

                <input type="text" id="search" placeholder="Search...">
                    <table class="table-fixed border-separate border-spacing-6">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Company</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="results">
                        @foreach ($people as $person)
                            <tr>
                                <td>{{ $person->name ?? '' }}</td>
                                <td>{{ $person->company ?? '' }}</td>
                                <td>{{ $person->email ?? '' }}</td>
                                <td>{{ $person->phone ?? '' }}</td>
                                <td>
                                    <a href="{{ route('person.edit', $person->id) }}">Edit</a>
                                    <a href="{{ route('person.destroy', $person->id) }}" onclick="return confirm('Are you sure you want to delete?')">Delete</a>
                                </td>
                            </tr>
                        @endforeach

                        </tbody>
                    </table>

                    <!-- Pagination links -->
                    <div class="mt-4">
                        {{ $people->links() }}
                    </div>
                </div>
                
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                <script>
                    $(document).ready(function() {
                        $('#search').on('keyup', function() {
                            let query = $(this).val();
                            $.ajax({
                                url: "{{ route('search') }}",
                                type: "GET",
                                data: {'query': query},
                                success: function(data) {
                                    $('#results').html(data);
                                }
                            });
                        });
                    });
                </script>
            </div>
        </div>
    </div>
</x-app-layout>
