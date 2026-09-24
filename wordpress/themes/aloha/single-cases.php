<?php
/**
 * 施工例詳細
 *
 * スタイル: assets/@scss/components/_caseDetail.scss → assets/css/cases.css
 * スライダー: assets/js/case-detail.js
 * 読み込み・共通関数: inc/redesign.php
 *
 * 既存の ACF フィールドをそのまま使用:
 *   cases-main-pic / sub01〜06_photo・sub01〜06_text / area_owner / spec / drawing
 */

get_header();

while ( have_posts() ) :
	the_post();

	$case_id = get_the_ID();

	// スライダー: メイン写真 + サブ写真（キャプション付き）
	$slides = array();
	$main   = aloha_field_image_url( 'cases-main-pic' );
	if ( $main ) {
		$slides[] = array(
			'src'     => $main,
			'caption' => '',
		);
	}
	for ( $i = 1; $i <= 6; $i++ ) {
		$src = aloha_field_image_url( sprintf( 'sub%02d_photo', $i ) );
		if ( $src ) {
			$slides[] = array(
				'src'     => $src,
				'caption' => (string) aloha_field( sprintf( 'sub%02d_text', $i ) ),
			);
		}
	}

	$term_groups = aloha_case_terms( $case_id );
	$owner       = (string) aloha_field( 'area_owner' );
	$spec        = aloha_field( 'spec' );
	$drawing     = aloha_field( 'drawing' );
	$archive_url = get_post_type_archive_link( 'cases' );
	?>
