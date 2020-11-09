@extends('layouts.rakuten')

@section('content')
    <div class="container">

{{--        パンくず--}}
        <div>
            <a href="{{ action('RakutenController@index') }}">エリア一覧</a> &gt;
            ホテル検索
        </div>
        <h1>楽天APIでホテルを検索する</h1>

        {{-- ペジネーションを読み込む --}}
        @include ('rakuten/hotelSearchPaginate')

        @if (isset($result->hotels))
            <div class="search-results">
                @foreach ($result->hotels as $key1 => $hotel)
                    <div class="row col-md-8 hotel-info">
                        <div class="col-md-8">
                            <div class="hotel-name">
                                <a href="/rakuten/hotelDetail/{{ $hotel->hotel[0]->hotelBasicInfo->hotelNo }}">
                                ホテル名
                                    {{ $hotel->hotel[0]->hotelBasicInfo->hotelName }}
                                    ({{ $hotel->hotel[0]->hotelBasicInfo->hotelNo }})
                                </a>
                            </div>
                            <div>
                                住所
                                {{ $hotel->hotel[0]->hotelBasicInfo->address1 }}
                                {{ $hotel->hotel[0]->hotelBasicInfo->address2 }}
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div>
                                @if ($hotel->hotel[0]->hotelBasicInfo->hotelThumbnailUrl !== '')
                                    <img src="{{ $hotel->hotel[0]->hotelBasicInfo->hotelThumbnailUrl }}" />
                                @endif
                                @if ($hotel->hotel[0]->hotelBasicInfo->roomThumbnailUrl !== '')
                                    <img src="{{ $hotel->hotel[0]->hotelBasicInfo->roomThumbnailUrl }}" />
                                @endif
                            </div>
                        </div>
                    </div>
                    @if (count($hotel->hotel) > 2)
                    <div><pre>{{ print_r($hotel) }}</pre></div>
                    @endif
                @endforeach
            </div>
        @endif
        @if ($result->pagingInfo->last == $result->pagingInfo->recordCount )
            <div class="search-result search-result-last">
                これ以上検索結果は表示できません。
            </div>
        @endif

        {{-- ペジネーションを読み込む --}}
        @include ('rakuten/hotelSearchPaginate')
    </div>
@endsection
