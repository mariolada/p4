<?php
session_start();

class BaseDatos {

    protected $database;
    protected $formulario;
    public function __construct() {
      
        $this->formulario = "";
    }

    public function crearBase() {
        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","test");
        if($this->database->connect_error){
            echo "Ha ocurrido un error en la conexión: ".$this->database->connect_error;
        }
        $sqlCrearBase = "CREATE DATABASE IF NOT EXISTS EJERCICIO6 COLLATE utf8_spanish_ci";

       if($this->database->query($sqlCrearBase)===TRUE) {

       } else {
        echo "<p>Error en la creación de la base de datos</p>";

       }

    }

    public function crearTabla() {
        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio6");
        if($this->database->connect_error){
            echo "Ha ocurrido un error en la conexión: ".$this->database->connect_error;
        }
        $sqlCrearTabla = "CREATE TABLE IF NOT EXISTS PruebasUsabilidad(
            DNI VARCHAR(255) ,
            Nombre VARCHAR(255),
            Apellidos VARCHAR(255),
            Email VARCHAR(255),
            Telefono VARCHAR(255),
            Edad int,
            Sexo VARCHAR(255),
            NivelInformatica int,
            TiempoTarea int,
            PasaPrueba VARCHAR(255),
            Comentario VARCHAR(255),
            PropuestaMejora VARCHAR(255),
            Puntuacion int,
            PRIMARY KEY(DNI),
            CHECK (Edad>=0 and Edad <= 120 and ((Sexo='masculino') or (Sexo='femenino')) and (PasaPrueba = 'si' or PasaPrueba = 'no') and (Puntuacion>=0 or Puntuacion <=10))
            );
            ";

