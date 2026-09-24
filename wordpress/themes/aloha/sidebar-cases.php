<?php
/**
 * 施工例サイドバー：スタイルごとに最新の施工例1件を表示する。
 * スタイルの並び・説明文は inc/redesign.php の aloha_case_styles()。
 */
?>
<div class="Column__side Side">
	<div class="Side__block">
		<div class="TitleContent__wrap Side__TitleContent__wrap">
			<div class="TitleContent Side__TitleContent">
				<p class="TitleContent__text Side__TitleContent__text">施工例 ［Construction Cases］</p>
			</div>
		</div>
		<?php foreach ( aloha_case_styles() as $aloha_slug => $aloha_style ) : ?>
			<?php
			$aloha_term = get_term_by( 'slug', $aloha_slug, 'cases_category' );
			if ( ! $aloha_term || ! $aloha_term->count ) {
				continue;
			}
			$aloha_link  = get_term_link( $aloha_term );
			$aloha_query = new WP_Query(
				array(
					'post_type'      => 'cases',
					'posts_per_page' => 1,
					'no_found_rows'  => true,
					'tax_query'      => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
						array(
							'taxonomy' => 'cases_category',
							'field'    => 'term_id',
							'terms'    => $aloha_term->term_id,
						),
					),
				)
			);
			if ( is_wp_error( $aloha_link ) || ! $aloha_query->have_posts() ) {
				continue;
			}
			$aloha_query->the_post();
			?>
			<ul class="Side__list">
				<li class="Column__item">
					<div class="Column__item-pic">
						<a href="<?php echo esc_url( $aloha_link ); ?>">
							<img src="<?php echo esc_url( aloha_case_main_pic() ); ?>" alt="">
							<div class="Column__item-name-wrap Side__item-name-wrap">
								<p class="Column__item-name Side__item-name"><?php echo esc_html( $aloha_style['en'] ); ?></p>
							</div>
						</a>
					</div>
					<div class="Column__item-category-desc"><?php echo esc_html( $aloha_style['desc'] ); ?></div>
					<div class="Column__item-btn">
						<a href="<?php echo esc_url( $aloha_link ); ?>">READ MORE</a>
					</div>
				</li>
			</ul>
			<?php wp_reset_postdata(); ?>
		<?php endforeach; ?>
	</div>
</div>
