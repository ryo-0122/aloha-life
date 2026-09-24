<?php
/**
 * 施工事例詳細
 *
 * スタイル: assets/scss/_case-detail.scss（コンパイル済み: assets/css/case-detail.css）
 * スライダー: assets/js/case-detail.js
 * 設定・関数: inc/redesign.php（建物概要のフィールド名・ギャラリーのフィールド名など）
 */

get_header();

while ( have_posts() ) :
	the_post();

	$case_id      = get_the_ID();
	$gallery_ids  = aloha_case_gallery_ids( $case_id );
	$term_groups  = aloha_case_term_groups( $case_id );
	$archive_link = get_post_type_archive_link( ALOHA_CASES_POST_TYPE );
	$catalog_link = apply_filters( 'aloha_catalog_url', home_url( '/catalog/' ) );

	// 建物概要（値が1件も入っていなければ表ごと非表示）
	$overview     = array();
	$has_overview = false;
	foreach ( aloha_case_overview_fields() as $label => $field ) {
		$value = aloha_get_field( $field, $case_id );
		$value = is_array( $value ) ? implode( ' / ', array_filter( array_map( 'strval', $value ) ) ) : (string) $value;
		if ( '' !== trim( $value ) ) {
			$has_overview = true;
		}
		$overview[ $label ] = $value;
	}
	?>
