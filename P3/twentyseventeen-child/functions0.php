<?php
/*
Plugin Name: Hostinger Change URL
Description: This plugin <strong>updates all urls in your wordpress website</strong> by replacing old urls with new urls.
Author: Hostinger.com
Author URI: https://www.hostinger.com/
Author Email: info@hostinger.com
Version: 1.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/
// Registramos el widget
function wpb_load_widget() {
	register_widget( 'wpb_widget' );
}
add_action( 'widgets_init', 'wpb_load_widget' );

// Creamos el widget 
class wpb_widget extends WP_Widget {

function __construct() {
parent::__construct(

// El ID de nuestro widget
'wpb_widget', 

// El nombre con el cual aparecerá en el backoffice de WP
__('Widget de Prueba', 'wpb_widget_domain'), 

// Descripción del widget
array( 'description' => __( 'Ejemplo para probar widget personalizado', 'wpb_widget_domain' ), ) 
);
}

// Creamos la parte pública del widget

public function widget( $args, $instance ) {
$title = apply_filters( 'widget_title', $instance['title'] );

// los argumentos del antes y después del widget vienen definidos por el tema
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $title . $args['after_title'];

// Aquí es donde debemos introducir el código que queremos que se ejecute
echo __( 'Hola Mundo', 'wpb_widget_domain' );
echo $args['after_widget'];
}
		
// Backend  del widget
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
}
else {
$title = __( 'Titulo', 'wpb_widget_domain' );
}
// Formulario del backend
 ?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Titulo:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php	
}
// Actualizamos el widget reemplazando las viejas instancias con las nuevas
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
return $instance;
}
} // La clase wp_widget termina aquí
?>