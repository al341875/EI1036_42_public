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



//Al activar el plugin quiero que se cree una tabla en la BD, que creará la función my_group_install.



//Añado action hook, de forma que cuando se realice la acción de una petición a la URL: wp-admin/admin-post.php?action=my_datos 
//se llame a mi controlador definido en la función my_datos definido en el fichero include/functions.php

//Solo activado el hook para usuarios autentificados,  



//La siguiente sentencia activaria la acción para todos los usuarios.
//add_action('admin_post_nopriv_my_datos', 'my_datos');

include(plugin_dir_path( __FILE__ ).'include/functions.php');

register_activation_hook( __FILE__, 'AS_MP_Ejecutar_crearT');

//add_action( 'plugins_loaded', 'Ejecutar_crearT' ); // esto se ejecuta siempre que se llama al plugin
function AS_MP_Ejecutar_crearT(){
    AS_MP_CrearT("A_GrupoCliente");
}
function hook_css() {
    ?>
        <style>
            .wp_head_example {
                background-color : #f1f1f1;
            }
        </style>
    <?php
 }
 add_action('wp_head', 'hook_css');
  
  
  
  
 

//add_action('admin_post_nopriv_my_datos', 'MP_my_datos'); //no autentificados
add_action('admin_post_my_datos', 'my_datos'); 
?>