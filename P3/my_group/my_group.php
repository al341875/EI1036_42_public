<?php
/*
Plugin Name: my_group
Description: Register group of persons.
Author URI: lola
Author Email: dllido@uji.es
Version: 1.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

//Estas 2 instrucciones me aseguran que el usuario accede a través del WP. Y no directamente
if ( ! defined( 'WPINC' ) ) exit;

if ( ! defined( 'ABSPATH' ) ) exit;

//Al activar el plugin quiero que se cree una tabla en la BD, que creará la función my_group_install.
register_activation_hook( __FILE__, 'my_group_install' );


//Añado action hook, de forma que cuando se realice la acción de una petición a la URL: wp-admin/admin-post.php?action=my_datos 
//se llame a mi controlador definido en la función my_datos definido en el fichero include/functions.php

//Solo activado el hook para usuarios autentificados,  

add_action('admin_post_my_datos', 'my_datos');

//La siguiente sentencia activaria la acción para todos los usuarios.
//add_action('admin_post_nopriv_my_datos', 'my_datos');

include_once(plugin_dir_path( __FILE__ ).'include/functions.php');
?>