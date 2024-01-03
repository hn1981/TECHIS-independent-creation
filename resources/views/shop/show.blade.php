@extends('adminlte::page')

@section('title', '店舗データ詳細')

@section('content_header')
    <h1>店舗データ詳細</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-10">
            <div class="card card-primary">
                    <div class="card-body">
                        <div class="form-group">
                            <label>名前</label>
                            <p>{{ $shop->name }}</p>
                        </div>

                        <div class="form-group">
                            <label>県名</label>
                            <p>{{ $shop->prefecture->prefecture_name }}</p>
                        </div>

                        <div class="form-group">
                            <label>住所</label>
                            @if ($shop->address)
                            <p><a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($shop->address) }}" target="_blank">{{ $shop->address }}</a></p>
                            @else
                            <p>登録がありません</p>
                            @endif
                        </div>

                        <div class="form-group">
                            <label>店舗URL</label>
                            @if ($shop->url)
                            <p><a href="{{ $shop->url }}" target="_blank">{{ $shop->url }}</a></p>
                            @else
                            <p>登録がありません</p>
                            @endif
                        </div>

                        <div class="form-group">
                            <label>説明</label>
                            <p>{{ $shop->description ? $shop->description : '説明はありません' }}</p>
                        </div>

                        <div class="form-group">
                            <label for="images">画像</label>
                            <div class="d-flex flex-wrap">
                                @foreach ($shop->shopImages as $image)
                                    <div class="mr-3">
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
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <a href="{{ route('shops.index') }}" class="btn btn-secondary" style="margin-right: 10px;">店舗一覧に戻る</a>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary">前のページに戻る</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
