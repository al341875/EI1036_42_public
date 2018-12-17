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
			'description' => 'Basic Widget ',
		);
		parent::__construct( 'my_widget1', 'My Widget1', $widget_ops );
	}	


// Creamos la parte pública del widget

public function widget( $args, $instance ) {
$title = apply_filters( 'widget_text', $instance['title'] );
$dir = apply_filters( 'widget_text', $instance['dir'] );
$dir2= apply_filters( 'widget_title', "Dirección" );
$tit2= apply_filters( 'widget_title', "Nombre " );

// los argumentos del antes y después del widget vienen definidos por el tema
echo $args['before_widget'];
if ( ! empty( $title ) )
echo $args['before_title'] . $tit2 . $args['after_title'];
echo $title;
if ( ! empty( $dir ) )
echo $args['before_title'] . $dir2 . $args['after_title'];
echo $dir;
print'<aside>
<iframe width="400" height="215" frameborder="0"
	scrolling="no" marginheight="0" marginwidth="0"
	src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d48905.864757584204!2d-0.10358445831708697!3d39.994673751967284!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0xd5ffe0fca9b5147%3A0x1368bf53b3a7fb3f!2sUniversidade+Jaime+I!5e0!3m2!1spt-PT!2spt!4v1544721392991;output=embed">
</iframe>
<p>
	<a href="https://www.google.es/maps/place/Universidade+Jaime+I/@39.9944107,-0.1035845,13z/data=!4m5!3m4!1s0xd5ffe0fca9b5147:0x1368bf53b3a7fb3f!8m2!3d39.9945804!4d-0.0688791"style="color:#0000FF;text-align:left">See bigger map</a>
	</p>
	</aside>'
// Aquí es donde debemos introducir el código que queremos que se ejecute



echo $args['after_widget'];
}
		
// Backend  del widget
public function form( $instance ) {
if ( isset( $instance[ 'title' ] ) ) {
$title = $instance[ 'title' ];
$dir = $instance['dir'];
}
else {
$title = __( 'title', 'my_widget_domain' );
}
// Formulario del backend
 ?>
<p>
<label for="<?php echo $this->get_field_id( 'title' ); ?>"><?php _e( 'Titulo de la tienda:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
</p>
<?php	
?>
<p>
<label for="<?php echo $this->get_field_id( 'dir' ); ?>"><?php _e( 'Direccion de la Tienda:' ); ?></label> 
<input class="widefat" id="<?php echo $this->get_field_id( 'dir' ); ?>" name="<?php echo $this->get_field_name( 'dir' ); ?>" type="text" value="<?php echo esc_attr( $dir); ?>" />
</p>
<?php	
}
// Actualizamos el widget reemplazando las viejas instancias con las nuevas
public function update( $new_instance, $old_instance ) {
$instance = array();
$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
$instance['dir'] = ( ! empty( $new_instance['dir'] ) ) ? strip_tags( $new_instance['dir'] ) : '';
return $instance;

}
} // La clase wp_widget termina aquí
?>