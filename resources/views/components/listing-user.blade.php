@props(['user'])
@php
    $phone = ($user->phone) ? phonecode($user->phone) : '';
    $active = (boolval($user->active)) ? 'Active' : 'Not active';
@endphp
<div class="flex flex-row space-between space-x-4 shadow-md mb-2">
    <div class="order-num-row">
      {{ $user->id }}
    </div> 
    
    <div class="name-row">
      <a href="{{ route('users.edit', $user->id) }}">{{ $user->name }}</a>
    </div>

    <div class="role-row">
      {{ $user->role }}
    </div>

    <div class="email-row">
      {{ $user->email }}
    </div>
    
    <div class="phone-row">
      {!! $phone !!}
    </div>
  
    <div>
        Status: {{ $active }}
    </div>
    
    
</div>
