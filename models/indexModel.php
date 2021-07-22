<?php

class indexModel extends modelBase{

	/***********************************************************************************/
	/*----------------------- validar el inicio de sesion -----------------------------*/
	/***********************************************************************************/

	public function validate_user()
	{
		$status=0;
		//------------DATOS ENVIADOS DEL FORMULARIO DE LOGIN----------------
		$user = trim($_POST["usuario"]);
		$pass = md5(trim($_POST["contrasena"]));
		//------------FIN DATOS ENVIADOS DEL FORMULARIO DE LOGIN----------------

		if($user != "" && $pass != ""){

			$select = $this->db->prepare('SELECT * FROM pa_usuario WHERE nombre_usuario LIKE :user AND contrasena LIKE :pass;');
			$select->bindParam(':user', $user);
			$select->bindParam(':pass', $pass);
			$select->execute();

			while($columna = $select->fetch()){
				$status          	     = 1;
				$_SESSION['id'] 	     = $columna['id'];
				$_SESSION['correo']    = $columna['correo'];
				$_SESSION['nombre']    = $columna['empleado'];
				$_SESSION['esabogado'] = $columna['esabogado'];
				$_SESSION['documento'] = $columna['nombre_usuario'];
			}

			if ($status == 1){
				header("refresh: 0; URL=index.php?controller=admin&action=Registrar_Noticias");
				die();
			} else {
				header("refresh: 0; URL=index.php?controller=index&action=login_user&log=0");
			}

		} else {
			header("refresh: 0; URL=index.php?controller=index&action=login_user&log=1");
		}

	}

	/***********************************************************************************/
	/*------------------------------ Cerrar sesión ------------------------------------*/
	/***********************************************************************************/

	public function close_session()
	{
		session_unset();
		session_destroy();
		header("refresh: 0;URL=/ramajudicialingreso/");
		die();
	}


} // Fin de la clase
	?>
