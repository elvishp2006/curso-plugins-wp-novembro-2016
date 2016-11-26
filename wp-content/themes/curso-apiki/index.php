<?php get_header(); ?>
		<div class="title-main">
			<div class="container">
				<h1 class="title">Blog <?php single_term_title( ' - Categoria:' ); ?></h1>
			</div>
		</div>

		<div class="container">
			<div id="content">
				<div class="list-news">
					<?php
						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/blog-loop' );
						endwhile;
					?>
				</div>

				<div class="pagination">
					<?php apiki_pagination(); ?>
				</div>
			</div>

			<?php get_sidebar(); ?>
		</div>
<?php get_footer(); ?>