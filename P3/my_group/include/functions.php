<?php
/**
 * * Descripción: Controlador principal
 * *
 * * Descripción extensa: Iremos añadiendo cosas complejas en PHP.
 * *
 * * @author  Lola <dllido@uji.es> 
 * * @copyright 2018 Lola
 * * @license http://www.fsf.org/licensing/licenses/gpl.txt GPL 2 or later
 * * @version 2
 * */
include_once(plugin_dir_path( __FILE__ ).'./include/gestionBD');
$table = "A_clientGroup";

//seguridad wp
if ( ! defined( 'WPINC' ) ) exit;
if ( ! defined( 'ABSPATH' ) ) exit;

//Funcion instalación plugin. Crea tabla
function my_group_install(){
    $query="CREATE TABLE IF NOT EXISTS $table (person_id INT(11) NOT NULL AUTO_INCREMENT,
    nombre VARCHAR(100), apellidos VARCHAR(100), email VARCHAR(100),  foto_file VARCHAR(25), clienteMail VARCHAR(100)  PRIMARY KEY(client_id))";
    //echo $query;
    $pdo->exec($query);
}


add_action('admin_post_nopriv_my_datos', 'my_datos');
add_action('admin_post_my_datos', 'my_datos');


//CONTROLADOR
//Esta función realizará distintas acciones en función del valor del parámetro
//$_REQUEST['proceso'], o sea se activara al llamar a url semejantes a 
//https://host/wp-admin/admin-post.php?action=my_datos&proceso=r 
if ( ! function_exists( 'my_datos' ) ) {
function my_datos()
{ 
    global $pdo;
    global $table;
    global $$user_ID , $user_email;

    get_currentuserinfo();
    if ('' == $user_ID) {
                //no user logged in
                exit;
    }
    if (!(isset($_REQUEST['action']) and isset($_REQUEST['proceso'])))  exit;

    get_header();
    switch ($_REQUEST['proceso']) {
    
        case "registro":
            
            ?>
            <h1>GestiÓn de Usuarios </h1>
            <form class="fom_usuario" action="?action=my_datos&proceso=registrar" method="POST">
                <label for="clienteMail">Tu correo</label>
                <br/>
                <input type="text" name="clienteMail"  size="20" maxlength="25" value="<?php print $userID?> "
                readonly />
                <br/>
                <legend>Datos básicos</legend>
                <label for="nombre">Nombre</label>
                <br/>
                <input type="text" name="userName" class="item_requerid" size="20" maxlength="25" value="<?php print $userName ?>"
                placeholder="Miguel Cervantes" />
                <br/>
                <label for="email">Email</label>
                <br/>
                <input type="text" name="email" class="item_requerid" size="20" maxlength="25" value="<?php print $email ?>"
                placeholder="kiko@ic.es" />
                <br/>
                <label for="passwd">Clave</label>
                <br/>
                <input type="password" name="passwd" class="item_requerid" size="8" maxlength="25" value="<?php print $passwd ?>"
                />
                <br/>
                <input type="submit" value="Enviar">
                <input type="reset" value="Deshacer">
            </form>
            <?php
            break;
        case "registrar":
            if (count($_REQUEST) < 3) {
                print ("No has rellenado el formulario correctamente");
                return;
            }
            $query = "INSERT INTO     $table (nombre, email,clienteMail)
                                VALUES (?,?,?)";
                            
            try { 
                $a=array($_REQUEST['userName'], $_REQUEST['email'],$_REQUEST['clienteMail'] );
                $consult = $pdo->prepare($query);
                $a=$consult->execute(array($_REQUEST['userName'], $_REQUEST['email'],$_REQUEST['passwd']  ));
                if (1>$a)echo "InCorrecto";
        
                } 
            catch (PDOExeption $e) {
                    echo ($e->getMessage());
                }
        default:
            //Listado amigos o de todos si se es administrador.
            if (is_admin()) {$rows=consultar();}
            else {$rows=consultarFiltro("email", $user_email);}
            
            if (is_array($rows)) {/* Creamos un listado como una tabla HTML*/
                print '<div><table><th>';
                foreach ( array_keys($rows[0])as $key) {
                    echo "<td>", $key,"</td>";
                }
                print "</th>";
                foreach ($rows as $row) {
                    print "<tr>";
                    foreach ($row as $key => $val) {
                        echo "<td>", $val, "</td>";
                    }
                    print "</tr>";
                }
                print "</table></div>";
            }
            break;
        
    }

    get_footer();
    }
}

?>