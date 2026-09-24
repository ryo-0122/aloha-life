<?php get_header(); ?>
<main>
	<div class="HeroImage HeroImage__under" id="heroImage">
		<div class="HeroImage__inner">
		</div>
		<div class="HeroImage__inner-wrap"></div>
		<?php get_template_part( 'content', 'breadcrumb' ); ?>
	</div>
	<!-- 下層ページ -->
	<div class="Content__inner">
		<section class="Plan">
			<?php
$queried_object = get_queried_object();
$termName = $queried_object->slug;
$termja = $queried_object->name;
?>
<?php
// 見出しは inc/redesign.php の aloha_plan_category_meta() から（住宅プランのカテゴリ以外は従来どおり NEW PRODUCT DESIGN）
$plan_meta = aloha_plan_category_meta( $termName );
?>
			<div class="Title__wrap">
				<div class="Title Title01 Title01__under">
					<h1 class="Title__text Title__text-eng"><?php echo esc_html( $plan_meta ? $plan_meta['en'] : 'NEW PRODUCT DESIGN' ); ?></h1>
					<div class="Title__text-jap-wrap">
						<h2 class="Title__text Title__text-jap"><?php echo esc_html( $plan_meta ? $plan_meta['ja'] : $termja ); ?></h2>
					</div>
				</div>
			</div>
			<div class="category-search pc">
				<div class="div">
					<p class="search-title">住宅プラン</p>
					<ul>
						<?php
$terms = get_terms( array( 'taxonomy' => 'plan_category', 'hide_empty' => false ) );
foreach ( $terms as $term ) {
echo '<li><a href="' .get_term_link($term->slug, 'plan_category').' ">' .esc_html($term->name). '</a></li>'; // タームタイトル
}
?> </ul>
				</div>
				<div class="div">
					<p class="search-title">住宅の大きさ</p>
					<ul>
						<?php
$terms = get_terms('plan_size');
foreach ( $terms as $term ) {
echo '<li><a href="' .get_term_link($term->slug, 'plan_size').' ">' .esc_html($term->name). '</a></li>'; // タームタイトル
}
?>
					</ul>
				</div>
				<div class="div">
					<p class="search-title">階数</p>
					<ul>
						<?php
$terms = get_terms('plan_numberoffloors');
foreach ( $terms as $term ) {
echo '<li><a href="' .get_term_link($term->slug, 'plan_numberoffloors').' ">' .esc_html($term->name). '</a></li>'; // タームタイトル
}
?>
					</ul>
				</div>
				<div class="div">
					<p class="search-title">間取り</p>
					<ul>
						<?php
$terms = get_terms('plan_floor_plan');
foreach ( $terms as $term ) {
echo '<li><a href="' .get_term_link($term->slug, 'plan_floor_plan').' ">' .esc_html($term->name). '</a></li>'; // タームタイトル
}
?>
					</ul>
				</div>
				<div class="div">
					<p class="search-title">玄関方位</p>
					<ul>
						<?php
$terms = get_terms('plan_direction');
foreach ( $terms as $term ) {
echo '<li><a href="' .get_term_link($term->slug, 'plan_direction').' ">' .esc_html($term->name). '</a></li>'; // タームタイトル
}
?>
					</ul>
				</div>
				<div class="div">
					<p class="search-title">間口</p>
					<ul>
						<?php
$terms = get_terms('plan_frontage');
foreach ( $terms as $term ) {
echo '<li><a href="' .get_term_link($term->slug, 'plan_frontage').' ">' .esc_html($term->name). '</a></li>'; // タームタイトル
}
?>

					</ul>
				</div>
			</div>
			<dl id="acMenu" class="sp">
				<dt>お気に入りのプランで絞り込む</dt>
				<dd>
					<div class="category-search">
						<div class="div">
							<p class="search-title">住宅プラン</p>
							<ul>
								<?php
$terms = get_terms( array( 'taxonomy' => 'plan_category', 'hide_empty' => false ) );
foreach ( $terms as $term ) {
echo '<li><a href="' .get_term_link($term->slug, 'plan_category').' ">' .esc_html($term->name). '</a></li>'; // タームタイトル
}
?> </ul>
						</div>
						<div class="div">
							<p class="search-title">住宅の大きさ</p>
							<ul>
								<?php
