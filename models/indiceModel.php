<?php

class indiceModel extends modelBase{

	public function Auto_Carga_Indice()
	{
		ini_set('display_errors', 1);
		ini_set('display_startup_errors', 1);
		error_reporting(E_ALL);

		// Recepción de datos desde el JS
		$objeto       = json_decode($_POST["objeto"], true);
		$especialidad = trim($objeto['especialidad']);
		$radicado     = trim($objeto['radicado']);
		$idbd         = 0;

		// Variable Html Content
		$body = "";

		//----------------------------------------------------------------------------------Arreglos de Especialidad-----------------------------------------------------------------------
		$vect_tca = array("2315");
		$vect_disc = array("1102");
		$vect_jepms = array("3187");
		$vect_penal = array("4088", "4071", "4009", "4004", "4018", "4007", "3104", "3107");  //OK
		$vect_laboral = array("3105", "2205", "4105");  //OK
		$vect_infancia = array("4071", "3118");
		$vect_sala_penal = array("2204");    //OK
		$vect_civil_familia = array("4003", "3110", "2213", "3103");  //OK
		$vect_administrativo = array("3331", "3333", "3339", "2333");

		if ( in_array(substr($especialidad, 5, -3), $vect_penal) ){
			$idbd = 11;
		} else if( in_array(substr($especialidad, 5, -3), $vect_laboral) ){
			$idbd = 7;
		} else if( in_array(substr($especialidad, 5, -3), $vect_administrativo) ){
			$idbd = 12;
		} else if( in_array(substr($especialidad, 5, -3), $vect_civil_familia) ){
			$idbd = 7;
		} else if( in_array(substr($especialidad, 5, -3), $vect_infancia) ){
			$idbd = 13;																																// Lo mismo de penal
		} else if( in_array(substr($especialidad, 5, -3), $vect_jepms) ){
			$idbd = 14;
		} else if( in_array(substr($especialidad, 5, -3), $vect_sala_penal) ){
			$idbd = 15;
		} else if( in_array(substr($especialidad, 5, -3), $vect_tca) ){
			$idbd = 16;
		} else if( in_array(substr($especialidad, 5, -3), $vect_disc) ){            // FALTA
			$idbd = 17;
		}
		//----------------------------------------------------------------------------------Fin Arreglos de Especialidad-----------------------------------------------------------------------

		if($idbd != 0){
			// Instancia del modelo y captura de datos de BD Justicia
			$listar = $this->db->prepare("SELECT * FROM pa_base_datos WHERE id = ".$idbd.";");
			$listar->execute();
			$datosbd_b = $listar->fetch();
			$datosbd_1 = $datosbd_b['ip'];
			$datosbd_2 = $datosbd_b['bd'];
			$datosbd_3 = $datosbd_b['usuario'];
			$datosbd_4 = $datosbd_b['clave'];

			// Conexión con BD Justicia
			$serverName = $datosbd_1;
			$connectionInfo = array( "Database"=>$datosbd_2, "UID"=>$datosbd_3, "PWD"=>$datosbd_4, "CharacterSet"=>"UTF-8");
			$conn = sqlsrv_connect($serverName, $connectionInfo);

			//---------------------------------------------------------------- Información del proceso-----------------------------------------------------------------
			header('Content-Type: text/html; charset=UTF-8');
			$query_Actu = ("
			SELECT
			info_pro.[A103NOMBPONE] AS juzgado
			,pro_tipo.[A052DESCPROC] AS tipoProceso
			,pro_clas.[A053DESCCLAS] AS claseProceso
			,sub_clas.[A071DESCSUBC] AS subClaseProceso
			FROM [$datosbd_2].[dbo].[T103DAINFOPROC] AS info_pro
			INNER JOIN [$datosbd_2].[dbo].[T053BACLASGENE] AS pro_clas ON info_pro.[A103CODICLAS] = pro_clas.[A053CODICLAS]
			INNER JOIN [$datosbd_2].[dbo].[T052BAPROCGENE] AS pro_tipo ON info_pro.[A103CODIPROC] = pro_tipo.[A052CODIPROC]
			INNER JOIN [$datosbd_2].[dbo].[T071BASUBCGENE] AS sub_clas ON info_pro.[A103CODISUBC] = sub_clas.[A071CODISUBC]
			WHERE info_pro.[A103LLAVPROC] like '$radicado'
			");
			$paramsQ  = array();
			$optionsQ = array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
			$stmt = sqlsrv_query($conn, $query_Actu , $paramsQ, $optionsQ);

			//------------------------------------------------------------------- Accionantes del proceso--------------------------------------------------------------------

			$sql_partes = ("
			SELECT
			info_suje.[A112NUMESUJE] AS cedula
			,info_suje.[A112NOMBSUJE] AS nombre
			,tipo_suje.[A057DESCSUJE] AS tipo
			FROM [$datosbd_2].[dbo].[T112DRSUJEPROC] AS info_suje
			INNER JOIN [$datosbd_2].[dbo].[T057BASUJEGENE] AS tipo_suje ON info_suje.[A112CODISUJE] = tipo_suje.A057CODISUJE
			WHERE info_suje.[A112LLAVPROC] = "."'$radicado'"."
			AND info_suje.[A112CODISUJE] = '0001';
			");
			$params  = array();
			$options = array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
			$stmt1 = sqlsrv_query($conn, $sql_partes, $params, $options);

			//------------------------------------------------------------------- Accionados del proceso--------------------------------------------------------------------

			$sql_partes2 = ("
			SELECT
			info_suje.[A112NUMESUJE] AS cedula
			,info_suje.[A112NOMBSUJE] AS nombre
			,tipo_suje.[A057DESCSUJE] AS tipo
			FROM [$datosbd_2].[dbo].[T112DRSUJEPROC] AS info_suje
			INNER JOIN [$datosbd_2].[dbo].[T057BASUJEGENE] AS tipo_suje ON info_suje.[A112CODISUJE] = tipo_suje.A057CODISUJE
			WHERE info_suje.[A112LLAVPROC] = "."'$radicado'"."
			AND info_suje.[A112CODISUJE] = '0002';
			");
			$params2  = array();
			$options2 = array( "Scrollable" => SQLSRV_CURSOR_KEYSET );
			$stmt2 = sqlsrv_query($conn, $sql_partes2, $params2, $options2);

			//-------------------------------------------------------------- Construcción de HtmlContent -------------------------------------------------------------------

			// Relacionar información del proceso

			//Info del proceso
			$juzgado = "";
			$tipoProceso = "";
			$claseProceso = "";
			while( $row = sqlsrv_fetch_array($stmt) ){
				$juzgado = str_replace(" DE MANIZALES", "", strval($row['juzgado']));
				$juzgado = str_replace("FAMILIA DEL CIRCUITO", "FAMILIA", $juzgado);
				$juzgado = str_replace("JUEZ ", "", $juzgado);
				$juzgado = str_replace("JUZGADO   ", "JUZGADO ", $juzgado);
				$juzgado = str_replace("JUZGADO  ", "JUZGADO ", $juzgado);
				$juzgado = str_replace("MPAL DE MZLES -TUTELAS", "MUNICIPAL", $juzgado);
				$juzgado = str_replace("MUNICIPL", "MUNICIPAL", $juzgado);
				$juzgado = str_replace(" -TUTELAS", "", $juzgado);
				$juzgado = str_replace(" CTO MZLES", " Del Circuito", $juzgado);
				$juzgado = strtolower($juzgado);
				$juzgado = ucwords($juzgado);
				$body = $body.$juzgado."////";
				$tipoProceso = $row['tipoProceso'];
				$claseProceso = $row['claseProceso'];
			}

			if($juzgado == ""){
				echo"300301";
			} else {

				//-------------------------------------------------------------------------- Establecer Subserie --------------------------------------------------------------------
				if($idbd == 7){

					// Civil y Familia
					$especialidad = substr($radicado, 5, -14);
					if($especialidad == "4003"){
						if(trim($tipoProceso) == "Orden Constitucional y Legal"){
							if(strpos(trim($claseProceso), 'Habeas Corpus') !== false){
								$body = $body."Acción de Hábeas Corpus////";
								$lb++;
							} else {
								$body = $body."Acción de Tutela////";
								$lb++;
							}
						} else {
							if(trim($tipoProceso) == "Proceso Disciplinario" || trim($tipoProceso) == "Disciplinario"){
								$body = $body."Proceso disciplinario contra empleados////";
							} else {
								$body = $body."Expedientes de Procesos Judiciales Contenciosos de Mínima y Menor Cuantía////";
							}
						}
					} else if ($especialidad == "3103"){
						if(trim($tipoProceso) == "Orden Constitucional y Legal"){
							if(strpos(trim($claseProceso), 'Habeas Corpus') !== false){
								$body = $body."Acción de Hábeas Corpus////";
								$lb++;
							} else {
								$body = $body."Acción de Tutela////";
								$lb++;
							}
						} else{
							if(trim($tipoProceso) == "Proceso Disciplinario" || trim($tipoProceso) == "Disciplinario"){
								$body = $body."Proceso disciplinario contra empleados////";
							} else {
								$body = $body."Expedientes de Procesos Judiciales Contenciosos de Mayor Cuantía Jurisdicción Civil////";
							}
						}
					} else if ($especialidad == "3110"){
						if(trim($tipoProceso) == "Orden Constitucional y Legal"){
						  if(strpos(trim($claseProceso), 'Habeas Corpus') !== false){
						    $body = $body."Acción de Hábeas Corpus////";
						    $lb++;
						  } else {
						    $body = $body."Acción de Tutela////";
						    $lb++;
						  }
						} else {
							if(trim($tipoProceso) == "Proceso Disciplinario" || trim($tipoProceso) == "Disciplinario"){
								$body = $body."Proceso disciplinario contra empleados////";
							} else {
								$body = $body."Expedientes de Procesos Judiciales de Familia////";
							}
						}
					} else if ($especialidad == "2213"){
						if(trim($tipoProceso) == "Orden Constitucional y Legal"){
						  if(strpos(trim($claseProceso), 'Habeas Corpus') !== false){
						    $body = $body."Acción de Hábeas Corpus////";
						    $lb++;
						  } else {
						    $body = $body."Acción de Tutela////";
						    $lb++;
						  }
						} else {
							if(trim($tipoProceso) == "Proceso Disciplinario" || trim($tipoProceso) == "Disciplinario"){
								$body = $body."Proceso disciplinario contra empleados////";
							} else {
								$body = $body."Expedientes de Procesos Judiciales de Revisión////";
							}
						}
					} else{
						// Fin Civil y Familia

						// Laboral
						$lb = 0;
						if(trim($tipoProceso) == "Proceso Disciplinario" || trim($tipoProceso) == "Disciplinario"){
							$body = $body."Proceso disciplinario contra empleados////";
						} else {
							if(strpos(trim($juzgado), 'MAG') !== false || strpos($juzgado, 'Tribunal') !== false){
								if(trim($tipoProceso) == "Orden Constitucional y Legal"){
									if(strpos(trim($claseProceso), 'Habeas Corpus') !== false){
										$body = $body."Acción de Hábeas Corpus////";
										$lb++;
									} else {
										$body = $body."Acción de Tutela////";
										$lb++;
									}
								} else {
									$body = $body."Proceso Judicial de Revisión////";
									$lb++;
								}
							} else if(strpos(trim($juzgado), 'PEQUEÑAS') !== false){
								if(trim($tipoProceso) == "De Ejecución"){
									$body = $body."Proceso Judicial Laboral Ejecutivo////";
									$lb++;
								} else {
									$body = $body."Proceso Judicial Laboral de única instancia////";
									$lb++;
								}
							} else if(trim($tipoProceso) == "Orden Constitucional y Legal"){
								if(strpos(trim($claseProceso), 'Habeas Corpus') !== false){
									$body = $body."Acción de Hábeas Corpus////";
									$lb++;
								} else {
									$body = $body."Acción de Tutela////";
									$lb++;
								}
							} else {
								if(trim($tipoProceso) == "De Ejecución"){
									$body = $body."Proceso Judicial Laboral Ejecutivo////";
									$lb++;
								} else {
									$body = $body."Proceso Judicial Laboral Ordinario////";
									$lb++;
								}
							}
							if($lb == 0){
								$body = $body."No se encontró subserie ***PONER MANUAL***////";
							}
							// Fin Laboral
						}
					}

				} else if($idbd == 11){ //Penal

					if(trim($tipoProceso) == "Proceso Disciplinario" || trim($tipoProceso) == "Disciplinario"){
						$body = $body."Proceso disciplinario contra empleados////";
					} else {
						$lb = 0;
						if(strpos(trim($juzgado), 'MAG') !== false || strpos($juzgado, 'Tribunal') !== false || strpos($juzgado, 'Mg') !== false){
							if(trim($tipoProceso) == "Orden Constitucional y Legal"){
								if(strpos(trim($claseProceso), 'Habeas Corpus') !== false){
									$body = $body."<input type='text' class='form-control' id='serie' name='serie' value='Acción de Hábeas Corpus'/>////";
									$lb++;
								} else {
									$body = $body."<input type='text' class='form-control' id='serie' name='serie' value='Acción de Tutela'/>////";
									$lb++;
								}
							} else {
								$body = $body."<select id='serie' name='ToSelectSerie' class='form-control'><option value='' selected>Seleccione Subserie</option><option value='Expedientes De Procesos Judiciales Penales Ley 906 De 2004'>Expedientes De Procesos Judiciales Penales Ley 906 De 2004</option><option value='Expedientes De Procesos Judiciales Penales Ley 600 De 2000'>Expedientes De Procesos Judiciales Penales Ley 600 De 2000</option><option value='Expedientes De Procesos Judiciales Penales Ley 1826 De 2017'>Expedientes De Procesos Judiciales Penales Ley 1826 De 2017</option><option value='Proceso Judicial de Revisión'>Proceso Judicial de Revisión</option></select>////";
								$lb++;
							}
						} else {
							if(trim($tipoProceso) == "Orden Constitucional y Legal"){
								if(strpos(trim($claseProceso), 'Habeas Corpus') !== false){
									$body = $body."<input type='text' class='form-control' id='serie' name='serie' value='Acción de Hábeas Corpus'/>////";
									$lb++;
								} else {
									$body = $body."<input type='text' class='form-control' id='serie' name='serie' value='Acción de Tutela'/>////";
									$lb++;
								}
							} else {
								$body = $body."<select id='serie' name='ToSelectSerie' class='form-control'><option value='' selected>Seleccione Subserie</option><option value='Expedientes De Procesos Judiciales Penales Ley 906 De 2004'>Expedientes De Procesos Judiciales Penales Ley 906 De 2004</option><option value='Expedientes De Procesos Judiciales Penales Ley 600 De 2000'>Expedientes De Procesos Judiciales Penales Ley 600 De 2000</option><option value='Expedientes De Procesos Judiciales Penales Ley 1826 De 2017'>Expedientes De Procesos Judiciales Penales Ley 1826 De 2017</option></select>////";
								$lb++;
							}
						}
						if($lb == 0){
							$body = $body."No se encontró subserie ***PONER MANUAL***////";
						}
					}
				} else if($idbd == 12){ // Administrativo

					if(trim($tipoProceso) == "Proceso Disciplinario" || trim($tipoProceso) == "Disciplinario"){
						$body = $body."Proceso disciplinario contra empleados////";
					} else {
						$lb = 0;
						if(trim($tipoProceso) == "Constitucionales"){
							$body = $body.trim($claseProceso)."////";
							$lb++;
						} else {
							if(trim($claseProceso) == "CONCILIACIONES PREJUDICIALES"){
								$body = $body."Conciliaciones Extrajudiciales////";
								$lb++;
							} else {
								$body = $body."<select id='serie' name='ToSelectSerie' class='form-control'><option value='' selected>Seleccione Subserie</option><option value='Expediente de Procesos Judiciales de Controversias Contractuales'>Expediente de Procesos Judiciales de Controversias Contractuales</option><option value='Expediente de procesos Judiciales de Nulidad Electoral'>Expediente de procesos Judiciales de Nulidad Electoral</option><option value='Expediente de procesos Judiciales de Nulidad y Restablecimiento del Derecho'>Expediente de procesos Judiciales de Nulidad y Restablecimiento del Derecho</option><option value='Expediente de procesos Judiciales de Reparación Directa'>Expediente de procesos Judiciales de Reparación Directa</option><option value='Expediente de procesos Judiciales de Repetición'>Expediente de procesos Judiciales de Repetición</option><option value='Expediente de procesos Judiciales Ejecutivos'>Expediente de procesos Judiciales Ejecutivos</option></select>////";
								$lb++;
							}
						}
						if($lb == 0){
							$body = $body."No se encontró subserie ***PONER MANUAL***////";
						}
					}
				} else if($idbd == 13){ // Infancia y Adolecencia

					if(trim($tipoProceso) == "Proceso Disciplinario" || trim($tipoProceso) == "Disciplinario"){
						$body = $body."Proceso disciplinario contra empleados////";
					} else {
						$lb = 0;
						if(strpos(trim($juzgado), 'MAG') !== false || strpos($juzgado, 'Tribunal') !== false || strpos($juzgado, 'Mg') !== false){
							if(trim($tipoProceso) == "Orden Constitucional y Legal"){
								if(strpos(trim($claseProceso), 'Habeas Corpus') !== false){
									$body = $body."<input type='text' class='form-control' id='serie' name='serie' value='Acción de Hábeas Corpus'/>////";
									$lb++;
								} else {
									$body = $body."<input type='text' class='form-control' id='serie' name='serie' value='Acción de Tutela'/>////";
									$lb++;
								}
							} else {
								$body = $body."<select id='serie' name='ToSelectSerie' class='form-control'><option value='' selected>Seleccione Subserie</option><option value='Expedientes De Procesos Judiciales Penales Ley 906 De 2004'>Expedientes De Procesos Judiciales Penales Ley 906 De 2004</option><option value='Expedientes De Procesos Judiciales Penales Ley 600 De 2000'>Expedientes De Procesos Judiciales Penales Ley 600 De 2000</option><option value='Expedientes De Procesos Judiciales Penales Ley 1826 De 2017'>Expedientes De Procesos Judiciales Penales Ley 1826 De 2017</option><option value='Proceso Judicial de Revisión'>Proceso Judicial de Revisión</option></select>////";
								$lb++;
							}
						} else {
							if(trim($tipoProceso) == "Orden Constitucional y Legal"){
								if(strpos(trim($claseProceso), 'Habeas Corpus') !== false){
									$body = $body."<input type='text' class='form-control' id='serie' name='serie' value='Acción de Hábeas Corpus'/>////";
									$lb++;
								} else {
									$body = $body."<input type='text' class='form-control' id='serie' name='serie' value='Acción de Tutela'/>////";
									$lb++;
								}
							} else {
								$body = $body."<select id='serie' name='ToSelectSerie' class='form-control'><option value='' selected>Seleccione Subserie</option><option value='Expedientes De Procesos Judiciales Penales Ley 906 De 2004'>Expedientes De Procesos Judiciales Penales Ley 906 De 2004</option><option value='Expedientes De Procesos Judiciales Penales Ley 600 De 2000'>Expedientes De Procesos Judiciales Penales Ley 600 De 2000</option><option value='Expedientes De Procesos Judiciales Penales Ley 1826 De 2017'>Expedientes De Procesos Judiciales Penales Ley 1826 De 2017</option></select>////";
								$lb++;
							}
						}
						if($lb == 0){
							$body = $body."No se encontró subserie ***PONER MANUAL***////";
						}
					}

				} else if($idbd == 14){

					if(trim($tipoProceso) == "Orden Constitucional y Legal"){
						if(strpos(trim($claseProceso), 'Habeas Corpus') !== false){
							$body = $body."<input type='text' class='form-control' id='serie' name='serie' value='Acción de Hábeas Corpus'/>////";
							$lb++;
						} else {
							$body = $body."<input type='text' class='form-control' id='serie' name='serie' value='Acción de Tutela'/>////";
							$lb++;
						}
					} else {
						$body = $body."<select id='serie' name='ToSelectSerie' class='form-control'><option value='' selected>Seleccione Subserie</option><option value='Expedientes De Procesos Judiciales Penales Ley 906 De 2004'>Expedientes De Procesos Judiciales Penales Ley 906 De 2004</option><option value='Expedientes De Procesos Judiciales Penales Ley 600 De 2000'>Expedientes De Procesos Judiciales Penales Ley 600 De 2000</option><option value='Expedientes De Procesos Judiciales Penales Ley 1826 De 2017'>Expedientes De Procesos Judiciales Penales Ley 1826 De 2017</option><option value='Proceso Judicial de Revisión'>Proceso Judicial de Revisión</option></select>////";
						$lb++;
					}

				} else if($idbd == 15){ //Sala Penal

					if(trim($tipoProceso) == "Proceso Disciplinario" || trim($tipoProceso) == "Disciplinario"){
						$body = $body."Proceso disciplinario contra empleados////";
					} else {
						if(strpos(trim($juzgado), 'MAG') !== false || strpos($juzgado, 'Tribunal') !== false || strpos($juzgado, 'Mg') !== false){
							if(trim($tipoProceso) == "Orden Constitucional y Legal"){
								if(strpos(trim($claseProceso), 'Habeas Corpus') !== false){
									$body = $body."<input type='text' class='form-control' id='serie' name='serie' value='Acción de Hábeas Corpus'/>////";
								} else {
									$body = $body."<input type='text' class='form-control' id='serie' name='serie' value='Acción de Tutela'/>////";
								}
							} else {
								$body = $body."<select id='serie' name='ToSelectSerie' class='form-control'><option value='' selected>Seleccione Subserie</option><option value='Expedientes De Procesos Judiciales Penales Ley 906 De 2004'>Expedientes De Procesos Judiciales Penales Ley 906 De 2004</option><option value='Expedientes De Procesos Judiciales Penales Ley 600 De 2000'>Expedientes De Procesos Judiciales Penales Ley 600 De 2000</option><option value='Expedientes De Procesos Judiciales Penales Ley 1826 De 2017'>Expedientes De Procesos Judiciales Penales Ley 1826 De 2017</option><option value='Proceso Judicial de Revisión'>Proceso Judicial de Revisión</option></select>////";
							}
						} else {
							$body = $body."<input type='text' class='form-control' id='serie' name='serie' value='No se encontró subserie ***PONER MANUAL***'/>////";
						}
					}
				} else if($idbd == 16){ // Tribunal Contencioso Administrativo

					if(trim($tipoProceso) == "Proceso Disciplinario" || trim($tipoProceso) == "Disciplinario"){
						$body = $body."Proceso disciplinario contra empleados////";
					} else {
						$lb = 0;
						if(trim($tipoProceso) == "Constitucionales"){
							$body = $body.trim($claseProceso)."////";
							$lb++;
						} else {
							if(trim($claseProceso) == "CONCILIACIONES PREJUDICIALES"){
								$body = $body."Conciliaciones Extrajudiciales////";
								$lb++;
							} else {
								$body = $body."<select id='serie' name='ToSelectSerie' class='form-control'><option value='' selected>Seleccione Subserie</option><option value='Expediente de Procesos Judiciales de Controversias Contractuales'>Expediente de Procesos Judiciales de Controversias Contractuales</option><option value='Expediente de procesos Judiciales de Nulidad Electoral'>Expediente de procesos Judiciales de Nulidad Electoral</option><option value='Expediente de procesos Judiciales de Nulidad y Restablecimiento del Derecho'>Expediente de procesos Judiciales de Nulidad y Restablecimiento del Derecho</option><option value='Expediente de procesos Judiciales de Reparación Directa'>Expediente de procesos Judiciales de Reparación Directa</option><option value='Expediente de procesos Judiciales de Repetición'>Expediente de procesos Judiciales de Repetición</option><option value='Expediente de procesos Judiciales Ejecutivos'>Expediente de procesos Judiciales Ejecutivos</option></select>////";
								$lb++;
							}
						}
						if($lb == 0){
							$body = $body."No se encontró subserie ***PONER MANUAL***////";
						}
					}
				} else if($idbd == 17){

					if(trim($tipoProceso) == "Orden Constitucional y Legal"){
						if(strpos(trim($claseProceso), 'Habeas Corpus') !== false){
							$body = $body."<input type='text' class='form-control' id='serie' name='serie' value='Acción de Hábeas Corpus'/>////";
						} else {
							$body = $body."<input type='text' class='form-control' id='serie' name='serie' value='Acción de Tutela'/>////";
						}
					} else {
						if(trim($tipoProceso) == "Proceso Disciplinario" || trim($tipoProceso) == "Disciplinario"){
							$body = $body."Proceso disciplinario contra funcionarios////";
						} else {
							$body = $body."No se encontró subserie ***PONER MANUAL***////";
						}
					}

				}
				//------------------------------------------------------------------------ Fin Establecer Subserie -------------------------------------------------------------------

				//Relacionar accionantes del proceso
				$accionantes = "";
				while( $row2 = sqlsrv_fetch_array($stmt1) ){
					$accionantes = $accionantes.$row2['nombre']." ";
				}
				$accionantes = strtolower($accionantes);
				$accionantes = ucwords($accionantes);
				$body = $body.$accionantes."////";

				//Relacionar accionados del proceso
				$accionados  = "";
				while( $row3 = sqlsrv_fetch_array($stmt2) ){
					$accionados = $accionados.$row3['nombre']." ";
				}
				$accionados = strtolower($accionados);
				$accionados = ucwords($accionados);
				$body = $body.$accionados."////";
				echo $body;

			}

		} else {
			echo"200201";
		}
	}

	public function generate()
	{
		date_default_timezone_set('America/Bogota');
		$status   = 0;
		$radicado = $_POST['radicado'];
		$ciudad   = $_POST['ciudad'];
		$despacho = $_POST['despacho'];
		$serie    = $_POST['serie'];
		$partes1  = $_POST['partes1'];
		$partes2  = $_POST['partes2'];
		$terceros = $_POST['terceros'];

		require 'assets/Classes/PHPExcel/IOFactory.php';
		$objPHPExcel = PHPEXCEL_IOFactory::load('ArchivosExpDig/IndiceElectronicoV2.xlsm');
		$objPHPExcel->setActiveSheetIndex(0);

		$obj = $objPHPExcel->getActiveSheet();
		$obj->getColumnDimension('A')->setWidth(3);
		$obj->getColumnDimension('B')->setWidth(12);
		$obj->getColumnDimension('C')->setWidth(12);
		$obj->getColumnDimension('D')->setWidth(12);
		$obj->getColumnDimension('E')->setWidth(12);
		$obj->getColumnDimension('F')->setWidth(12);
		$obj->getColumnDimension('G')->setWidth(12);
		$obj->getColumnDimension('H')->setWidth(12);
		$obj->getColumnDimension('I')->setWidth(12);
		$obj->getColumnDimension('J')->setWidth(12);
		$obj->getColumnDimension('K')->setWidth(12);
		$obj->getColumnDimension('L')->setWidth(12);
		$obj->getColumnDimension('M')->setWidth(12);
		$obj->getColumnDimension('N')->setWidth(12);
		$obj->getColumnDimension('O')->setWidth(12);
		$obj->getColumnDimension('P')->setWidth(12);

		$objPHPExcel->getActiveSheet()->setCellValue('E6', $ciudad);
		$objPHPExcel->getActiveSheet()->setCellValue('E7', $despacho);
		$objPHPExcel->getActiveSheet()->setCellValue('E8', $serie);
		$objPHPExcel->getActiveSheet()->setCellValue('E9', "".$radicado." ");
		$objPHPExcel->getActiveSheet()->setCellValue('E10', $partes1);
		$objPHPExcel->getActiveSheet()->setCellValue('E11', $partes2);
		$objPHPExcel->getActiveSheet()->setCellValue('E12', $terceros);
		$objPHPExcel->getActiveSheet()->setCellValue('O7', 'NO');
		$objPHPExcel->getActiveSheet()->setCellValue('O9', '0');
		$objPHPExcel->getActiveSheet()->setCellValue('O10', '0');

		header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
		header('Content-Disposition: attachment;filename="IndiceElectronicoV2.xlsm"');
		header('Cache-Control: max-age=0');
		$objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
		$objWriter->save('php://output');

	}
}
?>
