<?php
/*
Plugin Name: Curso de plugins - Destaques
Plugins URI: https://apiki.com/produtos/apiki-wp-cursos/
Description: Implementa um tipo de post customizado para destaques da página inicial.
Version: 0.1.0
Author: Apiki WordPress
Author URI: https://apiki.com/
*/

if ( ! function_exists( 'add_action' ) ) {
	exit( 0 );
}

const PC_DB_VERSION = '0.2.0';

register_activation_hook( __FILE__, 'zo7i_activate' );
register_deactivation_hook( __FILE__, 'zo7i_deactivate' );
register_uninstall_hook( __FILE__, 'zo7i_uninstall' );

function zo7i_activate()
{
	error_log( 'Curso de plugins - Destaques: Ativado!' );
	zo7i_insert_default_data();
}

function zo7i_deactivate()
{
	error_log( 'Curso de plugins - Destaques: Desativado!' );
}

function zo7i_uninstall()
{
	error_log( 'Curso de plugins - Destaques: Desinstalado!' );
}

add_action( 'init', 'zo7i_register_highlights' );

function zo7i_register_highlights()
{
	$labels = array(
		'name'          => 'Destaques',
		'singular_name' => 'Destaque',
	);

	register_post_type(
		'zo7i_highlights',
		array(
			'labels'    => $labels,
			'public'    => false,
			'show_ui'   => true,
			'menu_icon' => 'dashicons-schedule',
			'supports'  => array( 'title', 'thumbnail', 'excerpt', 'page-attributes' ),
		)
	);
}

add_action( 'admin_menu', 'zo7i_add_admin_menu' );

function zo7i_add_admin_menu()
{
	add_submenu_page(
		'edit.php?post_type=zo7i_highlights',
		'Destaques - Configurações',
		'Configurações',
		'manage_options',
		'highlights-config',
		'zo7i_render_config_menu_page'
	);
}

function zo7i_render_config_menu_page()
{
	?>
	<div class="wrap">
		<h1>Configurações de destaques</h1>

		<?php settings_errors(); ?>

		<form action="options.php" method="post">
			<?php
			settings_fields( 'zo7i_settings_group' );
			do_settings_sections( 'zo7i_settings_group' );
			submit_button();
			?>
		</form>
	</div>
	<?php
}

add_action( 'admin_init', 'zo7i_settings_init' );

function zo7i_settings_init()
{
	register_setting( 'general', 'zo7i_settings' );

	add_settings_section(
		'zo7i_settings_section',
		'Destaques da página inicial',
		'zo7i_settings_section_callback',
		'general'
	);

	add_settings_field(
		'highlights-quantity',
		'Quantidade',
		'zo7i_render_quantity',
		'general',
		'zo7i_settings_section',
		array(
			'label_for' => 'highlights-quantity-id',
		)
	);

	add_settings_field(
		'highlights-background-url',
		'Background URL',
		'zo7i_render_background_url',
		'general',
		'zo7i_settings_section',
		array(
			'label_for' => 'highlights-background-url-id',
		)
	);
}

function zo7i_settings_section_callback()
{
	?>
	<p class="description">Configure aqui a exibição dos destaques de sua página inicial.</p>
	<?php
}

function zo7i_render_quantity( $args )
{
	$option = get_option( 'zo7i_settings' );
	?>
	<input type="number"
		   id="<?php echo $args['label_for']; ?>"
		   class="regular-text"
		   name="zo7i_settings[highlights-quantity]"
		   value="<?php echo intval( @$option['highlights-quantity'] ); ?>" />
	<?php
}

function zo7i_render_background_url( $args )
{
	$option = get_option( 'zo7i_settings' );
	?>
	<input type="text"
		   id="<?php echo $args['label_for']; ?>"
		   class="regular-text"
		   name="zo7i_settings[highlights-background-url]"
		   value="<?php echo esc_url( @$option['highlights-background-url'] ); ?>" />
	<?php
}

include 'class-widget-highlight.php';

add_action( 'widgets_init', 'zo7i_register_widgets' );

function zo7i_register_widgets()
{
	register_widget( 'Highlight_Widget' );
}

add_shortcode( 'zo7i_destaque', 'zo7i_destaque' );

function zo7i_destaque( $atts )
{
	$atts = shortcode_atts(
		array(
			'background_url' => '',
			'ids'            => '',
		),
		$atts
	);

	$args = apply_filters(
		'zo7i_shortcode_query_args',
		array(
			'post_type'      => 'zo7i_highlights',
			'post__in'       => explode( ', ', $atts['ids'] ),
			'posts_per_page' => 1,
			'orderby'        => 'rand',
		)
	);

	$query = new WP_Query( $args );

	if ( ! $query->have_posts() ) {
		return;
	}

	ob_start();
	?>
	<div class="slider-wrapper slider-featured"
		style="background: url(<?php echo esc_url( $atts['background_url'] ); ?>) no-repeat top center; background-size: cover;">

		<ul class="slider">
			<?php while ( $query->have_posts() ) : $query->the_post(); ?>
			<li>
				<div class="slider-featured-box">
					<figure class="thumbnail">
						<?php the_post_thumbnail( 'thumbnail' ); ?>
					</figure>
					<h2 class="title"><?php the_title(); ?></h2>
					<div class="excerpt">
						<?php the_excerpt(); ?>
					</div>
				</div>
			</li>
			<?php endwhile; ?>
		</ul>
	</div>
	<?php
	wp_reset_postdata();

	$result = ob_get_clean();

	return apply_filters( 'zo7i_shortcode_content', $result );
}

add_action( 'plugins_loaded', 'zo7i_maybe_create_table' );

function zo7i_maybe_create_table()
{
	$version = get_option( 'zo7i_database_version' );

	if ( $version === PC_DB_VERSION ) {
		return;
	}

	global $wpdb;

	$table   = $wpdb->prefix . 'highlights';
	$charset = $wpdb->get_charset_collate();

	$sql = "
		CREATE TABLE {$table} (
			ID bigint(20) NOT NULL AUTO_INCREMENT,
			name text,
			description text,
			example text,
			PRIMARY KEY  (ID)
		) {$charset}
	";

	require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );

	dbDelta( $sql );

	update_option( 'zo7i_database_version', PC_DB_VERSION );
}

function zo7i_insert_default_data()
{
	global $wpdb;

	$table  = $wpdb->prefix . 'highlights';
	$result = $wpdb->get_var( "SELECT COUNT(*) FROM {$table}" );

	if ( $result ) {
		return;
	}

	$wpdb->insert(
		$table,
		array(
			'name'        => 'Curso de plugins',
			'description' => 'Apiki WordPress',
			'example'     => 'Algum valor',
		)
	);
}
