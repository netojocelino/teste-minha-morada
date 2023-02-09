<nav class="d-flex justify-content-center py-3">
    <ul class="nav nav-pills">
        @auth
            <li class="nav-item">
                <a
                    class="nav-link {{ $page == 'home' ? 'active' : '' }}"
                    href="{{ url('/') }}"
                >
                        Home
                </a>
            </li>
        @endauth
        <li class="nav-item">
            <a
                class="nav-link {{ $page == 'login' ? 'active' : '' }}"
                href="{{ url('/login') }}"
            >
                    Login
            </a>
        </li>
        <li class="nav-item">
            <a
                class="nav-link {{ $page == 'signup' ? 'active' : '' }}"
                href="{{ url('/signup') }}"
            >
                    Cadastrar
            </a>
        </li>
        <li class="nav-item">
            <a
                class="nav-link {{ $page == 'forgot-password' ? 'active' : '' }}"
                href="{{ url('/forgot-password') }}"
            >
                    Recuperar Senha
            </a>
        </li>
        @auth
            <li class="nav-item">
                <a
                    class="nav-link"
                    href="{{ url('/logout') }}"
                >
                        Sair
                </a>
            </li>
        @endauth

    </ul>
</nav>