<div class="CaseDetail">
	<main class="CaseDetail__wrap">

		<div class="CaseDetail__breadcrumb">
			<?php if ( $archive_link ) : ?>
				<a href="<?php echo esc_url( $archive_link ); ?>">施工事例一覧</a>&nbsp;&rang;&nbsp;
			<?php endif; ?>
			<?php the_title(); ?>
		</div>

		<?php if ( $gallery_ids ) : ?>
			<div class="CaseDetail__slider" data-case-slider>
				<?php foreach ( $gallery_ids as $index => $image_id ) : ?>
					<?php
					echo wp_get_attachment_image(
						$image_id,
						'large',
						false,
						array(
							'class'   => 'CaseDetail__slider-slide' . ( 0 === $index ? ' is-active' : '' ),
							'alt'     => get_post_meta( $image_id, '_wp_attachment_image_alt', true ) ? get_post_meta( $image_id, '_wp_attachment_image_alt', true ) : get_the_title() . ' 写真' . ( $index + 1 ),
							'loading' => 0 === $index ? 'eager' : 'lazy',
						)
					);
					?>
				<?php endforeach; ?>
				<?php if ( count( $gallery_ids ) > 1 ) : ?>
					<button type="button" class="CaseDetail__slider-arrow CaseDetail__slider-arrow--prev" aria-label="前の写真">&lsaquo;</button>
					<button type="button" class="CaseDetail__slider-arrow CaseDetail__slider-arrow--next" aria-label="次の写真">&rsaquo;</button>
					<div class="CaseDetail__slider-dots" aria-hidden="true">
						<?php foreach ( $gallery_ids as $index => $image_id ) : ?>
							<i<?php echo 0 === $index ? ' class="is-active"' : ''; ?>></i>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
			<?php if ( count( $gallery_ids ) > 1 ) : ?>
				<div class="CaseDetail__thumbs">
					<?php foreach ( $gallery_ids as $index => $image_id ) : ?>
						<button type="button" class="CaseDetail__thumb<?php echo 0 === $index ? ' is-active' : ''; ?>" data-index="<?php echo esc_attr( $index ); ?>" aria-label="<?php echo esc_attr( sprintf( '写真%dを表示', $index + 1 ) ); ?>">
							<?php echo wp_get_attachment_image( $image_id, 'thumbnail', false, array( 'alt' => '' ) ); ?>
						</button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		<?php else : ?>
			<div class="CaseDetail__slider"><span class="CaseDetail__placeholder-photo"></span></div>
		<?php endif; ?>

		<div class="CaseDetail__detail">
			<?php if ( $term_groups ) : ?>
				<div class="CaseDetail__tag-groups">
					<?php
					$group_index = 0;
					foreach ( $term_groups as $group ) :
						// 1つ目のタクソノミー（スタイル）だけ黒背景で強調
						$group_class = 0 === $group_index ? ' CaseDetail__tag-group--style' : '';
						$group_index++;
						?>
						<div class="CaseDetail__tag-group<?php echo esc_attr( $group_class ); ?>">
							<span class="CaseDetail__tag-group-label"><?php echo esc_html( $group['label'] ); ?></span>
							<div class="CaseDetail__tag-group-tags">
								<?php foreach ( $group['terms'] as $term ) : ?>
									<?php $term_link = get_term_link( $term ); ?>
									<?php if ( ! is_wp_error( $term_link ) ) : ?>
										<a href="<?php echo esc_url( $term_link ); ?>"><?php echo esc_html( $term->name ); ?></a>
									<?php else : ?>
										<span><?php echo esc_html( $term->name ); ?></span>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<h1 class="CaseDetail__title"><?php the_title(); ?></h1>

			<div class="CaseDetail__lead">
				<?php the_content(); ?>
			</div>

			<?php if ( $has_overview ) : ?>
				<div class="CaseDetail__overview">
					<div class="CaseDetail__overview-title">建物概要</div>
					<div class="CaseDetail__overview-table">
						<?php foreach ( $overview as $label => $value ) : ?>
							<div class="CaseDetail__overview-cell">
								<div class="CaseDetail__overview-key"><?php echo esc_html( $label ); ?></div>
								<div class="CaseDetail__overview-value"><?php echo '' !== trim( $value ) ? esc_html( $value ) : '&mdash;'; ?></div>
							</div>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endif; ?>
		</div>

		<div class="CaseDetail__cta"><a href="<?php echo esc_url( $catalog_link ); ?>" class="CaseDetail__btn">カタログを請求する &rarr;</a></div>
	</main>

	<?php
	// 関連事例: 同じタームを持つ施工事例を優先し、足りなければ新着で補う
	$related_ids = array();
	$tax_query   = array( 'relation' => 'OR' );
	foreach ( $term_groups as $taxonomy => $group ) {
		$tax_query[] = array(
			'taxonomy' => $taxonomy,
			'field'    => 'term_id',
			'terms'    => wp_list_pluck( $group['terms'], 'term_id' ),
		);
	}
	if ( count( $tax_query ) > 1 ) {
		$related_ids = get_posts(
			array(
				'post_type'      => ALOHA_CASES_POST_TYPE,
				'posts_per_page' => 3,
				'post__not_in'   => array( $case_id ),
				'tax_query'      => $tax_query, // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				'orderby'        => 'rand',
				'fields'         => 'ids',
			)
		);
	}
	if ( count( $related_ids ) < 3 ) {
		$related_ids = array_merge(
			$related_ids,
			get_posts(
				array(
					'post_type'      => ALOHA_CASES_POST_TYPE,
					'posts_per_page' => 3 - count( $related_ids ),
					'post__not_in'   => array_merge( array( $case_id ), $related_ids ),
					'fields'         => 'ids',
				)
			)
		);
	}
	?>

	<?php if ( $related_ids ) : ?>
		<section class="CaseDetail__related" aria-labelledby="case-related-heading">
			<div class="CaseDetail__related-head">
				<h2 id="case-related-heading" class="CaseDetail__related-head-en">Related Works</h2>
				<span class="CaseDetail__related-head-jp">関連事例</span>
			</div>
			<div class="CaseDetail__related-grid">
				<?php foreach ( $related_ids as $related_id ) : ?>
					<a href="<?php echo esc_url( get_permalink( $related_id ) ); ?>" class="CaseDetail__related-card">
						<?php
						if ( has_post_thumbnail( $related_id ) ) {
							echo get_the_post_thumbnail( $related_id, 'large', array( 'alt' => get_the_title( $related_id ), 'loading' => 'lazy' ) );
						} else {
							echo '<span class="CaseDetail__placeholder-photo"></span>';
						}
						?>
						<h3><?php echo esc_html( get_the_title( $related_id ) ); ?></h3>
						<span class="CaseDetail__related-card-more">More</span>
					</a>
				<?php endforeach; ?>
			</div>
		</section>
	<?php endif; ?>
</div>
	<?php
endwhile;

get_footer();
