@props(['order', 'count'])
@php
    $time = $order->created_at;
    $phone = phonecode($order->phone);
    $userinfo = json_decode($order->userinfo);
    $sent = (boolval($order->sent)) ? 'Sent' : 'Not sent';
    $lead = boolval($order->lead) ? 'Sold' : 'Wait';
@endphp
<div class="flex flex-row space-between space-x-4 shadow-md mb-2">
    <div class="order-num-row">
      {{ $count }} {{-- $order->id --}}
    </div> 
    
    <div class="time-row">
      <b>{{ __('Time') }}:</b> {{ $time->format('d-m-Y H:i:s') }}
    </div>
    
    <div class="phone-row">
      <b>{{ __('Phone') }}:</b> {!! $phone !!}
    </div>

    <div class="page-row">
      <b>{{ __('URL') }}:</b> {{ $order->page }}
    </div>
  
    <div>
        Status: {{ $sent }} | {{ $lead }}
    </div>
    {{-- <div>
      @php 
        //echo (str_contains(parse_url($order->page, PHP_URL_PATH), 'course')) ? 'Курси' : 'Вії' . '<br />';
        //echo parse_url($order->page, PHP_URL_QUERY).'<br />';
        //echo parse_url($order->page, PHP_URL_FRAGMENT).'<br />';
      @endphp
    </div> --}}
    {{--
    @foreach($userinfo as $field => $value)
        {{ $field }}: {{ $value }}
    @endforeach
    --}}
</div>
