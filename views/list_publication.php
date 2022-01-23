<!-- Page Heading -->
<div class="d-sm-flex align-items-center justify-content-between mb-4">
    <h1 class="h3 mb-0 text-gray-800">Publicaciones</h1>
    <a href="publications/registro" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm">Nueva Publicación</a>
</div>
<div class="card shadow mb-4">
    <div class="card-header py-3">
        <h6 class="m-0 font-weight-bold text-primary">Lista de Usuarios</h6>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered text-center" id="dataTable" width="100%" cellspacing="0">
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Descripción</th>
                        <th>Fecha de Creación</th>
                        <th>Usuario</th>
                        <th>Rol</th>
                        <?php if ($_SESSION['user_rol'] == "Rol alto medio" || $_SESSION['user_rol'] == "Rol alto") { ?>
                            <th>Acciones</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($res as $key => $value) { ?>
                        <tr>
                            <td><?= $value['titulo'] ?></td>
                            <td><?= $value['descripcion'] ?></td>
                            <td><?= date("d/m/Y h:i a", strtotime($value['date_entered'])) ?></td>
                            <td><?= $value['nombre'] . " " . $value['apellido'] ?></td>
                            <td><?= $value['rol'] ?></td>
                            <?php if ($_SESSION['user_rol'] == "Rol alto medio") { ?>
                                <td>
                                    <a href="<?= ROUTE_SYSTEM ?>publications/show/<?= $value['id'] ?>"><i class="fa fa-edit"></i></a>
                                </td>
                            <?php } else if ($_SESSION['user_rol'] == "Rol alto") { ?>
                                <td>
                                    <a href="<?= ROUTE_SYSTEM ?>publications/show/<?= $value['id'] ?>"><i class="fa fa-edit"></i></a>
                                    <a href="#" class="delete_publication" data-id_publication='<?= $value['id'] ?>'><i class="fa fa-trash ml-2"></i></a>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>