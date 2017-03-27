<nav class="navbar navbar-default">
    <div class="container-fluid">
        <!-- Brand and toggle get grouped for better mobile display -->
        
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="/">
            <i class="material-icons">spa</i>
            Агробизнес</a>
        </div>

        <!-- Navbar Right -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li class="active"><a href="/">
                <i class="material-icons">home</i>
                Домой</a></li>
                <li><a href="/blog">
                <i class="material-icons">create</i>
                Блог</a></li>
                <li><a href="/about">
                <i class="material-icons">group</i>
                О нас</a></li>
                <li><a href="/contact">
                <i class="material-icons">cast</i>
                Связь</a></li>
                <li class="dropdown">
                    @if (Auth::check())
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                    
                    @role('manager')      
                    <i class="material-icons">visibility</i>
                    @endrole
                    @role('trader')      
                    <i class="material-icons">person</i>
                    @endrole
                    {{ Auth::user()->name }}
                        <span class="caret"></span></a>
                    @else
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                    <i class="material-icons">lock_open</i>
                    Вход
                        <span class="caret"></span></a>
                    @endif

                    <ul class="dropdown-menu" role="menu">
                        @if (Auth::check())
                            @role('manager')
                                <li><a href="/admin">
                                <i class="material-icons">star</i>
                                Админ</a></li>
                            @endrole
                            @role('user')
                                <li><a href="/admin">
                                <i class="material-icons">fitness_center</i>
                                Кабинет</a></li>
                            @endrole
                            @role('trader')
                                <li><a href="/trader">
                                <i class="material-icons">work</i>
                                Портфель</a></li>
                                <li><a href="/trader">
                                <i class="material-icons">tune</i>
                                Профиль</a></li>
                            @endrole
                                <li><a href="/users/logout">
                                <i class="material-icons">exit_to_app</i>
                                Выход</a></li>
                        @else
                            <li><a href="/users/register">
                            <i class="material-icons">person_add</i>
                            Регистрация</a></li>
                            <li><a href="/users/login">
                            <i class="material-icons">vpn_key</i>
                            Вход</a></li>
                        @endif
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>