<?php
/**
 * トップページ
 *
 * スタイル: assets/@scss/components/_home.scss → assets/css/home.css
 * 読み込み・共通関数: inc/redesign.php
 *
 * 並び（サイトマップ v2）: ヒーロー → 施工事例 → コンセプト → 住宅プラン → CONTENTS → イベント
 *   → お問い合わせ → お知らせ → バナー → リフォームブログ → Facebook
 *
 * 既存のまま維持しているもの:
 * - NEWS & TOPICS はブログ・ニュース投稿（post）の最新6件
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

// 施工事例の見学予約（ヘッダーの「見学予約」と同じ）
$reserve_url = 'https://www.ie-miru.jp/cms/yoyaku/seibukensetsu/events/4847';

// 施工事例のスタイル別ボタン（施工例がある スタイルだけ）
$case_style_links = array();
foreach ( aloha_case_styles() as $style_slug => $style ) {
	$style_term = taxonomy_exists( 'cases_category' ) ? get_term_by( 'slug', $style_slug, 'cases_category' ) : false;
	if ( ! $style_term || ! $style_term->count ) {
		continue;
	}
	$style_link = get_term_link( $style_term );
	if ( ! is_wp_error( $style_link ) ) {
		$case_style_links[] = array( 'label' => $style['ja'], 'url' => $style_link );
	}
}

// 住宅プランの5スタイル（写真は各カテゴリの画像。無ければコンセプトの写真）
$plan_fallback_img = array(
	'surfers_house'     => 'concept/plantation-house.jpg',
	'midcentury_modern' => 'concept/midcentury-house.jpg',
);
$plan_tiles = array();
foreach ( aloha_plan_categories() as $plan_cat ) {
	$plan_meta    = aloha_plan_category_meta( $plan_cat['slug'] );
	$plan_imgs    = array_filter(
		array(
			$plan_meta ? $plan_meta['img'] : '',
			isset( $plan_fallback_img[ $plan_cat['slug'] ] ) ? $plan_fallback_img[ $plan_cat['slug'] ] : '',
		)
	);
	$plan_tiles[] = array(
		'en'    => $plan_cat['en'],
		'ja'    => $plan_cat['label'],
		'url'   => $plan_cat['url'],
		'img'   => $plan_imgs ? aloha_theme_image( $plan_imgs ) : '',
		'ready' => $plan_cat['count'] > 0,
	);
}

// CONTENTS（最初のプレビューと同じ6項目）。url が空の項目は表示しない。
$contents = array(
	array( 'label' => '施工例', 'url' => home_url( '/cases/' ), 'img' => array( 'Top_content_04_2.jpg', 'Top_content_04@2x.jpg', 'Top_content_04.jpg' ) ),
	array( 'label' => 'モデルハウス LOCO-LATTE', 'url' => home_url( '/modelhouse/' ), 'img' => array( 'Top_content_01_2.jpg', 'Top_content_01@2x.jpg', 'Top_content_01.jpg' ) ),
	array( 'label' => 'ハワイの不動産物件', 'url' => home_url( '/hawaii/' ), 'img' => array( 'Top_content_03_2.jpg', 'Top_content_03@2x.jpg', 'Top_content_03.jpg' ) ),
	// 西部建設のリフォームサイト（リフォームブログの取得元と同じ）
	array( 'label' => '西部建設リフォーム', 'url' => 'https://iedock.seibukensetu.jp/', 'img' => array( 'top/contents-reform.jpg' ), 'external' => true ),
	array( 'label' => '県外で建てる（設計施工管理サービス）', 'url' => home_url( '/housedesign/' ), 'img' => array( 'Top_content_05_2.jpg', 'Top_content_05@2x.jpg', 'Top_content_05.jpg' ) ),
	array( 'label' => 'LINEお友達追加', 'url' => home_url( '/line/' ), 'img' => array( 'line_bnr_img.png' ) ), // footer.php と同じリンク・画像
);

// バナー（最初のプレビューと同じ2つ）。url が空の項目は表示しない。
$banners = array(
	array( 'tag' => 'VR展示場', 'title' => 'VRで、憧れのハワイアンライフを体感', 'url' => home_url( '/vr-exhibition/' ), 'img' => array( 'top/banner-vr.jpg' ), 'dark' => false ),
	array( 'tag' => '近隣エリア', 'title' => '施工エリア以外のお客様へ', 'url' => home_url( '/housedesign/' ), 'img' => array( 'top/banner-area.jpg', 'concept/hawaii-sunset.jpg' ), 'dark' => false ),
);

// single-cases.php で使っている既存のオンライン相談予約URL
$online_consult_url = 'https://www.ie-miru.jp/cms/yoyaku/seibukensetsu/events/19642';

$works_query = new WP_Query(
	array(
		'post_type'      => 'cases',
		'posts_per_page' => 6,
		'orderby'        => 'date',
		'order'          => 'DESC',
	)
);

$news_query = new WP_Query(
	array(
		'post_type'           => 'post', // ブログ・ニュース投稿のみ（施工例は Works、不動産は各ページで案内）
		'posts_per_page'      => 6,
		'ignore_sticky_posts' => true,
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
				<a href="<?php echo esc_url( $reserve_url ); ?>" class="Home__btn--outline" target="_blank" rel="noopener">モデルハウス見学予約</a>
			</div>
		</div>
	</section>

	<!-- WORKS -->
	<section class="Home__works" aria-labelledby="home-works-heading">
		<div class="Home__works-head">
			<h2 id="home-works-heading" class="Home__works-head-en">Works</h2>
			<span class="Home__works-head-jp">施工事例</span>
		</div>
		<?php if ( $case_style_links ) : ?>
			<nav class="Home__works-styles" aria-label="スタイルから施工事例を探す">
				<?php foreach ( $case_style_links as $style_link ) : ?>
					<a href="<?php echo esc_url( $style_link['url'] ); ?>"><?php echo esc_html( $style_link['label'] ); ?></a>
				<?php endforeach; ?>
			</nav>
		<?php endif; ?>
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

	<!-- CONCEPT（ブランドメッセージと3つのこだわり） -->
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
				<p class="Home__lifestyle-about">ALOHA&amp;STYLEは、ウッドデッキや吹き抜けリビングなどハワイアンスタイルを実現するための住宅を、豊富な提案力と実績で自由自在にお造りします。</p>
				<a href="<?php echo esc_url( home_url( '/concept/' ) ); ?>" class="Home__btn--outline">ALOHA&amp;STYLEのコンセプト</a>
			</div>
		</div>
		<ol class="Home__points">
			<li>
				<span class="Home__points-num">01</span>
				<strong>プランテーションハウス</strong>
				<p>深い軒（ラナイ）が内と外をつなぐ、飾りすぎず暮らして気持ちのいい家。</p>
			</li>
			<li>
				<span class="Home__points-num">02</span>
				<strong>リゾートモダン＆ミッドセンチュリー</strong>
				<p>大きな開口で光と風を取り込み、素材の質感とフォルムで彩る家。</p>
			</li>
			<li>
				<span class="Home__points-num">03</span>
				<strong>ハワイの暮らしを、日本で</strong>
				<p>住まいの設計・施工に加え、イベントや雑貨・インテリアまで、ハワイの暮らしをご提案。</p>
			</li>
		</ol>
	</section>

	<!-- PLAN（住宅プランの5スタイル） -->
	<section class="Home__plans" aria-labelledby="home-plans-heading">
		<div class="Home__section-head">
			<h2 id="home-plans-heading" class="Home__section-head-en">Plan</h2>
			<span class="Home__section-head-jp">住宅プラン</span>
		</div>
		<ul class="Home__plans-grid">
			<?php foreach ( $plan_tiles as $plan_tile ) : ?>
				<li>
					<a href="<?php echo esc_url( $plan_tile['url'] ); ?>" class="Home__plan-tile<?php echo $plan_tile['ready'] ? '' : ' is-soon'; ?>">
						<?php aloha_image_tag( $plan_tile['img'], '', 'Home__placeholder-photo' ); ?>
						<span class="Home__plan-tile-overlay">
							<span class="Home__plan-tile-en"><?php echo esc_html( $plan_tile['en'] ); ?></span>
							<span class="Home__plan-tile-ja"><?php echo esc_html( $plan_tile['ja'] ); ?></span>
							<span class="Home__plan-tile-more"><?php echo $plan_tile['ready'] ? 'プランを見る &rarr;' : '準備中'; ?></span>
						</span>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
		<div class="Home__works-more"><a href="<?php echo esc_url( get_post_type_archive_link( 'plan' ) ? get_post_type_archive_link( 'plan' ) : home_url( '/plan/' ) ); ?>">住宅プランの一覧を見る</a></div>
	</section>

	<!-- CONTENTS（家づくりの選択肢・サービス） -->
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

	<!-- EVENTS（ie-miru の外部ウィジェット。表示先の div を先に置いてからスクリプトを読み込む。6件表示） -->
	<section class="Home__events" aria-labelledby="home-events-heading">
		<div class="Home__section-head">
			<h2 id="home-events-heading" class="Home__section-head-en">Event Information</h2>
			<span class="Home__section-head-jp">イベント情報</span>
		</div>
		<div class="Home__events-widget">
			<div id="js-iemiru-cms-index-page" style="width: 100%; display: block;"></div>
			<script src="https://www.ie-miru.jp/cms/yoyaku/seibukensetsu.js?limit=6"></script>
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

	<?php
	$visible_banners = array_filter(
		$banners,
		function ( $banner ) {
			return '' !== $banner['url'];
		}
	);
	?>
	<?php if ( $visible_banners ) : ?>
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
		</section>
	<?php endif; ?>

	<!-- REFORM BLOG（一覧は footer.php の reformBlogData() が追加） -->
	<section class="Home__reform js-news-generate" aria-labelledby="home-reform-heading">
		<div class="Home__section-head">
			<h2 id="home-reform-heading" class="Home__section-head-en">Reform Blog</h2>
			<span class="Home__section-head-jp">リフォームブログ</span>
		</div>
		<ul class="Home__reform-list js-news-generate-target"></ul>
	</section>

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
