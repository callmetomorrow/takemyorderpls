@section('page.title', 'Dashboard')
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    @can('is-subscribed')
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h1 class="font-medium leading-tight text-xl mt-0 mb-4">{{ __('Statistics for:') }} {{ $date }}</h1>
                    {{ __('Filter by date: ') }}<x-input type="date" id="datepick" name="trip-start" value="{{ $date }}" min="2022-01-01" max="{{ date('Y-m-d') }}" />
                    @forelse($orders as $order)    
                        <x-listing-item :order="$order" :count="$loop->iteration" />
                    @empty
                        <b>{{ __('Nothing found') }}</b>
                    @endforelse

                    {{ $orders->links() }}
                
                </div>
            </div>
        </div>
    </div>
    @endcan
</x-app-layout>

@once
    @push('footer')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/js/bootstrap.min.js"></script>
        <script>
            const datepick = document.getElementById('datepick');
            datepick.addEventListener('change', (e) => {
                window.location.href = '{{ route('dashboard') }}/' + e.target.value;
            })
        </script>
    @endpush
@endonce
