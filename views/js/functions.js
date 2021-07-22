/*
*		Rama Judicial del Podér Público
*		Funciones de generación de Índice Electrónico del Expediente Judicial
*		Desarrollador por: Ing - Sebastián Martínez Valencia
*/

function cleanAlert()
{
	setTimeout(function(){
		$('#acta').css({'border':'1px solid #ccc'});
		$('#radicado').css({'border':'1px solid #ccc'});
		$('#ndocs').css({'border':'1px solid #ccc'});
		$('#resultfrm').css({'display':'none'});
	}, 4000);
}

function loadData()
{
	var esp      = document.getElementById('esp').value.trim();
	var radicado = document.getElementById("radicado").value;

	if(radicado.length > 23){
		document.getElementById("radicado").value = radicado.substring(0, 23);
	} else if (radicado.length == 23){
		if(window.XMLHttpRequest) {
			conexion = new XMLHttpRequest();
		}
		else if(window.ActiveXObject) {
			conexion = new ActiveXObject("Microsoft.XMLHTTP");
		}

		var objeto = {
			"especialidad":esp,
			"radicado":radicado,
		}

		var obj = JSON.stringify(objeto);
		// Preparar la funcion de respuesta
		conexion.onreadystatechange = respuesta;

		// Realizar peticion HTTP
		conexion.open('POST', 'index.php?controller=indice&action=Auto_Carga_Indice');
		conexion.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
		conexion.send("objeto="+obj);


		function respuesta() {
			if(conexion.readyState == 4) {
				if(conexion.status == 200) {
					if (conexion.responseText != ""){
						if(conexion.responseText == "200201"){
							$('#resultfrm').css({'border':'1px solid #ebccd1','background-color':'#f2dede','color':'#a94442','padding':'5px','display':'block','width':'96%','margin':'10px 2%'});
							$('#resultfrm').html("No se reconoce la especialidad para la generación del Índice. Contacte a Ingeniería CSJCF.");
							cleanAlert();
						} else if(conexion.responseText == "300301"){
							$('#resultfrm').css({'border':'1px solid #ebccd1','background-color':'#f2dede','color':'#a94442','padding':'5px','display':'block','width':'96%','margin':'10px 2%'});
							$('#resultfrm').html("No se encontró un proceso relacionado con ese radicado. Verifique e intente de nuevo.");
							cleanAlert();
						} else {
							var vect = conexion.responseText.split("////");
							if(vect[0].trim() != ""){
								document.getElementById("despacho").value = vect[0];
							} else {
								document.getElementById("despacho").value = "";
							}
							if(vect[1].trim() != ""){
								document.getElementById("serie").value = vect[1];
							} else {
								document.getElementById("serie").value = "";
							}
							if(vect[3].trim() != ""){
								document.getElementById("partes1").value = vect[3];
							} else {
								document.getElementById("partes1").value = "";
							}
							if(vect[2].trim() != ""){
								document.getElementById("partes2").value = vect[2];
							} else {
								document.getElementById("partes2").value = "";
							}
						}
					}
				}
			}
		}
	}
}

function GuardarIndice()
{
	var esp      = document.getElementById('esp').value.trim();
	var radicado = document.getElementById('radicado').value.trim();
	var ciudad   = document.getElementById('ciudad').value;
	var despacho = document.getElementById('despacho').value.trim();
	var serie    = document.getElementById('serie').value.trim();
	var partes1  = document.getElementById('partes1').value.trim();
	var partes2  = document.getElementById('partes2').value.trim();
	var terceros = document.getElementById('terceros').value.trim();

	if(esp == ""){
		$('#resultfrm').css({'border':'1px solid #ebccd1','background-color':'#f2dede','color':'#a94442','padding':'5px','display':'block','width':'96%','margin':'10px 2%'});
		$('#resultfrm').html("No se encontró una especialidad para generar el Índice Electrónico.");
		cleanAlert();
	} else if(radicado == ""){
		$('#radicado').css({'border':'1px solid #a94442'});
		$('#resultfrm').css({'border':'1px solid #ebccd1','background-color':'#f2dede','color':'#a94442','padding':'5px','display':'block','width':'96%','margin':'10px 2%'});
		$('#resultfrm').html("Debe digitar el radicado para poder generar el índice.");
		cleanAlert();
	} else if(radicado.length < 23){
		$('#radicado').css({'border':'1px solid #a94442'});
		$('#resultfrm').css({'border':'1px solid #ebccd1','background-color':'#f2dede','color':'#a94442','padding':'5px','display':'block','width':'96%','margin':'10px 2%'});
		$('#resultfrm').html("Debe digitar los 23 digitos del radicado para poder generar el índice.");
		cleanAlert();
	} else if(despacho == "" || serie == "" || partes1 == "" || partes2 == ""){
		$('#resultfrm').css({'border':'1px solid #ebccd1','background-color':'#f2dede','color':'#a94442','padding':'5px','display':'block','width':'96%','margin':'10px 2%'});
		$('#resultfrm').html("Todos los campos excepto 'Terceros Intervinietes', son obligatorios.");
		cleanAlert();
	} else {
		$("#IEGen").submit();
		location.reload();
	}

}

/************************************************************************************************************************************************************************/
