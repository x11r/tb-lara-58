@extends('layouts.rakuten')

@section('content')
    <div class="container">
        <h1>楽天APIでホテルを検索する</h1>
        <div>
        @if (isset($areas->areaClasses))
            <div id="rakutenArea">
            @foreach ($areas->areaClasses->largeClasses[0]->largeClass[1]->middleClasses as $key1 => $middleClasses)
                <div>
                    <span class="middle-class">
                        {{ $middleClasses->middleClass[0]->middleClassName }}
                    </span>
                    <span>
                        @foreach ($middleClasses->middleClass[1]->smallClasses as $key2 => $smallClasses)
                            @if (isset($smallClasses->smallClass[1]))
                                @foreach ($smallClasses->smallClass[1]->detailClasses as $key3 => $detailClasses)
                                    <span class="small-class">
                                        <a href="/rakuten/hotelSearch/{{
                $areas->areaClasses->largeClasses[0]->largeClass[0]->largeClassCode }}/{{
                $middleClasses->middleClass[0]->middleClassCode }}/{{
                $smallClasses->smallClass[0]->smallClassCode }}/{{
                $detailClasses->detailClass->detailClassCode
            }}">{{ $detailClasses->detailClass->detailClassName }}</a>
                                    </span>
                                @endforeach
                            @else
                                <span class="small-class">
                                    <a href="/rakuten/hotelSearch/{{
                $areas->areaClasses->largeClasses[0]->largeClass[0]->largeClassCode }}/{{
                $middleClasses->middleClass[0]->middleClassCode }}/{{
                $smallClasses->smallClass[0]->smallClassCode }}">
                                        {{ $smallClasses->smallClass[0]->smallClassName }}
                                    </a>
                                </span>
                            @endif
                        @endforeach
                    </span>
                </div>
            @endforeach
            </div>
        @endif
        </div>
    </div>
@endsection
