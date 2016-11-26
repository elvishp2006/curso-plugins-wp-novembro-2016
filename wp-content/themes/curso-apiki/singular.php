<?php
	get_header();

	the_post();
?>
		<?php if ( has_post_thumbnail() ) : ?>
		<figure class="thumbnail-main">
			<?php the_post_thumbnail( 'apiki-header-single' ); ?>
		</figure>
		<?php endif; ?>

		<div class="container">
			<div id="content">
				<h1 class="title title-large"><?php the_title(); ?></h1>
				<article class="the-content">
					<?php the_content(); ?>
				</article>

				<?php the_terms( get_the_ID(), 'apiki_course_subject', '<ul class="card-tags"><li class="tag">', '</li><li class="tag">', '</li></ul>' ); ?>
			</div>

			<?php get_sidebar(); ?>
		</div>

		<div class="container">
			<?php get_template_part( 'template-parts/view-more' ); ?>

			<?php
				if ( is_single() ) :
					comments_template(); 
				endif;
			?>
		</div>

<?php get_footer(); ?>