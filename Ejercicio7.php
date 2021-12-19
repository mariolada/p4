<?php
//session_start();

class BaseDatos {

    protected $database;
    protected $formulario;
    public function __construct() {
      
        $this->formulario = "";
    }

    public function inicializarAplicacion() {
        $this->crearBase();
        $this->crearTabla();
        $this->crearBase();
        $this->cargarDatos();
    }

    public function crearBase() {
        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","test");
        if($this->database->connect_error){
            echo "Ha ocurrido un error en la conexión: ".$this->database->connect_error;
        }
        $sqlCrearBase = "CREATE DATABASE IF NOT EXISTS EJERCICIO7 COLLATE utf8_spanish_ci";

       if($this->database->query($sqlCrearBase)===TRUE) {
       } else {
        echo "<p>Error en la creación de la base de datos</p>";

       }

    }

    public function crearTabla() {
        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio7");
        if($this->database->connect_error){
            echo "Ha ocurrido un error en la conexión: ".$this->database->connect_error;
        }
        $sqlCrearTablaBanda = "CREATE TABLE IF NOT EXISTS Banda(
            idBanda VARCHAR(255) ,
            nombreBanda VARCHAR(255),
            nMiembros int,
            generoPreferido VARCHAR(255),
            PRIMARY KEY (idBanda)
            );";
            $this->database->real_query($sqlCrearTablaBanda);

        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio7");

