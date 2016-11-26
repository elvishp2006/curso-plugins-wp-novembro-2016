<?php get_header(); ?>

		<div class="title-main">
			<div class="container">
				<h1 class="title">Listagem de Cursos</h1>
			</div>
		</div>

		<div class="container">
			<div class="list list-card">
				<?php
					while ( have_posts() ) :
						the_post();
						get_template_part( 'template-parts/course-loop' );
					endwhile;
				?>
			</div>

			<div class="pagination">
				<?php apiki_pagination(); ?>
			</div>
		</div>

<?php get_footer(); ?>