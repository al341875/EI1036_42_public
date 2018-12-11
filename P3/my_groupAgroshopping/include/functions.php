<?php
/**
 * * Descripción: Controlador principal
 * *
 * * Descripción extensa: Iremos añadiendo cosas complejas en PHP.
 * *
 * * @author  Jordi <al341875@uji.es> 
 * * @copyright 2018 Lola
 * * @license http://www.fsf.org/licensing/licenses/gpl.txt GPL 2 or later
 * * @version 2
 * */


//Estas 2 instrucciones me aseguran que el usuario accede a través del WP. Y no directamente
if ( ! defined( 'WPINC' ) ) exit;

if ( ! defined( 'ABSPATH' ) ) exit;





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
function MP_CrearTSportRunner($table){
    
    $MP_pdoSportRunner = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD); 
    $query="CREATE TABLE IF NOT EXISTS $table (person_id INT(11) NOT NULL AUTO_INCREMENT, nombre VARCHAR(100),  email VARCHAR(100),  foto_file VARCHAR(25), clienteMail VARCHAR(100),  PRIMARY KEY(person_id))";
    $consult = $MP_pdoSportRunner->prepare($query);
    $consult->execute (array());
}



function MP_Register_FormSportRunner($MP_userSportRunner , $user_email)
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
        <input type="text" name="userName" class="item_requerid" size="20" maxlength="25" value="<?php print $MP_userSportRunner["userName"] ?>"
        placeholder="Miguel Cervantes" />
        <br/>
        <label for="email">Email</label>
        <br/>
        <input type="text" name="email" class="item_requerid" size="20" maxlength="25" value="<?php print $MP_userSportRunner["email"] ?>"
        placeholder="kiko@ic.es" />
        <br/>
 <label for="foto_file">Foto</label>
<br/>
<input type="file" name="foto" class="item_requerid" value="<?php print $foto ?>" required />
<br/>
<br/>

        <input type="submit" value="Enviar">
        <input type="reset" value="Deshacer">
    </form>
<?php
}

//CONTROLADOR
//Esta función realizará distintas acciones en función del valor del parámetro
//$_REQUEST['proceso'], o sea se activara al llamar a url semejantes a 
//https://host/wp-admin/admin-post.php?action=my_datos&proceso=r 

function MP_my_datosSportRunner()
{ 
    global $user_ID , $user_email;
    $table='A_GrupoCliente';
    $MP_pdoSportRunner = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD); 
    
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
            $MP_userSportRunner=null; //variable a rellenar cuando usamos modificar con este formulario
            MP_Register_FormSportRunner($MP_userSportRunner,$user_email);
            break;
        case "registrar":
            if (count($_REQUEST) < 4) {
                print ("No has rellenado el formulario correctamente");
                return;
            }
                        $fotoURL="";
			$fotoURL2="";
			$IMAGENES_USUARIOS = '/mnt/data/vhosts/casite-1014616.cloudaccess.net/httpdocs/Lab/P1/fotos/';
			$Base = '/Lab/P1/fotos/';
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
            $consult = $MP_pdoSportRunner->prepare($query);
            $a=$consult->execute($a);
            if (1>$a) {echo "InCorrecto $query";}
            else wp_redirect(admin_url( 'admin-post.php?action=my_datos&proceso=listar'));
            break;
        case "listar":
?>
            <style>
table.tablaAmigos {
  font-family: "Palatino Linotype", "Book Antiqua", Palatino, serif;
  border: 1px solid #AAAAAA;
  background-color: #EEEEEE;
  width: 100%;
  text-align: center;
  border-collapse: collapse;
}
table.tablaAmigos td, table.tablaAmigos th {
  border: 1px solid #AAAAAA;
  padding: 4px 2px;
}
table.tablaAmigos tbody td {
  font-size: 13px;
}
table.tablaAmigos tr:nth-child(even) {
  background: #FFFFFF;
}
table.tablaAmigos th {
  background: #D2DEAB;
  background: -moz-linear-gradient(top, #dde6c0 0%, #d6e1b3 66%, #D2DEAB 100%);
  background: -webkit-linear-gradient(top, #dde6c0 0%, #d6e1b3 66%, #D2DEAB 100%);
  background: linear-gradient(to bottom, #dde6c0 0%, #d6e1b3 66%, #D2DEAB 100%);
  border-bottom: 1px solid #FFFFFF;
}
table.tablaAmigos th {
  font-size: 17px;
  font-weight: bold;
  color: #5FA310;
  text-align: center;
  border-left: 0px solid #FFFFFF;
}
table.tablaAmigos th:first-child {
  border-left: none;
}

table.tablaAmigos tfoot .links {
  text-align: right;
}
table.tablaAmigos tfoot .links a{
  display: inline-block;
  background: #1C6EA4;
  color: #FFFFFF;
  padding: 2px 8px;
  border-radius: 5px;
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

            $consult = $MP_pdoSportRunner->prepare($query);
            $a=$consult->execute($a);
            $rows=$consult->fetchAll(PDO::FETCH_ASSOC);
            if (is_array($rows)) {/* Creamos un listado como una tabla HTML*/
                print '<div><table class="tablaAmigos"><tr>';
                foreach ( array_keys($rows[0])as $key) {
                    echo "<th>", $key,"</th>";
                }
                print "</tr>";
                 foreach ($rows as $row) {
                    print "<tr>";
                    foreach ($row as $key => $val) {
                        if ($key=="foto_file"){
							echo "<td>", "<img src='$val' width='100' height='100'>", "</td>";
						} else {
							echo "<td>", $val, "</td>";}

                    }
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