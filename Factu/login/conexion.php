<DOCTYPE HTML>
<meta charset = "utf8" />
<?php  
// crear conexion con oracle
$conexion = oci_connect("C##LOGIN", "2003", "localhost/xe"); 
 
if (!$conexion) {    
  $m = oci_error();    
  echo $m['message'], "n";    
  exit; 
} else {    
  echo "Conexi�n con �xito a Oracle!"; } 


?>
