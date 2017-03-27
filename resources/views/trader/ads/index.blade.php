@extends('master')
@section('title', 'Все Объявления')
@section('content')

    <div class="container col-md-8 col-md-offset-2">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h2> Все Объявления </h2>
            </div>
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif
            @if ($ads->isEmpty())
                <p> Нет Объявлений! </p>
            @else
                <table class="table">
                    <thead>
                    <tr>
                        <th>ID</th>
                        <th>Title</th>
                        <th>Slug</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($ads as $ad)
                        <tr>
                            <td>{!! $ad->id !!}</td>
                            <td>
                                <a href="{!! action('Trader\AdsController@edit', $ad->id) !!}">{!! $ad->title !!} </a>
                            </td>
                            <td>{!! $ad->slug !!}</td>
                            <td>{!! $ad->created_at !!}</td>
                            <td>{!! $ad->updated_at !!}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>

@endsection