@extends('master')
@section('name', 'Register')

@section('content')
    <div class="container col-md-6 col-md-offset-3">
        <div class="well well bs-component">

            <form class="form-horizontal" method="post">

                @foreach ($errors->all() as $error)
                    <p class="alert alert-danger">{{ $error }}</p>
                @endforeach

                 {!! csrf_field() !!}

                <fieldset>
                    <legend>Регистрация аккаунта</legend>
                    <div class="form-group">
                        <label for="name" class="col-lg-2 control-label">Имя</label>
                        <div class="col-lg-10">
                            <input type="text" class="form-control" id="name" placeholder="Имя" name="name" value="{{ old('name') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="email" class="col-lg-2 control-label">Email</label>
                        <div class="col-lg-10">
                            <input type="email" class="form-control" id="email" placeholder="your@gmail.com" name="email" value="{{ old('email') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="phone" class="col-lg-2 control-label">Телефон</label>
                        <div class="col-lg-10">
                            <input type="phone" class="form-control" id="phone" placeholder="0505555555" name="phone" value="{{ old('phone') }}">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="col-lg-2 control-label">Пароль</label>
                        <div class="col-lg-10">
                            <input type="password" class="form-control"  name="password">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="col-lg-2 control-label">Повторите пароль</label>
                        <div class="col-lg-10">
                            <input type="password" class="form-control"  name="password_confirmation">
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-lg-10 col-lg-offset-2">
                            <button type="reset" class="btn btn-default">Отмена</button>
                            <button type="submit" class="btn btn-primary">Отправить</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
@endsection