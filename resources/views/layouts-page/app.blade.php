<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Agus Mart</title>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="{{ asset('plugins/fontawesome-free/css/all.min.css') }}">

    <!-- DataTables -->
    <link rel="stylesheet" href="{{ asset('plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="{{ asset('plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css') }}">

    <!-- Select2 -->
    <link rel="stylesheet" href="{{ asset('plugins/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css') }}">

    <!-- Theme style -->
    <link rel="stylesheet" href="{{ asset('dist/css/adminlte.min.css') }}">

    <style>
        html,
        body {
            height: 100%;
        }

        .wrapper {
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .content-wrapper {
            flex: 1 0 auto;
        }

        footer.main-footer {
            flex-shrink: 0;
        }
    </style>
</head>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">

        <!-- Navbar -->
        @include('layouts-page.navbar')
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        @include('layouts-page.sidebar')
        <!-- /.Main Sidebar Container -->

        <!-- Content Wrapper. Contains page content -->
        @yield('content')
        <!-- /.content-wrapper -->

        <!-- Main Footer -->
        @include('layouts-page.footer')
        <!-- /.Main Footer -->
    </div>
    <!-- ./wrapper -->

    <!-- REQUIRED SCRIPTS -->

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/jquery.min.js') }}"></script>

    <!-- Bootstrap 4 -->
    <script src="{{ asset('plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

    <!-- DataTables  & Plugins -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

    <!-- SweetAlert2 -->
    <script src="{{ asset('plugins/sweetalert2/sweetalert2.min.js') }}"></script>

    <!-- Select2 -->
    <script src="{{ asset('plugins/select2/js/select2.full.min.js') }}"></script>

    <!-- AdminLTE App -->
    <script src="{{ asset('dist/js/adminlte.min.js') }}"></script>

    <!-- Script Tambahan -->
    @yield('script')

    <!-- Script Notifikasi -->
    <script>
        $(document).ready(function() {
            // Load notifications on page load
            loadNotifications();

            // Set interval to check for new notifications every 30 seconds
            setInterval(loadNotifications, 30000);

            // Load notifications when bell is clicked
            $('#notification-bell').on('click', function() {
                loadNotifications();
            });
        });

        function loadNotifications() {
            $.ajax({
                url: '{{ route('notifications.get') }}',
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    updateNotificationUI(response);
                },
                error: function(xhr, status, error) {
                    console.error('Error loading notifications:', error);
                }
            });
        }

        function updateNotificationUI(data) {
            const count = data.count;
            const notifications = data.notifications;

            // Update notification count
            $('#notification-count').text(count);
            $('#notification-total').text(count);

            // Update badge visibility
            if (count > 0) {
                $('#notification-count').show();
                // Change bell icon to filled when there are notifications
                $('#notification-bell i').removeClass('far').addClass('fas');
            } else {
                $('#notification-count').hide();
                // Change bell icon to outline when no notifications
                $('#notification-bell i').removeClass('fas').addClass('far');
            }

            // Update notification list
            const notificationList = $('#notification-list');
            notificationList.empty();

            if (notifications.length === 0) {
                notificationList.append(`
            <div class="dropdown-item text-center text-muted">
                <i class="fas fa-check-circle mr-2"></i>
                Tidak ada notifikasi
            </div>
        `);
            } else {
                // Limit to show only first 10 notifications
                const limitedNotifications = notifications.slice(0, 10);

                limitedNotifications.forEach(function(notification, index) {
                    const notificationItem = createNotificationItem(notification);
                    notificationList.append(notificationItem);

                    // Add divider except for last item
                    if (index < limitedNotifications.length - 1) {
                        notificationList.append('<div class="dropdown-divider"></div>');
                    }
                });
            }

            // Update page title with notification count
            updatePageTitle(count);
        }

        function createNotificationItem(notification) {
            const colorClass = getColorClass(notification.color);

            return `
        <a href="#" class="dropdown-item notification-item" data-type="${notification.type}">
            <i class="${notification.icon} mr-2 ${colorClass}"></i>
            <div class="media">
                <div class="media-body">
                    <h6 class="dropdown-item-title">
                        ${notification.title}
                    </h6>
                    <p class="text-sm text-muted mb-0">${notification.message}</p>
                </div>
            </div>
        </a>
    `;
        }

        function getColorClass(color) {
            switch (color) {
                case 'danger':
                    return 'text-danger';
                case 'warning':
                    return 'text-warning';
                case 'info':
                    return 'text-info';
                case 'success':
                    return 'text-success';
                default:
                    return 'text-muted';
            }
        }

        function updatePageTitle(count) {
            const baseTitle = document.title.replace(/^\(\d+\)\s*/, '');

            if (count > 0) {
                document.title = `(${count}) ${baseTitle}`;
            } else {
                document.title = baseTitle;
            }
        }

        // Handle notification item click
        $(document).on('click', '.notification-item', function(e) {
            e.preventDefault();

            const notificationType = $(this).data('type');

            // Handle different notification types
            switch (notificationType) {
                case 'stock_empty':
                case 'stock_low':
                case 'stock_over':
                    // Redirect to inventory page
                    window.location.href = '{{ route('barang.index') }}';
                    break;
                case 'expiry_1day':
                case 'expiry_3days':
                case 'expiry_2weeks':
                case 'expiry_1month':
                case 'expired':
                    // Redirect to expiry report page
                    window.location.href = '{{ route('barang-masuk.index') }}';
                    break;
                default:
                    console.log('Unknown notification type:', notificationType);
            }
        });

        // Add CSS for better notification styling
        const notificationCSS = `
            <style>
            .notification-item {
                border-left: 3px solid transparent;
                transition: all 0.3s ease;
            }

            .notification-item:hover {
                background-color: #f8f9fa;
                border-left-color: #007bff;
            }

            .notification-item[data-type="stock_empty"],
            .notification-item[data-type="expiry_1day"],
            .notification-item[data-type="expired"] {
                border-left-color: #dc3545;
            }

            .notification-item[data-type="stock_low"],
            .notification-item[data-type="expiry_3days"],
            .notification-item[data-type="expiry_2weeks"] {
                border-left-color: #ffc107;
            }

            .notification-item[data-type="stock_over"],
            .notification-item[data-type="expiry_1month"] {
                border-left-color: #17a2b8;
            }

            #notification-count {
                animation: pulse 2s infinite;
            }

            @keyframes pulse {
                0% { transform: scale(1); }
                50% { transform: scale(1.1); }
                100% { transform: scale(1); }
            }

            .dropdown-menu-lg {
                max-height: 400px;
                overflow-y: auto;
            }

            .notification-item .media-body {
                padding-left: 0.5rem;
            }

            .notification-item .dropdown-item-title {
                font-size: 0.875rem;
                margin-bottom: 0.25rem;
            }
            </style>
            `;

        // Inject CSS
        $('head').append(notificationCSS);
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const notificationCount = document.getElementById('notification-count');
            const notificationTotal = document.getElementById('notification-total');
            const notificationList = document.getElementById('notification-list');
            const allNotificationsContent = document.getElementById('all-notifications-content');

            // Ambil notifikasi
            function loadNotifications() {
                fetch("{{ route('notifications.get') }}") // sesuaikan dengan route kamu
                    .then(res => res.json())
                    .then(data => {
                        const {
                            notifications,
                            count
                        } = data;

                        // Update badge
                        notificationCount.textContent = count;
                        notificationTotal.textContent = count;

                        // Update list dropdown
                        notificationList.innerHTML = notifications.slice(0, 5).map(n => `
                        <a href="#" class="dropdown-item text-${n.color}">
                            <i class="${n.icon} mr-2"></i> ${n.title}<br>
                            <small>${n.message}</small>
                        </a>
                        <div class="dropdown-divider"></div>
                    `).join('');

                        // Update modal
                        allNotificationsContent.innerHTML = notifications.length ? notifications.map(n => `
                        <div class="alert alert-${n.color} d-flex align-items-center">
                            <i class="${n.icon} mr-2"></i>
                            <div>
                                <strong>${n.title}</strong><br>
                                <span>${n.message}</span>
                            </div>
                        </div>
                    `).join('') : '<p class="text-center text-muted">Tidak ada notifikasi.</p>';
                    })
                    .catch(err => {
                        console.error('Gagal memuat notifikasi:', err);
                        allNotificationsContent.innerHTML =
                            '<p class="text-danger text-center">Gagal memuat notifikasi.</p>';
                    });
            }

            // Muat saat halaman dibuka
            loadNotifications();

            // Muat ulang saat modal dibuka
            document.getElementById('see-all-notifications').addEventListener('click', function() {
                loadNotifications();
            });
        });
    </script>

</body>

</html>
