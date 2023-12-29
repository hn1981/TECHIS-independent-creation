@extends('adminlte::page')

@section('title', 'ラーメンデータ編集')

@section('content_header')
    <h1>ラーメンデータ編集</h1>
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
                <form id="edit-form" method="POST" action="{{ route('ramens.update', ['ramen' => $ramen->id]) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">名前</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $ramen->name }}">
                        </div>

                        <div class="form-group">
                            <label for="shopName">店舗名</label>
                            <select id="shopName" class="form-control" name="shop_id">
                                @foreach ($shops as $shop)
                                <option value="{{ $shop->id }}" {{ $ramen->shop_id == $shop->id ? 'selected' : '' }}>{{ $shop->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="description">説明</label>
                            <textarea id="description" name="description" class="form-control" rows="3">{{ $ramen->description }}</textarea>
                        </div>

                        <div class="row">
                            <div class="form-group col-md-6">
                                <label for="visitDate">訪問日</label>
                                <input type="date" class="form-control" id="visitDate" name="eating_date" value="{{ $ramen->eating_date }}">
                            </div>

                            @php
                            $userReview = $ramen->reviews()->where('user_id', auth()->id())->first();
                            @endphp

                            <div class="form-group col-md-6">
                                <label for="rating">レーティング</label>
                                <input type="range" class="custom-range" id="rating" name="rating" min="1" max="5" value="{{ $userReview->rating }}">
                                <span id="ratingValue">{{ $userReview->rating }}</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="comment">レビュー</label>
                            <textarea id="comment" name="comment" class="form-control" rows="10">{{ $userReview ? $userReview->comment : '' }}</textarea>
                        </div>

                        <div class="form-group">
                            <label for="images">既存の画像（最大3枚）</label>
                            @foreach ($ramen->ramenImages as $image)
                                <div>
                                    <img src="{{ asset('storage/' . $image->image_path) }}" width="100" height="100" data-toggle="modal" data-target="#imageModal-{{ $image->id }}">
                                        <!-- モーダル本体 -->
                                            <div class="modal fade" id="imageModal-{{ $image->id }}" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel-{{ $image->id }}" aria-hidden="true">
                                                <div class="modal-dialog modal-lg" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="imageModalLabel-{{ $image->id }}"></h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <!-- 原寸大画像を表示 -->
                                                            <img src="{{ asset('storage/' . $image->image_path) }}" class="img-fluid">
                                                        </div>
                                                        <div class="modal-footer" style="border: none;">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">確認</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                    <label>
                                        <input type="checkbox" name="delete_images[]" value="{{ $image->id }}"> 削除する
                                    </label>
                                </div>
                            @endforeach
                        </div>

                        <div class="form-group">
                            <label for="images">新しい画像を追加</label>
                            <input type="file" class="form-control" id="images" name="images[]" multiple>
                        </div>



                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary edit-btn mr-3">編集</button>
                        <a href="{{ route('ramens.index') }}" class="btn btn-secondary">商品一覧に戻る</a>
                    </div>
                </form>
            </div>
        </div>
    </div>

<!-- モーダル -->
<div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header" style="border: none;">
                <h5 class="modal-title" id="modalLabel">確認</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
            このアクションを実行しますか？
            </div>
            <div class="modal-footer" style="border: none;">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">キャンセル</button>
                <button type="button" class="btn btn-primary" id="confirmAction">実行</button>
            </div>
        </div>
    </div>
</div>


@stop

@section('css')
@stop

@section('js')
<script>

    $(document).ready(function() {
        // 初期読み込みで現在のスライダーの値を表示
        $('#ratingValue').text($('#rating').val());

        // レーティングのスライダーを動かすと下の数字が変わる
        $('#rating').on('input change', function() {
            $('#ratingValue').text($(this).val());
        });
    });
</script>

<script>
$(document).ready(function() {
    let currentAction;

    $('.edit-btn').click(function(e) {
        e.preventDefault(); // 編集機能の実行を停止
        currentAction = 'update';  // 編集アクションを指定
        $('#confirmModal').modal('show'); //モーダル表示
    });

    $('#confirmAction').click(function()
    {
        if (currentAction === 'update') {
        document.getElementById('edit-form').submit(); //  モーダルの実行ボタンが押下された場合は削除
        }
    });
    });
</script>
@stop
