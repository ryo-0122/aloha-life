<?php
/**
 * トップページ
 *
 * スタイル: assets/@scss/components/_home.scss → assets/css/home.css
 * 読み込み・共通関数: inc/redesign.php
 *
 * 既存のまま維持しているもの:
 * - NEWS & TOPICS の取得条件（post / work / realestate の最新6件）
 * - 施工例の写真（ACF cases-main-pic）とタクソノミー
 * - イベント情報（ie-miru の外部ウィジェット）
 * - リフォームブログ（footer.php の reformBlogData() が .js-news-generate-target に一覧を追加）
 * - Facebook ページプラグイン（SDK は footer.php で読み込み）
 */

get_header();

// ヒーロー写真。6枚そろっていればフェードで切り替え、足りなければ1枚目を固定表示。
$hero_slides = array();
for ( $i = 1; $i <= 6; $i++ ) {
	$url = aloha_theme_image( sprintf( 'top/HeroSlide%02d.jpg', $i ) );
	if ( $url ) {
		$hero_slides[] = $url;
	}
}
if ( ! $hero_slides ) {
	$fallback = aloha_theme_image( array( 'Aloha_main.jpg', 'HeroImage_bg.jpg' ) );
	if ( $fallback ) {
		$hero_slides[] = $fallback;
	}
}

// ABOUT の写真。assets/images/top/about-*.jpg があれば優先し、無ければヒーロー写真を流用。
$about_cells = array(
	array( 'cap' => 'Exterior', 'jp' => '外観', 'img' => array( 'top/about-exterior.jpg', 'top/HeroSlide02.jpg' ) ),
	array( 'cap' => 'Living', 'jp' => 'リビング', 'img' => array( 'top/about-living.jpg', 'top/HeroSlide03.jpg' ) ),
	array( 'cap' => 'Wood Deck', 'jp' => 'ウッドデッキ', 'img' => array( 'top/about-deck.jpg', 'top/HeroSlide04.jpg' ) ),
	array( 'cap' => 'Light & Wind', 'jp' => '光と風', 'img' => array( 'top/about-light.jpg', 'top/HeroSlide05.jpg' ) ),
	array( 'cap' => 'Detail', 'jp' => 'こだわり', 'img' => array( 'top/about-detail.jpg', 'top/HeroSlide06.jpg' ) ),
);

$pillars = array(
	array( 'name' => 'CONCEPT', 'jp' => 'コンセプト', 'url' => home_url( '/concept/' ), 'img' => array( 'Top_menu_01_3.jpg', 'Top_menu_01@2x.jpg', 'Top_menu_01.jpg' ) ),
	array( 'name' => 'FLOW', 'jp' => 'ALOHA&STYLEの家ができるまで', 'url' => home_url( '/flow/' ), 'img' => array( 'Top_menu_02_3.jpg', 'Top_menu_02@2x.jpg', 'Top_menu_02.jpg' ) ),
	array( 'name' => 'PLAN', 'jp' => '住宅プラン', 'url' => home_url( '/plan/' ), 'img' => array( 'Top_menu_03_3.jpg', 'Top_menu_03@2x.jpg', 'Top_menu_03.jpg' ) ),
	array( 'name' => 'WORKS', 'jp' => '施工事例', 'url' => home_url( '/cases/' ), 'img' => array( 'Top_menu_04_3.jpg', 'Top_menu_04@2x.jpg', 'Top_menu_04.jpg' ) ),
);

// CONTENTS（最初のプレビューと同じ6項目）。url が空の項目は表示しない。
$contents = array(
	array( 'label' => '施工例', 'url' => home_url( '/cases/' ), 'img' => array( 'Top_content_04_2.jpg', 'Top_content_04@2x.jpg', 'Top_content_04.jpg' ) ),
	array( 'label' => 'モデルハウス LOCO-LATTE', 'url' => home_url( '/modelhouse/' ), 'img' => array( 'Top_content_01_2.jpg', 'Top_content_01@2x.jpg', 'Top_content_01.jpg' ) ),
	array( 'label' => 'ハワイの不動産物件', 'url' => home_url( '/hawaii/' ), 'img' => array( 'Top_content_03_2.jpg', 'Top_content_03@2x.jpg', 'Top_content_03.jpg' ) ),
	// 要確認：リフォームブログの取得元（iedock.seibukensetu.jp）をリフォームサイトとしてリンク
	array( 'label' => '西部建設リフォーム', 'url' => 'https://iedock.seibukensetu.jp/', 'img' => array( 'top/contents-reform.jpg' ), 'external' => true ),
	array( 'label' => '県外で建てる（設計施工管理サービス）', 'url' => home_url( '/housedesign/' ), 'img' => array( 'Top_content_05_2.jpg', 'Top_content_05@2x.jpg', 'Top_content_05.jpg' ) ),
	array( 'label' => 'LINEお友達追加', 'url' => home_url( '/line/' ), 'img' => array( 'line_bnr_img.png' ) ), // footer.php と同じリンク・画像
);

