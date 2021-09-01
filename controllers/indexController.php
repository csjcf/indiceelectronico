<?php
class indexController extends controllerBase
{

	/*-------------------- Index --------------------*/
	public function index()
	{
		require 'models/indexModel.php';
		$model = new indexModel();
		$id = $_GET['esp'];
		$especialidad = $model->get_especialidad($id);
		$this->view->show("generar_indice.php", compact('id', 'especialidad'));
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
