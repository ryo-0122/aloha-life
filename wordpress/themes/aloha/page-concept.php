<?php
/**
 * コンセプト（固定ページ /concept/）
 *
 * 文章・写真はカタログ「ALOHA&STYLE PLANTATION HOUSE & SURFER'S HOUSE ARCHIVES（WORKS 2026）」より。
 * スタイル: assets/@scss/components/_concept.scss → assets/css/concept.css
 *
 * WordPress はスラッグ concept の固定ページで、このテンプレートを自動で使う（本文は管理画面ではなくここで管理）。
 */

get_header();

$cc_img = get_template_directory_uri() . '/assets/images/concept/';

// 住宅プランの5カテゴリ。カテゴリが作成済みならその一覧へ、未作成なら住宅プラン一覧へリンク。
$cc_styles = array(
	array( 'en' => "SURFER'S HOUSE", 'jp' => 'サーファーズハウス', 'slug' => 'surfers_house' ),
	array( 'en' => 'RESORT MODERN', 'jp' => 'リゾートモダン', 'slug' => 'resort_modern' ),
	array( 'en' => 'MID-CENTURY MODERN', 'jp' => 'ミッドセンチュリーモダン', 'slug' => 'midcentury_modern' ),
	array( 'en' => 'RENOVATION', 'jp' => 'リノベーション', 'slug' => 'renovation' ),
	array( 'en' => 'APARTMENT', 'jp' => 'アパート', 'slug' => 'apartment' ),
);
$cc_plan_url = get_post_type_archive_link( 'plan' ) ? get_post_type_archive_link( 'plan' ) : home_url( '/plan/' );
?>
<main class="Concept">

	<!-- HERO -->
	<section class="Concept__hero" aria-labelledby="concept-hero-title">
		<img src="<?php echo esc_url( $cc_img . 'hero-terrace.jpg' ); ?>" alt="ALOHA&amp;STYLEが手がけた住まいの、ハワイの風を感じるテラス" decoding="async">
		<div class="Concept__hero-body">
			<span class="Concept__kicker">Concept</span>
			<h1 id="concept-hero-title" class="Concept__hero-title">ハワイの風を感じる毎日が、<br>ここから始まる。</h1>
		</div>
	</section>

	<!-- MESSAGE -->
	<section class="Concept__message" aria-labelledby="concept-message-heading">
		<div class="Concept__wrap Concept__message-inner">
			<div>
				<span class="Concept__kicker">Our Message</span>
				<h2 id="concept-message-heading" class="Concept__heading">It's not just a house,<br>It's a lifestyle.</h2>
				<p class="Concept__message-text">
					家は、ただ住むだけの箱じゃない。<br>
					家族と笑い、仲間と集い、<br>
					時には一人で静かに過ごす、<br>
					かけがえのない時間を育む場所。<br>
					私たちが届けたいのは、<br>
					そんな「暮らしの楽しさ」そのものです。
				</p>
			</div>
			<div class="Concept__message-photos">
				<figure><img src="<?php echo esc_url( $cc_img . 'message-a.jpg' ); ?>" alt="テラスとつながる、開放的な住まいの外観" loading="lazy" decoding="async"></figure>
				<figure><img src="<?php echo esc_url( $cc_img . 'message-b.jpg' ); ?>" alt="芝生の庭に面したカバードポーチのある住まい" loading="lazy" decoding="async"></figure>
			</div>
		</div>
	</section>

	<!-- BASICS -->
	<section class="Concept__basics" aria-labelledby="concept-basics-heading">
		<div class="Concept__wrap">
			<header class="Concept__basics-head">
				<span class="Concept__kicker">Basics</span>
				<h2 id="concept-basics-heading" class="Concept__heading">私たちの家づくりの基本</h2>
			</header>

			<article class="Concept__style">
				<figure class="Concept__style-photo"><img src="<?php echo esc_url( $cc_img . 'plantation-house.jpg' ); ?>" alt="白い外壁と深いカバードポーチのプランテーションハウス" loading="lazy" decoding="async"></figure>
				<div>
					<h3 class="Concept__style-name">The Plantation House</h3>
					<span class="Concept__style-label">プランテーションハウス</span>
					<div class="Concept__style-text">
						<p>プランテーションハウスとは、もともとサトウキビ農園などで働く移民の為に作られた庶民的な家。日差しの強さを和らげるため軒が深く、その部分をハワイではラナイと呼びます。</p>
						<p>室内から見ても外と中をつなぐ中間的な空間となる為、奥深い広がりと豊かさを味わえ、古きよきハワイの趣が素直に感じられます。飾りすぎず、シンプルで、暮らして気持ちのいい家を叶えるプランテーションハウスをコンセプトにしています。</p>
					</div>
				</div>
			</article>

			<article class="Concept__style Concept__style--reverse">
				<figure class="Concept__style-photo"><img src="<?php echo esc_url( $cc_img . 'midcentury-house.jpg' ); ?>" alt="水平ラインと木の外壁が特徴のミッドセンチュリーモダンの住まい" loading="lazy" decoding="async"></figure>
				<div>
					<h3 class="Concept__style-name">Resort Modern &amp;<br>Mid-Century Modern House</h3>
					<span class="Concept__style-label">リゾートモダン ＆ ミッドセンチュリーモダン</span>
					<div class="Concept__style-text">
						<p>リゾートモダンは、現代的な洗練されたデザインに、リゾート地特有の開放感とリラックスした雰囲気を融合させたスタイル。ミッドセンチュリーモダンは、1950〜60年代のアメリカで発展した、機能性と美しさを兼ね備えたデザインスタイル。</p>
						<p>どちらも大きな開口部で室内外をシームレスにつなぎ、光と風を取り込む設計が特徴です。自然素材の温かみと都会的なスタイリッシュさが調和し、装飾に頼らず、素材の質感とフォルムの美しさで空間を彩ります。タイムレスで、日常の中に非日常のリラクゼーションを感じられる、暮らして心地よい家を叶えるスタイルをコンセプトにしています。</p>
					</div>
				</div>
			</article>
		</div>
	</section>

	<!-- HAWAII -->
	<section class="Concept__hawaii" aria-label="ハワイの暮らし">
		<div class="Concept__wrap Concept__hawaii-inner">
			<figure class="Concept__hawaii-photo"><img src="<?php echo esc_url( $cc_img . 'hawaii-sunset.jpg' ); ?>" alt="夕暮れのハワイの海とヤシの木" loading="lazy" decoding="async"></figure>
			<div>
				<div class="Concept__hawaii-block">
					<span class="Concept__kicker">Hawaii</span>
					<h2 class="Concept__hawaii-title">ずっと暮らしていたくなる島 ハワイ</h2>
					<p class="Concept__hawaii-text">観光地として有名なハワイ。何度も旅行先として選ぶ方が多く、そして日本人が第二の人生を過ごす場として移住したり別荘を持つ方が多い地域の一つ。自然にあふれ、海にもすぐ行ける。食文化も豊か。屋外に出れば、四方を囲む太平洋の青さと、空の広さをどこでも楽しめる環境です。</p>
				</div>
				<div class="Concept__hawaii-block">
					<span class="Concept__kicker">Hawaiian Life in Japan</span>
					<h2 class="Concept__hawaii-title">日本でハワイの暮らしを</h2>
					<p class="Concept__hawaii-text">
						<?php // 要確認：カタログ原文「南の島ということを意識を配慮する方も多いですが」を、意味が通るよう整えています。 ?>
						わたしたちALOHA &amp; STYLE Inc.は、日本の住宅会社ですが、そうしたハワイの暮らしやすさ・文化に触れ、ハワイをコンセプトにした住宅の提案からイベントの企画、雑貨やインテリアの輸入販売を長年手がけてきました。南の島ということで暑さを心配される方も多いですが、日本に比べれば気温は高く日差しは強めでも、湿度が低く山からの風が絶えず届くため、日本の高湿度環境に比べるととても過ごしやすいのです。多くの方が何度も行きたくなる旅行先であり、年齢を重ねた方が余生を過ごす場所として選ばれることには理由があります。みなさまにご提供しているのはハワイアン住宅の設計・施工だけではなく、多くの日本人がファンになった「ハワイの暮らし」を、毎日の生活で実現するための様々な提案です。
					</p>
				</div>
			</div>
		</div>
	</section>

	<!-- STORY（代表メッセージ） -->
	<section class="Concept__story" aria-labelledby="concept-story-heading">
		<div class="Concept__wrap Concept__story-inner">
			<div class="Concept__story-profile">
				<figure class="Concept__story-photo"><img src="<?php echo esc_url( $cc_img . 'president.jpg' ); ?>" alt="ALOHA&amp;STYLE Inc. 代表取締役社長 湯浅康則" loading="lazy" decoding="async"></figure>
				<p class="Concept__story-sign">ALOHA&amp;STYLE Inc.<br>西部建設株式会社<br>代表取締役 社長<strong>湯浅 康則</strong></p>
			</div>
			<div>
				<span class="Concept__kicker">Message</span>
				<h2 id="concept-story-heading" class="Concept__story-title">なぜハワイがコンセプトの家づくりなのか？</h2>
				<div class="Concept__story-body">
					<p>「なぜ“ハワイ”をコンセプトにしているの？」とよく聞かれます。<br>単純に「ハワイが好き」だからです！</p>
					<p>そして、私がハワイを好きになるきっかけは両親、特に父親（弊社会長）の影響が大きく、彼は建設業を営む以前に外洋船の船員だったそうです。「あこがれのハワイ航路」という歌もありますが、今も昔も船乗りにとってハワイは本当にあこがれの土地だったそうです。そして父親もこういう「ハワイ好き」だったとのこと。</p>
					<?php // 要確認：カタログ原文「両親（オアフ島）に初めて、弟（笑 務勤）と一緒に」「たまらない程の空港へ」「完全に魅力になってしまいました」を整えています。 ?>
					<p>私はというと、「ハワイなんて、ミーハーな奴らが行くところ」と決めつけ、少し敬遠していたのですが、両親と弟と一緒に初めてハワイ（オアフ島）に行ったのが28歳の時です。ホノルルの空港へ降り立った瞬間に感じた空気感が、今思えば感動に近くて・・・。降り立った時点で、完全に虜になってしまいました。</p>
					<?php // 要確認：カタログ原文「見学などとも見学」「とってもケフラを踊っている」を整えています。 ?>
					<p>その時の滞在中には、建築屋らしくハワイの住宅や建築はもとより、街並みなども見学。高級住宅街でのオープンハウスやホームセンターなどに行った際は、楽しくて仕方なかったことを覚えています。どこへ行こうと何をしようと、景色は美しく、空気はきれい、人はやさしい。どこからか流れてくるハワイアンミュージックは気分を楽しませてくれ、気分はすっかりフラを踊っている――そんな楽しくて癒されて大満足なハワイ初体験でした。（その後、何十回と行っていますけど(^_^)）</p>
					<p class="Concept__story-quote">「こんな1年中快適で楽しい生活が出来たら最高だなぁ。」<br>これが住宅づくりのコンセプトになるのでは？<br>自分が大好きなハワイのように、「一年を通じて快適で楽しいライフスタイルを提案したい」！</p>
					<p>こうしてハワイをコンセプトにした住宅づくりをスタートさせることになったのです。</p>
					<p>住宅設備や資材などをハワイから仕入れ、15年ほど前に建てたのが体感ハウス“LOCO-LATTE”です。そして雑貨やアロハシャツなども仕入れ、ハワイアンセレクトショップ“LOCO-LATTE”も同時に立ち上げました。こうやって、ハワイアンスタイル提案“ALOHA&amp;STYLE”が始まっていきました。</p>
					<p>初めてハワイを訪れてから、約20年。今では本業の住宅造りやその時始めた雑貨店はもちろんのこと、ハワイアンイベントやハワイの不動産ツアーなどもやっています。</p>
					<p>現在、西部建設は30周年を迎えることができました。今後もハワイ好きなお客様や会社のメンバー、関係会社の方々と一緒に“ALOHA &amp; STYLE”をどんどん展開していきます！</p>
				</div>
			</div>
		</div>
	</section>

	<!-- STYLES（スタイルで選ぶ） -->
	<section class="Concept__styles" aria-labelledby="concept-styles-heading">
		<div class="Concept__wrap">
			<span class="Concept__kicker">Styles</span>
			<h2 id="concept-styles-heading" class="Concept__heading">スタイルで選ぶ<span class="Concept__heading-jp">住宅プラン</span></h2>
			<ul class="Concept__styles-grid">
				<?php foreach ( $cc_styles as $style ) : ?>
					<?php
					$term = taxonomy_exists( 'plan_category' ) ? get_term_by( 'slug', $style['slug'], 'plan_category' ) : false;
					$link = $term ? get_term_link( $term ) : $cc_plan_url;
					$link = is_wp_error( $link ) ? $cc_plan_url : $link;
					?>
					<li class="Concept__styles-item">
						<a href="<?php echo esc_url( $link ); ?>">
							<span>
								<span class="Concept__styles-en"><?php echo esc_html( $style['en'] ); ?></span>
								<span class="Concept__styles-jp"><?php echo esc_html( $style['jp'] ); ?></span>
							</span>
							<span class="Concept__styles-more">プランを見る &rarr;</span>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		</div>
	</section>

	<!-- NEXT -->
	<section class="Concept__next" aria-labelledby="concept-next-heading">
		<div class="Concept__wrap">
			<span class="Concept__kicker">Next</span>
			<h2 id="concept-next-heading" class="Concept__heading">もっと知る</h2>
			<ul class="Concept__next-grid">
				<li><a href="<?php echo esc_url( home_url( '/cases/' ) ); ?>"><strong>施工事例</strong><span>プランテーションハウス・サーファーズハウスの実例</span><em>Works &rarr;</em></a></li>
				<li><a href="<?php echo esc_url( home_url( '/flow/' ) ); ?>"><strong>家づくりの流れ</strong><span>ご相談からお引き渡しまで</span><em>Flow &rarr;</em></a></li>
				<li><a href="<?php echo esc_url( home_url( '/housedesign/' ) ); ?>"><strong>県外で建てる</strong><span>岡山以外にお住まいの方へ</span><em>Anywhere in Japan &rarr;</em></a></li>
			</ul>
			<div class="Concept__cta">
				<div>
					<p class="Concept__cta-title">Let's create your own ALOHA.</p>
					<p class="Concept__cta-text">カタログのご請求、モデルハウスの見学はお気軽にどうぞ。</p>
				</div>
				<div class="Concept__cta-actions">
					<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="Concept__btn">資料請求・お問い合わせ &rarr;</a>
					<a href="https://www.ie-miru.jp/cms/yoyaku/seibukensetsu/events/4847" class="Concept__btn--outline" target="_blank" rel="noopener">モデルハウス見学予約</a>
				</div>
			</div>
		</div>
	</section>

</main>
<?php
get_footer();
