<?php
/**
 * * Descripción: Controlador principal
 * *
 * * Descripción extensa: Iremos añadiendo cosas complejas en PHP.
 * *
 * * @author  Jordi <al316454@uji.es>, Juan Bautista <al341847@uji.es> 
 * * @copyright 2018 Jordi y Juan
 * * @license http://www.fsf.org/licensing/licenses/gpl.txt GPL 2 or later
 * * @version 1
 * */


//Estas 2 instrucciones me aseguran que el usuario accede a través del WP. Y no directamente
if ( ! defined( 'WPINC' ) ) exit;

if ( ! defined( 'ABSPATH' ) ) exit;






//Funcion instalación plugin. Crea tabla
function MP_CrearAS($table){
    
    $MP_pdo_AS = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD); 
    $query="CREATE TABLE IF NOT EXISTS $table (person_id INT(11) NOT NULL AUTO_INCREMENT, nombre VARCHAR(100),  email VARCHAR(100),  foto_file VARCHAR(200), clienteMail VARCHAR(100),  PRIMARY KEY(person_id))";
    $consult = $MP_pdo_AS->prepare($query);
    $consult->execute (array());
}



function MP_Register_FormAS($MP_userAS , $user_email)
{//formulario registro amigos de $user_email
    ?>


    <h1>GestiÓn de Usuarios </h1>
    <form class="form" action="?action=my_datos&proceso=registrar" method="POST" enctype="multipart/form-data">
        <label for="clienteMail">Tu correo</label>
        <br/>
        <input type="text" name="clienteMail"  size="20" maxlength="25" value="<?php print $user_email?>"
        readonly />
        <br/>
        <legend>Datos básicos</legend>
<br/>
        <label for="nombre">Nombre</label>
        <br/>
        <input type="text" name="userName" class="item_requerid" size="20" maxlength="25" value="<?php print $MP_userAS["userName"] ?>"
        placeholder="Miguel Cervantes" />
        <br/>
        <label for="email">Email</label>
        <br/>
        <input type="text" name="email" class="item_requerid" size="20" maxlength="25" value="<?php print $MP_userAS["email"] ?>"
        placeholder="kiko@ic.es" />
        <br/>
 <label for="foto_file">Foto</label>
<br/>
<input type="file" id="foto" name="foto" class="item_requerid" value="<?php print $foto ?>" required />
<br/>
<br>
<p> <img id="img_foto" src="" width="100" height="60"></p>

        <input type="submit" value="Enviar">
        <input type="reset" value="Deshacer">
    </form>


<script type="text/javascript" charset="utf-8">

      function mostrarFoto(file, imagen) {
	  //carga la imagen de file en el elemento src imagen
         var reader = new FileReader();
         reader.addEventListener("load", function () {
            imagen.src = reader.result;
         });
         reader.readAsDataURL(file);
      }

      function ready() {
         var fichero = document.querySelector("#foto");
         var imagen  = document.querySelector("#img_foto");
	  //escuchamos evento selección nuevo fichero.
         fichero.addEventListener("change", function (event) {
            mostrarFoto(this.files[0], imagen);
         });
      }

      ready();

   </script>
<?php
}

