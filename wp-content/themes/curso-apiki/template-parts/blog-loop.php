					<a class="news" href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>">
						<?php if ( has_post_thumbnail() ) : ?>
						<figure class="thumbnail">
							<?php the_post_thumbnail( 'apiki-blog-home' ); ?>
						</figure>
						<?php endif; ?>
						<div class="excerpt">
							<h3 class="title"><?php the_title(); ?></h3>
							<?php the_excerpt(); ?>
						</div>
					</a>