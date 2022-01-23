<div class="container">
    <!-- Outer Row -->
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-12 col-md-9">
            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="p-5">
                        <div class="text-center">
                            <?php if (isset($res['id']) && $res['id'] != "") { ?>
                                <h1 class="h4 text-gray-900 mb-4">¡Actualizar Publicación! <b><?= $res['titulo']; ?></b></h1>
                            <?php } else { ?>
                                <h1 class="h4 text-gray-900 mb-4">¡Nueva Publicación!</h1>
                            <?php } ?>
                        </div>
                        <input type="hidden" id="id_register" value="<?= $res['id'] ?>">
                        <form class="user" name="formPublicationEdit" id="formPublicationEdit" autocomplete="off">
                            <div class="form-group">
                                <input type="text" class="form-control" id="Titulo" name="Titulo" placeholder="Titulo" value="<?= $res['titulo'] ?>">
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="Descripcion" id="Descripcion" cols="20" rows="3" placeholder="Descripción de la publicación"><?= trim($res['descripcion']) ?></textarea>
                            </div>
                            <input type="submit" value="Actualizar Registro" class="btn btn-primary btn-user btn-block">
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>