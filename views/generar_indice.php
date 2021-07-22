<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Consejo Seccional de Caldas | Índice Electrónico del Expediente Judicial</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="views/plugins/fontawesome-free/css/all.min.css">
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <link rel="stylesheet" href="views/css/adminlte.min.css">
  <link rel="stylesheet" href="views/css/style.css">
  <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  <script src="views/js/functions.js"></script>
</head>
<body class="hold-transition sidebar-mini">
  <div class="wrapper">
    <div class="col-md-8 offset-md-2 col-sm-10 offset-sm-1 col-xs-12">
      <div class="row">
        <div class="col-md-6" style="width: 45%;">
          <img src="views/img/consejo.png" style="width:200px; margin-top: 14px;">
        </div>
        <div class="col-md-6" style="width: 50%;">
          <img src="views/img/escudo.png" style="float: right;">
        </div>
      </div>
    </div>
    <div class="col-md-8 offset-md-2 col-sm-10 offset-sm-1 col-xs-12">
      <?php require_once('views/nav.php'); ?>
    </div>
    <!-- Content Contains page content -->
    <div class="content">
      <!-- Main content -->
      <section class="content">
        <div class="col-md-8 offset-md-2 col-sm-10 offset-sm-1 col-xs-12">
          <div class="card card-primary" style="margin: 3% 0%;">
            <div class="card-header" style="background-color: #236093;">
              <h3 class="card-title">Formulario del Índice</h3>
            </div>
            <!-- /.card-header -->
            <!-- form start -->
            <form role="form" method="POST" action="index.php?controller=indice&action=generate" id="IEGen" target="_blank">
              <div class="card-body">

                <div class="col-md-6" style="float: left;">
                  <div class="form-group">
                    <input type="hidden" id="esp" name="esp" value="<?php echo $especialidad; ?>"/>
                    <label for="radicado" style="font-weight: bold; margin-bottom: 0px;">Radicado del proceso</label>
                    <small id="partes1Help" class="form-text text-muted" style="margin-top: 0px;">Los 23 digitos sin espacios ni ningún caractér diferente a números</small>
                    <input type="text" class="form-control" id="radicado" name="radicado" onkeyup="loadData();" />
                  </div>
                  <div class="form-group">
                    <label for="ciudad" style="font-weight: bold; margin-bottom: 0px;">Ciudad</label>
                    <small id="partes1Help" class="form-text text-muted" style="margin-top: 0px;">Ciudad en la que reposa el proceso</small>
                    <input type="text" class="form-control" id="ciudad" name="ciudad" value="Manizales" readonly />
                  </div>
                  <div class="form-group">
                    <label for="despacho" style="font-weight: bold; margin-bottom: 0px;">Despacho Judicial</label>
                    <small id="partes1Help" class="form-text text-muted" style="margin-top: 0px;">Juzgado o Magistrado del Tribunal al que fue asignado el proceso</small>
                    <input type="text" class="form-control" id="despacho" name="despacho" />
                  </div>
                  <div class="form-group">
                    <label for="partes1" style="font-weight: bold; margin-bottom: 0px;">Partes Procesales (Parte A)</label>
                    <small id="partes1Help" class="form-text text-muted" style="margin-top: 0px;">(Demandado, Procesado, Accionado)</small>
                    <input type="text" class="form-control" id="partes1" name="partes1" />
                  </div>
                </div>

                <div class="col-md-6" style="float: left;">
                  <div class="form-group">
                    <label for="terceros" style="font-weight: bold; margin-bottom: 0px;">Terceros Intervinientes</label>
                    <small id="partes1Help" class="form-text text-muted" style="margin-top: 0px;">Personas llamadas en garantía o vinculación de terceros</small>
                    <input type="text" class="form-control" id="terceros" name="terceros" />
                  </div>
                  <div class="form-group">
                    <label for="serie" style="font-weight: bold; margin-bottom: 0px;">Serie o Subserie documental</label>
                    <small id="partes1Help" class="form-text text-muted" style="margin-top: 0px;">Clasificación o tipo de proceso judicial</small>
                    <input type="text" class="form-control" id="serie" name="serie" />
                  </div>
                  <div class="form-group">
                    <label for="partes2" style="font-weight: bold; margin-bottom: 0px;">Partes Procesales (Parte B)</label>
                    <small id="partes2Help" class="form-text text-muted" style="margin-top: 0px;">(Demandante, Denunciante, Accionante)</small>
                    <input type="text" class="form-control" id="partes2" name="partes2" />
                  </div>
                </div>

              </div>
              <!-- /.card-body -->
              <div id="resultfrm"></div>
              <div class="card-footer">
                <div id="env" class="btn btn-primary float-right" onclick="GuardarIndice();" style='min-width: 90px; background-color: #236093; cursor:pointer;'>Generar Índice</div>
              </div>
              <div id="load">
                <img src="views/img/cargando.gif" class="loading">
              </div>
            </form>

          </div>
        </div>
      </section>
      <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->

    <div class="col-md-8 offset-md-2 col-sm-10 offset-sm-1 col-xs-12">
      <footer class="main-footer" style="margin-left: 0px; line-height: 5px;">
        <p align="center">Rama Judicial del Poder Público</p>
        <p align="center">Micrositio de generación del Índice Electrónico del Expediente Judicial.</p>
        <p align="center"><?php echo date('Y'); ?> Todos los derechos reservados.</p><br>
      </footer>
    </div>
  </div>
  <!-- ./wrapper -->

  <script src="views/plugins/jquery/jquery.min.js"></script>
  <script src="views/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="views/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>
  <script src="views/js/adminlte.min.js"></script>
  <script src="views/js/demo.js"></script>
  <script type="text/javascript">
  $(document).ready(function () {
    bsCustomFileInput.init();
  });
  </script>
</body>
</html>
