@extends('layout')

@section('page.title', 'Dashboard')
    
@section('content')
@can('logged-in')
<div class="container">
    <h1>Dashboard</h1>
    
    @foreach($orders as $order)
        <x-listing-item :order="$order" />
    @endforeach
    
    {{ $orders->links() }}
</div>
@endcan
@endsection
@once
    @push('footer')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.2.0/js/bootstrap.min.js"></script>
    @endpush
@endonce