        $this->database->real_query($sqlCrearTabla);
    }
    public function cargarDatos($archivo) {
        if($archivo['type']==="application/vnd.ms-excel") {
        $file = fopen($archivo['tmp_name'],'r');
        while(($linea = fgetcsv($file))!==FALSE) {
            $dni = $linea[0];
            $nombre = $linea[1];
            $apellidos = $linea[2];
            $email = $linea[3];
            $telefono = $linea[4];
            $edad = $linea[5];
            $sexo = $linea[6];
            $nivelInformatica = $linea[7];
            $tiempoDeTarea = $linea[8];
            $pruebaRealizadaCorrectamente = $linea[9];
            $com = $linea[10];
            $proposicionMejora = $linea[11];
            $puntuacion = $linea[12];
            $this->insertarDatos($dni, $nombre, $apellidos,  $email,$telefono, $edad,$sexo,$nivelInformatica,$tiempoDeTarea, $pruebaRealizadaCorrectamente, $com,  $proposicionMejora, $puntuacion  );
        }
        fclose($file);
    }
    }
    public function  insertarDatos($dni, $nombre, $apellidos,  $email,$telefono, $edad,$sexo,$info,$tiempoDeTarea, $pruebaRealizadaCorrectamente, $com,  $proposicionMejora, $puntuacion) {
        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio6");
        if($this->database->connect_error){
            echo "Ha ocurrido un error en la conexión: ".$this->database->connect_error;
        }
        $insercion = $this->database->prepare('INSERT INTO pruebasusabilidad (DNI, nombre, apellidos, email, telefono, edad, sexo, nivelInformatica, tiempoTarea, pasaPrueba, comentario,propuestaMejora, puntuacion)
        values (?,?,?,?,?,?,?,?,?,?,?,?,?)
        ');
        $insercion -> bind_param('sssssssssssss',$dni, $nombre, $apellidos,  $email,$telefono, $edad,$sexo,$info,$tiempoDeTarea, $pruebaRealizadaCorrectamente, $com,  $proposicionMejora, $puntuacion);
        $insercion ->execute();
        $this->formulario = "";
    }

    public function formularioInsertar() {
        $this->formulario = "
        <label for = 't1'> DNI: </label>
        <input type = 'text' id = 't1' name = 'dni'/>
        <label for = 't2'> Nombre: </label>
        <input type = 'text' id = 't2' name = 'nombre'/>
        <label for = 't3'> Apellidos: </label>
        <input type = 'text' id = 't3' name = 'apellidos'/>
        <label for = 't4'> email: </label>
        <input type = 'text' id = 't4' name = 'email'/>
        <label for = 't5'> Tlfno: </label>
        <input type = 'text' id = 't5' name = 'telefono'/>
        <label for = 't6'> Edad: </label>
        <input type = 'text' id = 't6' name = 'edad'/>
        <label for = 't7'> Sexo: </label>
        <input type = 'text' id = 't7' name = 'sexo'/>
        <label for = 't8'> Nivel de Informatica: </label>
        <input type = 'text' id = 't8' name = 'nivelinformatica'/>
        <label for = 't9'> Tiempo de Tarea: </label>
        <input type = 'text' id = 't9' name = 'tiempotarea'/>
        <label for = 't10'> Prueba superada: </label>
        <input type = 'text' id = 't10' name = 'pasaprueba'/>
        <label for = 't11'> Comentario: </label>
        <input type = 'text' id = 't11' name = 'comentario'/>
        <label for = 't12'> Proposiciones de Mejora: </label>
        <input type = 'text' id = 't12' name = 'mejoras'/>
        <label for = 't13'> Puntuacion: </label>
        <input type = 'text' id = 't13' name = 'puntuacion'/>
        
        <input type = 'submit' value = 'Insertar Datos' name = 'insertarDatosEnBase'>
        ";
    }
    public function formularioActualizar() {
        $this->formulario = "
        <label for = 't1'> DNI: </label>
        <input type = 'text' id = 't1' name = 'dni'/>
        <label for = 't2'> Nombre: </label>
        <input type = 'text' id = 't2' name = 'nombre'/>
        <label for = 't3'> Apellidos: </label>
        <input type = 'text' id = 't3' name = 'apellidos'/>
        <label for = 't4'> email: </label>
        <input type = 'text' id = 't4' name = 'email'/>
        <label for = 't5'> Tlfno: </label>
        <input type = 'text' id = 't5' name = 'telefono'/>
        <label for = 't6'> Edad: </label>
        <input type = 'text' id = 't6' name = 'edad'/>
        <label for = 't7'> Sexo: </label>
        <input type = 'text' id = 't7' name = 'sexo'/>
        <label for = 't8'> Nivel de Informatica: </label>
        <input type = 'text' id = 't8' name = 'nivelinformatica'/>
        <label for = 't9'> Tiempo de Tarea: </label>
        <input type = 'text' id = 't9' name = 'tiempotarea'/>
        <label for = 't10'> Prueba superada: </label>
        <input type = 'text' id = 't10' name = 'pasaprueba'/>
        <label for = 't11'> Comentario: </label>
        <input type = 'text' id = 't11' name = 'comentario'/>
        <label for = 't12'> Proposiciones de Mejora: </label>
        <input type = 'text' id = 't12' name = 'mejoras'/>
        <label for = 't13'> Puntuacion: </label>
        <input type = 'text' id = 't13' name = 'puntuacion'/>
        
        <input type = 'submit' value = 'Actualizar Dato' name = 'actualizarDatosEnBase'>
        ";
    }
    public function formularioEliminar() {
        $this->formulario = "
        <label for = 't1'> Introduce el DNI de la persona a Eliminar: </label>
        <input type = 'text' id = 't1' name = 'dni'/>
               
        <input type = 'submit' value = 'Eliminar' name = 'eliminarDatosEnBase'>
        ";
    }


    public function buscarDatos($dni){
        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio6");
        $busqueda = $this->database->prepare('SELECT * FROM pruebasUsabilidad where dni = ?;');
        $busqueda->bind_param('s',$dni);
        $busqueda->execute();
        $busqueda->bind_result($dniBuscado, $nombre, $apellidos,  $email,$telefono, $edad,$sexo,$info,$tiempoDeTarea, $pruebaRealizadaCorrectamente, $comentario,  $proposicionMejora, $puntuacion);
        $busqueda->fetch();
        $this->formulario = "<p>Persona DNI: <strong>".$dniBuscado.
        "</strong>, Nombre: <strong>".$nombre.
        "</strong>, Apellidos: <strong>".$apellidos.
        "</strong>, Email: <strong>".$email.
        "</strong>, Telefono: <strong>".$telefono.
        "</strong>, Edad: <strong>".$edad.
        "</strong>, Sexo: <strong>".$sexo.
        "</strong>, Nivel De Informatica: <strong>".$info.
        "</strong>, Tiempo de tarea: <strong>".$tiempoDeTarea.
        "</strong>, Prueba realizada correctamente: <strong>".$pruebaRealizadaCorrectamente.
        "</strong>, Comentario: <strong>".$comentario.
        "</strong>, Proposicion de Mejora: <strong>".$proposicionMejora.
        "</strong>, Puntuacion: <strong>".$puntuacion."</strong></p>";

    }

    public function formularioBuscar() {
        $this->formulario = "
        <label for = 't1'> Introduce el DNI de la persona a buscar: </label>
        <input type = 'text' id = 't1' name = 'dni'/>
               
        <input type = 'submit' value = 'Buscar' name = 'buscarDatosEnBase'>
        ";
    }

    public function modificarDatos($dni, $nombre, $apellidos,  $email,$telefono, $edad,$sexo,$info,$tiempoDeTarea, $pruebaRealizadaCorrectamente, $com,  $proposicionMejora, $puntuacion){
        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio6");
        if($this->database->connect_error){
            echo "Ha ocurrido un error en la conexión: ".$this->database->connect_error;
        }
        $actualizacion = $this->database->prepare('UPDATE  pruebasusabilidad set  nombre = ?, apellidos=?, email=?, telefono=?, edad=?, sexo=?, nivelInformatica=?, 
        tiempoTarea=?, pasaPrueba=?, comentario=?,propuestaMejora=?, puntuacion=? where DNI = ?)');
        $actualizacion -> bind_param('sssssssssssss',$nombre, $apellidos,  $email,$telefono, $edad,$sexo,$info,$tiempoDeTarea, $pruebaRealizadaCorrectamente, $com,  $proposicionMejora, $puntuacion,$dni);
        $actualizacion ->execute();
    

    }

    public function eliminarDatos($dni){
        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio6");
        if($this->database->connect_error){
            echo "Ha ocurrido un error en la conexión: ".$this->database->connect_error;
        }
        $eliminacion = $this->database->prepare('DELETE FROM pruebasUsabilidad where dni = ?;');
        $eliminacion->bind_param('s',$dni);
        $eliminacion->execute();
    }

    public function generarInforme(){
        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio6");
        $busqueda = $this->database->prepare("SELECT count(*) FROM pruebasUsabilidad");
        $busqueda->execute();
        $busqueda->bind_result($nfilas);
        $busqueda->fetch();
        if($nfilas>0) {

        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio6");
        $queryMediaEdad = $this->database->prepare("SELECT AVG(Edad)  FROM pruebasUsabilidad");
        $queryMediaEdad ->execute();
        $queryMediaEdad->bind_result($edadMedia);
        $queryMediaEdad->fetch();
        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio6");
        $queryMediaTiempo= $this->database->prepare("SELECT AVG(tiempoTarea)  FROM pruebasUsabilidad");
        $queryMediaTiempo ->execute();
        $queryMediaTiempo->bind_result($tiempoMedio);
        $queryMediaTiempo->fetch();
        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio6");
        $queryNivelMedio= $this->database->prepare("SELECT AVG(NivelInformatica)  FROM pruebasUsabilidad");
        $queryNivelMedio ->execute();
        $queryNivelMedio->bind_result($nivelinformaticoMedio);
        $queryNivelMedio->fetch();
        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio6");
        $queryPuntuacionMedia= $this->database->prepare("SELECT AVG(puntuacion)  FROM pruebasUsabilidad");
        $queryPuntuacionMedia ->execute();
        $queryPuntuacionMedia->bind_result($puntuacionMedia);
        $queryPuntuacionMedia->fetch();
        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio6");
        $queryNHombres= $this->database->prepare("SELECT count(*)  FROM pruebasUsabilidad where sexo = 'masculino'");
        $queryNHombres ->execute();
        $queryNHombres->bind_result($nHombres);
        $queryNHombres->fetch();

        $porcentajeHombres = ($nHombres/(float)$nfilas)*100;
        $porcentajeMujeres = 100-$porcentajeHombres;
        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio6");
        $queryTerminaronTarea= $this->database->prepare("SELECT count(*)  FROM pruebasUsabilidad where pasaPrueba = 'si'");
        $queryTerminaronTarea ->execute();
        $queryTerminaronTarea->bind_result($nTerminaronTarea);
        $queryTerminaronTarea->fetch();

        $porcentajePasaronPrueba = ($nTerminaronTarea/$nfilas)*100;
        $porcentajeNoPasaronPrueba = 100 - $porcentajePasaronPrueba;




        $this->formulario = "<table> <caption>Informe Base De Datos</caption
        <tr>
        <th> Edad Media </th>
        <th> Hombres (%) </th>
        <th> Mujeres (%) </th>
        <th> Media Nivel Informático </th>
        <th> Tiempo Medio para Tarea(min) </th>
        <th> Terminaron la tarea(%) </th>
        <th> No terminaron la tarea(%) </th>
        <th> Media Puntuación(0-10) </th>
    </tr>
    <tr>
    <th> $edadMedia </th>
    <th> $porcentajeHombres </th>
    <th> $porcentajeMujeres </th>
    <th> $nivelinformaticoMedio </th>
    <th> $tiempoMedio</th>
    <th>  $porcentajePasaronPrueba  </th>
    <th>  $porcentajeNoPasaronPrueba  </th>
    <th> $puntuacionMedia </th>
</tr>
</table>";
        }

          


    }

    public function exportarDatos(){
        $this->formulario = "";
        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio6");
        $datos = $this->database->query("SELECT * FROM pruebasUsabilidad");
        while($datosFila = $datos->fetch_row()) {
            $this->formulario .= $datosFila[0].";".
            $datosFila[1].",".
            $datosFila[2].",".
            $datosFila[3].",".
            $datosFila[4].",".
            $datosFila[5].",".
            $datosFila[6].",".
            $datosFila[7].",".
            $datosFila[8].",".
            $datosFila[9].",".
            $datosFila[10].",".
            $datosFila[11].",".
            $datosFila[12]."\n";

        }
        $datos->close();
        file_put_contents("datosExportados.csv", $this->formulario);
        $this->formulario = "";


    }

    public function getFormulario(){
        return $this->formulario;
    }
    

}


