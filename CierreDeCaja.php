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
$Sucursal=$_POST["Sucursal"];
$HoraImpresion=$_POST["Horadeimpresion"];
$TotalFinal=$_POST["TicketVentasTotl"];
$TotalTickets=$_POST["TotalTicketsTickets"];
$TicketInicial=$_POST["TicketInicialTicket"];
$TicketFinal=$_POST["TicketFinalTicket"];
$Servicio=$_POST["NombreServicio"];
$TotalServicios=$_POST["TotalServicio"];
$TotalCreditosDentales=$_POST["Totaldentales"];
$TotalEnfermeros=$_POST["TotalCreditoEnfermeria"];
$Turno=$_POST["TurnoCorteticket"];

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
 

 /**
 * Arrange ASCII text into columns
 * 
 * @param string $leftCol
 *            Text in left column
 * @param string $rightCol
 *            Text in right column
 * @param number $leftWidth
 *            Width of left column
 * @param number $rightWidth
 *            Width of right column
 * @param number $space
 *            Gap between columns
 * @return string Text in columns
 */
function columnify($leftCol, $rightCol, $leftWidth, $rightWidth, $space = 4)
{
    $leftWrapped = wordwrap($leftCol, $leftWidth, "\n", true);
    $rightWrapped = wordwrap($rightCol, $rightWidth, "\n", true);

    $leftLines = explode("\n", $leftWrapped);
    $rightLines = explode("\n", $rightWrapped);
    $allLines = array();
    for ($i = 0; $i < max(count($leftLines), count($rightLines)); $i ++) {
        $leftPart = str_pad(isset($leftLines[$i]) ? $leftLines[$i] : "", $leftWidth, " ");
        $rightPart = str_pad(isset($rightLines[$i]) ? $rightLines[$i] : "", $rightWidth, " ");
        $allLines[] = $leftPart . str_repeat(" ", $space) . $rightPart;
    }
    return implode($allLines, "\n") . "\n";
}
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
$printer->text("Cierre de caja"); 
$printer -> feed(1);
$printer->text("Cajero: $Vendedor" . "\n");
$printer->text("Turno: $Turno" . "\n");
$printer->text("Sucursal: $Sucursal" . "\n");
$printer->text("Total de venta: $ $TotalFinal" . "\n");
$printer->text("Total de tickets: $TotalTickets" . "\n");
$printer->text("Ticket inicial: N° $TicketInicial" . "\n");
$printer->text("Ticket final: N° $TicketFinal" . "\n");
$printer -> setEmphasis(true);
$printer -> text("Desglose de servicios\n");


$printer -> setEmphasis(false);
foreach ($Servicio as $key => $value) {
   
  $printer->text("$value\n"); 
  $printer ->text("$$TotalServicios[$key]\n");



}
$printer -> setEmphasis(true);
$printer -> text("Desglose de créditos\n");
$printer -> feed(1);
if($TotalEnfermeros == ""){
  $printer -> text("Por el momento no existen creditos de enfermeria \n");
}  else{
  $printer->text("Crédito Enfermeria\n"); 
  $printer -> setEmphasis(false);
  $printer->text("$$TotalEnfermeros\n"); 
  $printer -> setEmphasis(true);
}

if($TotalCreditosDentales == ""){
  $printer -> text("Por el momento no existen abonos dentales \n");
}  else{
  $printer->text("Abonos dentales\n"); 
  $printer -> setEmphasis(false);
  $printer->text("$$TotalCreditosDentales\n"); 
  $printer -> setEmphasis(true);
}



/*
	Podemos poner también un pie de página
*/

 
/*Alimentamos el papel 3 veces*/
$printer->feed(3);
 
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