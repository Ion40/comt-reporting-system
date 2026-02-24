<header class="app-topbar topbar-active" id="header">
    <div class="page-container topbar-menu">
        <div class="d-flex align-items-center gap-2">

            <!-- Brand Logo -->
            <a href="{{ route("dashboard")  }}" class="logo">
                            <span class="logo-light">
                                <span class="logo-lg"><img src="{{asset("/images/logo_oficial.png")}}" alt="logo"></span>
                                <span class="logo-sm"><img src="{{asset("/images/logo_oficial.png")}}" alt="small logo"></span>
                            </span>

                <span class="logo-dark">
                                <span class="logo-lg"><img src="{{asset("/images/logo_oficial.png")}}" alt="dark logo"></span>
                                <span class="logo-sm"><img src="{{asset("/images/logo_oficial.png")}}" alt="small logo"></span>
                            </span>
            </a>

            <!-- Sidebar Menu Toggle Button -->
            <button class="sidenav-toggle-button px-2">
                <i class="ri-menu-5-line fs-24"></i>
            </button>

            <!-- Horizontal Menu Toggle Button -->
            <button class="topnav-toggle-button px-2" data-bs-toggle="collapse" data-bs-target="#topnav-menu-content">
                <i class="ri-menu-5-line fs-24"></i>
            </button>

            <!-- Topbar Page Title -->
            <div class="topbar-item d-none d-md-flex px-2">

                <div>
                    <h4 class="page-title fs-20 fw-semibold mb-0">Dashboard</h4>

                </div>


            </div>

        </div>

        <div class="d-flex align-items-center gap-2">
            <!-- User Dropdown -->
            <div class="topbar-item nav-user">
                <div class="dropdown">
                    <a class="topbar-link dropdown-toggle drop-arrow-none px-2" data-bs-toggle="dropdown"
                       data-bs-offset="0,25" type="button" aria-haspopup="false" aria-expanded="false">
                        <!-- todo: poner imagen de usuario -->
                        <img src="{{asset("/images/user.png")}}" width="32" class=" me-lg-2 d-flex"
                             alt="user-image">
                        <span class="d-lg-flex flex-column gap-1 d-none">
                                        <h5 class="my-0">{{ Auth::user()->nombre }}</h5>
                                    </span>
                        <i class="ri-arrow-down-s-line d-none d-lg-block align-middle ms-1"></i>
                    </a>
                    <div class="dropdown-menu dropdown-menu-end">
                        <!-- item-->
                        <div class="dropdown-header noti-title">
                            <h6 class="text-overflow m-0">Bienvenido !</h6>
                        </div>

                        <!-- item-->
                        <a href="javascript:void(0);" class="dropdown-item">
                            <i class="ri-account-circle-line me-1 fs-16 align-middle"></i>
                            <span class="align-middle">Mi Perfil</span>
                        </a>

                        <div class="dropdown-divider"></div>

                        <!-- item-->
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <a href="{{route('logout')}}" target="_blank"
                               onclick="event.preventDefault(); this.closest('form').submit();"
                               class="dropdown-item active fw-semibold text-danger">
                                <i class="ri-logout-box-line me-1 fs-16 align-middle"></i>
                                <span class="align-middle">Cerrar Sesión</span>
                            </a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