if(isset($_SESSION['BaseDeDatos'])) {
    $_SESSION['BaseDeDatos'] = new BaseDatos();
} else {
    $_SESSION['BaseDeDatos'] = new BaseDatos();
}

$formularioActual = "";
if(count($_POST)>0) {
    if(isset($_POST['crearBase'])) $_SESSION['BaseDeDatos']->crearBase();
    if(isset($_POST['crearTabla'])) $_SESSION['BaseDeDatos']->crearTabla();
    if(isset($_POST['envioDatos'])) {
        if ($_FILES) {
            $_SESSION['BaseDeDatos']->cargarDatos($_FILES['archivo']);
        }
    } 
    if(isset($_POST['insertarDatos'])) $_SESSION['BaseDeDatos']->formularioInsertar();
    if(isset($_POST['modificarDatos'])) $_SESSION['BaseDeDatos']->formularioActualizar();
    if(isset($_POST['buscarDatos'])) $_SESSION['BaseDeDatos']->formularioBuscar();
    if(isset($_POST['eliminarDatos'])) $_SESSION['BaseDeDatos']->formularioEliminar();
    if(isset($_POST['generarInforme'])) $_SESSION['BaseDeDatos']->generarInforme();
    if(isset($_POST['exportarDatos'])) $_SESSION['BaseDeDatos']->exportarDatos();

    if(isset($_POST['insertarDatosEnBase'])) $_SESSION['BaseDeDatos']->insertarDatos($_POST['dni'], $_POST['nombre'],$_POST['apellidos'],$_POST['email'],$_POST['telefono'],$_POST['edad'],$_POST['sexo'],
    $_POST['nivelinformatica'],$_POST['tiempotarea'],$_POST['pasaprueba'],$_POST['comentario'],$_POST['mejoras'],$_POST['puntuacion']);

    if(isset($_POST['actualizarDatosEnBase'])) $_SESSION['BaseDeDatos']->modificarDatos($_POST['dni'], $_POST['nombre'],$_POST['apellidos'],$_POST['email'],$_POST['telefono'],$_POST['edad'],$_POST['sexo'],
    $_POST['nivelinformatica'],$_POST['tiempotarea'],$_POST['pasaprueba'],$_POST['comentario'],$_POST['mejoras'],$_POST['puntuacion']);

    if(isset($_POST['eliminarDatosEnBase'])) $_SESSION['BaseDeDatos']->eliminarDatos($_POST['dni']);

    if(isset($_POST['buscarDatosEnBase'])) $_SESSION['BaseDeDatos']->buscarDatos($_POST['dni']);

    $formularioActual = $_SESSION['BaseDeDatos']->getFormulario();
    
}

