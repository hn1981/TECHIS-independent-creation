@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1 class="m-1">こんにちは{{ $user->name }}さん</h1>
@stop

@section('content')

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <h5>{{ $user->name }}さんのラーメン情報</h5>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <thead>
                        <tr>
                            <th>食べたラーメンの数</th>
                            <th>訪問した店舗の数</th>
                            <th>行った都道府県の数</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $totalRamenCount ? $totalRamenCount : 'まだありません' }}</td>
                            <td>{{ $uniqueShopCount ? $uniqueShopCount : 'まだありません' }}</td>
                            <td>{{ $uniquePrefectureCount ? $uniquePrefectureCount : 'まだありません' }}</a></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <!--  -->
                <h5>訪問店舗ランキング（上位5件）</h5>
            </div>
            <div class="card-body table-responsive p-0 m-0 align-items-center">
                <table class="table table-hover text-nowrap">
                    <tbody>
                        <tr>
                            <td>
                                @foreach ($topShopVisits as $shopName => $groupCount)
                                <p>{{$loop->iteration}}位　{{ $shopName }} : {{ $groupCount }}回</p>
                                @endforeach
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-6">
        <div class="card">
            <div class="card-header d-flex align-items-center">
                <!--  -->
                <h5>訪問都道府県ランキング（上位5件）</h5>
            </div>
            <div class="card-body table-responsive p-0">
                <table class="table table-hover text-nowrap">
                    <tbody>
                        <tr>
                            <td>
                                @foreach ($prefectureShopCounts as $prefectureName => $groupCount)
                                <p>{{$loop->iteration}}位　{{ $prefectureName }} : {{ $groupCount }}回</p>
                                @endforeach
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@stop

@section('css')
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