// バナー（最初のプレビューと同じ2つ＋キャンペーン）。url が空の項目は表示しない。
$banners = array(
	array( 'tag' => 'VR展示場', 'title' => 'VRで、憧れのハワイアンライフを体感', 'url' => '', 'img' => array( 'top/banner-vr.jpg' ), 'dark' => false ), // 要確認：VR展示場のURL
	array( 'tag' => '近隣エリア', 'title' => '施工エリア以外のお客様へ', 'url' => home_url( '/housedesign/' ), 'img' => array( 'top/banner-area.jpg', 'concept/hawaii-sunset.jpg' ), 'dark' => false ),
);
$campaign = array( 'label' => '住宅省エネ2024キャンペーン', 'url' => '' ); // 要確認：キャンペーンのURL（年度も）

// single-cases.php で使っている既存のオンライン相談予約URL
$online_consult_url = 'https://www.ie-miru.jp/cms/yoyaku/seibukensetsu/events/19642';

$works_query = new WP_Query(
	array(
		'post_type'      => 'cases',
		'posts_per_page' => 3,
		'orderby'        => 'date',
		'order'          => 'DESC',
	)
);

$news_query = new WP_Query(
	array(
		'post_type'      => array( 'post', 'work', 'realestate' ),
		'posts_per_page' => 6,
	)
);
?>
<main class="Home">

	<!-- HERO -->
	<section class="Home__hero<?php echo 6 === count( $hero_slides ) ? '' : ' Home__hero--static'; ?>" aria-labelledby="home-hero-heading">
		<?php foreach ( $hero_slides as $index => $slide ) : ?>
			<img class="Home__hero-slide" src="<?php echo esc_url( $slide ); ?>" alt="<?php echo esc_attr( sprintf( 'ALOHA & STYLEが手がけたハワイアンスタイル住宅の写真%d', $index + 1 ) ); ?>" loading="<?php echo 0 === $index ? 'eager' : 'lazy'; ?>" decoding="async">
		<?php endforeach; ?>

		<div class="Home__hero-title">
			<div class="Home__hero-eyebrow">Okayama &mdash; Hawaiian Style House</div>
			<h2 id="home-hero-heading" class="Home__hero-heading">ALOHA &amp; STYLE</h2>
			<p class="Home__hero-tagline">岡山で叶える、ハワイアンスタイルの住まい。<br>豊富な提案力と実績で、憧れの暮らしをかたちにします。</p>
			<div class="Home__hero-ctas">
				<a href="<?php echo esc_url( home_url( '/cases/' ) ); ?>" class="Home__btn">施工事例を見る &rarr;</a>
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="Home__btn--outline">お問い合わせ</a>
			</div>
		</div>
	</section>

	<!-- ABOUT -->
	<section class="Home__about" aria-labelledby="home-about-heading">
		<div class="Home__about-grid">
			<div class="Home__about-cell Home__about-text">
				<h2 id="home-about-heading" class="Home__about-text-label">About Aloha &amp; Style</h2>
				<p>ALOHA&amp;STYLEは、ウッドデッキや吹き抜けリビングなどハワイアンスタイルを実現するための住宅を、豊富な提案力と実績で自由自在にお造りします。暮らしの拠点でも、上質な非日常のご提案をいたします。</p>
			</div>
			<?php foreach ( $about_cells as $cell ) : ?>
				<a href="<?php echo esc_url( home_url( '/concept/' ) ); ?>" class="Home__about-cell">
					<?php aloha_image_tag( aloha_theme_image( $cell['img'] ), 'ALOHA&STYLEの住まいの' . $cell['jp'] . 'の写真', 'Home__placeholder-photo' ); ?>
					<span class="Home__about-cell-overlay">
						<span class="Home__about-cell-cap"><?php echo esc_html( $cell['cap'] ); ?></span>
						<span class="Home__about-cell-jp"><?php echo esc_html( $cell['jp'] ); ?></span>
						<span class="Home__about-cell-more">More</span>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
	</section>

	<!-- PILLARS -->
	<nav class="Home__pillars" aria-label="主要コンテンツ">
		<div class="Home__pillar-grid">
			<?php foreach ( $pillars as $index => $pillar ) : ?>
				<a href="<?php echo esc_url( $pillar['url'] ); ?>" class="Home__pillar-tile">
					<?php aloha_image_tag( aloha_theme_image( $pillar['img'] ), '', 'Home__placeholder-photo' ); ?>
					<span class="Home__pillar-overlay">
						<span class="Home__pillar-num"><?php echo esc_html( sprintf( '%02d', $index + 1 ) ); ?></span>
						<span class="Home__pillar-name"><?php echo esc_html( $pillar['name'] ); ?></span>
						<span class="Home__pillar-jp"><?php echo esc_html( $pillar['jp'] ); ?></span>
					</span>
				</a>
			<?php endforeach; ?>
		</div>
	</nav>

	<!-- LIFESTYLE -->
	<section class="Home__lifestyle" aria-labelledby="home-lifestyle-heading">
		<div class="Home__lifestyle-inner">
			<div class="Home__lifestyle-photo">
				<?php aloha_image_tag( aloha_theme_image( array( 'top/lifestyle.jpg', 'concept/hero-terrace.jpg', 'Aloha_main.jpg', 'HeroImage_bg.jpg' ) ), 'ALOHA&STYLEが手がけた住まいの、ハワイの風を感じるテラス', 'Home__placeholder-photo' ); ?>
			</div>
			<div class="Home__lifestyle-text">
				<p class="Home__lifestyle-en">It's not just a house, It's a lifestyle.</p>
				<h2 id="home-lifestyle-heading">家は、ただ住むだけの<br>箱じゃない。</h2>
				<p>
					家族と笑い、仲間と集い、<br>
					時には一人で静かに過ごす、<br>
					かけがえのない時間を育む場所。<br>
					私たちが届けたいのは、<br>
					そんな「暮らしの楽しさ」そのものです。
				</p>
				<a href="<?php echo esc_url( home_url( '/concept/' ) ); ?>" class="Home__btn--outline">ALOHA&amp;STYLEのコンセプト</a>
			</div>
		</div>
	</section>

	<!-- WORKS -->
	<section class="Home__works" aria-labelledby="home-works-heading">
		<div class="Home__works-head">
			<h2 id="home-works-heading" class="Home__works-head-en">Works</h2>
			<span class="Home__works-head-jp">施工事例</span>
		</div>
		<?php if ( $works_query->have_posts() ) : ?>
			<div class="Home__works-grid">
				<?php
				while ( $works_query->have_posts() ) :
					$works_query->the_post();
					$tags = aloha_case_hashtags();
					?>
					<a href="<?php the_permalink(); ?>" class="Home__work-card">
						<?php aloha_image_tag( aloha_case_main_pic(), get_the_title() . 'の写真', 'Home__placeholder-photo' ); ?>
						<h3><?php the_title(); ?></h3>
						<span class="Home__work-card-more">More</span>
						<?php if ( $tags ) : ?>
							<div class="Home__work-card-tags"><span>&mdash;</span> <?php echo esc_html( $tags ); ?></div>
						<?php endif; ?>
					</a>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
		<div class="Home__works-more"><a href="<?php echo esc_url( home_url( '/cases/' ) ); ?>">施工事例の一覧を見る</a></div>
	</section>

	<!-- NEWS & TOPICS -->
	<section class="Home__news" aria-labelledby="home-news-heading">
		<div class="Home__section-head">
			<h2 id="home-news-heading" class="Home__section-head-en">News &amp; Topics</h2>
			<span class="Home__section-head-jp">新着情報</span>
		</div>
		<?php if ( $news_query->have_posts() ) : ?>
			<div class="Home__news-list">
				<?php
				while ( $news_query->have_posts() ) :
					$news_query->the_post();
					$label = aloha_news_label();
					?>
					<a href="<?php the_permalink(); ?>" class="Home__news-item">
						<?php
						if ( has_post_thumbnail() ) {
							the_post_thumbnail( 'medium_large', array( 'alt' => '', 'loading' => 'lazy' ) );
						} else {
							aloha_image_tag( aloha_theme_image( 'no_img.jpg' ), '', 'Home__placeholder-photo' );
						}
						?>
						<span class="Home__news-meta">
							<time class="Home__news-date" datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'Y.m.d' ) ); ?></time>
							<?php if ( $label ) : ?>
								<span class="Home__news-cat"><?php echo esc_html( $label ); ?></span>
							<?php endif; ?>
						</span>
						<span class="Home__news-title"><?php the_title(); ?></span>
					</a>
				<?php endwhile; ?>
			</div>
		<?php endif; ?>
		<?php wp_reset_postdata(); ?>
	</section>

	<!-- CONTENTS -->
	<section class="Home__contents" aria-labelledby="home-contents-heading">
		<div class="Home__section-head">
			<h2 id="home-contents-heading" class="Home__section-head-en">Contents</h2>
			<span class="Home__section-head-jp">サービス・コンテンツ</span>
		</div>
		<div class="Home__contents-grid">
			<?php foreach ( $contents as $content ) : ?>
				<?php
				if ( '' === $content['url'] ) {
					continue;
				}
				?>
				<a href="<?php echo esc_url( $content['url'] ); ?>" class="Home__content-tile"<?php echo ! empty( $content['external'] ) ? ' target="_blank" rel="noopener"' : ''; ?>>
					<?php aloha_image_tag( aloha_theme_image( $content['img'] ), '', 'Home__placeholder-photo' ); ?>
					<span class="Home__content-tile-label"><span><?php echo esc_html( $content['label'] ); ?></span></span>
				</a>
			<?php endforeach; ?>
		</div>
	</section>

	<!-- CONTACT -->
	<section class="Home__contact" aria-labelledby="home-contact-heading">
		<div class="Home__contact-inner">
			<div>
				<h2 id="home-contact-heading" class="Home__contact-en">Contact</h2>
				<p class="Home__contact-text">家づくりのご相談、モデルハウスの見学、資料のご請求はお気軽にどうぞ。</p>
			</div>
			<div class="Home__contact-actions">
				<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="Home__btn">お問い合わせ &rarr;</a>
				<a href="<?php echo esc_url( $online_consult_url ); ?>" class="Home__btn--outline" target="_blank" rel="noopener">オンライン相談を予約</a>
			</div>
		</div>
	</section>

	<!-- EVENTS（ie-miru の外部ウィジェット。既存と同じ読み込み方） -->
	<section class="Home__events" aria-labelledby="home-events-heading">
		<div class="Home__section-head">
			<h2 id="home-events-heading" class="Home__section-head-en">Event Information</h2>
			<span class="Home__section-head-jp">イベント情報</span>
		</div>
		<div class="Home__events-widget">
			<script src="https://www.ie-miru.jp/cms/yoyaku/seibukensetsu.js?limit=4"></script>
			<div id="js-iemiru-cms-index-page" style="width: 100%; display: block;"></div>
		</div>
	</section>

	<!-- REFORM BLOG（一覧は footer.php の reformBlogData() が追加） -->
	<section class="Home__reform js-news-generate" aria-labelledby="home-reform-heading">
		<div class="Home__section-head">
			<h2 id="home-reform-heading" class="Home__section-head-en">Reform Blog</h2>
			<span class="Home__section-head-jp">リフォームブログ</span>
		</div>
		<ul class="Home__reform-list js-news-generate-target"></ul>
	</section>

	<?php
	$visible_banners = array_filter(
		$banners,
		function ( $banner ) {
			return '' !== $banner['url'];
		}
	);
	?>
	<?php if ( $visible_banners || '' !== $campaign['url'] ) : ?>
		<!-- BANNERS -->
		<section class="Home__banners" aria-label="お知らせバナー">
			<?php if ( $visible_banners ) : ?>
				<div class="Home__banner-grid<?php echo 1 === count( $visible_banners ) ? ' Home__banner-grid--single' : ''; ?>">
					<?php foreach ( $visible_banners as $banner ) : ?>
						<a href="<?php echo esc_url( $banner['url'] ); ?>" class="Home__banner-tile">
							<?php aloha_image_tag( aloha_theme_image( $banner['img'] ), '', 'Home__placeholder-photo' ); ?>
							<span class="Home__banner-tag<?php echo $banner['dark'] ? ' Home__banner-tag--dark' : ''; ?>"><?php echo esc_html( $banner['tag'] ); ?></span>
							<span class="Home__banner-ttl<?php echo $banner['dark'] ? ' Home__banner-ttl--dark' : ''; ?>"><?php echo esc_html( $banner['title'] ); ?></span>
						</a>
					<?php endforeach; ?>
				</div>
			<?php endif; ?>
			<?php if ( '' !== $campaign['url'] ) : ?>
				<div class="Home__campaign"><a href="<?php echo esc_url( $campaign['url'] ); ?>" class="Home__btn--outline"><?php echo esc_html( $campaign['label'] ); ?></a></div>
			<?php endif; ?>
		</section>
	<?php endif; ?>

	<!-- SNS（Facebook SDK は footer.php で読み込み） -->
	<section class="Home__sns" aria-labelledby="home-sns-heading">
		<div class="Home__section-head">
			<h2 id="home-sns-heading" class="Home__section-head-en">Facebook</h2>
		</div>
		<div class="Home__sns-fb" id="fb_page_plugin_area">
			<div class="fb-page" data-href="https://www.facebook.com/alohaandstyle" data-tabs="timeline" data-width="500" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="false">
				<blockquote cite="https://www.facebook.com/alohaandstyle" class="fb-xfbml-parse-ignore"><a href="https://www.facebook.com/alohaandstyle">ALOHA &amp; STYLE（Facebook）</a></blockquote>
			</div>
		</div>
	</section>

</main>
<?php
get_footer();
