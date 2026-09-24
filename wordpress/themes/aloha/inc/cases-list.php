<?php
/**
 * 施工例一覧の共通出力。
 *
 * archive-cases.php / taxonomy-cases_category.php / taxonomy-feature.php / taxonomy-life_style.php から呼び出す。
 * 取得条件は既存テンプレートと同じ（施工例 16件ずつ、ページ送りは wp_pagenavi。無ければ WordPress 標準のページ送り）。
 * スタイル: assets/@scss/components/_caseList.scss → assets/css/cases.css
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 施工例一覧を出力する。
 *
 * @param WP_Term|null $term 絞り込むターム。null なら全件（/cases/）。
 */
function aloha_render_cases_list( $term = null ) {
	$paged = max( 1, (int) get_query_var( 'paged' ) );
	$args  = array(
		'post_type'      => 'cases',
		'posts_per_page' => 16,
		'paged'          => $paged,
	);
	if ( $term instanceof WP_Term ) {
		$args['tax_query'] = array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
			array(
				'taxonomy' => $term->taxonomy,
				'field'    => 'slug',
				'terms'    => $term->slug,
			),
		);
	}
	$query = new WP_Query( $args );

	$archive_url = get_post_type_archive_link( 'cases' );
	$archive_url = $archive_url ? $archive_url : home_url( '/cases/' );
	$taxonomies  = aloha_case_taxonomies();
	$tax_label   = $term instanceof WP_Term && isset( $taxonomies[ $term->taxonomy ] ) ? $taxonomies[ $term->taxonomy ] : '';
	?>
