<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        @foreach ($breadcrumbs as $breadcrumb)

        @if ($breadcrumb->url && !$loop->last)
        <li>
            <a href="{{ $breadcrumb->url }}" class="breadcrumb-item">
                {{ $breadcrumb->title }}
            </a>
        </li>
        @else
        <li>
            {{ $breadcrumb->title }}
        </li>
        @endif

        @unless($loop->last)
        <li class="breadcrumb-item active">
            /
        </li>
        @endif

        @endforeach
    </ol>
</nav>