@extends('adminlte::page')

@section('title', '商品一覧')

@section('content_header')
<div class="d-flex align-items-center">
    <i class="fa-solid fa-shop fa-2xl m-3"></i>
    <h1>店舗一覧</h1>
</div>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <!-- 検索フォーム -->
                <form class="" style="width: 40%;" action="@if (session('adminSession')) {{ route('shops.adminIndex') }} @else {{ route('shops.index') }} @endif" method="GET">
                    <div class="input-group flex-grow-1 d-flex align-items-center mr-2">
                        <input class="form-control flex-grow-1" type="text" name="search" placeholder="検索...">
                        <button class="btn btn-primary" type="submit">検索</button>
                    </div>
                </form>
                @if (!session('adminSession'))
                    <div class="input-group input-group-sm d-flex justify-content-end">
                        <div class="input-group-append">
                            <a href="{{ route('shops.create') }}" class="btn btn-default">店舗登録</a>
                        </div>
                    </div>
                @endif
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            @if (session('adminSession'))
                            <th>作成者</th>
                            <th>ID</th>
                            @endif
                            <th>店舗名</th>
                            <th>県名</th>
                            <th>住所</th>
                            <th>店舗URL</th>
                            <th>更新日</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shops as $shop)
                            <tr>
                                @if (session('adminSession'))
                                <td>{{ $shop->user->name }}</td>
                                <td>{{ $shop->id }}</td>
                                @endif
                                <td>{{ $shop->name }}</td>
                                <td>{{ $shop->prefecture->prefecture_name }}</td>
                                <td><a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($shop->address) }}" target="_blank">{{ mb_strlen($shop->address) > 15 ?  mb_substr($shop->address, 0, 15) . '...' : $shop->address }}</a></td>
                                <td><a href="{{ $shop->url }}" target="_blank">{{ mb_strlen($shop->url) > 30 ? mb_substr($shop->url, 0, 30) . '...' : $shop->url }}</a></td>
                                <td>{{ $shop->updated_at->format('Y/m/d') }}</td>
                                <td id="detail-form-{{ $shop->id }}" >
                                    <a href="{{ route('shops.show', ['shop' => $shop->id]) }}"><i class="fa-solid fa-file-lines fa-2xl" style="color: #bec1c6;"></i></a>
                                </td>
                                <td style="text-align: center;">
                                    <a href="{{ route('shops.edit', ['shop' => $shop->id]) }}"><i class="far fa-edit fa-2xl" style="color: #bec1c6;"></i></a>
                                </td>
                                <td style="text-align: center;">
                                    <form id="delete-form-{{ $shop->id }}" action="{{ route('shops.destroy', ['shop' => $shop->id]) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button style="border: none; background-color: transparent;" form="delete-form-{{ $shop->id }}" type="submit" class="delete-btn" data-id="{{ $shop->id }}"><i class="fa-solid fa-trash-can fa-2xl" style="color: #bec1c6;"></i></button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
    <!-- ページネイション -->
    {{ $shops->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </div>
</div>

<!-- モーダル -->
<!-- 削除確認 -->
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
                <p>このアクションを実行しますか？</p>
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
<!-- モーダルの実行内容 -->
<script>
$(document).ready(function()
{
    let currentAction;
    // 削除ボタンを押した場合
    $('.delete-btn').click(function(e) {
        e.preventDefault(); // 削除機能の実行を停止
        let itemId = $(this).data('id');
        currentAction = { type: 'delete', itemId: itemId };  // 削除アクションを指定
        $('#confirmModal').modal('show'); //モーダル表示
    });

    $('#confirmAction').click(function() {
        if (currentAction.type === 'delete' && currentAction.itemId) {
            let formId = "delete-form-" + currentAction.itemId; // 対応するフォームのIDを構築
        $('#' + formId).submit(); // フォームを送信
        }
    });

    // 名前のリンクを押した場合
    $('.name-link').click(function(e) {
        e.preventDefault(); // リンク先への遷移を停止
        let itemDetail = $(this).data('detail'); //foreachループで取得した$item->detailの情報をitemDetailに代入
        $('#detailModal .modal-body').text(itemDetail); //モーダルのmodal-body内にitemDetailを表示→modal-bodyには無記入でOK
        $('#detailModal').modal('show'); //モーダル表示
    });

});
</script>
@stop
