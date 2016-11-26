<?php
add_action( 'after_setup_theme', 'apiki_setup_theme' );
function apiki_setup_theme()
{
	apiki_theme_supports();
	apiki_add_image_sizes();
	apiki_register_menus();

	add_filter( 'widget_text','do_shortcode' );
	add_action( 'the_generator', '__return_false' );
	// add_filter( 'the_excerpt', 'apiki_custom_excerpt' );
	add_filter( 'excerpt_length', 'apiki_excerpt_length' );
	add_action( 'widgets_init', 'apiki_widgets_init' );

	add_action( 'wp_enqueue_scripts', 'apiki_add_scripts' );
	add_action( 'admin_enqueue_scripts', 'apiki_add_admin_scripts' );
	add_action( 'wp_enqueue_scripts', 'apiki_add_styles' );

	add_editor_style( '/assets/stylesheets/editor-style.css' );

	add_action( 'init', 'apiki_register_cpt' );
	add_action( 'init', 'apiki_register_tax' );

	add_action( 'admin_menu', 'apiki_change_posts_label' );

	add_action( 'add_meta_boxes', 'apiki_add_metaboxes' );
	add_action( 'save_post', 'apiki_save_postmeta' );

	add_action( 'wp_ajax_more_posts', 'apiki_catch_ajax_more_posts' );
	add_action( 'wp_ajax_nopriv_more_posts', 'apiki_catch_ajax_more_posts' );
}

function apiki_catch_ajax_more_posts()
{
	$offset = intval( $_REQUEST['offset'] );

	$more_posts_query = new WP_Query(
		array(
			'post_type'      => 'post',
			'posts_per_page' => 2,
			'offset'         => $offset,
		)
	);

	while ( $more_posts_query->have_posts() ) {
		$more_posts_query->the_post();
		get_template_part( 'template-parts/blog-loop' );
	}

	exit;
}

function apiki_save_postmeta( $post_id )
{
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	if ( isset( $_POST['apiki_course_time'] ) ) {
		update_post_meta( $post_id, 'apiki_course_time', esc_attr( $_POST['apiki_course_time'] ) );
	}

	if ( isset( $_POST['apiki_course_teacher'] ) ) {
		update_post_meta( $post_id, 'apiki_course_teacher', esc_attr( $_POST['apiki_course_teacher'] ) );
	}

	if ( isset( $_POST['apiki_course_link'] ) ) {
		update_post_meta( $post_id, 'apiki_course_link', esc_url( $_POST['apiki_course_link'] ) );
	}
}

function apiki_add_metaboxes()
{
	add_meta_box(
		'apiki_course_info',
		'Informações do Curso',
		'apiki_render_course_metabox',
		'apiki_course',
		'advanced',
		'high'
		// $callback_args
	);
}

function apiki_render_course_metabox( $post, $box )
{
	$time    = esc_attr( get_post_meta( $post->ID, 'apiki_course_time', true ) );
	$teacher = esc_attr( get_post_meta( $post->ID, 'apiki_course_teacher', true ) );
	$link    = esc_url( get_post_meta( $post->ID, 'apiki_course_link', true ) );
?>
	<p>
		<label for="apiki-course-time">Carga Horária:</label>
		<input type="text" id="apiki-course-time" name="apiki_course_time" class="large-text" value="<?php echo $time ?>" />
	</p>
	<p>
		<label for="apiki-course-teacher">Nome do Instrutor:</label>
		<input type="text" id="apiki-course-teacher" name="apiki_course_teacher" class="large-text" value="<?php echo $teacher ?>" />
	</p>
	<p>
		<label for="apiki-course-link">Link de Apoio:</label>
		<input type="text" id="apiki-course-link" name="apiki_course_link" class="large-text" value="<?php echo $link; ?>" />
	</p>
<?php
}

function apiki_register_tax()
{
	register_taxonomy(
		'apiki_course_subject',
		'apiki_course',
		array(
			'labels' => array(
				'name' => 'Assunto',
			),
			'public'            => true,
			'hierarchical'      => true,
			'show_admin_column' => true,
			'rewrite'           => array(
				'slug'       => 'curso-assunto',
				'with_front' => false,
			),
		)
	);

	register_taxonomy_for_object_type( 'post_tag', 'apiki_course' );
	register_taxonomy_for_object_type( 'apiki_course_subject', 'post' );
}

function apiki_change_posts_label()
{
	global $menu;

	$menu[5][0] = 'Blog';
}

function apiki_register_cpt()
{
	register_post_type(
		'apiki_course',
		array(
			'labels' => array(
				'name'         => 'Cursos',
				'not_found'    => 'Nenhum curso encontrado',
				'add_new_item' => 'Adicionar novo curso',
				'edit_item'    => 'Editar Curso',
			),
			'public'        => true,
			'menu_position' => 5,
			'menu_icon'     => 'dashicons-book-alt',
			'hierarchical'  => false,
			'supports'      => array( 'title', 'editor', 'excerpt', 'page-attributes', 'thumbnail' ),
			'has_archive'   => true,
			'rewrite'       => array(
				'slug'       => 'cursos',
				'with_front' => false,
			)
		)
	);
}