$terms = get_terms('plan_size');
foreach ( $terms as $term ) {
echo '<li><a href="' .get_term_link($term->slug, 'plan_size').' ">' .esc_html($term->name). '</a></li>'; // タームタイトル
}
?>
							</ul>
						</div>
						<div class="div">
							<p class="search-title">階数</p>
							<ul>
								<?php
$terms = get_terms('plan_numberoffloors');
foreach ( $terms as $term ) {
echo '<li><a href="' .get_term_link($term->slug, 'plan_numberoffloors').' ">' .esc_html($term->name). '</a></li>'; // タームタイトル
}
?>
							</ul>
						</div>
						<div class="div">
							<p class="search-title">間取り</p>
							<ul>
								<?php
$terms = get_terms('plan_floor_plan');
foreach ( $terms as $term ) {
echo '<li><a href="' .get_term_link($term->slug, 'plan_floor_plan').' ">' .esc_html($term->name). '</a></li>'; // タームタイトル
}
?>
							</ul>
						</div>
						<div class="div">
							<p class="search-title">玄関方位</p>
							<ul>
								<?php
$terms = get_terms('plan_direction');
foreach ( $terms as $term ) {
echo '<li><a href="' .get_term_link($term->slug, 'plan_direction').' ">' .esc_html($term->name). '</a></li>'; // タームタイトル
}
?>
							</ul>
						</div>
						<div class="div">
							<p class="search-title">間口</p>
							<ul>
								<?php
$terms = get_terms('plan_frontage');
foreach ( $terms as $term ) {
echo '<li><a href="' .get_term_link($term->slug, 'plan_frontage').' ">' .esc_html($term->name). '</a></li>'; // タームタイトル
}
?>

							</ul>
						</div>
					</div>
				</dd>
			</dl>




			<div class="Content__main-pic">
				<img src="<?php echo get_template_directory_uri(); ?>/assets/images/Plan_main.jpg"
					srcset="<?php echo get_template_directory_uri(); ?>/assets/images/Plan_main.jpg 1x, <?php echo get_template_directory_uri(); ?>/assets/images/Plan_main@2x.jpg 2x"
					alt="">
				<div class="Content__main-text-wrap Plan__main-text-wrap">
					<p class="Plan__main-lead">NEW PRODUCT DESIGN</p>
				</div>
			</div>
			<div class="Plan__body">
				<div class="Column">
					<div class="Column__main">



						<!-- ここにボタン -->




						<ul class="Column__list">
							<?php
$queried_object = get_queried_object();
$termName = $queried_object->slug;
$termcategory = $queried_object->taxonomy;
$args = array(
'post_type' => 'plan',
'posts_per_page' => -1,
'order'   => 'ASC',
'tax_query' => array(
array(
'taxonomy' => $termcategory, // タクソノミースラッグを指定
'field' => 'slug',
'terms' => $termName, // タームスラッグを指定
)
)
);
$the_query = new WP_Query($args); if($the_query->have_posts()): ?>
							<?php while ($the_query->have_posts()): $the_query->the_post(); ?>
							<li class="Column__item">
								<div class="Column__item-pic">
									<a href="<?php the_permalink(); ?>">
										<?php the_post_thumbnail(); ?>
										<div class="Column__item-name-wrap">
											<p class="Column__item-name"><?php the_title(); ?></p>
										</div>
									</a>
								</div>
								<div class="Column__item-btn">
									<a href="<?php the_permalink(); ?>">READ MORE</a>
								</div>
							</li>
							<?php endwhile; ?>
							<?php wp_reset_postdata(); ?>
							<?php else: ?>
							<!-- 投稿が無い場合：準備中の表示 -->
							<li class="Plan__coming-soon">
								<p class="Plan__coming-soon-title">現在準備中です</p>
								<p class="Plan__coming-soon-text">このスタイルのプランは近日公開予定です。先行してのご相談も承っています。</p>
								<a class="Plan__coming-soon-btn" href="<?php echo esc_url( home_url( '/contact/' ) ); ?>">お問い合わせ &rarr;</a>
							</li>
							<?php endif; ?>
						</ul>
					</div>
					<?php get_sidebar("plan"); ?>
				</div>
			</div>
		</section>
	</div>
</main>
<?php get_footer(); ?>