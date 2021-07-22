<?php

class indiceController extends controllerBase{

	public function Auto_Carga_Indice()
	{
		require 'models/indiceModel.php';
		$model = new indiceModel();
		echo $model->Auto_Carga_Indice();
	}

	public function generate()
	{
		require 'models/indiceModel.php';
		$model = new indiceModel();
		$model->generate();
	}

}//FIN class indiceController extends controllerBase
?>
