@extends('master')
@section('title', 'Кабинет трейдера')

@section('content')

    <div class="container">
        <div class="row banner">

            <div class="col-md-12">

                <div class="list-group">
                    
                    <div class="list-group-item">
                        <div class="row-action-primary">
                            <i class="material-icons">face</i>
                        </div>
                        <div class="row-content">
                            <div class="action-secondary"><i class="material-icons">account_box</i></div>
                            <h4 class="list-group-item-heading">Кабинет {{ Auth::user()->name }}</h4>
                        </div>
                    </div>
                    <div class="list-group-item">
                        <div class="row-action-primary">
                            <i class="material-icons">face</i>
                        </div>
                        <div class="row-content">
                            <div class="action-secondary"><i class="material-icons">account_box</i></div>
                            <h4 class="list-group-item-heading">Профиль</h4>
                            <a href="/admin/users" class="btn btn-default btn-raised">Изменить</a>
                        </div>
                    </div>
                    <div class="list-group-separator"></div>
                    <div class="list-group-item">
                        <div class="row-action-primary">
                            <i class="material-icons">content_copy</i>
                        </div>
                        <div class="row-content">
                            <div class="action-secondary"><i class="material-icons">border_color</i></div>
                            <h4 class="list-group-item-heading">Управление Объявлениями</h4>
                            <a href="/trader/ads" class="btn btn-default btn-raised">Мои Объявления</a>
                            <a href="/trader/ads/create" class="btn btn-primary btn-raised">Создать Объявление</a>
                        </div>
                    </div>
                    <div class="list-group-separator"></div>

                    <div class="list-group-item">
                    <div class="row-action-primary">
                        <i class="material-icons">format_list_bulleted</i>
                    </div>
                    <div class="row-content">
                        <div class="action-secondary"><i class="material-icons">format_list_bulleted</i></div>
                        <h4 class="list-group-item-heading">Управление Категориями</h4>
                        <a href="/trader/categories" class="btn btn-default btn-raised">Все Категории</a>
                        <a href="/trader/categories/create" class="btn btn-primary btn-raised">Создать Категорию</a>
                    </div>
                </div>
                <div class="list-group-separator"></div>
                </div>

            </div>

        </div>
    </div>

@endsection