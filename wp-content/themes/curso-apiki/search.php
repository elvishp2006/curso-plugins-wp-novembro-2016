<?php get_header(); ?>
		
		<div class="title-main">
			<div class="container">
				<h1 class="title">Busca</h1>
			</div>
		</div>

		<div class="container">
			<div id="content">
				<div class="content-search">
					<form action="<?php echo site_url(); ?>" class="search-form" method="get" role="search">
						<input title="Search for:" name="s" value="<?php the_search_query(); ?>" placeholder="Search …" class="search-field" type="search">
						<button class="icon-search"></button>
					</form>
					<div class="search-form-text">Resultados da pesquisa para: <strong><?php the_search_query(); ?></strong></div>
				</div>

				<div class="list list-card-content">
					<?php
						while ( have_posts() ) :
							the_post();
					?>
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
							<?php the_excerpt(); ?>
						</div>
					</div>
					<?php endwhile; ?>
				</div>

				<div class="pagination">
					<?php apiki_pagination(); ?>
				</div>
			</div>

			<?php get_sidebar(); ?>
		</div>

<?php get_footer(); ?>