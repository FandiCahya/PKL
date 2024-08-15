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
                <a class="nav-link" href="{{ route('instrukturs.index') }}">
                    <i class="fas fa-fw fa-chalkboard-teacher"></i>
                    <span>Instrukturs</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('kelola_room') }}">
                    <i class="fas fa-fw fa-home"></i>
                    <span>Room</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('time-slots.index') }}">
                    <i class="fas fa-fw fa-clock"></i>
                    <span>Time</span></a>
            </li>

            <li class="nav-item">
                <a class="nav-link" href="{{ route('kelola_promo') }}">
                    <i class="fas fa-fw fa-chalkboard"></i>
                    <span>Class</span></a>
            </li>

            {{-- <li class="nav-item">
                <a class="nav-link" href="{{ route('schedules.index') }}">
                    <i class="fas fa-fw fa-calendar-alt"></i>
                    <span>Schedule</span></a>
            </li> --}}

            <!-- Payments Nav Item -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('payments.index') }}">
                    <i class="fas fa-fw fa-credit-card"></i>
                    <span>Payments</span>
                </a>
            </li>

            <!-- Nav Item - Booking -->
            <li class="nav-item">
                <a class="nav-link" href="{{ route('kelola_booking') }}">
                    <i class="fas fa-fw fa-clipboard"></i>
                    <span>Booking</span></a>
            </li>

            {{-- <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBooking"
                    aria-expanded="true" aria-controls="collapseBooking">
                    <i class="fas fa-fw fa-clipboard"></i>
                    <span>Booking</span>
                </a>
                <div id="collapseBooking" class="collapse" aria-labelledby="headingBooking"
                    data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <a class="collapse-item" href="{{ route('kelola_booking') }}">Booking Room</a>
                        <a class="collapse-item" href="{{ route('booking_schedule.index') }}">Booking Schedule</a>
                    </div>
                </div>
            </li> --}}

            <!-- Nav Item - Blocking -->
            <li class="nav-item">
                <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseBlocking"
                    aria-expanded="false" aria-controls="collapseBlocking">
                    <i class="fas fa-fw fa-ban"></i>
                    <span>Blocking</span>
                </a>
                <div id="collapseBlocking" class="collapse" aria-labelledby="headingBlocking" data-parent="#accordionSidebar">
                    <div class="bg-white py-2 collapse-inner rounded">
                        <h6 class="collapse-header">Manage Blocking:</h6>
                        <a class="collapse-item" href="{{ route('blocked_dates.index') }}">Date & Time</a>
                        <a class="collapse-item" href="{{ route('blocked_tgl.index') }}">Date Only</a>
                    </div>
                </div>
            </li>
            
            

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <li class="nav-item">
                <a class="nav-link" href="{{ route('logs.index') }}">
                    <i class="fas fa-history"></i>
                    <span>Logs</span>
                </a>
            </li>

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>

        </ul>
        <!-- End of Sidebar -->
