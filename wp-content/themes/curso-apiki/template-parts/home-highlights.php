<?php
if ( ! function_exists( 'add_action' ) ) {
	exit( 0 );
}

$option = get_option( 'zo7i_settings' );

if ( intval( $option['highlights-quantity'] ) <= 0 ) {
	return;
}

$query = new WP_Query(
	array(
		'post_type'      => 'zo7i_highlights',
		'posts_per_page' => intval( $option['highlights-quantity'] ),
		'orderby'        => 'menu_order',
		'order'          => 'ASC',
	)
);

if ( ! $query->have_posts() ) {
	return;
}

$background_url = get_template_directory_uri() . '/assets/images/wallpaper.png';

if ( $option['highlights-background-url'] ) {
	$background_url = $option['highlights-background-url'];
}
?>

<div class="slider-wrapper slider-featured"
	 style="background: url(<?php echo esc_url( $background_url ); ?>) no-repeat top center; background-size: cover;">

	<ul class="slider" data-component="bxslider">
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

<?php wp_reset_postdata(); ?>