function MP_Update_Form_AS($MP_userAS , $user_email,$nom,$email,$foto,$person_id)
{//formulario registro amigos de $user_email

    ?>


    <h1>GestiÓn de Usuarios </h1>
    <form class="form" action="?action=my_datos&proceso=updatear" method="POST" enctype="multipart/form-data">
        <label for="clienteMail">Tu correo</label>
        <br/>
        <input type="text" name="clienteMail"  size="20" maxlength="25" value="<?php print $user_email?>"
        readonly />
        <br/>
        <legend>Datos básicos</legend>
<br/>
        <label for="nombre">Nombre</label>
        <br/>
        <input type="text" name="userName" class="item_requerid" size="20" maxlength="25" value="<?php print $nom ?>"
        placeholder="Miguel Cervantes" />
        <br/>
        <label for="email">Email</label>
        <br/>
        <input type="text" name="email" class="item_requerid" size="20" maxlength="25" value="<?php print $email ?>"
        placeholder="kiko@ic.es" />
        <br/>
	<input type="hidden" name="person_id" value="<?php print $person_id ?>">
 <label for="foto_file">Foto</label>
<br/>
<input type="file" id="foto" name="foto" class="item_requerid" value="<?php print $foto ?>" required/>

<br/>
<p>Foto anterior:</p>
<p><img id="imagen" src="<?php print $foto ?>" width="100" height="60"></p>
<br>
<p>Foto actual:</p>
<p> <img id="img_foto" src="" width="100" height="60"></p>
        <input type="submit" value="Enviar">
        <input type="reset" value="Deshacer">
    </form>



<script type="text/javascript" charset="utf-8">
      function mostrarFoto(file, imagen) {
	  //carga la imagen de file en el elemento src imagen
         var reader = new FileReader();
         reader.addEventListener("load", function () {
 if (!(/\.(jpg)$/i).test(file.name)) {
	alert('El archivo a adjuntar no es una imagen valida .jpg');
	imagen.src = "";
	document.querySelector("#foto").value="";
 }else {
        
            if (file.size > 200000)
            {
                alert('El peso de la imagen no puede exceder los 200kb y tu imagen tiene: ');
		imagen.src = "";
		document.querySelector("#foto").value="";
            }
            else {
		imagen.src = reader.result;
                alert('Imagen correcta.');            
            }
}
        });
 reader.readAsDataURL(file);

         
}


      function ready() {
         var fichero = document.querySelector("#foto");
	//var fichero = document.querySelector('input[type="file"]');
         var imagen  = document.querySelector("#img_foto");
	
	  //escuchamos evento selección nuevo fichero.
         fichero.addEventListener("change", function (event) {
            mostrarFoto(this.files[0], imagen);
		//console.log(fichero.files);
         });
      }

      ready();
   </script>
<?php
}
//CONTROLADOR
//Esta función realizará distintas acciones en función del valor del parámetro
//$_REQUEST['proceso'], o sea se activara al llamar a url semejantes a 
//https://host/wp-admin/admin-post.php?action=my_datos&proceso=r 

