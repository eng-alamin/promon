@php
    use Illuminate\Support\Str;

    // route = null means the module is not built yet (shown dimmed, not clickable).
    $items = [
        ['label' => 'Home', 'icon' => 'bi-grid', 'route' => 'admin.dashboard', 'active' => ['admin.dashboard']],
        ['label' => 'Enquiries', 'icon' => 'bi-chat-left-text', 'route' => 'admin.enquiries.index', 'active' => ['admin.enquiries.*']],
        ['label' => 'Categories', 'icon' => 'bi-tags', 'route' => 'admin.categories.index', 'active' => ['admin.categories.*']],
        ['label' => 'Customers', 'icon' => 'bi-people', 'route' => 'admin.customers.index', 'active' => ['admin.customers.*']],
        ['label' => 'FAQs', 'icon' => 'bi-question-circle', 'route' => 'admin.faqs.index', 'active' => ['admin.faqs.*']],
        ['label' => 'Users', 'icon' => 'bi-person-gear', 'route' => 'admin.users.index', 'active' => ['admin.users.*'], 'superAdminOnly' => true],
        ['label' => 'Settings', 'icon' => 'bi-gear', 'route' => 'admin.settings.edit', 'active' => ['admin.settings.*'], 'superAdminOnly' => true],
    ];
@endphp

<nav class="rail" aria-label="Main">
    <a class="logo" href="{{ route('admin.dashboard') }}" aria-label="{{ config('app.name') }} home">{{ Str::upper(Str::substr(config('app.name'), 0, 1)) }}</a>

    @foreach ($items as $item)
        @continue(($item['superAdminOnly'] ?? false) && ! auth()->user()->isSuperAdmin())
        @if ($item['route'])
            @php($isActive = request()->routeIs(...$item['active']))
            <a href="{{ route($item['route']) }}" @class(['nav-i', 'on' => $isActive]) @if ($isActive) aria-current="page" @endif>
                <i class="bi {{ $item['icon'] }}" aria-hidden="true"></i><span>{{ $item['label'] }}</span>
            </a>
        @else
            <span class="nav-i soon" role="link" aria-disabled="true" title="Coming in a later step">
                <i class="bi {{ $item['icon'] }}" aria-hidden="true"></i><span>{{ $item['label'] }} (soon)</span>
            </span>
        @endif
    @endforeach

    <span class="sp"></span>

    <form method="POST" action="{{ route('admin.logout') }}" style="display:contents">
        @csrf
        <button type="submit" class="nav-i" aria-label="Log out">
            <i class="bi bi-box-arrow-right" aria-hidden="true"></i><span>Log out</span>
        </button>
    </form>
</nav>
