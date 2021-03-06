<?php
/*
Plugin Name: my_groupAgroshopping
Description: Register group of persons.
Author URI: Jordi
Author Email: al341875@uji.es
Version: 1.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/




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
            include_once(plugin_dir_path(__FILE__) . '../templates/registro.php');
           
            //MP_Register_FormAS($MP_userAS,$user_email);
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
            include_once(plugin_dir_path(__FILE__) . '../templates/update.php');

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
          

    
      //JSON return listar jSon encode
      //return json_encode($rows)
      //JSON FIN
   // echo "</div>";
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