<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
    <a class="navbar-brand" href="{{ route('home') }}">Home</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNavDropdown">
    @can('logged-in')
      <ul class="navbar-nav">
        <li class="nav-item">
            <a href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
        </li>
        <li>
            {{ auth()->user()->name }}
        </li>
        <li>
            <x-form method="POST" action="{{ route('logout') }}">
                <x-button class="btn-link" name="logout">Logout</x-button>
            </x-form>
        </li>
      </ul>
    @endcan
    </div>
  </div>
</nav>