@extends('adminlte::page')

@section('title', '商品一覧')

@section('content_header')
<div class="d-flex align-items-center">
    <i class="fa-solid fa-bowl-food fa-2xl m-3"></i>
    <h1>ラーメン一覧</h1>
</div>
    @stop

@section('content')






<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <!-- 検索フォーム -->
                <form class="" style="width: 40%;" action="@if (session('adminSession')) {{ route('ramens.adminIndex') }} @else {{ route('ramens.index') }} @endif" method="GET">
                    <div class="input-group flex-grow-1 d-flex align-items-center mr-2">
                        <input class="form-control flex-grow-1" type="text" name="search" placeholder="検索...">
                        <button class="btn btn-primary" type="submit">検索</button>
                    </div>
                </form>
                @if (!session('adminSession'))
                    <div class="input-group input-group-sm d-flex justify-content-end">
                        <div class="input-group-append">
                            <a href="{{ route('ramens.create') }}" class="btn btn-default">ラーメン登録</a>
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
                            <th>名前</th>
                            <th>県名</th>
                            <th>店舗名</th>
                            <th>訪問日</th>
                            <th>更新日</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($ramens as $ramen)
                            <tr>
                                @if (session('adminSession'))
                                <td>{{ $ramen->user->name }}</td>
                                <td>{{ $ramen->id }}</td>
                                @endif
                                <td>{{ $ramen->name }}</td>
                                <td>{{ optional(optional($ramen->shop)->prefecture)->prefecture_name ?? '未設定' }}</td>
                                <td><a href="{{ route('shops.show', ['shop' => $ramen->shop->id]) }}">{{ optional($ramen->shop)->name ?? '未設定' }}</a></td>
                                <td>{{ $ramen->eating_date }}</td>
                                <td>{{ $ramen->updated_at->format('Y/m/d') }}</td>
                                <td id="detail-form-{{ $ramen->id }}" >
                                    <a href="{{ route('ramens.show', ['ramen' => $ramen->id]) }}"><i class="fa-solid fa-file-lines fa-2xl" style="color: #bec1c6;"></i></a>
                                </td>
                                <td style="text-align: center;">
                                    <a href="{{ route('ramens.edit', ['ramen' => $ramen->id]) }}"><i class="far fa-edit fa-2xl" style="color: #bec1c6;"></i></a>
                                </td>
                                <td style="text-align: center;">
                                    <form id="delete-form-{{ $ramen->id }}" action="{{ route('ramens.destroy', ['ramen' => $ramen->id]) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    </form>
                                    <button class="delete-btn" style="border: none; background-color: transparent;" form="delete-form-{{ $ramen->id }}" type="submit" data-id="{{ $ramen->id }}"><i class="fa-solid fa-trash-can fa-2xl" style="color: #bec1c6;"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
    <!-- ページネイション -->
    {{ $ramens->links('pagination::bootstrap-4') }}
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
