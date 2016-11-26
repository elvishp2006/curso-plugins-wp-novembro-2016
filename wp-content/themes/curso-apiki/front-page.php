<?php get_header(); ?>

<?php get_template_part( 'template-parts/home-highlights' ); ?>

		<div class="container">
			<h2 class="title-large">Últimos Cursos</h2>
			<div class="list list-card">
				<?php
					$course_query = new WP_Query(
						array(
							'post_type'      => 'apiki_course',
							'posts_per_page' => 4,
						)
					);

					while ( $course_query->have_posts() ) :
						$course_query->the_post();
						get_template_part( 'template-parts/course-loop' );
					endwhile;

					wp_reset_postdata();
				?>
			</div>

			<h2 class="title-large">Cursos de Desenvolvimento</h2>
			<div class="list list-card">
				<?php
					$course_tax_query = new WP_Query(
						array(
							'post_type'      => 'apiki_course',
							'posts_per_page' => 4,
							'tax_query'      => array(
								'relation' => 'AND',
								array(
									'taxonomy' => 'apiki_course_subject',
									'field'    => 'slug',
									'terms'    => 'desenvolvimento',
									'operator' => 'IN',
								),
								array(
									'taxonomy' => 'apiki_course_subject',
									'field'    => 'slug',
									'terms'    => array( 'gestao', 'infraestrutura', 'marketing' ),
									'operator' => 'NOT IN',
								)
							),
							'order'   => 'ASC',
							'orderby' => 'title',
						)
					);

					while ( $course_tax_query->have_posts() ) :
						$course_tax_query->the_post();
						get_template_part( 'template-parts/course-loop' );
					endwhile;

					wp_reset_postdata();
				?>
			</div>

			<h2 class="title-large">Cursos do Instrutor Arilton</h2>
			<div class="list list-card">
				<?php
					$course_tax_query = new WP_Query(
						array(
							'post_type'      => 'apiki_course',
							'posts_per_page' => 4,
							'order'          => 'ASC',
							'orderby'        => 'title',
							'meta_query' => array(
								array(
									'key'     => 'apiki_course_teacher',
									'value'   => 'Arilton Freitas',
									'compare' => '=',
									'type'    => 'CHAR',
								)
							)
						)
					);

					while ( $course_tax_query->have_posts() ) :
						$course_tax_query->the_post();
						get_template_part( 'template-parts/course-loop' );
					endwhile;

					wp_reset_postdata();
				?>
			</div>

			<section class="blog-wrap">
				<h2 class="title-large">Blog</h2>
				<div class="list-news">
					<?php
						global $wp_query;

						// print_r( $wp_query ); exit;

						query_posts( 'post_type=post&posts_per_page=2' );

						// print_r( $wp_query ); exit;

						while ( have_posts() ) :
							the_post();
							get_template_part( 'template-parts/blog-loop' );
						endwhile;

						wp_reset_query();

						// print_r( $wp_query ); exit;
					?>
					<div class="pagination">
						<ul>
							<li>
								<a href="javascript:;" class="next page-numbers" id="more-posts">Ver Mais</a>
							</li>
						</ul>
					</div>
				</div>
			</section>
		</div>
<?php get_footer(); ?>
