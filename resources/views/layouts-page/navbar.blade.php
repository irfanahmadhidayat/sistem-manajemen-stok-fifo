<nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">
        <!-- Notifications Dropdown Menu -->
        <li class="nav-item dropdown" id="notification-dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#" id="notification-bell">
                <i class="far fa-bell fa-lg"></i>
                <span class="badge badge-danger navbar-badge" id="notification-count">0</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" id="notification-menu">
                <span class="dropdown-item dropdown-header" id="notification-header">
                    <span id="notification-total">0</span> Notifikasi
                </span>
                <div class="dropdown-divider"></div>
                <div id="notification-list">
                    <!-- Notifications will be loaded here -->
                </div>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer" data-toggle="modal"
                    data-target="#allNotificationsModal" id="see-all-notifications">Lihat Semua Notifikasi</a>
            </div>
        </li>

        <!-- User Dropdown Menu -->
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="fas fa-user fa-lg"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item">
                        <i class="fas fa-sign-out-alt mr-2"></i> Log Out
                    </button>
                </form>
            </div>
        </li>
    </ul>
</nav>


<!-- Modal Notifikasi -->
<div class="modal fade" id="allNotificationsModal" tabindex="-1" role="dialog" aria-labelledby="allNotificationsLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="allNotificationsLabel">Semua Notifikasi</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Tutup">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="all-notifications-content">
                <!-- Isi notifikasi lengkap akan dimuat lewat JS -->
                <p class="text-center text-muted">Memuat notifikasi...</p>
            </div>
        </div>
    </div>
</div>
