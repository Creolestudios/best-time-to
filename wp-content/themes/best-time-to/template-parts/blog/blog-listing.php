<?php
/**
 * Template part for blog listing according to category.
 *
 * Listing based on category.
 */

$post_id          = get_the_ID();
$post_url         = get_permalink();
$post_title       = get_the_title();
$post_image       = get_the_post_thumbnail_url( $post_id, 'large' );
$post_author_id   = get_post_field( 'post_author', $post_id );
$post_author_name = get_the_author_meta( 'display_name', $post_author_id );
$post_author_img  = get_avatar( $post_author_id, 96 );
$post_date        = get_the_date();
$post_tags        = get_the_tags();
$post_category    = get_the_category();
$post_nature_term = get_the_terms( $post_id, 'nature' );
?>

<div class="blog">
	<div class="blog-item">
		<a href="<?php echo esc_url( $post_url ); ?>">
			<?php
			if ( ! empty( $post_image ) ) {
				?>
					<img src="<?php echo esc_attr( $post_image ); ?>" alt="blog-images" />
				<?php
			} else {
				$no_image_thumbnail = get_field( 'no_thumbnail_image', 'option' );
				?>
					<img src="<?php echo esc_url( $no_image_thumbnail ); ?>" alt="no-image-thumbnail">
				<?php
			}
			?>
		</a>
		<div class="content">
			<div class="badges">
				<?php
				if ( $post_tags ) {
					foreach ( $post_tags as $tag ) {
						$tag_url      = get_term_link( $tag );
						$tag_name     = $tag->name;
						$new_badge    = '';
						$feature_bdge = '';

						$tag->slug == 'new' ? $new_badge         = 'new' : '';
						$tag->slug == 'featured' ? $feature_bdge = 'featured' : '';
						?>
							<a href="<?php echo esc_url( $tag_url ); ?>" class="badge <?php echo esc_attr( $new_badge ), esc_attr( $feature_bdge ); ?>"><?php echo esc_html( $tag->name ); ?></a>
						<?php
					}
				}
				if ( $post_category ) {
					foreach ( $post_category as $category ) {
						$category_url = get_category_link( $category );
						?>
							<a href="<?php echo esc_url( $category_url ); ?>" class="badge"><?php echo esc_html( $category->name ); ?></a>
						<?php
					}
				}
				if ( $post_nature_term ) {
					foreach ( $post_nature_term as $term ) {
						$term_url = get_category_link( $term );
						?>
							<a href="<?php echo esc_url( $term_url ); ?>" class="badge"><?php echo esc_html( $term->name ); ?></a>
						<?php
					}
				}
				?>
			</div>
			<a href="<?php echo esc_url( $post_url ); ?>">
				<h6><?php echo esc_html( $post_title ); ?></h6>
			</a>
			<div class="author">
				<?php echo wp_kses_post( $post_author_img ); ?>
				<div class="auth-detail">
					<h5><?php echo esc_html( $post_author_name ); ?></h5>
					<h6><?php echo esc_html( $post_date ); ?></h6>
				</div>
			</div>
		</div>
	</div>
</div>