            $sqlCrearTablaAlbum =  "CREATE TABLE IF NOT EXISTS Album(
                idAlbum VARCHAR(255),
                nombreAlbum VARCHAR(255) ,
                nCanciones int,
                duracion VARCHAR(255),
                genero VARCHAR(255)
                );
                ";
                
        $this->database->real_query($sqlCrearTablaAlbum);

        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio7");
            $sqlCrearTablaGira =  "CREATE TABLE IF NOT EXISTS Gira(
            idGira VARCHAR(255),
            idBanda VARCHAR(255),
            nombreGira VARCHAR(255),
            nConciertos int,
            PRIMARY KEY (idGira,idBanda),
            FOREIGN KEY (idBanda) REFERENCES Banda(idBanda)
            );";
            $this->database->real_query($sqlCrearTablaGira);
            
            $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio7");
            $sqlCrearTablaCancion =  "CREATE TABLE IF NOT EXISTS Cancion(
            idCancion VARCHAR(255),
            idBanda VARCHAR(255),
            idAlbum VARCHAR(255),
            nombreCancion VARCHAR(255),
            duracion VARCHAR(255),
            genero VARCHAR(255),    
            PRIMARY KEY (idCancion,idBanda,idAlbum),
            FOREIGN KEY (idBanda) REFERENCES Banda(idBanda) 
            );";
            $this->database->real_query($sqlCrearTablaCancion);

    }
    public function cargarDatos() {
        $file = fopen('albumes.csv','r');
        while(($linea = fgetcsv($file))!==FALSE) {
            $this->insertarAlbum($linea[0],$linea[1],$linea[2],$linea[3],$linea[4]);
        }
        fclose($file);
        $file = fopen('bandas.csv','r');
        while(($linea = fgetcsv($file))!==FALSE) {
            $this->insertarBanda($linea[0],$linea[1],$linea[2],$linea[3]);
        }
        fclose($file);
        $file = fopen('giras.csv','r');
        while(($linea = fgetcsv($file))!==FALSE) {
            $this->insertarGira($linea[0],$linea[1],$linea[2],$linea[3]);
        }
        fclose($file);
        $file = fopen('canciones.csv','r');
        while(($linea = fgetcsv($file))!==FALSE) {
            $this->insertarCancion($linea[0],$linea[1],$linea[2],$linea[3],$linea[4],$linea[5]);
        }
        fclose($file);
    
    }
    public function  insertarCancion($idCancion, $idBanda, $idAlbum,  $nombreCancion,$duracion, $genero) {
        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio7");
        if($this->database->connect_error){
            echo "Ha ocurrido un error en la conexión: ".$this->database->connect_error;
        }
        $insercion = $this->database->prepare('INSERT INTO cancion (idCancion, idBanda, idAlbum, nombreCancion, duracion, genero)
        values (?,?,?,?,?,?)
        ');
        $insercion -> bind_param('ssssss',$idCancion, $idBanda, $idAlbum,  $nombreCancion,$duracion, $genero);
        $insercion ->execute();
        $this->formulario = "";
    }
    public function formularioInsertarCancion() {
        $this->formulario = "
        <label for = 't1'> ID Cancion(Nombre): </label>
        <input type = 'text' id = 't1' name = 'idcancion'/>
        <label for = 't2'> ID banda: </label>
        <input type = 'text' id = 't2' name = 'idbanda'/>
        <label for = 't3'> ID Album: </label>
        <input type = 'text' id = 't3' name = 'idalbum'/>
        <label for = 't4'> Nombre: </label>
        <input type = 'text' id = 't4' name = 'nombrecancion'/>
        <label for = 't5'> Duracion: </label>
        <input type = 'text' id = 't5' name = 'duracion'/>
        <label for = 't6'> Genero: </label>
        <input type = 'text' id = 't6' name = 'genero'/>        
        <input type = 'submit' value = 'Insertar Cancion' name = 'insertarCancion'>
        ";
    }
    public function  insertarAlbum($idAlbum, $nombreAlbum, $nCanciones, $duracion,$genero) {
        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio7");
        if($this->database->connect_error){
            echo "Ha ocurrido un error en la conexión: ".$this->database->connect_error;
        }
        $insercion = $this->database->prepare('INSERT INTO album (idAlbum, nombreAlbum, nCanciones, duracion, genero)
        values (?,?,?,?,?)
        ');
        $insercion -> bind_param('sssss',$idAlbum, $nombreAlbum, $nCanciones,  $duracion,$genero);
        $insercion ->execute();
        $this->formulario = "";
    }
    public function formularioInsertarAlbum() {
        $this->formulario = "
        <label for = 't1'> ID Album(Nombre): </label>
        <input type = 'text' id = 't1' name = 'idalbum'/>
        <label for = 't2'> Nombre: </label>
        <input type = 'text' id = 't2' name = 'nombrealbum'/>
        <label for = 't3'> Nº canciones: </label>
        <input type = 'text' id = 't3' name = 'ncanciones'/>
        <label for = 't4'> Duracion: </label>
        <input type = 'text' id = 't4' name = 'duracion'/>
        <label for = 't5'> Genero: </label>
        <input type = 'text' id = 't5' name = 'genero'/>        
        <input type = 'submit' value = 'Insertar Album' name = 'insertarAlbum'>
        ";
    }

    public function mostrarCanciones() {
        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio7");
        $busqueda = $this->database->prepare("SELECT * FROM cancion");
        $busqueda->execute();
        $res = $busqueda->get_result();
        $this->formulario = "<table>
        <caption>Canciones</caption>
        <tr>
        <th>Nombre Cancion</th>
        <th>Banda</th>
        <th>Album</th>
        <th>Duracion</th>
        <th>Genero</th>
    </tr>
   ";
        while($datosFila = $res->fetch_array()) {
            $this->formulario .="<tr>";
            $this->formulario .= "<td>".$datosFila[0]."</td>";
            $this->formulario .= "<td>".$datosFila[1]."</td>";
            $this->formulario .= "<td>".$datosFila[2]."</td>";
            $this->formulario .= "<td>".$datosFila[4]."</td>";
            $this->formulario .= "<td>".$datosFila[5]."</td>";
            $this->formulario .="</tr>";
        }
        $this->formulario .= "</table>";
        
    }
    
    public function mostrarArtistas() {
        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio7");
        $busqueda = $this->database->prepare("SELECT * FROM banda");
        $busqueda->execute();
        $res = $busqueda->get_result();
        $this->formulario = "<table>
        <caption>Bandas/Artistas</caption>
        <tr>
        <th>Nombre Banda</th>
        <th>Nº miembros</th>
        <th>Genero Preferido</th>
    </tr>
   ";
        while($datosFila = $res->fetch_array()) {
            $this->formulario .="<tr>";
            $this->formulario .= "<td>".$datosFila[0]."</td>";
            $this->formulario .= "<td>".$datosFila[2]."</td>";
            $this->formulario .= "<td>".$datosFila[3]."</td>";
            $this->formulario .="</tr>";
        }
        $this->formulario .= "</table>";
        
    }

    public function mostrarAlbumes() {
        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio7");
        $busqueda = $this->database->prepare("SELECT * FROM album");
        $busqueda->execute();
        $res = $busqueda->get_result();
        $this->formulario = "<table>
        <caption>Album</caption>
        <tr>
        <th>Nombre Album/th>
        <th>Nº canciones</th>
        <th>Duracion</th>
        <th>Genero</th>
    </tr>
   ";
        while($datosFila = $res->fetch_array()) {
            $this->formulario .="<tr>";
            $this->formulario .= "<td>".$datosFila[0]."</td>";
            $this->formulario .= "<td>".$datosFila[2]."</td>";
            $this->formulario .= "<td>".$datosFila[3]."</td>";
            $this->formulario .= "<td>".$datosFila[4]."</td>";
            $this->formulario .="</tr>";
        }
        $this->formulario .= "</table>";
        
    }

    public function mostrarGiras() {
        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio7");
        $busqueda = $this->database->prepare("SELECT * FROM album");
        $busqueda->execute();
        $res = $busqueda->get_result();
        $this->formulario = "<table>
        <caption>Giras</caption>
        <tr>
        <th>Nombre Gira</th>
        <th>Banda</th>
        <th>Nº conciertos</th>
    </tr>
   ";
        while($datosFila = $res->fetch_array()) {
            $this->formulario .="<tr>";
            $this->formulario .= "<td>".$datosFila[0]."</td>";
            $this->formulario .= "<td>".$datosFila[1]."</td>";
            $this->formulario .= "<td>".$datosFila[3]."</td>";
            $this->formulario .="</tr>";
        }
        $this->formulario .= "</table>";
        
    }





    public function  insertarBanda($idBanda, $nombreBanda, $nMiembros, $generoPreferido) {
        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio7");
        if($this->database->connect_error){
            echo "Ha ocurrido un error en la conexión: ".$this->database->connect_error;
        }
        $insercion = $this->database->prepare('INSERT INTO banda (idBanda, nombreBanda, nMiembros, generoPreferido)
        values (?,?,?,?)
        ');
        $insercion -> bind_param('ssss',$idBanda, $nombreBanda, $nMiembros,  $generoPreferido);
        $insercion ->execute();
        $this->formulario = "";
    }   
     public function formularioInsertarBanda() {
        $this->formulario = "
        <label for = 't1'> ID Banda(Nombre): </label>
        <input type = 'text' id = 't1' name = 'idbanda'/>
        <label for = 't2'> Nombre: </label>
        <input type = 'text' id = 't2' name = 'nombrebanda'/>
        <label for = 't3'> Nº miembros: </label>
        <input type = 'text' id = 't3' name = 'nmiembros'/>
        <label for = 't4'> Genero preferido: </label>
        <input type = 'text' id = 't4' name = 'generopreferido'/>  
        <input type = 'submit' value = 'Insertar Banda' name = 'insertarBanda'>
        ";
    }
    public function  insertarGira($idGira, $idBanda, $nombreGira, $nConciertos) {
        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio7");
        if($this->database->connect_error){
            echo "Ha ocurrido un error en la conexión: ".$this->database->connect_error;
        }
        $insercion = $this->database->prepare('INSERT INTO gira (idGira, idBanda, nombreGira, nConciertos)
        values (?,?,?,?)
        ');
        $insercion -> bind_param('ssss',$idGira, $idBanda, $nombreGira,  $nConciertos);
        $insercion ->execute();
        $this->formulario = "";
    }
    public function formularioInsertarGira() {
        $this->formulario = "
        <label for = 't1'> ID Gira(Nombre): </label>
        <input type = 'text' id = 't1' name = 'idgira'/>
        <label for = 't2'> ID Banda: </label>
        <input type = 'text' id = 't2' name = 'idbanda'/>
        <label for = 't3'> Nombre: </label>
        <input type = 'text' id = 't3' name = 'nombregira'/>
        <label for = 't4'> Nº conciertos: </label>
        <input type = 'text' id = 't4' name = 'nconciertos'/>  
        <input type = 'submit' value = 'Insertar Gira' name = 'insertarGira'>
        ";
    }

    public function formularioCancionesDeAlbum() {
        $this->formulario = "
        <label for = 't1'> Introduzca el nombre del Album: </label>
        <input type = 'text' id = 't1' name = 'nombreAlbum'/>
        <input type = 'submit' value = 'Buscar canciones del Album' name = 'albumCanciones'>
        ";
    }

    public function buscarCancionesDeAlbum($idAlbum) {
        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio7");
        $busqueda = $this->database->prepare("SELECT idCancion, idBanda, genero, duracion FROM cancion where idAlbum = ?");
        $busqueda->bind_param('s',$idAlbum);
        $busqueda->execute();
        $res = $busqueda->get_result();
        $this->formulario = "<ul>";
        while($datosFila = $res->fetch_array()) {
            $this->formulario .= "<li>Nombre cancion: ". $datosFila[0].", Artista: ".$datosFila[1].", Genero: ".$datosFila[2].", duracion: ". $datosFila[3]."</li>";
        }
        $this->formulario .= "</ul>";

    }



    public function buscarCancionesQueMeGustenGenero($genero) {
        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio7");
        $busqueda = $this->database->prepare("SELECT idCancion, idBanda, genero FROM cancion where genero = ?");
        $busqueda->bind_param('s',$genero);
  
        $busqueda->execute();
        $res = $busqueda->get_result();
        $this->formulario = "";
        while($datosFila = $res->fetch_array()) {
            $this->formulario .= "<ul><li>Nombre cancion: ". $datosFila[0]."</li>
            <li>Nombre Artista: ". $datosFila[1]."</li>
            <li>Genero: ". $datosFila[2]."</li></ul>";
        }

      
      
    }
    public function formularioBuscarCancionesGenero() {
        $this->formulario = "
        <label for = 't1'> Introduce el género de una canción que te guste: </label>
        <input type = 'text' id = 't1' name = 'genero'/>
    
        <input type = 'submit' value = 'Buscar' name = 'buscarCanciones'>
        ";
    }

    public function buscarArtistasSimilares($nombreBanda) {
        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio7");
        $busqueda = $this->database->prepare("SELECT * FROM banda where idBanda = ?");
        $busqueda->bind_param('s',$nombreBanda);
        $busqueda->execute();
        $busqueda->bind_result($idBanda, $nombreBandaResult, $nMiembros,  $generoPreferido);
        $busqueda->fetch();

        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio7");
        $busqueda2 = $this->database->prepare("SELECT idBanda, nombreBanda, nMiembros, generoPreferido FROM banda where generoPreferido = ? and nombreBanda != ?");
        $busqueda2->bind_param('ss',$generoPreferido,$nombreBanda);
        $busqueda2->execute();
        $res = $busqueda2->get_result();
        $this->formulario = "<ul>";
        while($datosFila = $res->fetch_array()) {
            $this->formulario .= "<li>Nombre Banda: <strong>". $datosFila[0]."</strong></li>
            <li>Nº miembros: ". $datosFila[2]."</li>
            <li>Genero Preferido: ". $datosFila[3]."</li>
            <li>---------------------------</li>";
        }




        $this->formulario.= "</ul>";
      
    }
    public function formularioBuscarArtistasSimilares() {
        $this->formulario = "
        <label for = 't1'> Introduce una banda que te guste. Obtendrás una lista con artistas similares </label>
        <input type = 'text' id = 't1' name = 'nombreBanda'/>
    
        <input type = 'submit' value = 'Buscar' name = 'buscarArtistasSimilares'>
        ";
    }

    public function albumDeCancion($idCancion) {
        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio7");
        $busqueda = $this->database->prepare('SELECT album.idAlbum, album.nombreAlbum, album.nCanciones, album.duracion, album.genero FROM cancion, album where cancion.idcancion = ? and cancion.idAlbum = album.idAlbum;');
        $busqueda->bind_param('s',$idCancion);
        $busqueda->execute();
        $busqueda->bind_result($idAlbum, $nombreAlbum, $nCanciones,  $duracion, $genero);
        $busqueda->fetch();
        $this->formulario = "<p>Nombre Album: <strong>".$nombreAlbum."</strong>, Nº canciones: ".$nCanciones.", Genero: ".$genero."</p>";

    }

    public function formularioAlbumDeCancion() {
        $this->formulario = "
        <label for = 't1'> Introduce el nombre de la canción de la que quieras saber su álbum </label>
        <input type = 'text' id = 't1' name = 'idCancion'/>
    
        <input type = 'submit' value = 'Buscar' name = 'buscarAlbumDeCancion'>
        ";
    }

    


    public function buscarDatos($dni){
        $this->database= new mysqli("localhost","DBUSER2021","DBPSWD2021","ejercicio6");
        $busqueda = $this->database->prepare('SELECT * FROM pruebasUsabilidad where dni = ?;');
        $busqueda->bind_param('s',$dni);
        $busqueda->execute();
        $busqueda->bind_result($dniBuscado, $nombre, $apellidos,  $email,$telefono, $edad,$sexo,$info,$tiempoDeTarea, $pruebaRealizadaCorrectamente, $comentario,  $proposicionMejora, $puntuacion);
        $busqueda->fetch();
        $this->formulario = "Persona DNI: ".$dniBuscado.
        "Nombre: ".$nombre.
        " Apellidos: ".$apellidos.
        " Email: ".$email.
        " Telefono: ".$telefono.
        " Edad: ".$edad.
        " Sexo: ".$sexo.
        " Nivel De Informatica: ".$info.
        " Tiempo de tarea: ".$tiempoDeTarea.
        " Prueba realizada correctamente: ".$pruebaRealizadaCorrectamente.
        " Comentario: ".$comentario.
        " Proposicion de Mejora: ".$proposicionMejora.
        " Puntuacion: ".$puntuacion;

    }
 




    public function getFormulario(){
        return $this->formulario;
    }
    

}

if(isset($_SESSION['BaseDeDatos'])) {

} else {
    $_SESSION['BaseDeDatos'] = new BaseDatos();
}

$formularioActual = "Obtener dato";
if(count($_POST)>0) {
    if(isset($_POST['inicializarAplicacion'])) $_SESSION['BaseDeDatos']->inicializarAplicacion();

    if(isset($_POST['fCancion'])) $_SESSION['BaseDeDatos']->formularioInsertarCancion();
    if(isset($_POST['fGira'])) $_SESSION['BaseDeDatos']->formularioInsertarGira();
    if(isset($_POST['fAlbum'])) $_SESSION['BaseDeDatos']->formularioInsertarAlbum();
    if(isset($_POST['fBanda'])) $_SESSION['BaseDeDatos']->formularioInsertarBanda();
    if(isset($_POST['buscarCancionesSimilaresF'])) $_SESSION['BaseDeDatos']->formularioBuscarCancionesGenero();
    if(isset($_POST['buscarAlbumCancionF'])) $_SESSION['BaseDeDatos']->formularioAlbumDeCancion();
    if(isset($_POST['buscarArtistasSimilaresF'])) $_SESSION['BaseDeDatos']->formularioBuscarArtistasSimilares();
    if(isset($_POST['buscarCancionesAlbumF'])) $_SESSION['BaseDeDatos']->formularioCancionesDeAlbum();
    if(isset($_POST['mostrarCanciones'])) $_SESSION['BaseDeDatos']->mostrarCanciones();
    if(isset($_POST['mostrarAlbumes'])) $_SESSION['BaseDeDatos']->mostrarAlbumes();
    if(isset($_POST['mostrarArtistas'])) $_SESSION['BaseDeDatos']->mostrarArtistas();
    if(isset($_POST['mostrarGiras'])) $_SESSION['BaseDeDatos']->mostrarGiras();
   

    if(isset($_POST['insertarGira'])) $_SESSION['BaseDeDatos']->insertarGira($_POST['idgira'], $_POST['idbanda'],$_POST['nombregira'],$_POST['nconciertos']);
    if(isset($_POST['insertarCancion'])) $_SESSION['BaseDeDatos']->insertarCancion($_POST['idcancion'], $_POST['idbanda'],$_POST['idalbum'],$_POST['nombrecancion'],$_POST['duracion'],$_POST['genero']);
    if(isset($_POST['insertarBanda'])) $_SESSION['BaseDeDatos']->insertarBanda($_POST['idbanda'], $_POST['nombrebanda'],$_POST['nmiembros'],$_POST['generopreferido']);
    if(isset($_POST['insertarAlbum'])) $_SESSION['BaseDeDatos']->insertarAlbum($_POST['idalbum'], $_POST['nombrealbum'],$_POST['ncanciones'],$_POST['duracion'],$_POST['genero']);

    if(isset($_POST['buscarArtistasSimilares']))$_SESSION['BaseDeDatos']->buscarArtistasSimilares($_POST['nombreBanda']);
    if(isset($_POST['buscarCanciones']))$_SESSION['BaseDeDatos']->buscarCancionesQueMeGustenGenero($_POST['genero']);
    if(isset($_POST['buscarAlbumDeCancion']))$_SESSION['BaseDeDatos']->albumDeCancion($_POST['idCancion']);
    if(isset($_POST['albumCanciones']))$_SESSION['BaseDeDatos']->buscarCancionesDeAlbum($_POST['nombreAlbum']);

    $formularioActual = $_SESSION['BaseDeDatos']->getFormulario();
    
}

echo "
<!DOCTYPE html>
<html lang='es'>
<head>
    <meta charset='UTF-8' />
    <meta name ='viewport' content ='width=device-width, initial scale=1.0' />
    <title>Ejercicio 7</title>
	<link rel='stylesheet' type='text/css' href='Ejercicio7.css' />
	<meta name = 'author' content = 'Mario Lada Martínez' />
	<meta name = 'description' content = 'Ejercicio 7'/>
	<meta name='keywords' content='Ejercicio 7'/>

</head>
<body>
<header>
<h1>Creación Base de datos</h1>
</header>
<p>Aplicación relacionada con la búsqueda de música que te puede gustar.</p>
<p>incluye varias funcionalidades, como por ejemplo buscar canciones similares a una dada, artistas similares a alguno que te gusten o buscar el álbum de una canción que te guste</p>
<p>Además, también puedes introducir tus propias canciones, artistas o albumes e informarte de que artistas de la base de datos son similares a ellos, las giras que han realizado...</p>
<p>Es una especie de Mini-Spotify</p>
<p><strong>El hecho de tener un campo de Id y un campo nombre en cada entidad es para evitar posibles repeticiones de nombre, pero en un principio tienen el mismo valor</strong></p>
<p>Puedes probar la aplicación con los siguientes datos por ejemplo: Artista(o banda) - twenty one pilots; Cancion - stressed out; Gira - the coldplays; Album - blurryface
<form action = '#' method = 'post'>
<input type='submit' value='Inicializar Aplicacion' name='inicializarAplicacion'/>
<input type='submit' value='Insertar Gira' name='fGira'/>
<input type='submit' value='Insertar Cancion' name='fCancion'/>
<input type='submit' value='Insertar Banda' name='fBanda'/>
<input type='submit' value='Insertar Album' name='fAlbum'/>
<input type='submit' value='Mostrar Canciones' name='mostrarCanciones'/>
<input type='submit' value='Mostrar Albumes' name='mostrarAlbumes'/>
<input type='submit' value='Mostrar Artistas' name='mostrarArtistas'/>
<input type='submit' value='Mostrar Giras' name='mostrarGiras'/>
<input type='submit' value='Buscar Canciones Similares' name='buscarCancionesSimilaresF'/>
<input type='submit' value='Buscar Canciones de Album' name='buscarCancionesAlbumF'/>
<input type='submit' value='Buscar Album de Cancion' name='buscarAlbumCancionF'/>
<input type='submit' value='Buscar Artistas Similares' name='buscarArtistasSimilaresF'/>
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