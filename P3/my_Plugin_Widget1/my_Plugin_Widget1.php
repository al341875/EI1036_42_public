<?php
/*
Plugin Name: my_Plugin_Widget1
Description: Este plugin añade un widget que pone un título y una descripción.
Author: dllido
Author Email: dllido@uji.es
Version: 1.0
License: GPLv3
License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/


// Registramos el widget
function load_my_widget() {
	register_widget( 'my_widget1' );
}
add_action( 'widgets_init', 'load_my_widget' );

// Creamos el widget 
class my_widget1 extends WP_Widget {

	public function __construct() {
			$widget_ops = array( 
				'classname' => 'my_widget',
				'description' => 'My Widget is awesome',
			);
			parent::__construct( 'my_widget1', 'My Widget1', $widget_ops );
		}	
	
	
	// Creamos la parte pÃºblica del widget
	
	public function widget( $args, $instance ) {
	$title= apply_filters( 'widget_title', "Nombre de la tienda" );
	$NombreTienda= apply_filters( 'widget_text', $instance['NombreTienda'] );
	$title2= apply_filters( 'widget_title', "Dirección de la tienda" );
	$DireccionTienda= apply_filters( 'widget_text', $instance['DireccionTienda'] );
	
	
	// los argumentos del antes y despues del widget vienen definidos por el tema
	echo $args['before_widget'];
	if ( ! empty( $NombreTienda) )
	echo $args['before_title'] . $title . $args['after_title'];
	echo $NombreTienda;
	if ( ! empty( $DireccionTienda) )
	echo $args['before_title'] . $title2 . $args['after_title'];
	echo $DireccionTienda;
	// AquÃ­ es donde debemos introducir el cÃ³digo que queremos que se ejecute
	echo $args['after_widget'];
	}
			
	// Backend  del widget
	public function form( $instance ) {
	if ( isset( $instance[ 'NombreTienda' ] ) ) {
	$NombreTienda= $instance[ 'NombreTienda' ];
	$DireccionTienda= $instance['DireccionTienda'];
	}
	else {
	$title = __( 'NombreTienda', 'my_widget_domain' );
	}
	// Formulario del backend
	 ?>
	<p>
	<label for="<?php echo $this->get_field_id( 'NombreTienda' ); ?>"><?php _e( 'Nombre de la Tienda:' ); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id( 'NombreTienda' ); ?>" name="<?php echo $this->get_field_name( 'NombreTienda' ); ?>" type="text" value="<?php echo esc_attr( $NombreTienda); ?>" />
	</p>
	<p>
	<label for="<?php echo $this->get_field_id( 'DireccionTienda' ); ?>"><?php _e( 'Direccion de la Tienda:' ); ?></label> 
	<input class="widefat" id="<?php echo $this->get_field_id( 'DireccionTienda' ); ?>" name="<?php echo $this->get_field_name( 'DireccionTienda' ); ?>" type="text" value="<?php echo esc_attr( $DireccionTienda); ?>" />
	</p>
	<?php	
	}
	// Actualizamos el widget reemplazando las viejas instancias con las nuevas
	public function update( $new_instance, $old_instance ) {
	$instance = array();
	$instance['NombreTienda'] = ( ! empty( $new_instance['NombreTienda'] ) ) ? strip_tags( $new_instance['NombreTienda'] ) : '';
	$instance['DireccionTienda'] = ( ! empty( $new_instance['DireccionTienda'] ) ) ? strip_tags( $new_instance['DireccionTienda'] ) : '';
	
	return $instance;
	}
	} // La clase wp_widget termina aqui
	
?>