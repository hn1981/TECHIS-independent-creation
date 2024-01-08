@extends('adminlte::page')

@section('title', '店舗データ編集')

@section('content_header')
<div class="d-flex align-items-center">
    <i class="fa-solid fa-shop fa-2xl m-3"></i>
    <h1>店舗データ編集</h1>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-md-10">
        @include('common.errors')
        <div class="card card-primary">
            <form id="edit-form" method="POST" action="{{ route('shops.update', ['shop' => $shop->id]) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="form-group">
                        <label for="shopName">名前</label>
                        <input type="text" class="form-control" id="shopName" name="name" value="{{ $shop->name }}">
                    </div>

                    <div class="form-group">
                        <label for="prefectureName">県名</label>
                        <select id="prefectureName" class="form-control" name="prefecture_id">
                            @foreach ($prefectures as $prefecture)
                            <option value="{{ $prefecture->id }}" {{ $shop->prefecture_id == $prefecture->id ? 'selected' : '' }}>{{ $prefecture->prefecture_name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="address">住所</label>
                            <input type="text" class="form-control" id="address" name="address" value="{{ $shop->address }}">
                        </div>

                        <div class="form-group col-md-6">
                            <label for="url">店舗URL</label>
                            <input type="url" class="form-control" id="url" name="url" value="{{ $shop->url }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="description">説明</label>
                        <textarea id="description" name="description" class="form-control" rows="3">{{ $shop->description }}</textarea>
                    </div>

                    <div class="form-group">
                        <label for="images">既存の画像（最大3枚）</label>
                        @foreach ($shop->shopImages as $image)
                            <div>
                                <img src="data:image/{{ $image->mime_type }};base64, {{ $image->image }}" width="100" height="100" data-toggle="modal" data-target="#imageModal-{{ $image->id }}">
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
                                                        <img src="data:image/{{ $image->mime_type }};base64, {{ $image->image }}" class="img-fluid">
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
                    <a href="{{ route('shops.index') }}" class="btn btn-secondary">店舗一覧に戻る</a>
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
