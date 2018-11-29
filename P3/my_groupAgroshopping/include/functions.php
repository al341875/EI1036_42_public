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






//Funcion instalación plugin. Crea tabla
function AS_MP_CrearT($table){
    
    $MP_pdo_AS = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD); 
    $query="CREATE TABLE IF NOT EXISTS $table (person_id INT(11) NOT NULL AUTO_INCREMENT, nombre VARCHAR(100),  email VARCHAR(100),  foto_file VARCHAR(100), clienteMail VARCHAR(100),  PRIMARY KEY(person_id))";
    $consult = $MP_pdo_AS->prepare($query);
    $consult->execute (array());
}

function AS_MP_Register_Form($MP_user , $user_email)
{//formulario registro amigos de $user_email
    ?>
    <h1>Gestión de Usuarios </h1>
    <form class="fom_usuario" action="?action=my_datos&proceso=registrar" method="POST">
        <label for="clienteMail">Tu correo</label>
        <br/>
        <input type="text" name="clienteMail"  size="20" maxlength="25" value="<?php print $user_email?>"
        readonly />
        <br/>
        <legend>Datos básicos</legend>
        <label for="nombre">Nombre</label>
        <br/>
        <input type="text" name="userName" class="item_requerid" size="20" maxlength="25" value="<?php print $MP_user["userName"] ?>"
        placeholder="Miguel Cervantes" />
        <br/>
        <label for="email">Email</label>
        <br/>
        <input type="text" name="email" class="item_requerid" size="20" maxlength="25" value="<?php print $MP_user["email"] ?>"
        placeholder="kiko@ic.es" />
        <br/>
        <label for="foto_file">foto</label>
		<br/>
        <input type="file" name="ffile" class="item_requerid"  value="<?php print $ffile ?>" />
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

function AS_MP_my_datos()
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
            $MP_user=null; //variable a rellenar cuando usamos modificar con este formulario
            AS_MP_Register_Form($MP_user,$user_email);
            break;
        case "registrar":
            $url33='/mnt/data/vhosts/casite-1006648.cloudaccess.net/httpdocs/FOTOS';
            $fotoURL="";
            $IMAGENES_USUARIOS='/mnt/data/vhosts/casite-1006648.cloudaccess.net/httpdocs/Lab/P1/img/';
            $URL = "";
            $location='/Lab/P1/img/';
            if(array_key_exists('ffile', $_FILES) && $_POST['email']) {
            $fotoURL = $IMAGENES_USUARIOS.$_POST['userName']."_".$_FILES['ffile']['name'];
            $URL = $location.$_POST['userName']."_".$_FILES['ffile']['name'];
            if (move_uploaded_file($_FILES['ffile']['name'], $url33))
                { echo "foto subida con éxito";
            }}
            $URL3='FOTOS/des.jpg';
            if (count($_REQUEST) < 3) {
                print ("No has rellenado el formulario correctamente");
                return;
            }
            $query = "INSERT INTO $table (nombre, email,foto_file,clienteMail) VALUES (?,?,?,?)";         
            $a=array($_REQUEST['userName'], $_REQUEST['email'],$URL3 ,$_REQUEST['clienteMail'] );
            //$pdo1 = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD); 
            $consult = $MP_pdo_AS->prepare($query);
            $a=$consult->execute($a);
            if (1>$a) {echo "InCorrecto $query";}
            else wp_redirect(admin_url( 'admin-post.php?action=my_datos&proceso=listar'));
            break;
        case "listar":
            //Listado amigos o de todos si se es administrador.
            ?>
            <style>
    table.paleBlueRows {
      font-family: "Times New Roman", Times, serif;
      border: 1px solid #FFFFFF;
      width: 350px;
      height: 200px;
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
                //print '<div><table><th>';
                print '<table class ="paleBlueRows" ><thead>';
               

                foreach ( array_keys($rows[0])as $key) {
                    echo "<th>", $key,"</th>";
                }
                print "</thead>";
                print "<tbody>";

                foreach ($rows as $row) {
                    print "<tr>";
                    foreach ($row as $key => $val) {
                        if($key == "foto_file"){
                            echo "<td><img src='$val' border='0' width='300' height='100'></td>";  
                        }else {
                      echo "<td>", $val, "</td>";
                       }
                    }
                    print "</tr>";
                }
                print "</table></tbody>";
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