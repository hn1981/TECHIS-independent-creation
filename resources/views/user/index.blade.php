@extends('adminlte::page')

@section('title', 'ユーザー一覧')

@section('content_header')
    <h1>ユーザー一覧</h1>
@stop

@section('content')






<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <!-- 検索フォーム -->
                <form class="" style="width: 28.75%;" action="{{ route('users.index') }}" method="GET">
                    <div class="input-group flex-grow-1 d-flex align-items-center mr-2">
                        <input class="form-control flex-grow-1" type="text" name="search" placeholder="検索...">
                        <button class="btn btn-primary" type="submit">検索</button>
                    </div>
                </form>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>ユーザー名</th>
                            <th>メールアドレス</th>
                            <th>所属ID</th>
                            <th></th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $user->role }}</td>
                                <td style="text-align: center;">
                                    <a href="{{ route('users.edit', ['user' => $user->id]) }}"><i class="far fa-edit fa-2xl" style="color: #bec1c6;"></i></a>
                                </td>
                                <td style="text-align: center;">
                                    <form id="delete-form-{{ $user->id }}" action="{{ route('users.destroy', ['user' => $user->id]) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    </form>
                                    <button class="delete-btn" style="border: none; background-color: transparent;" form="delete-form-{{ $user->id }}" type="submit" data-id="{{ $user->id }}"><i class="fa-solid fa-trash-can fa-2xl" style="color: #bec1c6;"></i></button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
    <!-- ページネイション -->
    {{ $users->links('pagination::bootstrap-4') }}
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
