<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="<?= ROUTE_SYSTEM ?>">
        <div class="sidebar-brand-icon rotate-n-15">
            <i class="fas fa-laugh-wink"></i>
        </div>
        <div class="sidebar-brand-text mx-3"><?= APP_NAME ?></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Inicio -->
    <li class="nav-item active">
        <a class="nav-link" href="<?= ROUTE_SYSTEM ?>">
            <i class="fas fa-fw fa-home"></i>
            <span>Inicio</span></a>
    </li>
    <?php if ($_SESSION['user_rol'] != "Rol básico") { ?>
        <!-- Nav Item - Tables -->
        <li class="nav-item">
            <a class="nav-link" href="<?= ROUTE_SYSTEM ?>users">
                <i class="fas fa-users fa-fw text-gray-400"></i>
                <span>Usuarios</span></a>
        </li>

        <!-- Nav Item - Tables -->
        <li class="nav-item">
            <a class="nav-link" href="<?= ROUTE_SYSTEM ?>publications">
                <i class="fas fa-envelope fa-fw text-gray-400"></i>
                <span>Publicaciones</span></a>
        </li>
    <?php } ?>
    <!-- Nav Item - Tables -->
    <li class="nav-item">
        <a class="nav-link" href="#" data-toggle="modal" data-target="#logoutModal">
            <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
            <span>Cerrar Sesión</span></a>
    </li>

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->