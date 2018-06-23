<?php
/**
 * Home feature
 */
?>

<div class="home-feature">
	<div class="home-feature-media">
		<img src="<?php echo esc_url( $feature['media'] ); ?>" alt="" />
	</div>
	<div class="home-feature-title"><h2><?php echo wp_kses_post( $feature['title'] ); ?></h2></div>
	<div class="home-feature-description"><?php echo wp_kses_post( $feature['description'] ); ?></div>
</div>
