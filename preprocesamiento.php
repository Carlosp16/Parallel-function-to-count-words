<?php 
$NODOS = 3;
$map2;  
$correcto = true;
$palabra = $_POST["palabra"];
$map3;
function map($palabra,$cantidad)
{
 global $map3;
 global $map4;
 global $entro;
 $longitudmaps=sizeof($map4);
$entro=0;
for ($row = 0; $row < $longitudmaps; $row++) {
  $longitudcols=sizeof($map4[$row]);

    if($map4[$row][0][0]==$palabra){
        $map4[$row][$longitudcols][0]=$palabra;
        $map4[$row][$longitudcols][1]=1;
        $entro=1;
        // print_r ($map4);
    }
}
    if($entro==0){
        $map4[$longitudmaps][0][0]=$palabra;
        $map4[$longitudmaps][0][1]=1;
    }

  if($longitudmaps==0){
    $map4[0][0][0]=$palabra;
    $map4[0][0][1]=$cantidad;
    //echo "Asigno";
  } 
}
function reduce($mapr,$ind)
{
 global $map5;
$longitudmapr=count($mapr);
$map5[$ind][0]=$mapr[0][0];
$map5[$ind][1]=$longitudmapr;
}
if ($_POST["es"] == "texto") { // VERIFICO SI SE RECIBIO UN ARCHIVO O UN TEXTO

	$texto = $_POST["texto"];

} elseif ($_POST["es"] == "file") {

	$target_file = basename($_FILES["file"]["name"]);

	if (pathinfo($target_file,PATHINFO_EXTENSION) != "txt") { // VERIFICO QUE SEA UN ARCHIVO DE TEXTO

		echo "El archivo subido no es un archivo de texto";
		$correcto = false;

	} else {

		$fp = fopen($_FILES["file"]["tmp_name"], "r");
		$texto = "";

		while(!feof($fp)) {

			$linea = fgets($fp);

			$texto = $texto." ".$linea;
		}

		fclose($fp);
	}
}

if ($correcto) { // LIMPIEZA DE LOS DATOS, ELIMINO ESPACIOS BLANCOS DE MAS, LOS CARACTERES ESPECIALES Y LOS NUMEROS, ADEMAS DE CONVERTIR TODO A MINUSCULAS
	$regex = '/[^A-Za-z0-9]/';
	$texto = trim($texto);
	$texto = preg_replace($regex, " ", $texto);
	$texto = preg_replace('/\s+/', " ", $texto);
	$texto = strtolower($texto);
	$palabra = strtolower($palabra);


	$array = str_word_count($texto, 1);
	$cantidad = ceil((count($array) / $NODOS));

	$chunks = array_chunk($array, $cantidad);
      $longitudini=count($chunks);

	for ($row = 0; $row < $longitudini; $row++) {
  		$longitudini2=count($chunks[$row]);
		for ($col = 0; $col < $longitudini2; $col++) {
    			$map2[$row][$col][0]=$chunks[$row][$col];
    			$map2[$row][$col][1]=1;
  		}
	}
	// CHUNKS TIENE $NODOS ARRAYS, AQUI SE DEBERIA HACER LA LLAMADA A MAP CON CADA PEDAZO DEL TEXTO INICIAL.
	  $longitudmap2=count($map2);
for ($row = 0; $row < $longitudmap2; $row++) {
  $longitudcol2=count($map2[$row]);

  for ($col = 0; $col < $longitudcol2; $col++) {
    map($map2[$row][$col][0],$map2[$row][$col][1]);// Aqui se hace la llamada paralela, si tuvieramos varios nodos
  }
}
// Viene reduce
  $longitudmap4=count($map4);
for ($row = 0; $row < $longitudmap4; $row++) {
  $longitudcol4=count($map4[$row]);
    reduce($map4[$row],$row);// Aqui se hace la llamada paralela, si tuvieramos varios nodos
}
$longitudmap5=count($map5);
for ($row = 0; $row < $longitudmap5; $row++) {
    if($map5[$row][0]==$_POST['palabra']){
    	    echo $map5[$row][0];
    echo ", ";
    echo $map5[$row][1];
    }
}

}


?>