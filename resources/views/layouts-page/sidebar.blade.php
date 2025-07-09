<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/adminlte" class="brand-link text-center">
        <span class="brand-text font-weight-bold">AGUS MART</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
        <!-- Sidebar Menu -->
        <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                <!-- DASHBOARD -->
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-tachometer-alt"></i>
                        <p>Dashboard</p>
                    </a>
                </li>

                <!-- DATA MASTER -->
                <li class="nav-header">DATA MASTER</li>
                <li
                    class="nav-item {{ request()->routeIs('barang.index', 'jenis-barang.index', 'satuan-barang.index') ? 'menu-open' : '' }}">
                    <a href="#"
                        class="nav-link {{ request()->routeIs('barang.index', 'jenis-barang.index', 'satuan-barang.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-box"></i>
                        <p>
                            Barang
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>
                    <ul class="nav nav-treeview">
                        <li class="nav-item">
                            <a href="{{ route('barang.index') }}"
                                class="nav-link {{ request()->routeIs('barang.index') ? 'active' : '' }}">
                                <i class="fas fa-tag nav-icon"></i>
                                <p>Nama Barang</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('jenis-barang.index') }}"
                                class="nav-link {{ request()->routeIs('jenis-barang.index') ? 'active' : '' }}">
                                <i class="fas fa-th-list nav-icon"></i>
                                <p>Jenis Barang</p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{ route('satuan-barang.index') }}"
                                class="nav-link {{ request()->routeIs('satuan-barang.index') ? 'active' : '' }}">
                                <i class="fas fa-ruler-combined nav-icon"></i>
                                <p>Satuan Barang</p>
                            </a>
                        </li>
                    </ul>
                </li>

                <!-- TRANSAKSI -->
                <li class="nav-header">TRANSAKSI</li>
                <li class="nav-item">
                    <a href="{{ route('barang-masuk.index') }}"
                        class="nav-link {{ request()->routeIs('barang-masuk.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-arrow-down"></i>
                        <p>Barang Masuk</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('barang-keluar.index') }}"
                        class="nav-link {{ request()->routeIs('barang-keluar.index') ? 'active' : '' }}">
                        <i class="nav-icon fas fa-arrow-up"></i>
                        <p>Barang Keluar</p>
                    </a>
                </li>

                <!-- LAPORAN -->
                <li class="nav-header">LAPORAN</li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-clipboard-list"></i>
                        <p>Stok Barang</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file-import"></i>
                        <p>Laporan Barang Masuk</p>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="#" class="nav-link">
                        <i class="nav-icon fas fa-file-export"></i>
                        <p>Laporan Barang Keluar</p>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
</aside>