function apiki_add_styles()
{
	wp_enqueue_style(
		'apiki-theme-style',
		get_stylesheet_uri(),
		array( 'dashicons' ),
		filemtime( TEMPLATEPATH . '/style.css' )
	);
}

function apiki_add_admin_scripts()
{
	wp_enqueue_script(
		'apiki-admin-theme',
		get_template_directory_uri() . '/ghost/assets/javascripts/admin-scripts.js',
		array( 'jquery' ),
		filemtime( TEMPLATEPATH . '/ghost/assets/javascripts/admin-scripts.js' ),
		true
	);
}

function apiki_add_scripts()
{

	// wp_register_script( $handle, $src, $deps, $ver, $in_footer );

	// wp_deregister_script( 'jquery' );

	// wp_register_script(
	// 	'jquery',
	// 	get_stylesheet_directory_uri() . '/ghost/assets/javascripts/libs/jquery.js',
	// 	array(),
	// 	filemtime( TEMPLATEPATH . '/ghost/assets/javascripts/libs/jquery.js' ),
	// 	true
	// );

	wp_enqueue_script(
		'apiki-theme-built',
		get_template_directory_uri() . '/ghost/assets/javascripts/built.js',
		array( 'jquery' ),
		filemtime( TEMPLATEPATH . '/ghost/assets/javascripts/built.js' ),
		true
	);

	wp_localize_script( 'apiki-theme-built', 'APIKI_AJAX_URL', admin_url( 'admin-ajax.php' ) );
}

function apiki_excerpt_length( $length )
{
	return 10;
}

if ( ! function_exists( 'apiki_custom_excerpt' ) ) {
	function apiki_custom_excerpt( $excerpt_text )
	{
		return '[APIKI]' . $excerpt_text;
	}
}

function apiki_theme_supports()
{
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'html5' );
}

function apiki_add_image_sizes()
{
	add_image_size( 'apiki-blog-home', 200, 200, true );
	add_image_size( 'apiki-header-single', 1400, 450, true );
	add_image_size( 'apiki-listing', 280, 170, true );
}

function apiki_register_menus()
{
	register_nav_menu( 'apiki-main-menu', 'Menu Principal' );
}

function apiki_pagination()
{
	global $wp_query;

	$big = 99999999999;

	echo paginate_links(
		array(
			'base'    => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
			'format'  => '?paged=%#%',
			'current' => max( 1, get_query_var( 'paged' ) ),
			'total'   => $wp_query->max_num_pages,
			'type'    => 'list',
		)
	);
}

function apiki_widgets_init()
{
	register_sidebar(
		array(
			'name'          => 'Sidebar single',
			'id'            => 'sidebar-single',
			'description'   => 'Widgets serão exibidos na single',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget'  => '</div>',
			'before_title'  => '<h3 class="widget-title">',
			'after_title'   => '</h3>',
		)
	);
}

add_filter( 'zo7i_widget_query_args', 'apiki_filter_query_highlights' );

function apiki_filter_query_highlights( $args )
{
	$args['post_type'] = 'post';

	return $args;
}

add_action( 'zo7i_widget_after_background', 'apiki_add_new_widget_field', 10, 2 );

function apiki_add_new_widget_field( $widget, $instance )
{
	?>
	<p>
		<label>Meu novo campo
			<input type="text"
					class="widefat"
					name="<?php echo esc_attr( $widget->get_field_name( 'meu-novo-campo' ) ); ?>"
					value="<?php echo esc_attr( @$instance['meu-novo-campo'] ); ?>" />
		</label>
	</p>
	<?php
}

add_filter( 'zo7i_widget_update', 'apiki_highlight_update', 10, 3 );

function apiki_highlight_update( $instance, $new_instance, $old_instance )
{
	$instance['meu-novo-campo'] = esc_attr( $new_instance['meu-novo-campo'] );
	$instance['my-page']        = intval( $new_instance['my-page'] );

	return $instance;
}

add_action( 'zo7i_widget_after_title', 'apiki_add_page_field', 10, 2 );

function apiki_add_page_field( $widget, $instance )
{
	?>
	<p>
		<label>Página
			<?php
			wp_dropdown_pages(
				array(
					'selected'         => intval( @$instance['my-page'] ),
					'name'             => esc_attr( $widget->get_field_name( 'my-page' ) ),
					'class'            => 'widefat',
					'show_option_none' => 'Selecione...',
				)
			);
			?>
		</label>
	</p>
	<?php
}

add_filter( 'zo7i_shortcode_query_args', 'apiki_highlight_shortcode_query_args' );

function apiki_highlight_shortcode_query_args( $args )
{
	return array(
		'post_type'      => 'post',
		'posts_per_page' => 1,
		'orderby'        => 'rand',
	);
}

add_filter( 'zo7i_shortcode_content', '__return_false' );
