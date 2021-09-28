<?php
date_default_timezone_set("America/Monterrey");
function fechaCastellano ($fecha) {
    $fecha = substr($fecha, 0, 10);
    $numeroDia = date('d', strtotime($fecha));
    $dia = date('l', strtotime($fecha));
    $mes = date('F', strtotime($fecha));
    $anio = date('Y', strtotime($fecha));
    $dias_ES = array("Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado", "Domingo");
    $dias_EN = array("Monday", "Tuesday", "Wednesday", "Thursday", "Friday", "Saturday", "Sunday");
    $nombredia = str_replace($dias_EN, $dias_ES, $dia);
  $meses_ES = array("Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre");
    $meses_EN = array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");
    $nombreMes = str_replace($meses_EN, $meses_ES, $mes);
    return $nombredia." ".$numeroDia." de ".$nombreMes." de ".$anio;
  }


$Vendedor=$_POST["VendedorTicket"];
$Sucursal=$_POST["SucursalApertura"];
$FondoAsignado=$_POST["FondoBase"];
$HoraImpresion=$_POST["Horadeimpresion"];
$TotalInicial=$_POST["TotalCajaDeApertura"];
$Turno=$_POST["TurnoTicket"];

require __DIR__ . '/autoload.php'; //Nota: si renombraste la carpeta a algo diferente de "ticket" cambia el nombre en esta línea
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\Printer;


 
/*
	Vamos a simular algunos productos. Estos
	podemos recuperarlos desde $_POST o desde
	cualquier entrada de datos. Yo los declararé
	aquí mismo
*/
 

 
$nombre_impresora = "XP-58";

$connector = new WindowsPrintConnector($nombre_impresora);
$printer = new Printer($connector);
$printer->setJustification(Printer::JUSTIFY_CENTER);

$logo = EscposImage::load("logoticketsv3.png", false);
$printer->bitImage($logo);
$printer -> feed(1);

/*
	Ahora vamos a imprimir un encabezado
*/



$printer->text(fechaCastellano(date("Y-m-d") . "\n"));
$printer -> feed(1);
$printer->text($HoraImpresion . "\n");
$printer->text("--------------------------------"); 
$printer->text("Apertura de caja"); 
$printer -> feed(1);
$printer->text("Vendedor: $Vendedor" . "\n");
$printer->text("Sucursal: $Sucursal" . "\n");
$printer->text("Turno: $Turno" . "\n");
$printer->text("Fondo de caja: $ $FondoAsignado" . "\n");
$printer->text("Valor en caja: $ $TotalInicial" . "\n");


/*
	Podemos poner también un pie de página
*/

 
/*Alimentamos el papel 3 veces*/
$printer->feed(4);
 
/*
	Cortamos el papel. Si nuestra impresora
	no tiene soporte para ello, no generará
	ningún error
*/
$printer->cut();
 
/*
	Por medio de la impresora mandamos un pulso.
	Esto es útil cuando la tenemos conectada
	por ejemplo a un cajón
*/
$printer->pulse();
 
/*
	Para imprimir realmente, tenemos que "cerrar"
	la conexión con la impresora. Recuerda incluir esto al final de todos los archivos
*/
$printer->close();
?>

<script>
    
        setTimeout(function(){
            window.close();
        },1000); //Dejara un tiempo de 3 seg para que el usuario vea que se envio el formulario correctamente


</script>