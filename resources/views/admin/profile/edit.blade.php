{{-- layouts/admin.blade.phpを読み込む --}}
@extends('layouts.admin')


{{-- admin.blade.phpの@yield('title')に'プロファイルの新規作成'を埋め込む --}}
@section('title', 'プロファイルの変更')

{{-- admin.blade.phpの@yield('content')に以下のタグを埋め込む --}}
@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 mx-auto">
                <h2>プロファイルの変更</h2>
                <form action="{{ action('Admin\ProfileController@edit') }}"
                      method="post" enctype="multipart/form-data">
                    error_count={{ count($errors) }}
                    @if (count($errors) > 0)
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif

                    <div class="form-group row">
                        <label class="col-md-2" for="name">名前</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="name"
                                   value="{{ $profile_form->name }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2" for="gender">性別</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="gender"
                                   value="{{ $profile_form->gender }}">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label class="col-md-2" for="hobby">趣味</label>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="hobby"
                                   value="{{ $profile_form->name }}">
                        </div>
                    </div>

                    <div class="form-group row">
                        <label class="col-md-2" for="introduction">自己紹介</label>
                        <div class="col-md-10">
                            <textarea class="form-control" name="introduction" rows="5">{{ $profile_form->introduction }}</textarea>
                        </div>
                    </div>
                    {{ csrf_field() }}
                    <input type="hidden" name="id" value="{{ $profile_form->id }}">
                    <input type="submit" class="btn btn-primary" value="更新">
                </form>

                <div class="row mt-5">
                    <div class="col-md-4 mx-auto">
                        <h2>編集履歴</h2>
                        @if ($profile_form->histories !== null)
                            <ul class="list-group">
                                @foreach ($profile_form->histories as  $history)
                                    <li class="list-group-item">{{ $history->edited_at }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