<div class="CaseDetail">
	<main class="CaseDetail__wrap">

		<nav class="CaseDetail__breadcrumb" aria-label="パンくずリスト">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">トップ</a>&nbsp;&rang;&nbsp;<a href="<?php echo esc_url( $archive_url ? $archive_url : home_url( '/cases/' ) ); ?>">施工事例一覧</a>&nbsp;&rang;&nbsp;<span aria-current="page"><?php the_title(); ?></span>
		</nav>

		<?php if ( $slides ) : ?>
			<div class="CaseDetail__slider" data-case-slider>
				<?php foreach ( $slides as $index => $slide ) : ?>
					<img class="CaseDetail__slider-slide<?php echo 0 === $index ? ' is-active' : ''; ?>" src="<?php echo esc_url( $slide['src'] ); ?>" alt="<?php echo esc_attr( $slide['caption'] ? $slide['caption'] : get_the_title() . ' 写真' . ( $index + 1 ) ); ?>" data-caption="<?php echo esc_attr( $slide['caption'] ); ?>" loading="<?php echo 0 === $index ? 'eager' : 'lazy'; ?>" decoding="async">
				<?php endforeach; ?>
				<?php if ( count( $slides ) > 1 ) : ?>
					<button type="button" class="CaseDetail__slider-arrow CaseDetail__slider-arrow--prev" aria-label="前の写真">&lsaquo;</button>
					<button type="button" class="CaseDetail__slider-arrow CaseDetail__slider-arrow--next" aria-label="次の写真">&rsaquo;</button>
					<div class="CaseDetail__slider-dots" aria-hidden="true">
						<?php foreach ( $slides as $index => $slide ) : ?>
							<i<?php echo 0 === $index ? ' class="is-active"' : ''; ?>></i>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
			<p class="CaseDetail__caption" aria-live="polite"><?php echo esc_html( $slides[0]['caption'] ); ?></p>
			<?php if ( count( $slides ) > 1 ) : ?>
				<div class="CaseDetail__thumbs">
					<?php foreach ( $slides as $index => $slide ) : ?>
						<button type="button" class="CaseDetail__thumb<?php echo 0 === $index ? ' is-active' : ''; ?>" data-index="<?php echo esc_attr( $index ); ?>" aria-label="<?php echo esc_attr( sprintf( '写真%dを表示', $index + 1 ) ); ?>">
							<img src="<?php echo esc_url( $slide['src'] ); ?>" alt="" loading="lazy" decoding="async">
						</button>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
		<?php endif; ?>

		<div class="CaseDetail__detail">
			<?php if ( $term_groups ) : ?>
				<div class="CaseDetail__tag-groups">
					<?php foreach ( aloha_case_taxonomies() as $taxonomy => $label ) : ?>
						<?php
						if ( empty( $term_groups[ $taxonomy ] ) ) {
							continue;
						}
						?>
						<div class="CaseDetail__tag-group<?php echo 'cases_category' === $taxonomy ? ' CaseDetail__tag-group--style' : ''; ?>">
							<span class="CaseDetail__tag-group-label"><?php echo esc_html( $label ); ?></span>
							<div class="CaseDetail__tag-group-tags">
								<?php foreach ( $term_groups[ $taxonomy ] as $term ) : ?>
									<?php $term_link = get_term_link( $term ); ?>
									<?php if ( is_wp_error( $term_link ) ) : ?>
										<span><?php echo esc_html( $term->name ); ?></span>
									<?php else : ?>
										<a href="<?php echo esc_url( $term_link ); ?>"><?php echo esc_html( $term->name ); ?></a>
									<?php endif; ?>
								<?php endforeach; ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>

			<h1 class="CaseDetail__title"><?php the_title(); ?></h1>
			<?php if ( '' !== trim( $owner ) ) : ?>
				<p class="CaseDetail__owner"><?php echo esc_html( $owner ); ?></p>
			<?php endif; ?>

			<div class="CaseDetail__lead">
				<?php the_content(); ?>
			</div>

			<?php if ( $spec ) : ?>
				<div class="CaseDetail__overview">
					<h2 class="CaseDetail__overview-title">建物概要</h2>
					<div class="CaseDetail__spec">
						<?php echo $spec; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- 既存テンプレートと同じく管理画面入力のHTMLをそのまま出力 ?>
					</div>
				</div>
			<?php endif; ?>

			<?php if ( $drawing ) : ?>
				<div class="CaseDetail__overview CaseDetail__drawing">
					<h2 class="CaseDetail__overview-title">間取り</h2>
					<?php
					if ( is_array( $drawing ) && ! empty( $drawing['url'] ) ) {
						printf( '<img src="%s" alt="%s" loading="lazy" decoding="async">', esc_url( $drawing['url'] ), esc_attr( get_the_title() . 'の間取り図' ) );
					} else {
						echo $drawing; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- 既存テンプレートと同じく管理画面入力のHTMLをそのまま出力
					}
					?>
				</div>
			<?php endif; ?>
		</div>

		<div class="CaseDetail__cta">
			<a href="https://www.ie-miru.jp/cms/yoyaku/seibukensetsu/events/19642" class="CaseDetail__btn" target="_blank" rel="noopener">オンライン相談を予約する &rarr;</a>
			<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="CaseDetail__btn--outline">お問い合わせ</a>
		</div>
	</main>

	<?php
	// 関連事例: 同じスタイル（cases_category）の施工例を優先し、足りなければ新着で補う
	$related_ids = array();
	if ( ! empty( $term_groups['cases_category'] ) ) {
		$related_ids = get_posts(
			array(
				'post_type'      => 'cases',
				'posts_per_page' => 3,
				'post__not_in'   => array( $case_id ),
				'fields'         => 'ids',
				'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
					array(
						'taxonomy' => 'cases_category',
						'field'    => 'term_id',
						'terms'    => wp_list_pluck( $term_groups['cases_category'], 'term_id' ),
					),
				),
			)
		);
	}
	if ( count( $related_ids ) < 3 ) {
		$related_ids = array_merge(
			$related_ids,
			get_posts(
				array(
					'post_type'      => 'cases',
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
						<?php aloha_image_tag( aloha_case_main_pic( $related_id ), get_the_title( $related_id ) . 'の写真', 'CaseDetail__placeholder-photo' ); ?>
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
