<?php
class indexController extends controllerBase
{

	/*-------------------- Login User --------------------*/
	public function index()
	{
		$especialidad = $_GET['esp'];
		$this->view->show("generar_indice.php", compact('especialidad'));
	}

	/*-------------------- Ruta Base --------------------*/
	public function ruta_base()
	{
		header("refresh: 0; URL=/login/");
	}

	/*-------------------- Error Controller --------------------*/
	public function error_controller()
	{
		$this->view->show("errors/error_controller.php");
	}

}
?>
