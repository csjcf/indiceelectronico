<?php
class frontController
{
	static function main()
	{
		/*----------------------------- Library ---------------------------*/
		require 'libs/config.php';
		require 'libs/SPDO.php';
		require 'libs/controllerBase.php';
		require 'libs/modelBase.php';
		require 'libs/view.php';
		require 'config.php';

		/*----------------------- ( User Registered ) ---------------------*/
		if (isset($_SESSION['id']) && isset($_SESSION['rol']))
		{
				//Controller ---> menuController
				if(! empty($_GET['controller']))
		      		$controllerName = $_GET['controller'] . 'Controller';
				else
		      		$controllerName = "menuController";

				//action ---> menu_user
				if(! empty($_GET['action']))
		      		$actionName = $_GET['action'];
				else
		     		 $actionName = "menu_user";
		}

		/*----------------------- ( User Unregistered ) ---------------------*/
		else
		{
				//Controller ---> indexController
				if(! empty($_GET['controller']))
		      			$controllerName = $_GET['controller'] . 'Controller';
				else
		    		  $controllerName = "indexController";

				//action ---> login_user
				if(! empty($_GET['action']))
		      		$actionName = $_GET['action'];
				else
		     		 $actionName = "index";
		}

		//Path ---> controllers/fileController.php
		$controllerPath = $config->get('controllersFolder') . $controllerName . '.php';

		if(is_file($controllerPath))
		      require $controllerPath;
		else
		      //die('El Controlador no existe - 404 not found');
			  echo '<script languaje="Javascript">location.href="views/errors/error_controller.php"</script>';

		if (is_callable(array($controllerName, $actionName)) == false)
		{
			trigger_error ($controllerName . '->' . $actionName . '` no existe', E_USER_NOTICE);
			return false;
		}

		$controller = new $controllerName();
		$controller->$actionName();

	}
}
?>
