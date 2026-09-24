<?php
/**
 * 県外で建てる（固定ページ /housedesign/。旧「設計施工管理サービス」）
 *
 * 文章は旧ページの内容を、県外の方にわかりやすい順番に並べ直したもの（費用・支払い割合・FAQ は旧ページのまま）。
 * 見た目はコンセプトページの部品（.Concept__*）を共通で使い、このページだけの部品は .Remote__*。
 * スタイル: assets/@scss/housedesign.scss → assets/css/housedesign.css
 */

get_header();

$rm_consult_url = 'https://www.ie-miru.jp/cms/yoyaku/seibukensetsu/events/19642';

$rm_hero = aloha_theme_image( array( 'housedesign_main2.jpg', 'concept/hawaii-sunset.jpg' ) );

// 家づくりの流れ（旧ページの 11 ステップ）
$rm_steps = array(
	array(
		'title' => 'ご相談・お問い合わせ',
		'text'  => 'お電話・メール・お問い合わせフォームから、お気軽にご連絡ください。',
		'free'  => true,
	),
	array(
		'title' => 'ご面談・ヒアリング',
		'text'  => 'ご都合のよい日程で、弊社事務所またはご希望の場所でお話を伺います（土日・祝日・夜も対応可能）。ご要望がまとまっていなくても大丈夫です。建築地が決まっている場合は、土地の形や寸法がわかる資料をお持ちください。土地探しからの方には、信頼できる不動産会社を無料でご紹介します。',
		'img'   => 'housedesign_01_2.jpg',
		'free'  => true,
	),
	array(
		'title' => 'ファーストプランのご提案',
		'text'  => 'ご要望・敷地の状況・法規制などを調べ、最初のプランをご提案します。ここまでは無料です。プランの修正や別の方向での検討をご希望の場合は、プラン作成申込金 10万円（税別）を頂きます（ご契約時に設計監理料へ充当）。',
		'img'   => 'housedesign_04.jpg',
		'free'  => true,
	),
	array(
		'title' => 'ご契約（設計監理契約）',
		'text'  => 'プランと設計の考え方にご納得いただけたら、設計監理契約を結び、具体的な設計に入ります。契約を無理におすすめすることはありません。辞退される場合、著作権の関係上、ご提案したプランの流用・改変はできず、資料はご返却いただきます。',
		'img'   => 'housedesign_03.jpg',
		'pay'   => '設計監理料の 30%',
	),
	array(
		'title' => '基本設計',
		'text'  => '対話を重ねながら、間取り・外観や内装のデザイン・設備・概算予算などの基本方針を決めます。この後は大きな変更が難しくなるため、ここでしっかり検討します。',
	),
	array(
		'title' => '実施設計',
		'text'  => '材料・設備機器・造作家具などを決めながら、見積もり・工事・行政への申請に必要な図面を作ります。',
	),
	array(
		'title' => '見積もり・各種申請',
		'text'  => '技術力や経営状態から信頼できる施工会社を数社選び、弊社から見積もりを依頼します。各社の内容を比較し、ご予算と仕様に納得いただけるまで調整します。並行して確認申請などの書類を作成・提出します（地元の設計事務所や施工会社に依頼する場合があります）。',
		'img'   => 'housedesign/housedesign_mitsumori2.jpg',
		'pay'   => '設計監理料の 40%',
	),
	array(
		'title' => '施工会社の決定・工事請負契約',
		'text'  => '内容と金額に合意いただけたら、決定した施工会社と工事請負契約を結んでいただきます。これ以降の変更は工事費の増減につながりますのでご注意ください。',
		'img'   => 'housedesign/housedesign_keiyaku.jpg',
	),
	array(
		'title' => '工事・現場のチェック',
		'text'  => '着工後は定期的に現場を確認し、設計どおりか、施工方法に問題がないかを専門家の目でチェックします。図面だけでは伝わらない想いが形になるよう施工会社と密に連携し、状況は随時ご報告します。仕上げの色や柄も、サンプルを見ながら一緒に選びます。',
		'img'   => 'housedesign/housedesign_genba2.jpg',
	),
	array(
		'title' => '完成・お引き渡し',
		'text'  => '行政と施工会社の検査の後、お客様と一緒に竣工検査を行い、手直しが必要な箇所の改善を求めます。すべて完了した時点でお引き渡しです。',
		'img'   => 'housedesign_06_2.jpg',
		'pay'   => '設計監理料の 30%',
	),
	array(
		'title' => 'アフターサービス',
		'text'  => 'お引き渡し後も、10年間の住宅瑕疵担保に関するご相談や不具合の対応など、施工会社との間に立ってサポートします（有償の場合があります）。将来のリフォームのご相談もお受けします。',
	),
);

