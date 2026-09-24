<?php
/**
 * トップページ
 *
 * スタイル: assets/scss/_top.scss（コンパイル済み: assets/css/top.css）
 * 設定・関数: inc/redesign.php
 *
 * 写真は assets/images/top/ に下記ファイル名で配置すると表示される（未配置の場合はグレーのプレースホルダー）。
 */

get_header();

// リンク先。既存サイトの URL に合わせて書き換える。
$aloha_links = array(
	'about'     => home_url( '/about/' ),
	'concept'   => home_url( '/concept/' ),
	'flow'      => home_url( '/flow/' ),
	'plan'      => get_post_type_archive_link( 'plan' ) ? get_post_type_archive_link( 'plan' ) : home_url( '/plan/' ),
	'cases'     => get_post_type_archive_link( ALOHA_CASES_POST_TYPE ) ? get_post_type_archive_link( ALOHA_CASES_POST_TYPE ) : home_url( '/cases/' ),
	'feature'   => home_url( '/feature/' ),
	'news'      => get_permalink( get_option( 'page_for_posts' ) ) ? get_permalink( get_option( 'page_for_posts' ) ) : home_url( '/news/' ),
	'modelhouse' => home_url( '/modelhouse/' ),
	'hawaii'    => get_post_type_archive_link( 'real-estate' ) ? get_post_type_archive_link( 'real-estate' ) : home_url( '/real-estate/' ),
	'reform'    => home_url( '/reform/' ),
	'service'   => home_url( '/service/' ),
	'line'      => home_url( '/line/' ),
	'vr'        => home_url( '/vr/' ),
	'area'      => home_url( '/area/' ),
	'campaign'  => home_url( '/campaign/' ),
);

$aloha_about_cells = array(
	array( 'cap' => 'Exterior', 'jp' => '外観', 'img' => 'about-exterior.jpg', 'url' => $aloha_links['about'] ),
	array( 'cap' => 'Living', 'jp' => 'リビング', 'img' => 'about-living.jpg', 'url' => $aloha_links['about'] ),
	array( 'cap' => 'Wood Deck', 'jp' => 'ウッドデッキ', 'img' => 'about-deck.jpg', 'url' => $aloha_links['about'] ),
	array( 'cap' => 'Light & Wind', 'jp' => '光と風', 'img' => 'about-light.jpg', 'url' => $aloha_links['about'] ),
	array( 'cap' => 'Detail', 'jp' => 'こだわり', 'img' => 'about-detail.jpg', 'url' => $aloha_links['about'] ),
);

$aloha_pillars = array(
	array( 'name' => 'CONCEPT', 'jp' => 'コンセプト', 'img' => 'pillar-concept.jpg', 'url' => $aloha_links['concept'] ),
	array( 'name' => 'FLOW', 'jp' => 'ALOHA&STYLEが出来るまで', 'img' => 'pillar-flow.jpg', 'url' => $aloha_links['flow'] ),
	array( 'name' => 'PLAN', 'jp' => '住宅プラン', 'img' => 'pillar-plan.jpg', 'url' => $aloha_links['plan'] ),
	array( 'name' => 'WORKS', 'jp' => '施工事例', 'img' => 'pillar-works.jpg', 'url' => $aloha_links['cases'] ),
);

$aloha_contents = array(
	array( 'label' => '施工例', 'img' => 'contents-works.jpg', 'url' => $aloha_links['cases'] ),
	array( 'label' => 'モデルハウス Loco Lofa', 'img' => 'contents-modelhouse.jpg', 'url' => $aloha_links['modelhouse'] ),
	array( 'label' => 'ハワイの不動産物件', 'img' => 'contents-hawaii.jpg', 'url' => $aloha_links['hawaii'] ),
	array( 'label' => '西部建設リフォーム', 'img' => 'contents-reform.jpg', 'url' => $aloha_links['reform'] ),
	array( 'label' => '設計施工管理サービス', 'img' => 'contents-service.jpg', 'url' => $aloha_links['service'] ),
	array( 'label' => 'LINEお友達追加', 'img' => 'contents-line.jpg', 'url' => $aloha_links['line'] ),
);

