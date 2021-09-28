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


  $NumberTicket =$_POST["NumeroTicketC"];
  $HoraImpresion=$_POST["HoradeimpresionC"];
  $Titular=$_POST["TitularCreditoC"];
  $Abono=$_POST["Abonoleticket"];
 
  $Vendedor=$_POST["VendedorTicketC"];
  $SaldoAnterior=$_POST["SaldoActualTicketC"];
  $Producto=$_POST["Nombre_ProdTicket"];
  $PrecioUni=$_POST["PrecioTicket"];
  $CantidadProd=$_POST["CantidadTicket"];
  $TotalProd=$_POST["Abonoleticket"];
  
  
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
$items = array(
    new item("$CantidadProd","$PrecioUni","0.00", "$TotalProd"),
  
);


$nombre_impresora = "XP-58";
$connector = new WindowsPrintConnector($nombre_impresora);
$printer = new Printer($connector);
$printer->setJustification(Printer::JUSTIFY_CENTER);

$logo = EscposImage::load("logoticketsv3.png", false);
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
$printer -> text("$Producto\n");
/* Items */
$printer -> setJustification(Printer::JUSTIFY_LEFT);
$printer -> setEmphasis(true);

$printer -> setEmphasis(false);
foreach ($items as $item) {
    $printer -> text($item);
}

$printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
$printer -> text("TOTAL: $TotalProd");
$printer -> selectPrintMode();
$printer -> setJustification(Printer::JUSTIFY_CENTER);
$printer -> feed(1);
$printer->text("<<<<<<<<DATOS DE CREDITO>>>>>>>>"); 

$printer->text("Saldo anterior: $". $SaldoAnterior ."\n");
$printer->text("Saldo nuevo: $". $Abono ."\n");
$printer->text("--------------------------------"); 
$printer->text("Nombre y firma"); 
$printer -> feed(1);
$printer->text("________________________________"); 
$printer -> feed(1);
$printer->text("$Titular"); 
$printer -> feed(1);
$printer->text("--------------------------------"); 
$printer -> feed(1);
$printer->text("CAJERO");
$printer -> feed(1);
$printer->text("$Vendedor");
$printer->feed(4);
$printer -> cut();
$printer -> close();

/* A wrapper to do organise item names & prices into columns */
class item
{
    private $cantidad;
    private $price;
    private $descuento;
    private $total;
    private $dollarSign;

    public function __construct($cantidad = '', $price ='' ,$descuento = '',$total='',$dollarSign = false)
    {
        $this -> cantidad = $cantidad;
        $this -> price = $price;
        $this -> descuento = $descuento;
        $this -> total = $total;
        $this -> dollarSign = $dollarSign;
    }
    
    public function __toString()
    {
        $rightCols = 10; //Columna derecha
        $leftCols =5;  //Columna izquierda
        if ($this -> dollarSign) {
            $leftCols = $leftCols / 2- $rightCols / 2;
        }
        $left = str_pad($this -> cantidad, $leftCols) ;
        $right = str_pad($this -> price, $rightCols) ;
        $right2 = str_pad($this -> descuento, $rightCols) ;
        $sign = ($this -> dollarSign ? '' : '');
        $right3 = str_pad($sign . $this -> total, $rightCols) ;
      
        return "$left$right$right2$right3\n";
    }
}
?>

<script>
    
        setTimeout(function(){
            window.close();
        },1000); //Dejara un tiempo de 3 seg para que el usuario vea que se envio el formulario correctamente


</script>