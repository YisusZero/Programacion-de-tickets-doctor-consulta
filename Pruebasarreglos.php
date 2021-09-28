<?php
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
 $importeuser = $_POST['ImporteT'];
 
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


foreach ($materia as $key => $value) {
   
   
    echo $value . "<br>";
 echo $totalProd[$key];    echo $Preciounitario[$key] ;     echo $importeuser[$key];     echo $TotalEfectivo[$key] ."<br>"; ;
  }
  


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



