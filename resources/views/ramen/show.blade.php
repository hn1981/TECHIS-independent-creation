@extends('adminlte::page')

@section('title', 'ラーメンデータ詳細')

@section('content_header')
    <h1>ラーメンデータ詳細</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-10">
            <div class="card card-primary">
                    <div class="card-body">
                        <div class="form-group">
                            <label>名前</label>
                            <p>{{ $ramen->name }}</p>
                        </div>

                        <div class="form-group">
                            <label>店舗名</label>
                            <p>{{ $ramen->shop->name }}</p>
                        </div>

                        <div class="form-group">
                            <label>説明</label>
                            <p>{{ $ramen->description ? $ramen->description : '説明はありません' }}</p>
                        </div>

                        <div class="form-group">
                            <label>訪問日</label>
                            <p>{{ $ramen->eating_date }}</p>
                        </div>

                        @php
                        $userReview = $ramen->reviews()->where('user_id', auth()->id())->first();
                        @endphp

                        <div class="form-group">
                            <label for="rating">レーティング</label>
                            <div class="d-flex flex-wrap">
                                <i class="fas fa-star" style="color: #ffdf0f;"></i>
                                <p class="ml-2">{{ $userReview->rating }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="comment">レビュー</label>
                            <p>{{ $userReview && $userReview->comment ? $userReview->comment : 'レビューはありません。' }}</p>
                        </div>

                        <div class="form-group">
                            <label for="images">画像</label>
                            <div class="d-flex flex-wrap">
                                @foreach ($ramen->ramenImages as $image)
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
                        <a href="{{ route('ramens.index') }}" class="btn btn-secondary">一覧に戻る</a>
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

    $(document).ready(function() {
        // 初期読み込みで現在のスライダーの値を表示
        $('#ratingValue').text($('#rating').val());

        // レーティングのスライダーを動かすと下の数字が変わる
        $('#rating').on('input change', function() {
            $('#ratingValue').text($(this).val());
        });
    });
</script>

@stop