<div class="CaseList">
	<main class="CaseList__wrap">

		<nav class="CaseList__breadcrumb" aria-label="パンくずリスト">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">トップ</a>&nbsp;&rang;&nbsp;
			<?php if ( $term instanceof WP_Term ) : ?>
				<a href="<?php echo esc_url( $archive_url ); ?>">施工事例一覧</a>&nbsp;&rang;&nbsp;<span aria-current="page"><?php echo esc_html( $term->name ); ?></span>
			<?php else : ?>
				<span aria-current="page">施工事例一覧</span>
			<?php endif; ?>
		</nav>

		<header class="CaseList__head">
			<?php if ( $term instanceof WP_Term ) : ?>
				<h1 class="CaseList__head-en"><?php echo esc_html( $term->name ); ?></h1>
				<span class="CaseList__head-jp">施工事例<?php echo $tax_label ? esc_html( ' ／ ' . $tax_label ) : ''; ?></span>
			<?php else : ?>
				<h1 class="CaseList__head-en">Works</h1>
				<span class="CaseList__head-jp">施工事例一覧</span>
			<?php endif; ?>
		</header>
		<?php if ( $term instanceof WP_Term && '' !== trim( $term->description ) ) : ?>
			<p class="CaseList__lead"><?php echo esc_html( $term->description ); ?></p>
		<?php elseif ( ! $term instanceof WP_Term ) : ?>
			<p class="CaseList__lead">ALOHA&amp;STYLEが手がけた住まい・店舗の施工事例です。スタイルや特徴、ライフスタイルから、理想に近い事例を探せます。</p>
		<?php endif; ?>

		<?php
		// 既存テンプレートと同じく、一覧トップのみメイン画像を表示（2ページ目以降は別画像）
		if ( ! $term instanceof WP_Term ) {
			$key_visual = aloha_theme_image( 1 === $paged ? 'cases_main2.jpg' : 'cases_main3.jpg' );
			if ( $key_visual ) {
				printf( '<div class="CaseList__key-visual"><img src="%s" alt="%s" decoding="async"></div>', esc_url( $key_visual ), esc_attr( 'ALOHA&STYLEの施工事例' ) );
			}
		}
		?>

		<div class="CaseList__filter" role="navigation" aria-label="施工事例の絞り込み">
			<?php foreach ( $taxonomies as $taxonomy => $label ) : ?>
				<?php
				if ( ! taxonomy_exists( $taxonomy ) ) {
					continue;
				}
				$terms = get_terms( array( 'taxonomy' => $taxonomy ) );
				if ( ! $terms || is_wp_error( $terms ) ) {
					continue;
				}
				if ( 'cases_category' === $taxonomy ) {
					$terms = aloha_sort_case_styles( $terms );
				}
				?>
				<div class="CaseList__filter-group">
					<span class="CaseList__filter-label"><?php echo esc_html( $label ); ?></span>
					<div class="CaseList__filter-tags">
						<?php if ( 'cases_category' === $taxonomy ) : ?>
							<a href="<?php echo esc_url( $archive_url ); ?>"<?php echo $term instanceof WP_Term ? '' : ' class="is-current" aria-current="page"'; ?>>すべて</a>
						<?php endif; ?>
						<?php foreach ( $terms as $filter_term ) : ?>
							<?php
							$is_current = $term instanceof WP_Term && (int) $term->term_id === (int) $filter_term->term_id && $term->taxonomy === $filter_term->taxonomy;
							$link       = get_term_link( $filter_term );
							if ( is_wp_error( $link ) ) {
								continue;
							}
							?>
							<a href="<?php echo esc_url( $link ); ?>"<?php echo $is_current ? ' class="is-current" aria-current="page"' : ''; ?>><?php echo esc_html( $filter_term->name ); ?></a>
						<?php endforeach; ?>
					</div>
				</div>
			<?php endforeach; ?>
		</div>

		<?php if ( $query->have_posts() ) : ?>
			<p class="CaseList__count">全<strong><?php echo esc_html( number_format_i18n( $query->found_posts ) ); ?></strong>件</p>
			<ul class="CaseList__grid">
				<?php
				while ( $query->have_posts() ) :
					$query->the_post();
					$styles = get_the_terms( get_the_ID(), 'cases_category' );
					$styles = $styles && ! is_wp_error( $styles ) ? aloha_sort_case_styles( $styles ) : array();
					?>
					<li>
						<a href="<?php the_permalink(); ?>" class="CaseList__card">
							<span class="CaseList__card-pic">
								<?php aloha_image_tag( aloha_case_main_pic(), get_the_title() . 'の写真', 'CaseDetail__placeholder-photo' ); ?>
							</span>
							<?php if ( $styles ) : ?>
								<span class="CaseList__card-tags">
									<?php foreach ( $styles as $style ) : ?>
										<span><?php echo esc_html( $style->name ); ?></span>
									<?php endforeach; ?>
								</span>
							<?php endif; ?>
							<span class="CaseList__card-title"><?php the_title(); ?></span>
							<span class="CaseList__card-more">More</span>
						</a>
					</li>
				<?php endwhile; ?>
			</ul>

			<?php if ( $query->max_num_pages > 1 ) : ?>
				<nav class="CaseList__pager" aria-label="ページ送り">
					<?php
					if ( function_exists( 'wp_pagenavi' ) ) {
						wp_pagenavi( array( 'query' => $query ) );
					} else {
						echo '<div class="nav-links">';
						echo paginate_links( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
							array(
								'total'     => $query->max_num_pages,
								'current'   => $paged,
								'prev_text' => '&lsaquo;',
								'next_text' => '&rsaquo;',
							)
						);
						echo '</div>';
					}
					?>
				</nav>
			<?php endif; ?>
		<?php else : ?>
			<p class="CaseList__empty">該当する施工事例はまだありません。<br><a href="<?php echo esc_url( $archive_url ); ?>">すべての施工事例を見る</a></p>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>

		<section class="CaseList__cta" aria-labelledby="case-list-cta-heading">
			<div>
				<h2 id="case-list-cta-heading" class="CaseList__cta-title">Contact</h2>
				<p class="CaseList__cta-text">気になる事例があれば、お気軽にご相談ください。</p>
			</div>
			<div class="CaseList__cta-actions">
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="CaseList__btn">お問い合わせ &rarr;</a>
				<a href="https://www.ie-miru.jp/cms/yoyaku/seibukensetsu/events/19642" class="CaseList__btn--outline" target="_blank" rel="noopener">オンライン相談を予約</a>
			</div>
		</section>
	</main>
</div>
	<?php
}
