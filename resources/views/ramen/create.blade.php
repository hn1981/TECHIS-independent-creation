@extends('adminlte::page')

@section('title', 'ラーメン登録')

@section('content_header')
    <h1>ラーメン登録</h1>
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
                <form method="POST" action="{{ route('ramens.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">名前</label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="名前">
                        </div>

                        <div class="form-group">
                            <div class=" d-flex align-items-center">
                                <label for="shopName">店舗名</label>
                                <p style="margin-bottom: 8px;" class="ml-3 mr-2">新規登録はこちらから</p>
                                <a href="{{ route('shops.create') }}" class="btn btn-default btn-sm" style="margin-bottom: 8px;">店舗登録</a>
                            </div>

                            <select id="shopName" class="select2 form-control" name="shop_id">

                                @foreach ($shops as $shop)
                                <option value="{{ $shop->id }}">{{ $shop->name }}</option>
                                @endforeach

                            </select>
                        </div>

                        <div class="form-group">
                            <label for="description">説明</label>
                            <textarea id="description" name="description" class="form-control" rows="3"></textarea>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="visitDate">訪問日</label>
                                <input type="date" class="form-control" id="visitDate" name="eating_date">
                            </div>

                            <div class="form-group col-md-6">
                                <label for="rating">レーティング</label>
                                <input type="range" class="custom-range" id="rating" name="rating" min="1" max="5">
                                <span id="ratingValue">3</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="comment">レビュー</label>
                            <textarea id="comment" name="comment" class="form-control" rows="10"></textarea>
                        </div>

                        <div class="form-group">
                            <label for="images">画像（最大3枚）</label>
                            <input type="file" class="form-control" id="images" name="images[]" multiple>
                        </div>

                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary mr-3">登録</button>
                        <a href="{{ route('ramens.index') }}" class="btn btn-secondary">商品一覧に戻る</a>
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

<script>
    $(document).ready(function() {
        $('.select2').select2();
    });
</script>

@stop
