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

$NumberTicket =$_POST["NumeroTicket"];
$FolioCredito =$_POST["FolioCredito"];
$Tratamiento=$_POST["TramientoTicket"];
$Titular=$_POST["TitularCredito"];
$Abono=$_POST["AbonoTicket"];
$Saldo=$_POST["SaldoTicket"];
$Vendedor=$_POST["VendedorTicket"];
$SaldoAnterior=$_POST["SaldoActualTicket"];
$validez=$_POST["FechaValidez"];
$HoraImpresion=$_POST["Horadeimpresion"];

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

$printer->text("¡Gracias por confiar ");
$printer -> feed(1);
$printer->text("su salud en nosotros! ");
$printer -> feed(1);
$printer->text("Calle 25 S/N entre 26 y 26a" . "\n");
$printer->text("Ticul,Yucatán " . "\n");
$printer->text("CP:97860" . "\n");
$printer->text("facturacion@doctorconsulta.mx" . "\n");
$printer->text("RFC:CLA1807241L2" . "\n");
$printer->text("9971061489" . "\n");
#La fecha también
$printer->text("--------------------------------"); 
$printer -> feed(1);
$printer->text("No. TICKET : $NumberTicket"); 
$printer -> feed(1);
$printer->text(fechaCastellano(date("Y-m-d") . "\n"));
$printer -> feed(1);
$printer->text($HoraImpresion . "\n");
$printer->text("--------------------------------"); 
$printer->text("Abono de crédito"); 
$printer -> feed(1);
$printer->text("--------------------------------"); 
$printer->text("Folio de crédito: $FolioCredito" . "\n");
$printer->text("Tratamiento:$Tratamiento" . "\n");
$printer->text("Titular: $Titular" . "\n");

$printer->text("--------------------------------"); 
$printer->text("Saldo anterior: $". $SaldoAnterior ."\n");
$printer->text("Abono: $". $Abono ."\n");
$printer->text("Saldo nuevo: $". $Saldo ."\n");
$printer->text("--------------------------------"); 
$printer->text("Estimado $Titular" . "\n");
$printer->text("Le recordamos que la vigencia de su crédito es hasta $validez" . "\n");
$printer->text("--------------------------------"); 
$printer->text("Firma del paciente"); 
$printer -> feed(1);
$printer->text("________________________________"); 
/*
	Podemos poner también un pie de página
*/
$printer->text("Muchas gracias por su compra");
$printer -> feed(1);
$printer->text("Le atendió $Vendedor");
$printer -> feed(1);
$printer->text("Visítanos en doctorconsulta.mx");
$printer -> feed(1);
$printer -> setTextSize(1,1);
$printer->text("* Este ticket es una reimpresión del original *");

$printer -> feed(1);
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