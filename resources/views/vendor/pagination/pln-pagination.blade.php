@if ($paginator->hasPages())
    <div class="pln-pagination">
        <div class="pagination-left">
            <span>Items per page</span>

            <select onchange="changePerPage(this.value)">
                @foreach([10, 25, 50, 100] as $size)
                    <option value="{{ $size }}" {{ request('per_page', $paginator->perPage()) == $size ? 'selected' : '' }}>
                        {{ $size }}
                    </option>
                @endforeach
            </select>

            <span>
                {{ $paginator->firstItem() }}–{{ $paginator->lastItem() }} of {{ $paginator->total() }} items
            </span>
        </div>

        <div class="pagination-right">
            @if ($paginator->onFirstPage())
                <span class="page-link disabled">|‹</span>
                <span class="page-link disabled">‹ Previous</span>
            @else
                <a class="page-link" href="{{ $paginator->url(1) }}">|‹</a>
                <a class="page-link" href="{{ $paginator->previousPageUrl() }}">‹ Previous</a>
            @endif

            <input
                type="number"
                min="1"
                max="{{ $paginator->lastPage() }}"
                value="{{ $paginator->currentPage() }}"
                onchange="goToPage(this.value)"
            >

            <span>of {{ $paginator->lastPage() }}</span>

            @if ($paginator->hasMorePages())
                <a class="page-link" href="{{ $paginator->nextPageUrl() }}">Next ›</a>
                <a class="page-link" href="{{ $paginator->url($paginator->lastPage()) }}">›|</a>
            @else
                <span class="page-link disabled">Next ›</span>
                <span class="page-link disabled">›|</span>
            @endif
        </div>
    </div>

    <script>
        function changePerPage(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('per_page', value);
            url.searchParams.set('page', 1);
            window.location.href = url.toString();
        }

        function goToPage(value) {
            const url = new URL(window.location.href);
            url.searchParams.set('page', value);
            window.location.href = url.toString();
        }
    </script>
@endif