$rm_faq = array(
	array(
		'q' => '対応エリアはどこまでですか？',
		'a' => 'まずはご相談ください。日本国内であれば、対応できるよう弊社もご協力します。',
	),
	array(
		'q' => '打ち合わせはどこでしてもらえますか？',
		'a' => 'ご都合のよい場所で打ち合わせできます。ただし、初回のご面談・ヒアリングとファーストプランのご提案の際は、交通費の実費をご請求します。',
	),
	array(
		'q' => '無料のヒアリングでは、どんなことを聞かれますか？',
		'a' => 'どんな住まいにしたいかを伺います。例えば、建てたい時期、家族構成、必要な部屋（間取り・水まわり・階段の形）、建物の大きさ、外観・内装のイメージ、床などの仕上げ、その他の条件です。',
	),
	array(
		'q' => '用意しておくとよい資料はありますか？',
		'a' => '建てる場所に関する資料です。敷地の測量図、住所・地番、前面道路の幅、高低差、敷地や周辺の写真、既存の建物・崖・擁壁の資料、地盤データ、用途地域・防火地域などの建築条件がわかるものをお持ちください。揃っていなくても大丈夫です。',
	),
);
?>
<main class="Concept Remote">

	<!-- HERO -->
	<section class="Concept__hero Remote__hero" aria-labelledby="remote-hero-title">
		<?php if ( $rm_hero ) : ?>
			<img src="<?php echo esc_url( $rm_hero ); ?>" alt="ALOHA&amp;STYLEが設計した住まい" decoding="async">
		<?php endif; ?>
		<div class="Concept__hero-body">
			<span class="Concept__kicker">Anywhere in Japan</span>
			<h1 id="remote-hero-title" class="Concept__hero-title Remote__hero-title">県外でも、<br>ALOHA&amp;STYLE の家を。</h1>
			<p class="Concept__hero-lead">近くに建てられる会社がなくても、諦めなくて大丈夫です。</p>
		</div>
	</section>

	<!-- INTRO -->
	<section class="Remote__intro" aria-labelledby="remote-intro-heading">
		<div class="Concept__wrap Remote__narrow">
			<span class="Concept__kicker">For you, outside Okayama</span>
			<h2 id="remote-intro-heading" class="Concept__heading">岡山以外にお住まいの方へ</h2>
			<p class="Remote__lead">
				ハワイの住まいに憧れるけれど、近くにアロハな家を建てられる住宅会社がない。<br>
				そんな方のために、ALOHA&amp;STYLE が設計と工事のチェックを担当し、<br class="Remote__pc">
				お住まいの地域の施工会社と組んで家を建てるサービスをご用意しています。
			</p>
		</div>
	</section>

	<!-- HOW IT WORKS -->
	<section class="Remote__how" aria-labelledby="remote-how-heading">
		<div class="Concept__wrap">
			<span class="Concept__kicker">How it works</span>
			<h2 id="remote-how-heading" class="Concept__heading">しくみ</h2>
			<div class="Remote__equation">
				<div class="Remote__role Remote__role--us">
					<span class="Remote__role-label">ALOHA&amp;STYLE</span>
					<strong>設計・工事のチェック</strong>
					<ul>
						<li>ご要望のヒアリングとプランのご提案</li>
						<li>設計図面の作成</li>
						<li>施工会社選びと見積もりの比較・調整</li>
						<li>工事が設計どおりかを現場でチェック</li>
					</ul>
				</div>
				<span class="Remote__op" aria-hidden="true">+</span>
				<div class="Remote__role">
					<span class="Remote__role-label">地域の施工会社</span>
					<strong>工事</strong>
					<ul>
						<li>お住まいの地域で実際に家を建てる</li>
						<li>工事請負契約はお客様と施工会社で結ぶ</li>
					</ul>
				</div>
				<span class="Remote__op" aria-hidden="true">=</span>
				<div class="Remote__role Remote__role--result">
					<span class="Remote__role-label">Your home</span>
					<strong>あなたの街に、<br>ALOHA&amp;STYLE の家</strong>
				</div>
			</div>
		</div>
	</section>

	<!-- FREE -->
	<section class="Remote__free" aria-labelledby="remote-free-heading">
		<div class="Concept__wrap Remote__free-inner">
			<div>
				<span class="Concept__kicker">Free</span>
				<h2 id="remote-free-heading" class="Concept__heading">最初のプランまで、無料です。</h2>
				<p class="Remote__free-text">ご相談・ヒアリング・ファーストプランのご提案までは費用はかかりません。<br>プランを見てから、依頼するかどうかを決めていただけます。</p>
				<p class="Remote__note">※初回のご面談とファーストプランのご提案が遠方の場合、交通費の実費をご請求します。</p>
			</div>
			<ol class="Remote__free-steps">
				<li><b>01</b>ご相談</li>
				<li><b>02</b>ヒアリング</li>
				<li><b>03</b>ファーストプラン</li>
			</ol>
		</div>
	</section>

	<!-- FLOW -->
	<section class="Remote__flow" aria-labelledby="remote-flow-heading">
		<div class="Concept__wrap">
			<span class="Concept__kicker">Flow</span>
			<h2 id="remote-flow-heading" class="Concept__heading">家づくりの流れ</h2>
			<ol class="Remote__steps">
				<?php foreach ( $rm_steps as $i => $step ) : ?>
					<?php $step_img = empty( $step['img'] ) ? '' : aloha_theme_image( $step['img'] ); ?>
					<li class="Remote__step<?php echo $step_img ? ' has-photo' : ''; ?>">
						<span class="Remote__step-num"><?php echo esc_html( sprintf( '%02d', $i + 1 ) ); ?></span>
						<div class="Remote__step-body">
							<h3 class="Remote__step-title">
								<?php echo esc_html( $step['title'] ); ?>
								<?php if ( ! empty( $step['free'] ) ) : ?><span class="Remote__badge Remote__badge--free">無料</span><?php endif; ?>
							</h3>
							<p><?php echo esc_html( $step['text'] ); ?></p>
							<?php if ( ! empty( $step['pay'] ) ) : ?>
								<p class="Remote__pay">お支払い：<?php echo esc_html( $step['pay'] ); ?>（応相談）</p>
							<?php endif; ?>
						</div>
						<?php if ( $step_img ) : ?>
							<figure class="Remote__step-photo"><img src="<?php echo esc_url( $step_img ); ?>" alt="" loading="lazy" decoding="async"></figure>
						<?php endif; ?>
					</li>
				<?php endforeach; ?>
			</ol>
		</div>
	</section>

	<!-- PRICE -->
	<section class="Remote__price" aria-labelledby="remote-price-heading">
		<div class="Concept__wrap">
			<span class="Concept__kicker">Price</span>
			<h2 id="remote-price-heading" class="Concept__heading">費用</h2>
			<div class="Remote__price-card">
				<div class="Remote__price-main">
					<span class="Remote__price-label">設計・管理サービス費用</span>
					<p class="Remote__price-num">300<small>万円</small></p>
					<p class="Remote__note">戸建て住宅（60坪程度まで）の場合。大きな建物や集合・複合住宅はご相談ください。</p>
					<p class="Remote__price-split">お支払い：ご契約時 30% ／ 設計・申請完了時 40% ／ 完成時 30%（応相談）</p>
				</div>
				<div class="Remote__price-detail">
					<p class="Remote__price-sub">含まれるもの</p>
					<ul>
						<li>基本設計・実施設計（配置図・平面図・立面図・外観パース）</li>
						<li>当社基準での見積もり作成</li>
						<li>施工会社の選定とコスト調整（地元の施工会社に依頼する場合は見積書のチェック）</li>
						<li>設計図面作成時の面談 3回</li>
						<li>完成時の検査立ち会い 1回</li>
					</ul>
					<p class="Remote__note">工事期間中の現場チェック（施工管理）は、ご依頼がある場合に別途費用で行います。</p>
				</div>
			</div>
		</div>
	</section>

	<!-- FAQ -->
	<section class="Remote__faq" aria-labelledby="remote-faq-heading">
		<div class="Concept__wrap Remote__narrow">
			<span class="Concept__kicker">FAQ</span>
			<h2 id="remote-faq-heading" class="Concept__heading">よくある質問</h2>
			<div class="Remote__faq-list">
				<?php foreach ( $rm_faq as $item ) : ?>
					<details class="Remote__faq-item">
						<summary><span aria-hidden="true">Q</span><?php echo esc_html( $item['q'] ); ?></summary>
						<p><?php echo esc_html( $item['a'] ); ?></p>
					</details>
				<?php endforeach; ?>
			</div>
		</div>
	</section>

	<!-- NEXT / CTA -->
	<section class="Concept__next" aria-labelledby="remote-next-heading">
		<div class="Concept__wrap">
			<span class="Concept__kicker">Next</span>
			<h2 id="remote-next-heading" class="Concept__heading">もっと知る</h2>
			<ul class="Concept__next-grid">
				<li><a href="<?php echo esc_url( home_url( '/cases/' ) ); ?>"><strong>施工事例</strong><span>ALOHA&amp;STYLE が手がけた住まい</span><em>Works &rarr;</em></a></li>
				<li><a href="<?php echo esc_url( home_url( '/plan/' ) ); ?>"><strong>住宅プラン</strong><span>5つのスタイルから選べるプラン集</span><em>Plan &rarr;</em></a></li>
				<li><a href="<?php echo esc_url( home_url( '/concept/' ) ); ?>"><strong>コンセプト</strong><span>私たちの家づくりの考え方</span><em>Concept &rarr;</em></a></li>
			</ul>
			<div class="Concept__cta">
				<div>
					<p class="Concept__cta-title">まずは、お気軽にご相談ください。</p>
					<p class="Concept__cta-text">遠方の方は、オンラインでのご相談もできます。</p>
				</div>
				<div class="Concept__cta-actions">
					<a href="<?php echo esc_url( home_url( '/contact/' ) ); ?>" class="Concept__btn">お問い合わせ &rarr;</a>
					<a href="<?php echo esc_url( $rm_consult_url ); ?>" class="Concept__btn--outline" target="_blank" rel="noopener">オンライン相談を予約</a>
				</div>
			</div>
		</div>
	</section>

</main>
<?php
get_footer();
