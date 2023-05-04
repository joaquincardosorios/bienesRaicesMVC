<?php
$path_sitio = realpath(dirname(__FILE__) . "/..") . "/";
$url_sitio = "/joaquin.rios/tickets/municipalidades/chillanviejo/";
$url_sitio2 = "http://" . $_SERVER['SERVER_NAME'] . $url_sitio;

define("CONST_APIMULTAS","http://129.168.0.30:65002");
define("CONST_APIMUNI","chillanviejo"); 

define("CONST_SITIO", $url_sitio);
define("CONST_BASEDATO", "pgsql");
define("CONST_HOSTNAME", "10.50.10.29");
define("CONST_HOSTNAME_SEG", "10.50.10.29");

define("CONST_SCHEMA", "public");
define("CONST_SCHEMA_SEG", "seguridad");

define("CONST_DBNAME", "chillanviejo");
define("CONST_DBNAME_SEG", "chillanviejo");

define("CONST_PATH_URL", "/SEGURIDAD");
define("CONST_PATH", $path_sitio);
define("CONST_PATH_DAEM", $path_sitio . "DAEM/");

define("CONST_PATH_LIB", CONST_PATH . "lib/");
define("CONST_PATH_LIB_DAEM", CONST_PATH_DAEM . "dir_lib/");
define("CONST_PATH_FRAMEWORK", CONST_PATH . "framework/");
define("CONST_PATH_TEMPLATE", CONST_PATH . "framework/templatepower/");

define("CONST_PATH_SITIO", $url_sitio);
define("CONST_PATH_SITIO2", $url_sitio2);

//--------------------------------------------------------
//SISTEMA DE GESTION EDUCATIVA
//--------------------------------------------------------
define("CONST_PATH_GEOREFERENCIA", "1"); //VALOR 0 SIN GEOREFERENCIA, VALOR 1 CON GEOREFERENCIA,
define("GB_ROOT_DIR", CONST_PATH_FRAMEWORK . "greybox/"); //Nuevas Ventanas POPUP
define("CONST_PATH_SITIO_DAEM", $path_sitio . "DAEM/");
define("CONST_PATH_SITIO_DAEM_IMAGEN", $path_sitio . "DAEM/tmp_img/");

define("CONST_CONTROL_CONTENIDO", "1"); //VALOR 0 SIN APLICACION, VALOR 1 CON APLICACION
define("CONST_ASISTENCIA_DOCENTE", "1"); //VALOR 0 SIN APLICACION, VALOR 1 CON APLICACION
define("CONST_ANTECEDENTES_SALUD", "1"); //VALOR 0 SIN APLICACION, VALOR 1 CON APLICACION
define("CONST_HORARIO_AUTO", "1"); //VALOR 0 SIN APLICACION, VALOR 1 CON APLICACION
define("CONST_BIBLIOTECA", "1"); //VALOR 0 SIN APLICACION, VALOR 1 CON APLICACION
define("CONST_RECEPCION_ANTECEDENTES", "1"); //VALOR 0 SIN APLICACION, VALOR 1 CON APLICACION
//--------------------------------------------------------

//-------------- DEFINICIONES DE CARPETA DE INFORMES
define("CONST_PATH_INFORMES", CONST_PATH . "tmp/"); //DEFINE LA CARPETA FISICA DE CREACION DE ARCHIVOS
define("CONST_PATH_INFORMES_W", CONST_PATH_SITIO . "tmp/"); //DEFINE LA CARPETA VIRTUAL PARA REFERENCIA
define("CONST_PATH_INFORMES_DAEM", CONST_PATH_DAEM . "tmp_rep/"); //DEFINE LA CARPETA FISICA DE CREACION DE ARCHIVOS
define("CONST_PATH_INFORMES_W_DAEM", CONST_PATH_SITIO_DAEM . "tmp_rep/"); //DEFINE LA CARPETA VIRTUAL PARA REFERENCIA DAEM

//------------- constantes para las funciones de los botones ---------------------------/
define("CONST_Consultar", 1);
define("CONST_Crear", 2);
define("CONST_Modificar", 3);
define("CONST_Eliminar", 4);
define("CONST_Imprimir", 5);
define("CONST_TipoIng_Todos", 7);
define("CONST_Cambia_FecEmision", 9);
define("CONST_Puede_Aprobar", 10);

//------------- Areas ---------------------------/
define("CONST_Municipalidad", 1);
define("CONST_Educacion", 2);
define("CONST_Salud", 3);

//-------------Sistemas---------------------------/
define("CONST_CG", 1);
define("CONST_TS", 2);
define("CONST_ADQ", 3);
define("CONST_CB", 4);
define("CONST_SSM", 5);
define("CONST_JPL", 6);
define("CONST_Remuneraciones", 8);
define("CONST_Personal", 9);
define("CONST_PCV", 13);
define("CONST_BOD", 14);
define("CONST_INV", 15);
define("CONST_LC", 17);
define("CONST_PM", 18);
define("CONST_DA", 20);
define("CONST_OP", 21);
define("CONST_CV", 22);
define("CONST_INS", 23);
define("CONST_OC", 24);
define("CONST_RER", 25);
define("CONST_SERVG", 26);

