<div>
    @if (isset($result->pagingInfo))
        <div>
            {{ $result->pagingInfo->recordCount }}件中
            {{ $result->pagingInfo->first }}件から
            {{ $result->pagingInfo->last }}件まで表示
        </div>

        <div class="row hotel-search-paginate">

            <div><a href="{{ request()->path() }}">最初</a></div>
            @if ($result->pagingInfo->page > 1)
                <div><a href="/{{ request()->path() }}?page={{ $result->pagingInfo->page - 1 }}">PREV</a></div>
            @endif

            @for ($i = 0; $i < 10; $i++)
                @if ($result->pagingInfo->page + $i - 1 < $result->pagingInfo->pageCount )
                    <div><a href="/{{ request()->path() }}?page={{ $result->pagingInfo->page + $i - 1 }}">NEXT {{ $result->pagingInfo->page + $i }}</a></div>
                @endif
            @endfor
            @if ($result->pagingInfo->last != $result->pagingInfo->first)
                    <div><a href="/{{ request()->path() }}?page={{ floor($result->pagingInfo->recordCount / ($result->pagingInfo->last - $result->pagingInfo->first)) + 1 }}">最後</a></div>
                @else
                    <div>最後</div>
            @endif
        </div>
    @endif
</div>
