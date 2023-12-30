@extends('adminlte::page')

@section('title', '店舗登録')

@section('content_header')
    <h1>店舗登録</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-10">
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                       @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                       @endforeach
                    </ul>
                </div>
            @endif

            <div class="card card-primary">
                <form method="POST" action="{{ route('shops.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">名前</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="名前">
                        </div>

                        <div class="form-group">
                            <label for="prefectureName">県名</label>
                            <select id="prefectureName" class="form-control" name="prefecture_id">

                                @foreach ($prefectures as $prefecture)
                                <option value="{{ $prefecture->id }}">{{ $prefecture->prefecture_name }}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="address">住所</label>
                                <input type="text" class="form-control" id="address" name="address" placeholder="住所">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="url">店舗URL</label>
                                <input type="url" class="form-control" id="url" name="url" placeholder="店舗URL">
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description">説明</label>
                            <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="images">画像（最大3枚）</label>
                            <input type="file" class="form-control" id="images" name="images[]" multiple>
                        </div>
                    </div>

                    <!-- 直前のURLを隠しフィールドで送信 -->
                    <input type="hidden" name="previousUrl" value="{{ $previousUrl }}">

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary mr-3">登録</button>
                        <a href="{{ route('shops.index') }}" class="btn btn-secondary">店舗一覧に戻る</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
<script>
    // レーティングのスライダーを動かすと下の数字が変わる
    $(document).ready(function() {
        $('#rating').on('input change', function() {
            $('#ratingValue').text($(this).val());
        });
    });
</script>

@stop