echo "
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8' />
    <meta name ='viewport' content ='width=device-width, initial scale=1.0' />
    <title>Ejercicio 6</title>
	<link rel='stylesheet' type='text/css' href='Ejercicio6.css' />
	<meta name = 'author' content = 'Mario Lada Martínez' />
	<meta name = 'description' content = 'Ejercicio 6'/>
	<meta name='keywords' content='Ejercicio 6'/>

</head>
<body>
<h1>Creación Base de datos</h1>
<p>He añadido algunas constraints para verificar por ejemplo que el sexo es o 'masculino' o 'femenino' que la edad es mayor que 0</p>
<p>o que el campo pasa prueba puede ser 'si' o 'no' y que la puntuacion es entre 0 y 10. Es importante saber esta información a la hora de insertar datos</p>
<label for = 'botonfile'>Mario Lada Martínez</label>
<form action = '#' method = 'post' enctype='multipart/form-data'>
<input type='submit' value='Crear Base' name='crearBase'/>
<input type='submit' value='Crear Tabla' name='crearTabla'/>

<input type='file' id = 'botonfile' name='archivo'  />
<input type='submit' value='Enviar Datos a cargar' name='envioDatos'  />
<input type='submit' value='Buscar Datos' name='buscarDatos'/>
<input type='submit' value='Insertar Datos' name='insertarDatos'/>
<input type='submit' value='Modificar Datos' name='modificarDatos'/>
<input type='submit' value='Eliminar Datos' name='eliminarDatos'/>
<input type='submit' value='Generar Informe' name='generarInforme'/>
<input type='submit' value='Exportar Datos' name='exportarDatos'/>
$formularioActual
</form>
<footer>  
<p>UO275874, Lector archivos, Universidad de Oviedo</p>
<address>
  <p> Información de contacto:
  <a href='mailto:UO275874@uniovi.es'>
  Mario Lada Martínez</a></p>
  </address>
</footer>  
</body>
</html>
"
?>