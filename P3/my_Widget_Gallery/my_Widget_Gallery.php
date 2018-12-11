<?php
/*
Plugin Name: my_Widget_Gallery
Description: Este plugin añade un widget que mostra una galeria de imagens
Author: Jordi
Author Email: al341875@uji.es
Version: 1.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/


// Registramos el widget
function load_my_widget_G() {
	register_widget( 'my_Widget_Gallery' );
}
add_action( 'widgets_init', 'load_my_widget_G' );

// Creamos el widget 
class my_Widget_Gallery extends WP_Widget {

public function __construct() {
		$widget_ops = array( 
			'classname' => 'Gallery_WD_P3',
			'description' => 'Gallery widget ',
		);
		parent::__construct( 'my_Widget_Gallery', 'My my_Widget_Gallery', $widget_ops );
	}	


// Creamos la parte pública del widget

public function widget( $args, $instance ) {
$current_user = wp_get_current_user();
$user_email = $current_user->user_email;
$user_login = $current_user->user_login;
// los argumentos del antes y después del widget vienen definidos por el tema
if (current_user_can('manage_options') or $user_email == '') {
} else {
	echo $args['before_widget'];
	echo $args['before_title'] .'Imagenes de Amigos de '. $user_login . $args['after_title'];
	$table = 'A_GrupoCliente';
    $MP_pdo_AS = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASSWORD); 

	$a = array();
	$ffile = 'foto_file';
	$campo = 'clienteMail';
	$query = "SELECT     $ffile FROM  $table      WHERE $campo =?";
	$a = array($user_email);
	$consult = $MP_pdoSportRunner->prepare($query);
	$a= $consult->execute($a);
	$rows = $consult->fetchAll(PDO::FETCH_ASSOC);

	print '<table class ="paleBlueRows" ><thead>';
	print "</thead>";
	print "<tbody>";

	foreach ($rows as $row) {
		print "<tr>";
		foreach ($row as $key => $val) {
			if($key == "foto_file"){
				echo "<td><img src='$val' border='0' width='150' height='150'></td>";  
			}else {
		  echo "<td>", $val, "</td>";
		   }
		}
		print "</tr>";
	}
	print "</table></tbody>";
}
echo $args['after_widget'];
}
		
// Backend  del widget
public function form( $instance ) {
}
// Formulario del backend

// Actualizamos el widget reemplazando las viejas instancias con las nuevas

} // La clase wp_widget termina aquí
?>