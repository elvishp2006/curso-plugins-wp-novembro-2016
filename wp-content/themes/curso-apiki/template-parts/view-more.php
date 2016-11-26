			<h2 class="title-large">Veja também</h2>

			<?php
				$subjects = get_the_terms( get_the_ID(), 'apiki_course_subject' );
				$term_ids = wp_list_pluck( $subjects, 'term_id' );

				$view_more_query = new WP_Query(
					array(
						'post_type'      => 'post',
						'posts_per_page' => 4,
						'tax_query'      => array(
							array(
								'taxonomy' => 'apiki_course_subject',
								'field'    => 'term_id',
								'terms'    => $term_ids,
								'operator' => 'IN',
							)
						)
					)
				);
			?>
			<div class="list list-card">
				<?php
					while ( $view_more_query->have_posts() ) :
						$view_more_query->the_post();
						get_template_part( 'template-parts/course-loop' );
					endwhile;

					wp_reset_postdata();
				?>
			</div>