<div class="container">
    <!-- Outer Row -->
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="p-5">
                        <div class="text-center">
                            <h1 class="h4 text-gray-900 mb-4">¡Nueva Cuenta!</h1>
                        </div>
                        <form class="user" name="formRegister" id="formRegister" autocomplete="off">
                            <div class="form-group">
                                <input type="text" class="form-control" id="Nombre" name="Nombre" placeholder="Nombre Completo">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" id="Apellido" name="Apellido" placeholder="Apellidos">
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control" id="Correo" name="Correo" placeholder="Dirección de correo">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control" id="Password" name="Password" placeholder="Contraseña">
                            </div>
                            <div class="form-group">
                                <select name="Rol" id="Rol" class="form-control">
                                    <option value="" selected="selected" disabled>Selección de Rol</option>
                                    <option value="Rol básico">Rol básico</option>
                                    <option value="Rol medio">Rol medio</option>
                                    <option value="Rol medio alto">Rol medio alto</option>
                                    <option value="Rol alto medio">Rol alto medio</option>
                                    <option value="Rol alto">Rol alto</option>
                                </select>
                            </div>
                            <input type="submit" value="Generar Registro" class="btn btn-primary btn-user btn-block">
                        </form>
                        <?php if ($data['token'] == "") { ?>
                            <hr>
                            <div class="text-center">
                                <a class="small" href="login">Ya tienes cuenta, ingresa aquí</a>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>