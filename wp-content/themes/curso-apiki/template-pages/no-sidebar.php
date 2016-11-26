<?php
/**
 * Template Name: Sem Sidebar
 */
	get_header();

	the_post();
?>
		<?php if ( has_post_thumbnail() ) : ?>
		<figure class="thumbnail-main">
			<?php the_post_thumbnail( 'apiki-header-single' ); ?>
		</figure>
		<?php endif; ?>

		<div class="container">
			<h1 class="title title-large"><?php the_title(); ?></h1>
			<article class="the-content">
				<?php the_content(); ?>
			</article>
		</div>

<?php get_footer(); ?>