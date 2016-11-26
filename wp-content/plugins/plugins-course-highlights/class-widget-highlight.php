<?php
if ( ! function_exists( 'add_action' ) ) {
	exit( 0 );
}

class Highlight_Widget extends WP_Widget
{
	public function __construct()
	{
		$args = array(
			'classname'   => 'widget-highlight',
			'description' => 'Exibe um destaque aleatório',
		);

		parent::__construct( 'highlight_widget', 'Destaque aleatório', $args );
	}

	public function form( $instance )
	{
		?>
		<p>
			<label>Título
				<input type="text"
					   class="widefat"
					   name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>"
					   value="<?php echo esc_attr( @$instance['title'] ); ?>" />
			</label>
		</p>

		<?php do_action( 'zo7i_widget_after_title', $this, $instance ); ?>

		<p>
			<label>Background URL
				<input type="text"
					   class="widefat"
					   name="<?php echo esc_attr( $this->get_field_name( 'background-url' ) ); ?>"
					   value="<?php echo esc_attr( @$instance['background-url'] ); ?>" />
			</label>
		</p>
		<?php
		do_action( 'zo7i_widget_after_background', $this, $instance );
	}

	public function update( $new_instance, $old_instance )
	{
		$instance['title']          = strip_tags( $new_instance['title'] );
		$instance['background-url'] = esc_url( $new_instance['background-url'] );

		return apply_filters( 'zo7i_widget_update', $instance, $new_instance, $old_instance );
	}

	public function widget( $args, $instance )
	{
		$query = $this->_get_query();

		if ( ! $query->have_posts() ) {
			return;
		}

		echo $args['before_widget'];

		if ( @$instance['title'] ) {
			echo $args['before_title'] . $instance['title'] . $args['after_title'];
		}
		?>
		<div class="slider-wrapper slider-featured"
			style="background: url(<?php echo esc_url( $instance['background-url'] ); ?>) no-repeat top center; background-size: cover;">

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
		echo $args['after_widget'];

		wp_reset_postdata();
	}

	private static function _get_query()
	{
		$args = apply_filters(
			'zo7i_widget_query_args',
			array(
				'post_type'      => 'zo7i_highlights',
				'posts_per_page' => 1,
				'orderby'        => 'rand',
			)
		);

		return new WP_Query( $args );
	}
}