// イベント情報（固定表示。イベント用の投稿タイプがあれば WP_Query に置き換える）
$aloha_events = array(
	array( 'tag' => '開催中', 'text' => 'リラックス感が心地良いリゾートスタイルの住まい 岡山市南区築港新町', 'img' => 'event-1.jpg' ),
	array( 'tag' => 'オンライン相談・セミナー', 'text' => '【vlog形式】ハワイアンプランテーションハウス No.1 ご紹介', 'img' => 'event-2.jpg' ),
	array( 'tag' => '分譲住宅', 'text' => '【予約制】オンラインでも安心してご相談。住宅設計＆購入資金相談会', 'img' => 'event-3.jpg' ),
);

$aloha_works = new WP_Query(
	array(
		'post_type'           => ALOHA_CASES_POST_TYPE,
		'posts_per_page'      => 3,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);

$aloha_news = new WP_Query(
	array(
		'post_type'           => 'post',
		'posts_per_page'      => 5,
		'ignore_sticky_posts' => true,
		'no_found_rows'       => true,
	)
);
?>
<div class="Top">

	<!-- HERO -->
	<section class="Top__hero" aria-label="ヒーロー画像スライドショー">
		<?php for ( $i = 1; $i <= 6; $i++ ) : ?>
			<img class="Top__hero-slide" src="<?php echo esc_url( get_theme_file_uri( sprintf( 'assets/images/top/HeroSlide%02d.jpg', $i ) ) ); ?>" alt="<?php echo esc_attr( sprintf( 'ALOHA & STYLEが手がけたハワイアンスタイル住宅の写真%d', $i ) ); ?>" loading="<?php echo 1 === $i ? 'eager' : 'lazy'; ?>" decoding="async">
		<?php endfor; ?>

		<div class="Top__hero-title">
			<div class="Top__hero-eyebrow">Okayama &mdash; Hawaiian Style House</div>
			<h1 class="Top__hero-heading">ALOHA &amp; STYLE</h1>
			<p class="Top__hero-tagline">岡山で叶える、ハワイアンスタイルの住まい。<br>豊富な提案力と実績で、憧れの暮らしをかたちにします。</p>
			<div class="Top__hero-ctas">
				<a href="<?php echo esc_url( $aloha_links['about'] ); ?>" class="Top__btn">ALOHAを詳しく知る &rarr;</a>
			</div>
		</div>
	</section>

	<!-- ABOUT -->
	<section class="Top__about" aria-labelledby="top-about-heading">
		<div class="Top__about-grid">
			<div class="Top__about-cell Top__about-text">
				<h2 id="top-about-heading" class="Top__about-text-label">About Aloha &amp; Style</h2>
				<p>ALOHA&amp;STYLEは、ウッドデッキや吹き抜けリビングなどハワイアンスタイルを実現するための住宅を、豊富な提案力と実績で自由自在にお造りします。暮らしの拠点でも、上質な非日常のご提案をいたします。</p>
			</div>
			<?php foreach ( $aloha_about_cells as $cell ) : ?>
				<a href="<?php echo esc_url( $cell['url'] ); ?>" class="Top__about-cell">
					<?php aloha_image_or_placeholder( aloha_top_image_url( $cell['img'] ), $cell['jp'] . 'のイメージ写真', 'Top__placeholder-photo' ); ?>
					<span class="Top__about-cell-overlay">
						<span class="Top__about-cell-cap"><?php echo esc_html( $cell['cap'] ); ?></span>
						<span class="Top__about-cell-jp"><?php echo esc_html( $cell['jp'] ); ?></span>
						<span class="Top__about-cell-more">More</span>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
	</section>

	<!-- PILLARS -->
	<section class="Top__pillars" aria-label="主要コンテンツへの導線">
		<div class="Top__pillar-grid">
			<?php foreach ( $aloha_pillars as $index => $pillar ) : ?>
				<a href="<?php echo esc_url( $pillar['url'] ); ?>" class="Top__pillar-tile">
					<?php aloha_image_or_placeholder( aloha_top_image_url( $pillar['img'] ), $pillar['jp'] . 'ページへのリンク画像', 'Top__placeholder-photo' ); ?>
					<span class="Top__pillar-overlay">
						<span class="Top__pillar-num"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span>
						<span class="Top__pillar-name"><?php echo esc_html( $pillar['name'] ); ?></span>
						<span class="Top__pillar-jp"><?php echo esc_html( $pillar['jp'] ); ?></span>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
	</section>

	<!-- LIFESTYLE -->
	<section class="Top__lifestyle" aria-labelledby="top-lifestyle-heading">
		<div class="Top__lifestyle-inner">
			<div class="Top__lifestyle-photo">
				<?php aloha_image_or_placeholder( aloha_top_image_url( 'lifestyle.jpg' ), 'ALOHA&STYLEが手がけた住宅でくつろぐ家族の写真', 'Top__placeholder-photo' ); ?>
			</div>
			<div class="Top__lifestyle-text">
				<h2 id="top-lifestyle-heading">暮らしから、<br>家を考える。</h2>
				<p>
					家族の成長も、日々の気分も、時間とともに変わっていく。<br>
					だからこそALOHA&amp;STYLEは、間取りありきではなく<br>
					「どう暮らしたいか」から住まいを考えます。
				</p>
				<a href="<?php echo esc_url( $aloha_links['feature'] ); ?>" class="Top__btn--outline">ALOHA&amp;STYLEの特徴を見る</a>
			</div>
		</div>
	</section>

	<!-- WORKS -->
	<section class="Top__works" aria-labelledby="top-works-heading">
		<div class="Top__works-head">
			<h2 id="top-works-heading" class="Top__works-head-en">Works</h2>
			<span class="Top__works-head-jp">施工事例</span>
		</div>
		<?php if ( $aloha_works->have_posts() ) : ?>
			<div class="Top__works-grid">
				<?php
				while ( $aloha_works->have_posts() ) :
					$aloha_works->the_post();
					$tags = aloha_case_hashtags();
					?>
					<a href="<?php the_permalink(); ?>" class="Top__work-card">
						<?php
						$work_img = aloha_post_image_url();
						if ( $work_img ) {
							printf( '<img src="%s" alt="%s" loading="lazy" decoding="async">', esc_url( $work_img ), esc_attr( get_the_title() . 'の外観・内観写真' ) );
						} else {
							echo '<span class="Top__placeholder-photo"></span>';
						}
						?>
						<h3><?php the_title(); ?></h3>
						<span class="Top__work-card-more">More</span>
						<?php if ( $tags ) : ?>
							<div class="Top__work-card-tags"><span>&mdash;</span> <?php echo esc_html( $tags ); ?></div>
						<?php endif; ?>
					</a>
				<?php endwhile; ?>
			</div>
			<?php wp_reset_postdata(); ?>
		<?php endif; ?>
		<div class="Top__works-more"><a href="<?php echo esc_url( $aloha_links['cases'] ); ?>">施工事例の一覧を見る</a></div>
	</section>

	<!-- NEWS & TOPICS -->
	<section class="Top__news" aria-labelledby="top-news-heading">
		<div class="Top__section-head">
			<h2 id="top-news-heading" class="Top__section-head-en">News &amp; Topics</h2>
			<span class="Top__section-head-jp">新着情報</span>
		</div>
		<?php if ( $aloha_news->have_posts() ) : ?>
			<div class="Top__news-list">
				<?php
				while ( $aloha_news->have_posts() ) :
					$aloha_news->the_post();
					?>
					<a href="<?php the_permalink(); ?>" class="Top__news-item">
						<?php
						$news_img = aloha_post_image_url( null, 'medium_large' );
						if ( $news_img ) {
							printf( '<img src="%s" alt="%s" loading="lazy" decoding="async">', esc_url( $news_img ), esc_attr( get_the_title() ) );
						} else {
							echo '<span class="Top__placeholder-photo"></span>';
						}
						?>
						<span class="Top__news-date"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></span>
						<span class="Top__news-title"><?php the_title(); ?></span>
					</a>
				<?php endwhile; ?>
			</div>
			<?php wp_reset_postdata(); ?>
		<?php endif; ?>
		<div class="Top__news-more"><a href="<?php echo esc_url( $aloha_links['news'] ); ?>" class="Top__btn--outline">すべて見る</a></div>
	</section>

	<!-- CONTENTS -->
	<section class="Top__contents" aria-labelledby="top-contents-heading">
		<div class="Top__section-head">
			<h2 id="top-contents-heading" class="Top__section-head-en">Contents</h2>
		</div>
		<div class="Top__contents-grid">
			<?php foreach ( $aloha_contents as $content ) : ?>
				<a href="<?php echo esc_url( $content['url'] ); ?>" class="Top__content-tile">
					<?php aloha_image_or_placeholder( aloha_top_image_url( $content['img'] ), $content['label'], 'Top__placeholder-photo' ); ?>
					<span class="Top__content-tile-label"><span><?php echo esc_html( $content['label'] ); ?></span></span>
				</a>
			<?php endforeach; ?>
		</div>
	</section>

	<!-- EVENTS -->
	<section class="Top__events" aria-labelledby="top-events-heading">
		<div class="Top__section-head">
			<h2 id="top-events-heading" class="Top__section-head-en">Event Information</h2>
			<span class="Top__section-head-jp">イベント情報</span>
		</div>
		<div class="Top__event-grid">
			<?php foreach ( $aloha_events as $event ) : ?>
				<div class="Top__event-card">
					<?php aloha_image_or_placeholder( aloha_top_image_url( $event['img'] ), '', 'Top__placeholder-photo' ); ?>
					<span class="Top__event-tag"><?php echo esc_html( $event['tag'] ); ?></span>
					<p><?php echo esc_html( $event['text'] ); ?></p>
				</div>
			<?php endforeach; ?>
		</div>
	</section>

	<!-- REFORM BLOG（既存の js-news-generate による自動生成に対応。JSが無い場合は下記が表示される） -->
	<section class="Top__reform js-news-generate" aria-labelledby="top-reform-heading">
		<div class="Top__section-head">
			<h2 id="top-reform-heading" class="Top__section-head-en">Reform Blog</h2>
			<span class="Top__section-head-jp">リフォームブログ</span>
		</div>
		<ul class="Top__reform-list js-news-generate-target">
			<li class="Top__reform-item"><span class="Top__reform-date">2025.9.10</span><span class="Top__reform-title">エコキュートへの交換で実現する省エネ生活と補助金活用法は？</span></li>
			<li class="Top__reform-item"><span class="Top__reform-date">2025.4.25</span><span class="Top__reform-title">知らなかったでは済まされない！マンションのリフォーム・リノベーションで気をつけたいポイントとは？</span></li>
			<li class="Top__reform-item"><span class="Top__reform-date">2025.4.24</span><span class="Top__reform-title">戸建て住宅を長持ちさせるために｜メンテナンスの必要性と適切な計画・予算づくりのポイントは？</span></li>
			<li class="Top__reform-item"><span class="Top__reform-date">2025.4.23</span><span class="Top__reform-title">お得に交換！2025年度「給湯省エネ事業」で賢く給湯器リフォーム</span></li>
			<li class="Top__reform-item"><span class="Top__reform-date">2025.4.22</span><span class="Top__reform-title">今がチャンス！「2025年度 先進的窓リノベ事業」でおトクに窓リフォームをしよう</span></li>
		</ul>
	</section>

	<!-- BANNERS -->
	<section class="Top__banners" aria-label="お知らせバナー">
		<div class="Top__banner-grid">
			<a href="<?php echo esc_url( $aloha_links['vr'] ); ?>" class="Top__banner-tile">
				<?php aloha_image_or_placeholder( aloha_top_image_url( 'banner-vr.jpg' ), 'VR展示場で憧れのハワイアンライフを体感', 'Top__placeholder-photo' ); ?>
				<span class="Top__banner-tag">VR展示場</span>
				<span class="Top__banner-ttl">VRで、憧れのハワイアンライフを体感</span>
			</a>
			<a href="<?php echo esc_url( $aloha_links['area'] ); ?>" class="Top__banner-tile">
				<?php aloha_image_or_placeholder( aloha_top_image_url( 'banner-area.jpg' ), '施工エリア以外のお客様へ', 'Top__placeholder-photo' ); ?>
				<span class="Top__banner-tag Top__banner-tag--dark">近隣エリア</span>
				<span class="Top__banner-ttl Top__banner-ttl--dark">施工エリア以外のお客様へ</span>
			</a>
		</div>
		<div class="Top__campaign"><a href="<?php echo esc_url( $aloha_links['campaign'] ); ?>" class="Top__btn--outline">住宅省エネ2024キャンペーン</a></div>
	</section>

	<!-- SNS -->
	<section class="Top__sns" aria-labelledby="top-sns-heading">
		<div class="Top__section-head">
			<h2 id="top-sns-heading" class="Top__section-head-en">Facebook / Instagram</h2>
		</div>
		<div class="Top__sns-grid">
			<?php for ( $i = 1; $i <= 3; $i++ ) : ?>
				<?php $sns_url = aloha_top_image_url( 'sns-' . $i . '.jpg' ); ?>
				<?php if ( $sns_url ) : ?>
					<img src="<?php echo esc_url( $sns_url ); ?>" alt="" loading="lazy" decoding="async">
				<?php else : ?>
					<span class="Top__placeholder-photo"></span>
				<?php endif; ?>
			<?php endfor; ?>
		</div>
	</section>

</div><!-- /.Top -->
<?php
get_footer();
