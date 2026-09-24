<?php
/*
Template Name: 住宅プラン（詳細ページ）
*/
?>
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
$product_terms = wp_get_object_terms($post->ID, 'plan_category');
if(!empty($product_terms)){
  if(!is_wp_error( $product_terms )){
    foreach($product_terms as $term){
    }
  }
};
			$termName = ( ! empty( $product_terms ) && ! is_wp_error( $product_terms ) ) ? $product_terms[0]->slug : '';
?>
<?php
// 見出しは inc/redesign.php の aloha_plan_category_meta() から（住宅プランのカテゴリ以外は従来どおり NEW PRODUCT DESIGN）
$plan_meta = aloha_plan_category_meta( $termName );
?>
<?php if ( $plan_meta ) : ?>
			<div class="Title__wrap">
				<div class="Title Title01 Title01__under">
					<h1 class="Title__text Title__text-eng"><?php echo esc_html( $plan_meta ? $plan_meta['en'] : '' ); ?></h1>
					<div class="Title__text-jap-wrap">
						<h2 class="Title__text Title__text-jap"><?php echo esc_html( $plan_meta ? $plan_meta['ja'] : '' ); ?></h2>
					</div>
				</div>
			</div>
<?php endif; ?>
         <div class="category-search pc">
					<div class="div">
						<p class="search-title">住宅プラン</p>
							<ul>
							<?php
$terms = get_terms( array( 'taxonomy' => 'plan_category', 'hide_empty' => false ) );
foreach ( $terms as $term ) {
echo '<li><a href="' .get_term_link($term->slug, 'plan_category').' ">' .esc_html($term->name). '</a></li>'; // タームタイトル
}
?>							</ul>
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
<dd><div class="category-search">
					<div class="div">
						<p class="search-title">住宅プラン</p>
							<ul>
							<?php
$terms = get_terms( array( 'taxonomy' => 'plan_category', 'hide_empty' => false ) );
foreach ( $terms as $term ) {
echo '<li><a href="' .get_term_link($term->slug, 'plan_category').' ">' .esc_html($term->name). '</a></li>'; // タームタイトル
}
?>							</ul>
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
						</div></dd>
