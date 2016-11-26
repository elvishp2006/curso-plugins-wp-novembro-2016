<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
	<title>Apiki WP Cursos</title>
	<?php wp_head(); ?>
</head>
<body>
	
	<header id="header">
		<div class="branding">
			<a href="<?php echo site_url(); ?>" title="">
				<img src="<?php echo get_template_directory_uri(); ?>/assets/images/branding.svg" alt="" width="22" height="22">
			</a>
		</div>
		<ul class="navigation-mobile">
			<li class="btn-menu"><span class="hamburger"></span></li>
			<li class="btn-search"><span class="icon-search"></span></li>
		</ul>

		<?php
			wp_nav_menu(
				array(
					'theme_location'  => 'apiki-main-menu',
					'container'       => 'nav',
					'container_class' => 'navigation-main',
					'fallback_cb'     => '',
					'depth'           => 1,
				)
			);
		?>

		<div class="header-search">
			<?php get_search_form(); ?>
		</div>
	</header>

	<div id="wrapper-container">