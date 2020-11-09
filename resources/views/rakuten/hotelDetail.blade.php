@extends('layouts.rakuten')

@section('content')
    <div class="container">
        <div class="row">
            <h1>{{ $basic_info->hotelName }}</h1>
            <div class="text-buttom">
                @if ($basic_info->hotelInformationUrl !== '')
                <a href="{{ $basic_info->hotelInformationUrl }}" target="_blank">URL</a>
                @endif
            </div>
        </div>
        <div class="row">
            <div class="col-md-8">

            </div>
            <div class="col-md-4 text-right">
                <div>外観</div>
                <div><img src="{{ $basic_info->hotelImageUrl }}"></div>
                <div>内観</div>
                <div><img src="{{ $basic_info->roomImageUrl }}"></div>
                <div>Map</div>
                <div><img src="{{ $basic_info->hotelMapImageUrl }}"></div>
            </div>
        </div>
        <div><pre>{{ print_r($basic_info) }}</pre></div>

    </div>
@endsection
