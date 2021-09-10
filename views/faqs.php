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
              <h3 class="card-title">Instrucciones de Uso</h3>
            </div>


            <div style="margin: 10px 10px;  font-weight: bold;">Funcionamiento general</div>
            <p align="justify" style="margin: 10px 10px;">El siguiente video expone los principales requisitos para el cumplimiento del protocolo del Índice Electrónico del Expediente Judicial y un ejemplo práctico de uso de la herramienta del mismo.</p>
            <video style="width: 96%; margin-left: 2%; height: auto;" controls>
              <source src="views/media/ie.mp4" type="video/mp4">
              No fue posible cargar el video. Intente en otro navegador.
            </video>
            <br>
            <div class="separator"></div>
            <div style="margin: 10px 10px;  font-weight: bold;">Excel no responde (Se bloquea)</div>
            <p align="justify" style="margin: 10px 10px;">
              Esto ocurre principalmente debido a que la herramienta no logró tener acceso a una o varias de las actuaciones de la carpeta del proceso que intenta ordenar. Uno de los casos más comunes es que el archivo esté dañado o sea ilegible.
              <br><br>
              Para el caso de los archivos ilegibles o que se encuentren dañados, es necesario entrar a la carpeta donde se encuentran las actuaciones, identificar el archivo dañado, intentar recuperarlo o repararlo y posteriormente ordenar el cuaderno de nuevo con la herramienta.
            </p>
            <div class="separator"></div>
            <div style="margin: 10px 10px;  font-weight: bold;">¿No logra hacer uso de la herramienta de ninguna forma?</div>
            <p align="justify" style="margin: 10px 10px;">
            Por favor, póngase en contácto al correo <span style="color: #007bff;">msebast@cendoj.ramajudicial.gov.co</span> con el asunto "Soporte Aplicación IE" y asegúrese de incluir un teléfono de contacto y especificar el despacho judicial al que pertenece.
            </p>


          </div>
        </div>
      </section>
          <!-- /.content -->
        </div>
        <!-- /.content-wrapper -->

        <div class="col-md-8 offset-md-2 col-sm-10 offset-sm-1 col-xs-12">
          <footer class="main-footer" style="margin-left: 0px; line-height: 5px;">
            <p align="center"><strong>Rama Judicial del Poder Público</strong></p>
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