//jpb-20061017. menu navegacional.
include_once CONST_PATH_FRAMEWORK . "class.navegacion.php";
include_once CONST_PATH_FRAMEWORK . "class.GeneraMenu.php";
session_start();

//------------- variables de sesion ---------------------------/
//$_SESSION['SES_dbmun']="";
//$_SESSION['SES_menu']="";
//$_SESSION['SES_dbseg']="";
//$_SESSION['SES_usuario']="";
session_register("SES_dbmun");
session_register("SES_menu");
session_register("SES_dbseg");
session_register("SES_usuario");

if (isset($_SESSION['SES_dbmun'])) {
//        die( 'Estoiy aca');

}
//--------------- librerias comunes a todos los formularios ----------------------//
include_once CONST_PATH_TEMPLATE . "class.TemplatePower.inc.php";
include_once CONST_PATH_FRAMEWORK . "class.formulario.php";

if (isset($_GET["pgName"])) {
    $menu = "";

    $nav = unserialize($_SESSION["SES_nav"]);
    if (is_object($nav)) {
        $arreglo = $nav->posicion_menu;
        $arreglo[$_GET["pgName"]] = "";
        $html = $nav->generar($arreglo);

        $_SESSION['SES_menu'] = $_SESSION["SES_menu_orig"] . $html;
        $nav->posicion_menu = "";
    }

}

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
//** 08-07-2009
//** German Guti�rrez Padilla
//**  Rutina que valida sesion de usuario

$file = CONST_PATH . "login/parSession.txt";
$time = file_get_contents($file);

$ruta = explode('/', $_SERVER["SCRIPT_NAME"]);
$file = count($ruta);
$rutaFile = "";
for ($j = 2; $j < $file - 1; $j++) {
    $rutaFile = $rutaFile . $ruta[$j] . '/';
}
$x_file = ($ruta[$file - 1]);

$RutaAplicacion = $rutaFile;
$NombreAplicacion = $x_file;
$tiempoSession = $time; // 60 -> 1 minuto
if ($x_file != 'login.php') {
    if (isset($_SESSION['LOGINOK'])) {

        $antes = $_SESSION["SESION_TIME"];

        if (time() - $antes > $tiempoSession) {

            session_unset();
            session_destroy();
            $script = "<script>alert('Session Expirada debe Identificarse Nuevamente !');";
            $script .= "window.parent.location = \"" . CONST_PATH_SITIO . "login.php\";</script>";
            echo $script;
            die();

        } else {
            $_SESSION["SESION_TIME"] = time();

        }

    }
} else {return;}

include_once CONST_PATH_LIB . "conection.php";
$oMPG = new CMPG();

if (1 == 1) {
    if ($x_file == 'login.php') {return;}

    $_SESSION['ompg'] = serialize($oMPG);
    $x_user = unserialize($_SESSION['SES_usuario']);

    if ($x_user->CodPerfil == "") {return;}

    $SQL = "Select * from seguridad.spseg_verifica_acceso_aplicacion ";
    $SQL .= "('$RutaAplicacion','$NombreAplicacion',$x_user->CodPerfil) as permiso";
    $dbmun = unserialize($_SESSION['SES_dbmun']);
    $Resultado = $oMPG->SD_Recordset_query($dbmun, $SQL);
    $arrPermiso = explode('*', trim($Resultado[0]['permiso']));
    $CodAplicacion = $arrPermiso[0];
    $permiso = $arrPermiso[1];
    $_SESSION["SES_Cod_App"] = $CodAplicacion;

    if ($RutaAplicacion == '') {
        return;
    }

    if ($permiso == '2') { // Permitir Acceso ,Es una CArga de MENEU PRincipal
        return;
    } else {
        if ($permiso == '1' or $permiso == '3' or $CodAplicacion == 2210) { // Permitir Acceso a la Aplicacion
            $SQL1 = "select b.codigo_funcionalidad,b.nombre_funcionalidad
			from seguridad.seg_funcionalidad_perfil a
			inner join seguridad.seg_funcionalidad b on (a.codigo_funcionalidad=b.codigo_funcionalidad)
			where a.codigo_perfil=$x_user->CodPerfil
			and a.codigo_aplicacion=$CodAplicacion ";
            $Resultado2 = $oMPG->SD_Recordset_query($dbmun, $SQL1);
            $arr_funciones = array();

            for ($i = 0; $i < count($Resultado2); $i++) {
                $arr_funciones[] = $Resultado2[$i]["codigo_funcionalidad"];
            }

            $_SESSION['SES_PERMISO_FUNCIONALIDAD'] = $arr_funciones;

            $script = "<label>Acceso Permitido</label>";

        } else { // DENEGAR ACCESO A LA APLICACION
            header("Location: " . CONST_PATH_SITIO . "blockApp.php?msg=$permiso");
        }
    }
}

if (isset($_SESSION["SES_menu"])) {
    $barra = $oMPG->ImprimeBarraNavegacion($CodAplicacion, $x_user->User, $x_user->NombreArea);
    $menu = $_SESSION["SES_menu"]; //$oMPG->BarraNavega1.
    $arrMenu = explode('<!--*-->', $menu);
    $_SESSION["SES_menu"] = $arrMenu[0] . '<!--*-->' . $oMPG->BarraNavega2;
}
