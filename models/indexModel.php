<?php

class indexModel extends modelBase{

	public function get_especialidad($id)
	{
		date_default_timezone_set('America/Bogota');
		$mbd = new PDO('mysql:host=localhost;dbname=ofijudi2020', 'cs', 'Servicios2020**');
		$select = $mbd->prepare('SELECT nombre_usuario FROM pa_usuario WHERE id = :id');
		$select->bindParam(':id', $id);
		$select->execute();
		echo $select->fetch();
	}


} // Fin de la clase
	?>