</dl>
			<?php if (have_posts()): ?>
			<?php while (have_posts()) : the_post(); ?>

			<div class="Content__main-pic">
				<?php the_post_thumbnail(); ?>
				<div class="Content__main-text-wrap Plan__main-text-wrap">
					<p class="Plan__main-lead">NEW PRODUCT DESIGN</p>
				</div>
			</div>
			<div class="Plan__body">
				<div class="Column">
					<div class="Column__main">
						<div class="slick slick__single">
							<div class="slick-slider__single">
								<?php if( get_field('slide01') ): ?>
								<div class="slick-item slick-item__single"><?php if( get_field('slide01') ): ?><img src="<?php the_field('slide01'); ?>" /><?php endif; ?></div>
								<?php else: ?>
								<?php endif; ?>
								<?php if( get_field('slide02') ): ?>
								<div class="slick-item slick-item__single"><?php if( get_field('slide02') ): ?><img src="<?php the_field('slide02'); ?>" /><?php endif; ?></div>
								<?php else: ?>
								<?php endif; ?>
								<?php if( get_field('slide03') ): ?>
								<div class="slick-item slick-item__single"><?php if( get_field('slide03') ): ?><img src="<?php the_field('slide03'); ?>" /><?php endif; ?></div>
								<?php else: ?>
								<?php endif; ?>
								<?php if( get_field('slide04') ): ?>
								<div class="slick-item slick-item__single"><?php if( get_field('slide04') ): ?><img src="<?php the_field('slide04'); ?>" /><?php endif; ?></div>
								<?php else: ?>
								<?php endif; ?>
								<?php if( get_field('slide05') ): ?>
								<div class="slick-item slick-item__single"><?php if( get_field('slide05') ): ?><img src="<?php the_field('slide05'); ?>" /><?php endif; ?></div>
								<?php else: ?>
								<?php endif; ?>
								<?php if( get_field('slide06') ): ?>
								<div class="slick-item slick-item__single"><?php if( get_field('slide06') ): ?><img src="<?php the_field('slide06'); ?>" /><?php endif; ?></div>
								<?php else: ?>
								<?php endif; ?>
							</div>
						</div>
						<h1><?php the_title(); ?><h1>
								<p class="PlanDetail__title-jap">概要・コンセプト</p>
								<p class="PlanDetail__comment"><?php echo post_custom('concept'); ?></p>
								<?php if( get_field('floor-image1') ): ?>
								<div class="PlanDetail__pic">
									<img src="<?php the_field('floor-image1'); ?>" />
								</div>
																<?php endif; ?>
																<?php if( get_field('floor-image2') ): ?>
																	<div class="PlanDetail__pic">
																	<img src="<?php the_field('floor-image2'); ?>" />
							</div>
								<?php endif; ?>

								<table class="PlanDetail__spec-list">
									<tbody class="PlanDetail__spec-list-inner">
										<?php if( get_field('1st_area') ): ?>
										<tr class="PlanDetail__spec-item">
											<th class="PlanDetail__spec-item-head">1階床面積</th>
											<td class="PlanDetail__spec-item-body"><?php echo post_custom('1st_area'); ?></td>
										</tr>
										<?php else: ?>
										<?php endif; ?>
										<?php if( get_field('2nd_area') ): ?>
										<tr class="PlanDetail__spec-item">
											<th class="PlanDetail__spec-item-head">2階床面積</th>
											<td class="PlanDetail__spec-item-body"><?php echo post_custom('2nd_area'); ?></td>
										</tr>
										<?php else: ?>
										<?php endif; ?>
										<?php if( get_field('3nd_area') ): ?>
										<tr class="PlanDetail__spec-item">
											<th class="PlanDetail__spec-item-head">3階床面積</th>
											<td class="PlanDetail__spec-item-body"><?php echo post_custom('3nd_area'); ?></td>
										</tr>
										<?php else: ?>
										<?php endif; ?>
										<?php if( get_field('garage_area') ): ?>
										<tr class="PlanDetail__spec-item">
											<th class="PlanDetail__spec-item-head">ガレージ面積</th>
											<td class="PlanDetail__spec-item-body"><?php echo post_custom('garage_area'); ?></td>
										</tr>
										<?php else: ?>
										<?php endif; ?>
										<?php if( get_field('total_area') ): ?>
										<tr class="PlanDetail__spec-item">
											<th class="PlanDetail__spec-item-head">延べ床面積</th>
											<td class="PlanDetail__spec-item-body"><?php echo post_custom('total_area'); ?></td>
										</tr>
										<?php else: ?>
										<?php endif; ?>
										<?php if( get_field('atrium_area') ): ?>
										<tr class="PlanDetail__spec-item">
											<th class="PlanDetail__spec-item-head">吹き抜け面積</th>
											<td class="PlanDetail__spec-item-body"><?php echo post_custom('atrium_area'); ?></td>
										</tr>
										<?php else: ?>
										<?php endif; ?>
										<?php if( get_field('terrace_area') ): ?>
										<tr class="PlanDetail__spec-item">
											<th class="PlanDetail__spec-item-head">テラス面積</th>
											<td class="PlanDetail__spec-item-body"><?php echo post_custom('terrace_area'); ?></td>
										</tr>
										<?php else: ?>
										<?php endif; ?>
										<?php if( get_field('other_area') ): ?>
										<tr class="PlanDetail__spec-item">
											<th class="PlanDetail__spec-item-head">その他床面積</th>
											<td class="PlanDetail__spec-item-body"><?php echo post_custom('other_area'); ?></td>
										</tr>
										<?php else: ?>
										<?php endif; ?>
										<?php if( get_field('construction_area') ): ?>
										<tr class="PlanDetail__spec-item">
											<th class="PlanDetail__spec-item-head">施工床面積</th>
											<td class="PlanDetail__spec-item-body"><?php echo post_custom('construction_area'); ?></td>
										</tr>
										<?php else: ?>
										<?php endif; ?>
										
									</tbody>
								</table>

								<?php endwhile; ?>
								<?php wp_reset_query(); ?>
								<?php else: ?>
								<!-- 投稿が無い場合の処理 -->
								<?php endif; ?>
								<!-- ハワイアンハウス -->

								<ul class="Column__list PlanDetail__Column__list">
									<?php
									$id = get_the_ID();
  $args = array(
    'post_type' => 'plan',
	'order'   => 'ASC',
	'post__not_in' => array($id),
    'tax_query' => array(
      array(
        'taxonomy' => 'plan_category', // タクソノミースラッグを指定
        'field' => 'slug',
        'terms' => $termName, // タームスラッグを指定
      )
    )
  );
  $the_query = new WP_Query($args); if($the_query->have_posts()):
?>
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
									<!-- 投稿が無い場合の処理 -->
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
