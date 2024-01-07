@extends('adminlte::page')

@section('title', 'プロフィール変更')

@section('content_header')
    @if (session('adminSession'))
    <div class="d-flex align-items-center">
    <i class="fas fa-fw fa-user fa-2xl m-3"></i>
        <h1>ユーザーデータ変更</h1>
    </div>
    @else
    <div class="d-flex align-items-center">
    <i class="fas fa-fw fa-lock fa-2xl m-3"></i>
    <h1>プロフィール変更</h1>
    </div>
    @endif
@stop

@section('content')
    <div class="row">
        <div class="col-md-10">
            @include('common.errors')
            <div class="card card-primary">
                <form id="edit-form" method="POST" action="{{ route('users.update', ['user' => $user->id]) }}">
                    @csrf
                    @method('PUT')
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">名前</label>
                            <input type="text" class="form-control" id="name" name="name" value="{{ $user->name }}">
                        </div>

                        <div class="form-group">
                            <label for="email">メールアドレス</label>
                            <input type="text" class="form-control" id="email" name="email" value="{{ $user->email }}">
                        </div>

                        @if (session('adminSession'))
                        <div class="form-group">
                            <label for="role">所属ID</label>
                            <input type="text" class="form-control" id="role" name="role" value="{{ $user->role }}">
                        </div>
                        @endif

                        <div class="form-group">
                            <label for="password">新しいパスワード（空欄の場合パスワードは変更されません）</label>
                            <input type="password" class="form-control" id="password" name="password" value="">
                        </div>

                        <div class="form-group">
                            <label for="password">新しいパスワード（確認用）</label>
                            <input type="password" class="form-control" id="password" name="password_confirmation" value="">
                        </div>
                    </div>

                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary edit-btn mr-3">編集</button>
                        <a href="{{ route('home') }}" class="btn btn-secondary">ホームに戻る</a>
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
        document.getElementById('edit-form').submit(); //  モーダルの実行ボタンが押下された場合は編集実行
        }
    });
    });
</script>
@stop
