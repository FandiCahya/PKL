        <!-- Sidebar -->
        <ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <a class="sidebar-brand d-flex align-items-center justify-content-center" href="/dashboard">
                <div class="sidebar-brand-icon">
                    <span>GYM Booking</span>
                </div>
                <div class="sidebar-brand-text mx-3">{{ auth()->check() ? auth()->user()->nama : 'Guest' }}</div>
            </a>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <li class="nav-item active">
                <a class="nav-link" href="/dashboard">
                    <i class="fas fa-fw fa-tachometer-alt"></i>
                    <span>Dashboard</span></a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Nav Item - Tables -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('kelola_user') }}">
                    <i class="fas fa-fw fa-users"></i>
                    <span>Users</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('kelola_room') }}">
                    <i class="fas fa-fw fa-home"></i>
                    <span>Room</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('kelola_booking') }}">
                    <i class="fas fa-fw fa-clipboard"></i>
                    <span>Bookings</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('kelola_promo') }}">
                    <i class="fas fa-fw fa-bullhorn"></i>
                    <span>Promotion</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('blocked_dates.index') }}">
                    <i class="fas fa-calendar-times"></i>
                    <span>Block Date</span>
                </a>
            </li>
            

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->
