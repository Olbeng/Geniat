<div class="container">
    <!-- Outer Row -->
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">¡Actualizar usuario! <b><?= $res['nombre']." ".$res['apellido']; ?></b></h1>
                        </div>
                        <input type="hidden" id="id_register" value="<?= $res['id']?>">
                        <form class="user" name="formRegisterEdit" id="formRegisterEdit" autocomplete="off">
                            <div class="form-group">
                                <input type="text" class="form-control" id="Nombre" name="Nombre" placeholder="Nombre Completo" value="<?= $res['nombre'] ?>">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="Apellido" name="Apellido" placeholder="Apellidos" value="<?= $res['apellido'] ?>">
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" id="Correo" name="Correo" placeholder="Dirección de correo" value="<?= $res['correo'] ?>">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" id="Password" name="Password" placeholder="Nueva Contraseña">
                            </div>
                            <div class="form-group">
                                <select name="Rol" id="Rol" class="form-control">
                                    <option value="" <?= $res['rol'] == "Rol básico" ? "selected" : "";  ?> disabled>Selección de Rol</option>
                                    <option value="Rol básico" <?= $res['rol'] == "Rol básico" ? "selected" : "";  ?>>Rol básico</option>
                                    <option value="Rol medio" <?= $res['rol'] == "Rol medio" ? "selected" : "";  ?>>Rol medio</option>
                                    <option value="Rol medio alto" <?= $res['rol'] == "Rol medio alto" ? "selected" : "";  ?>>Rol medio alto</option>
                                    <option value="Rol alto medio" <?= $res['rol'] == "Rol alto medio" ? "selected" : "";  ?>>Rol alto medio</option>
                                    <option value="Rol alto" <?= $res['rol'] == "Rol alto" ? "selected" : "";  ?>>Rol alto</option>
                                </select>
                            </div>
                            <input type="submit" value="Actualizar Registro" class="btn btn-primary btn-user btn-block">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>