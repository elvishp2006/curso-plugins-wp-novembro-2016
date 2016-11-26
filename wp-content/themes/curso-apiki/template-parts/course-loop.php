				<div class="card">
					<?php if ( has_post_thumbnail() ) : ?>
					<figure class="thumbnail">
						<?php the_post_thumbnail( 'apiki-listing' ); ?>
					</figure>
					<?php endif; ?>
					<h2 class="card-title">
						<a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>"><?php the_title(); ?></a>
					</h2>
					<div class="excerpt">
						<?php
							the_excerpt();

							$time    = esc_attr( get_post_meta( get_the_ID(), 'apiki_course_time', true ) );
							$teacher = esc_attr( get_post_meta( get_the_ID(), 'apiki_course_teacher', true ) );
						?>
						<p><strong>Carga Horária:</strong> <?php echo $time; ?></p>
						<p><strong>Nome do Professor:</strong> <?php echo $teacher; ?></p>
					</div>

					<?php the_terms( get_the_ID(), 'apiki_course_subject', '<ul class="card-tags"><li class="tag">', '</li><li class="tag">', '</li></ul>' ); ?>
				</div>