function MP_my_datosAS()
{ 
    global $user_ID , $user_email;
    $table='A_GrupoCliente';
    $MP_pdo_AS = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD); 
    
    wp_get_current_user();
    if ('' == $user_ID) {
                //no user logged in
                exit;
    }
    
    
    
    if (!(isset($_REQUEST['action'])) or !(isset($_REQUEST['proceso']))) { print("Opciones no correctas $user_email"); exit;}

    get_header();
    echo '<div class="wrap">';

    switch ($_REQUEST['proceso']) {
        case "registro":
            $MP_userAS=null; //variable a rellenar cuando usamos modificar con este formulario
            MP_Register_FormAS($MP_userAS,$user_email);
            break;
        case "registrar":
            if (count($_REQUEST) < 4) {
                print ("No has rellenado el formulario correctamente");
                return;
            }
                        $fotoURL="";
			$fotoURL2="";
            $IMAGENES_USUARIOS ='/mnt/data/vhosts/casite-1027620.cloudaccess.net/httpdocs/Lab/P1/img/';
			$Base = '/Lab/P1/img/';
			if(array_key_exists('foto', $_FILES) && $_POST['email']) {
				$fotoURL = $IMAGENES_USUARIOS.$_POST['userName']."_".$_FILES['foto']['name'];
				$fotoURL2 = $Base.$_POST['userName']."_".$_FILES['foto']['name'];
				if (move_uploaded_file($_FILES['foto']['tmp_name'], $fotoURL)){ 
					echo "foto subida con éxito";
				}
			}



            $query = "INSERT INTO $table (nombre, email,foto_file,clienteMail) VALUES (?,?,?,?)";         
            $a=array($_REQUEST['userName'], $_REQUEST['email'],$fotoURL2,$_REQUEST['clienteMail'] );
            //$pdo1 = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD); 
            $consult = $MP_pdo_AS->prepare($query);
            $a=$consult->execute($a);
            if (1>$a) {echo "InCorrecto $query";}
            else wp_redirect(admin_url( 'admin-post.php?action=my_datos&proceso=listar'));
            break;
case "update":
$table2='A_GrupoCliente';
			$valor=$_GET["person_id"];
			$query = "SELECT     * FROM       $table WHERE person_id=$valor "; 
			$consult = $MP_pdo_AS->prepare($query);
				$a=$consult->execute(array());
				//if (1>$a)echo "InCorrecto2";
				$result=($consult->fetch(PDO::FETCH_ASSOC));
			//var_dump($result);
			$MP_userAS=null; //variable a rellenar cuando usamos modificar con este formulario
			$nom= $result["nombre"];
			$email= $result["email"];
			$foto= $result["foto_file"];
			MP_pdo_AS($MP_userAS,$user_email,$nom,$email,$foto,$valor);
			break;
case "updatear":
$fotoURL="";
				$fotoURL2="";
				$IMAGENES_USUARIOS ='/mnt/data/vhosts/casite-1027620.cloudaccess.net/httpdocs/Lab/P1/img/';
				$Base = '/Lab/P1/img/';
				if(array_key_exists('foto', $_FILES) && $_POST['email']) {
					$fotoURL = $IMAGENES_USUARIOS.$_POST['userName']."_".$_FILES['foto']['name'];
					$fotoURL2 = $Base.$_POST['userName']."_".$_FILES['foto']['name'];
					if (move_uploaded_file($_FILES['foto']['tmp_name'], $fotoURL)){ 
						//echo "foto subida con éxito";
					}
				}
			$table2='A_GrupoCliente';
			$query = "UPDATE $table2 SET nombre=?, email=?, foto_file=? WHERE person_id=?";       
				$a=array($_REQUEST['userName'], $_REQUEST['email'],$fotoURL2,$_REQUEST['person_id'] );
				//$pdo1 = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD); 
				$consult = $MP_pdo_AS->prepare($query);
				$a=$consult->execute($a);
				wp_redirect(admin_url( 'admin-post.php?action=my_datos&proceso=listar'));
			break;
        case "listar":
?>
            <style>
    table.paleBlueRows {
      font-family: "Times New Roman", Times, serif;
      border: 1px solid #FFFFFF;
  
      text-align: center;
      border-collapse: collapse;
    }
    table.paleBlueRows td, table.paleBlueRows th {
      border: 1px solid #FFFFFF;
      padding: 3px 2px;
    }
    table.paleBlueRows tbody td {
      font-size: 13px;
    }
    table.paleBlueRows tr:nth-child(even) {
      background: #D0E4F5;
    }
    table.paleBlueRows thead {
      background: #0B6FA4;
      border-bottom: 5px solid #FFFFFF;
    }
    table.paleBlueRows thead th {
      font-size: 17px;
      font-weight: bold;
      color: #FFFFFF;
      text-align: center;
      border-left: 2px solid #FFFFFF;
    }
    table.paleBlueRows thead th:first-child {
      border-left: none;
    }
    
    table.paleBlueRows tfoot td {
      font-size: 14px;
    }
     
                
            </style>
<?php
            //Listado amigos o de todos si se es administrador.
            $a=array();
            if (current_user_can('administrator')) {$query = "SELECT     * FROM       $table ";}
            else {$campo="clienteMail";
                $query = "SELECT     * FROM  $table      WHERE $campo =?";
                $a=array( $user_email);
 
            } 

            $consult = $MP_pdo_AS->prepare($query);
            $a=$consult->execute($a);
            $rows=$consult->fetchAll(PDO::FETCH_ASSOC);
            if (is_array($rows)) {/* Creamos un listado como una tabla HTML*/
                print '<div><table  class ="paleBlueRows"><tr>';
                foreach ( array_keys($rows[0])as $key) {
                    echo "<th>", $key,"</th>";
                }
echo "<th>","Opciones","</th>";
                print "</tr>";
                 foreach ($rows as $row) {
                    print "<tr>";
                    foreach ($row as $key => $val) {
                        if ($key=="foto_file"){
							echo "<td>", "<img src='$val' width='100' height='100'>", "</td>";
						} else {
							echo "<td>", $val, "</td>";}

                    }
		    $id=$row["person_id"];
echo "<td> <a target='_blank' href='admin-post.php?action=my_datos&proceso=update&person_id=$id'>Update</a> </td>";
                    print "</tr>";
                }
                print "</table></div>";

            }
            else{echo "No existen valores";}
            break;
        default:
            print "Opción no correcta";
        
    }
    echo "</div>";
    // get_footer ademas del pie de página carga el toolbar de administración de wordpres si es un 
    //usuario autentificado, por ello voy a borrar la acción cuando no es un administrador para que no aparezca.
    if (!current_user_can('administrator')) {

        // for the admin page
        remove_action('admin_footer', 'wp_admin_bar_render', 1000);
        // for the front-end
        remove_action('wp_footer', 'wp_admin_bar_render', 1000);
    }

    get_footer();
    }
//add_action('admin_post_nopriv_my_datos', 'my_datos');
//add_action('admin_post_my_datos', 'my_datos'); //no autentificados
?>