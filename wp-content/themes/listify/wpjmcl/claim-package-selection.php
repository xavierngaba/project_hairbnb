<?php
/**
 * Claim package selection.
 *
 * @version 1.0.0
 * @since 2.6.0
 *
 * @var array $package WC Products for Listing Payments/WP Post for Paid Listing.
 *
 * @package Claim Listing
 * @category Template
 * @author Astoundify
 */

$stacked = apply_filters( 'listify_submit_listing_packages_stacked', false );
$count   = count( $packages ) > 3 ? 3 : count( $packages );
?>

<form id="<?php echo esc_attr( $form->get_form_name() ); ?>" class="job-manager-form wpjmcl_form wpjmcl_form_claim_package" method="post">

	<?php if ( $packages ) { ?>
		<input type="hidden" name="claim_id" value="<?php echo esc_attr( $form->claim_id ); ?>" />
		<input type="hidden" name="step" value="<?php echo esc_attr( $form->get_step() ); ?>">
	<?php } ?>

	<ul class="job-packages <?php echo esc_attr( $stacked ? 'job-packages--stacked' : ( 'job-packages--inline job-packages--count-' . $count ) ); ?>">

		<?php foreach ( $packages as $package ) : ?>

			<?php
			// Get Product from WC Product/WP Post object by checking if WC method exists.
			$product    = wc_get_product( method_exists( $package, 'get_id' ) ? $package : $package->ID );
			$tags       = wc_get_product_tag_list( $product->get_id() );
			$action_url = add_query_arg( 'choose_package', $product->get_id(), job_manager_get_permalink( 'submit_job_form' ) );
			?>

			<li class="job-package <?php echo esc_attr( $stacked ? 'job-package--stacked' : null ); ?>">

				<?php if ( $tags ) : ?>
				<span class="job-package-tag <?php echo esc_attr( $stacked ? 'job-package-tag--stacked' : null ); ?>">
					<span class="job-package-tag__text"><?php echo esc_attr( strip_tags( $tags ) ); ?></span>
				</span>
				<?php endif; ?>

				<div class="job-package-header <?php echo esc_attr( $stacked ? 'job-package-header--stacked' : null ); ?>">

					<div class="job-package-title <?php echo esc_attr( $stacked ? 'job-package-title--stacked' : null ); ?>">
						<?php echo esc_attr( $product->get_title() ); ?>
					</div>

					<div class="job-package-price <?php echo esc_attr( $stacked ? 'job-package-price--stacked' : null ); ?>">
						<?php echo $product->get_price_html(); // WPCS: XSS ok. ?>
					</div>

					<div class="job-package-purchase <?php echo esc_attr( $stacked ? 'job-package-purchase--stacked' : null ); ?>">
						<button class="button" type="submit" name="job_package" value="<?php echo esc_attr( $product->get_id() ); ?>"><?php esc_html_e( 'Get Started Now &rarr;', 'listify' ); ?></button>
					</div>
				</div>

				<div class="job-package-includes <?php echo esc_attr( $stacked ? 'job-package-includes---stacked' : null ); ?>">
					<?php
					$content = $product->get_description();
					$content = (array) explode( "\n", $content );
					?>
					<ul>
						<li><?php echo implode( '</li><li>', $content ); // WPCS: XSS ok. ?></li>
					</ul>
				</div>

				<div class="job-package-purchase <?php echo esc_attr( $stacked ? 'job-package-purchase--stacked' : null ); ?>">
					<button class="button" type="submit" name="job_package" value="<?php echo esc_attr( $product->get_id() ); ?>"><?php esc_html_e( 'Claim Listing &rarr;', 'listify' ); ?></button>
				</div>
			</li>

		<?php endforeach; ?>

	</ul><!-- .job-packages -->

</form>