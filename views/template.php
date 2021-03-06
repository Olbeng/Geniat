<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title><?= APP_NAME ?></title>

    <!-- Custom fonts for this template-->
    <link href="<?= ROUTE_SYSTEM ?>public/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    <!-- Custom styles for this template-->
    <link href="<?= ROUTE_SYSTEM ?>public/css/sb-admin-2.css" rel="stylesheet">

</head>

<body id="page-top">
    
    <input type="hidden" id="route" value="<?= ROUTE_SYSTEM ?>">
    <!-- Page Wrapper -->
    <div id="wrapper">

        <?php isset($data['token']) && $data['token'] != "" ? include VIEW_ROUTE . 'side_menu.php' : ''; ?>

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">
            <!-- Main Content -->
            <div id="content">
                <?php isset($data['token']) && $data['token'] != "" ? include VIEW_ROUTE . 'top_menu.php' : ''; ?>
                <!-- Begin Page Content -->
                <div class="container-fluid">
                    <?php include isset($data['view']) ? VIEW_ROUTE . $data['view'] . '.php' : VIEW_ROUTE . 'list_user.php'; ?>

                </div>
                <!-- /.container-fluid -->

            </div>
            <!-- End of Main Content -->
            <?php isset($data['token']) && $data['token'] != "" ? include VIEW_ROUTE . 'footer.php' : ''; ?>

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Listo para salir?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">??</span>
                    </button>
                </div>
                <div class="modal-body">Seleccione "Cerrar sesi??n" a continuaci??n si est?? listo para finalizar su sesi??n actual.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancelar</button>
                    <a class="btn btn-primary" href="<?= ROUTE_SYSTEM ?>users/logout">Cerrar Sesi??n</a>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap core JavaScript-->
    <script src="<?= ROUTE_SYSTEM ?>public/vendor/jquery/jquery.min.js"></script>
    <script src="<?= ROUTE_SYSTEM ?>public/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <!-- Core plugin JavaScript-->
    <script src="<?= ROUTE_SYSTEM ?>public/vendor/jquery-easing/jquery.easing.min.js"></script>
    <!-- Custom scripts for all pages-->
    <script src="<?= ROUTE_SYSTEM ?>public/js/bootstrap-notify.min.js"></script>
    <!-- jQuery Validate Form -->
    <script src="<?= ROUTE_SYSTEM ?>public/js/jquery.validate.min.js"></script>
    <!-- jQuery Form -->
    <script src="<?= ROUTE_SYSTEM ?>public/js/jquery.form.js"></script>
    <script src="<?= ROUTE_SYSTEM ?>public/js/sb-admin-2.min.js"></script>
    <script src="<?= ROUTE_SYSTEM ?>public/js/user.js"></script>
    <script src="<?= ROUTE_SYSTEM ?>public/js/publication.js"></script>


</body>

</html>