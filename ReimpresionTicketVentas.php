<?php
date_default_timezone_set("America/Monterrey");
$fcha = date("Y-m-d");
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
  $materia = $_POST['NombreProd'];
 $totalProd= $_POST['CantidadTotal'];
 $Preciounitario= $_POST['pro_cantidad'];
 $descuento= $_POST['Descuento'];
 $importeuser = $_POST['ImporteT'];
$Formadepago =$_POST['Formadepago'];
  $NumberTicket =$_POST["TicketVal"];
  $HoraImpresion=$_POST["Horadeimpresion"];

 $i=0;
 foreach ($_POST["TotalVentas"] as $TotalEfectivo) {
    ${"totlapago".$i} = $TotalEfectivo;
     $i++;
  }
  foreach ($_POST["PagoReal"] as $ValorEfectivo) {
    ${"pagare".$i} = $ValorEfectivo;
     $i++;
  }

  foreach ($_POST["Cambio"] as $Cambio) {
    ${"cambioo".$i} = $Cambio;
     $i++;
  }
  foreach ($_POST["Vendedor"] as $Vendedor) {
    ${"vendido".$i} = $Vendedor;
     $i++;
  }

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
$logo = EscposImage::load("logoticketsv3.png", false);
$logo2 = EscposImage::load("whats.png", false);
$logo3 = EscposImage::load("facebook.png", false);
$logo4 = EscposImage::load("www.png", false);
$printer->bitImage($logo);
$printer -> feed(1);
/* Information for the receipt */

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

/* Title of receipt */

$printer -> setEmphasis(true);
$printer -> text("CANT   PCIO U.  %DESC  IMPORTE\n");
$printer -> setEmphasis(false);

$printer -> setEmphasis(false);
foreach ($materia as $key => $value) {
   
  $printer->text("$value\n"); 
  $leftCol = "$totalProd[$key]     $Preciounitario[$key]     $descuento[$key]";
  $rightCol = "$importeuser[$key]";


  $printer->text(columnify($leftCol,$rightCol, 22, 32, 4));
}






$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> setEmphasis(true);

$printer -> setEmphasis(false);

$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
$printer -> text("TOTAL: $TotalEfectivo");
$printer -> selectPrintMode();
$printer -> setJustification(Printer::JUSTIFY_CENTER);
$printer -> feed(1);
$printer->text("<<<<<<<<FORMAS DE PAGO>>>>>>>>\n"); 

if($Formadepago == "Efectivo"){
  $printer -> text("EFECTIVO  CHEQUE  TARJETA  \n");
  $printer -> text("$ValorEfectivo  0.00  0.00 \n");
  $printer -> text("CREDITO VALE  TRANSF  \n");
  $printer -> text("0.00  0.00  0.00 \n");
} 
if($Formadepago == "Cheque"){
  $printer -> text("EFECTIVO  CHEQUE  TARJETA  \n");
  $printer -> text("0.00 $ValorEfectivo  0.00 \n");
  $printer -> text("CREDITO VALE  TRANSF  \n");
  $printer -> text("0.00  0.00  0.00 \n");
} 
if($Formadepago == "Tarjeta"){
  $printer -> text("EFECTIVO  CHEQUE  TARJETA  \n");
  $printer -> text("0.00   0.00 $ValorEfectivo\n");
  $printer -> text("CREDITO VALE  TRANSF  \n");
  $printer -> text("0.00  0.00  0.00 \n");
} 

if($Formadepago == "Credito"){
  $printer -> text("EFECTIVO  CHEQUE  TARJETA  \n");
  $printer -> text(" 0.00       0.00     0.00\n");
  $printer -> text("CREDITO VALE  TRANSF  \n");
  $printer -> text(" $ValorEfectivo 0.00  0.00  \n");
} 
if($Formadepago == "Vale"){
  $printer -> text("EFECTIVO  CHEQUE  TARJETA  \n");
  $printer -> text(" 0.00       0.00     0.00\n");
  $printer -> text("CREDITO VALE  TRANSF  \n");
  $printer -> text(" 0.00 $ValorEfectivo 0.00  \n");
} 
if($Formadepago == "Transferencia"){
  $printer -> text("EFECTIVO  CHEQUE  TARJETA  \n");
  $printer -> text(" 0.00       0.00     0.00\n");
  $printer -> text("CREDITO VALE  TRANSF  \n");
  $printer -> text("  0.00  0.00  $ValorEfectivo\n");
} 

$printer->text("--------------------------------"); 
$printer -> feed(1);
$printer -> text("CAMBIO: $Cambio \n");
$printer->text("Muchas gracias por su compra");
$printer -> feed(1);
$printer->text("Le atendió $Vendedor");
$printer -> feed(1);
$printer->bitImageColumnFormat($logo2); 
$printer->text("999-130-5852 \n");
$printer->bitImageColumnFormat($logo3); 
$printer->text("Doctor Consulta \n");
$printer->bitImageColumnFormat($logo4); 
$printer->text("Visítanos en doctorconsulta.mx");
 
$printer -> feed(1);
$printer -> setTextSize(1,1);
$printer->text("* Este ticket es una reimpresión del original *");

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

/* A wrapper to do organise item names & prices into columns */

?>
<script>
    
        setTimeout(function(){
            window.close();
        },1000); 


</script>



