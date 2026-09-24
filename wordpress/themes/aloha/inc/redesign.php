<?php
/**
 * リニューアル（トップページ・施工例詳細・ヘッダー/フッター）用の読み込みと共通関数。
 *
 * functions.php の末尾で読み込む:
 *   require_once get_template_directory() . '/inc/redesign.php';
 *
 * CSS は styles.css とは別ファイル（site-chrome.css / home.css / cases.css）にして読み込む。styles.css には Sass に無い直接編集が含まれているため、
 * styles.scss を再コンパイルせずに済むようにしている。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * CSS / JS / フォントを読み込む。
 * wp_head() は header.php の styles.css・style-ms.css より後に出力されるため、既存の指定を上書きできる。
 *
 * - 全ページ: ヘッダー・フッター（site-chrome.css）と欧文フォント Archivo
 * - トップページ: home.css
 * - コンセプト（/concept/）: concept.css
 * - 施工例（詳細・一覧・スタイル/特徴/ライフスタイル別一覧）: cases.css、詳細のみ case-detail.js
 */
function aloha_redesign_enqueue() {
	$is_home    = is_front_page();
	$is_case    = aloha_is_cases_page();
	$is_concept = is_page( 'concept' );

	// 欧文見出し用。読み込めない場合も游ゴシック等で表示される（外部フォントは必須ではない）。
	// 本文用の Zen Kaku Gothic New はリニューアルしたページだけで読み込む。
	$font_url = ( $is_home || $is_case || $is_concept )
		? 'https://fonts.googleapis.com/css2?family=Archivo:wght@500;700;900&family=Zen+Kaku+Gothic+New:wght@400;500;700;900&display=swap'
		: 'https://fonts.googleapis.com/css2?family=Archivo:wght@500;700&display=swap';
	wp_enqueue_style( 'aloha-redesign-fonts', $font_url, array(), null );

	$files = array( 'aloha-site-chrome' => 'assets/css/site-chrome.css' );
	if ( $is_home ) {
		$files['aloha-home'] = 'assets/css/home.css';
	}
	if ( $is_concept ) {
		$files['aloha-concept'] = 'assets/css/concept.css';
	}
	if ( $is_case ) {
		$files['aloha-cases'] = 'assets/css/cases.css';
	}
	if ( is_singular( 'cases' ) ) {
		wp_enqueue_script( 'aloha-case-detail', get_template_directory_uri() . '/assets/js/case-detail.js', array(), aloha_asset_version( 'assets/js/case-detail.js' ), true );
	}
	foreach ( $files as $handle => $path ) {
		wp_enqueue_style( $handle, get_template_directory_uri() . '/' . $path, array(), aloha_asset_version( $path ) );
	}
}
add_action( 'wp_enqueue_scripts', 'aloha_redesign_enqueue' );

/**
 * 施工例の詳細・一覧・タクソノミー別一覧のページかどうか。
 */
function aloha_is_cases_page() {
	return is_singular( 'cases' ) || is_post_type_archive( 'cases' ) || is_tax( array_keys( aloha_case_taxonomies() ) ) || is_page_template( 'page-cases.php' );
}

require_once __DIR__ . '/cases-list.php';

/**
 * ファイル更新日時をバージョンにして、ブラウザキャッシュで古いCSSが残らないようにする。
 */
function aloha_asset_version( $path ) {
	$file = get_template_directory() . '/' . $path;
	return file_exists( $file ) ? (string) filemtime( $file ) : null;
}

/**
 * assets/images/ 配下の画像URLを返す。候補を先頭から探し、最初に存在したものを使う。
 * どれも無ければ空文字（テンプレート側でプレースホルダーを表示）。
 *
 * @param string|string[] $candidates assets/images/ からの相対パス。
 */
function aloha_theme_image( $candidates ) {
	foreach ( (array) $candidates as $rel ) {
		if ( file_exists( get_template_directory() . '/assets/images/' . $rel ) ) {
			return get_template_directory_uri() . '/assets/images/' . $rel;
		}
	}
	return '';
}

/**
 * <img> または画像が無いときのプレースホルダーを出力する。
 */
function aloha_image_tag( $url, $alt, $placeholder_class, $loading = 'lazy' ) {
	if ( $url ) {
		printf(
			'<img src="%s" alt="%s" loading="%s" decoding="async">',
			esc_url( $url ),
			esc_attr( $alt ),
			esc_attr( $loading )
		);
	} else {
		printf( '<span class="%s" role="img" aria-label="%s"></span>', esc_attr( $placeholder_class ), esc_attr( $alt ) );
	}
}

/**
 * ACF の値を取得（ACF が無効でも投稿メタから取得できるようにする）。
 */
function aloha_field( $name, $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	return function_exists( 'get_field' ) ? get_field( $name, $post_id ) : get_post_meta( $post_id, $name, true );
}

/**
 * ACF の画像フィールド（URL / 配列 / ID のどれでも）から URL を取り出す。
 */
function aloha_field_image_url( $name, $post_id = null ) {
	$value = aloha_field( $name, $post_id );
	if ( is_array( $value ) && ! empty( $value['url'] ) ) {
		return $value['url'];
	}
	if ( is_numeric( $value ) ) {
		$url = wp_get_attachment_image_url( (int) $value, 'large' );
		return $url ? $url : '';
	}
	return is_string( $value ) ? $value : '';
}

/**
 * 施工例のメイン写真（ACF cases-main-pic）。無ければ既存の no_img.jpg。
 */
function aloha_case_main_pic( $post_id = null ) {
	$url = aloha_field_image_url( 'cases-main-pic', $post_id );
	return $url ? $url : aloha_theme_image( 'no_img.jpg' );
}

/**
 * 施工例のタクソノミー（スタイル / 特徴 / ライフスタイル）。single-cases.php の既存の分類と同じ。
 */
function aloha_case_taxonomies() {
	return array(
		'cases_category' => 'スタイル',
		'feature'        => '特徴',
		'life_style'     => 'ライフスタイル',
	);
}

/**
 * 施工例に付いているタームを タクソノミー => タームの配列 で返す（タームがあるものだけ）。
 */
function aloha_case_terms( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$groups  = array();
	foreach ( aloha_case_taxonomies() as $taxonomy => $label ) {
		if ( ! taxonomy_exists( $taxonomy ) ) {
			continue;
		}
		$terms = get_the_terms( $post_id, $taxonomy );
		if ( $terms && ! is_wp_error( $terms ) ) {
			$groups[ $taxonomy ] = 'cases_category' === $taxonomy ? aloha_sort_case_styles( $terms ) : $terms;
		}
	}
	return $groups;
}

/**
 * 施工例カード用の「#平屋 #家事ラク」形式の文字列。
 */
function aloha_case_hashtags( $post_id = null ) {
	$names = array();
	foreach ( aloha_case_terms( $post_id ) as $terms ) {
		foreach ( $terms as $term ) {
			$names[] = '#' . $term->name;
		}
	}
	return implode( ' ', array_unique( $names ) );
}

/**
 * NEWS & TOPICS のカテゴリ表示。投稿はカテゴリ名、それ以外は投稿タイプ名。
 */
function aloha_news_label( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	if ( 'post' === get_post_type( $post_id ) ) {
		$cats = get_the_category( $post_id );
		return $cats ? $cats[0]->name : '';
	}
	$type = get_post_type_object( get_post_type( $post_id ) );
	return $type ? $type->labels->singular_name : '';
}

/**
 * 住宅プランの5カテゴリ（plan_category）。
 * 管理画面でカテゴリが作成済みならその一覧の URL、未作成なら住宅プラン一覧の URL を返す。
 *
 * @return array<int, array{en: string, label: string, slug: string, url: string, count: int}>
 */
function aloha_plan_categories() {
	$fallback = get_post_type_archive_link( 'plan' ) ? get_post_type_archive_link( 'plan' ) : home_url( '/plan/' );
	$items    = array(
		array( 'en' => "SURFER'S HOUSE", 'label' => 'サーファーズハウス', 'slug' => 'surfers_house' ),
		array( 'en' => 'RESORT MODERN', 'label' => 'リゾートモダン', 'slug' => 'resort_modern' ),
		array( 'en' => 'MID-CENTURY MODERN', 'label' => 'ミッドセンチュリーモダン', 'slug' => 'midcentury_modern' ),
		array( 'en' => 'RENOVATION', 'label' => 'リノベーション', 'slug' => 'renovation' ),
		array( 'en' => 'APARTMENT', 'label' => 'アパート', 'slug' => 'apartment' ),
	);
	foreach ( $items as &$item ) {
		$term        = taxonomy_exists( 'plan_category' ) ? get_term_by( 'slug', $item['slug'], 'plan_category' ) : false;
		$link        = $term ? get_term_link( $term ) : $fallback;
		$item['url'] = is_wp_error( $link ) ? $fallback : $link;
		$item['count'] = $term ? (int) $term->count : 0;
	}
	unset( $item );
	return $items;
}

/**
 * グローバルナビ（header.php の PC・スマホ共通）。
 * cta: 'reserve'（見学予約）/ 'contact'（お問い合わせ）はボタン表示。
 */
function aloha_global_nav() {
	$plans = array();
	foreach ( aloha_plan_categories() as $plan ) {
		$plans[] = array(
			'label' => $plan['label'],
			'url'   => $plan['url'],
		);
	}
	$plan_url = get_post_type_archive_link( 'plan' ) ? get_post_type_archive_link( 'plan' ) : home_url( '/plan/' );
	array_unshift(
		$plans,
		array(
			'label' => '住宅プラン一覧',
			'url'   => $plan_url,
		)
	);

	return array(
		array(
			'label'    => 'コンセプト',
			'url'      => home_url( '/concept/' ),
			'children' => array(
				array( 'label' => 'コンセプト', 'url' => home_url( '/concept/' ) ),
				array( 'label' => 'ハワイアンドア', 'url' => home_url( '/hawaiian-door/' ) ),
				array( 'label' => '会社概要', 'url' => home_url( '/company/' ) ),
			),
		),
		array( 'label' => '施工事例', 'url' => home_url( '/cases/' ) ),
		array(
			'label'    => '住宅プラン',
			'url'      => $plan_url,
			'children' => $plans,
		),
		array( 'label' => 'モデルハウス', 'url' => home_url( '/modelhouse/' ) ),
		array( 'label' => '家づくりの流れ', 'url' => home_url( '/flow/' ) ),
		array( 'label' => '県外で建てる', 'url' => home_url( '/housedesign/' ) ),
		array( 'label' => 'ハワイ不動産', 'url' => home_url( '/hawaii/' ) ),
		array(
			'label'    => '見学予約',
			'url'      => 'https://www.ie-miru.jp/cms/yoyaku/seibukensetsu/events/4847',
			'cta'      => 'reserve',
			'external' => true,
		),
		array(
			'label' => 'お問い合わせ',
			'url'   => home_url( '/contact/' ),
			'cta'   => 'contact',
		),
	);
}

/**
 * 住宅プランのカテゴリごとの見出し（英語・日本語）と説明文。
 * taxonomy.php / single-plan.php / sidebar-plan.php で使用。
 */
function aloha_plan_category_meta( $slug ) {
	$meta = array(
		'surfers_house'     => array(
			'en'   => "SURFER'S HOUSE",
			'ja'   => 'サーファーズハウス',
			'lead' => 'ハワイの美しさと懐かしさを色濃く残すプランテーションハウス。それを日本で再現したALOHA&STYLEからの他に無いご提案。',
			'img'  => 'Plan_side_pic_01.jpg',
		),
		'resort_modern'     => array(
			'en'   => 'RESORT MODERN',
			'ja'   => 'リゾートモダン',
			'lead' => 'リゾート感溢れるデザイン、自由な間取りの空間。理想のご要望全てにお応えした高級住宅のご提案。',
			'img'  => 'Plan_side_pic_02.jpg',
		),
		'midcentury_modern' => array(
			'en'   => 'MID-CENTURY MODERN',
			'ja'   => 'ミッドセンチュリーモダン',
			'lead' => '1950〜60年代のアメリカで発展した、機能性と美しさを兼ね備えたデザイン。自然素材の温かみと都会的なスタイリッシュさが調和する住まい。',
			'img'  => 'Plan_side_pic_03.jpg',
		),
		'renovation'        => array(
			'en'   => 'RENOVATION',
			'ja'   => 'リノベーション',
			'lead' => '住まいとこれからの暮らしを詳しくお伺いし、過去の思い出と新しい生活をより楽しく変えていく空間づくり。',
			'img'  => 'top/contents-reform.jpg',
		),
		'apartment'         => array(
			'en'   => 'APARTMENT',
			'ja'   => 'アパート',
			'lead' => 'ALOHA&STYLEらしさを取り入れた集合住宅のプラン。',
			'img'  => '',
		),
	);
	return isset( $meta[ $slug ] ) ? $meta[ $slug ] : null;
}

/**
 * 住宅プランのカテゴリの旧URLを新URLへ転送する（カテゴリ名変更前のリンク・ブックマーク対策）。
 * hawaiian_house → surfers_house、stylish_modern・smart_modern → midcentury_modern
 */
function aloha_redirect_old_plan_categories() {
	if ( ! is_404() ) {
		return;
	}
	$map  = array(
		'hawaiian_house' => 'surfers_house',
		'stylish_modern' => 'midcentury_modern',
		'smart_modern'   => 'midcentury_modern',
	);
	$path = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	foreach ( $map as $old => $new ) {
		if ( preg_match( '#plan_category[/=]' . preg_quote( $old, '#' ) . '(/|$|&)#', $path ) ) {
			$term = get_term_by( 'slug', $new, 'plan_category' );
			$link = $term ? get_term_link( $term ) : '';
			if ( $link && ! is_wp_error( $link ) ) {
				wp_safe_redirect( $link, 301 );
				exit;
			}
		}
	}
}
add_action( 'template_redirect', 'aloha_redirect_old_plan_categories' );

/**
 * 施工例のタクソノミー（スタイル・特徴・ライフスタイル）を登録する。
 * 本番サイトと同じ URL（/cases/cases_category/◯◯/ など）になるようにしている。
 * すでにプラグイン等で登録済みの環境では何もしない。
 * ※登録後、初回だけ「設定 → パーマリンク」を開いて「変更を保存」を押す（URLの反映）。
 */
function aloha_register_case_taxonomies() {
	$taxonomies = array(
		'cases_category' => array( 'label' => 'スタイル', 'hierarchical' => true ),
		'feature'        => array( 'label' => '特徴', 'hierarchical' => false ),
		'life_style'     => array( 'label' => 'ライフスタイル', 'hierarchical' => false ),
	);
	foreach ( $taxonomies as $taxonomy => $args ) {
		if ( taxonomy_exists( $taxonomy ) ) {
			continue;
		}
		register_taxonomy(
			$taxonomy,
			'cases',
			array(
				'label'             => $args['label'],
				'labels'            => array(
					'name'          => $args['label'],
					'singular_name' => $args['label'],
				),
				'public'            => true,
				'hierarchical'      => $args['hierarchical'],
				'show_admin_column' => true,
				'show_in_rest'      => true,
				'rewrite'           => array( 'slug' => 'cases/' . $taxonomy, 'with_front' => false ),
			)
		);
	}
}
add_action( 'init', 'aloha_register_case_taxonomies', 20 );

/**
 * 施工例のスタイル（cases_category）。住宅プランと同じ並び＋店舗・事業用。
 * スラッグは本番サイトの URL（/cases/cases_category/◯◯/）に合わせている。
 * 整理は wordpress/scripts/migrate-case-categories.php で行う。
 *
 * @return array スラッグ => array( en, ja, desc )
 */
function aloha_case_styles() {
	return array(
		'surfershouse' => array(
			'en'   => "SURFER'S HOUSE",
			'ja'   => 'サーファーズハウス',
			'desc' => 'ハワイの美しさと懐かしさを色濃く残すプランテーションハウス。それを日本で再現したALOHA&STYLEならではの住まい。',
		),
		'resort'       => array(
			'en'   => 'RESORT MODERN',
			'ja'   => 'リゾートモダン',
			'desc' => 'リゾート感あふれるデザインと、自由な間取りの快適空間。施主様のご要望にお応えした注文住宅。',
		),
		'midcentury'   => array(
			'en'   => 'MID-CENTURY MODERN',
			'ja'   => 'ミッドセンチュリーモダン',
			'desc' => 'ヴィンテージ感のある素材と、都会的で開放感のあるデザイン。家族の暮らしを大切にまとめた住まい。',
		),
		'renovation'   => array(
			'en'   => 'RENOVATION',
			'ja'   => 'リノベーション',
			'desc' => '住まいの思い出を活かしながら、新しい暮らしをより楽しく変えていく空間づくり。',
		),
		'apart'        => array(
			'en'   => 'APARTMENT',
			'ja'   => 'アパート',
			'desc' => '入居者に選ばれる、ALOHA&STYLEらしいデザインの賃貸住宅。',
		),
		'business'     => array(
			'en'   => 'SHOP & BUSINESS',
			'ja'   => '店舗・事業用',
			'desc' => '店舗併用住宅、サロン、オフィス、工場など。訪れる人の気持ちを高める空間をご提案します。',
		),
	);
}

/**
 * スタイルのタームを aloha_case_styles() の順に並べる（一覧にないものは後ろ）。
 *
 * @param WP_Term[] $terms タームの配列。
 * @return WP_Term[]
 */
function aloha_sort_case_styles( $terms ) {
	$order = array_flip( array_keys( aloha_case_styles() ) );
	usort(
		$terms,
		function ( $a, $b ) use ( $order ) {
			$pa = isset( $order[ $a->slug ] ) ? $order[ $a->slug ] : 99;
			$pb = isset( $order[ $b->slug ] ) ? $order[ $b->slug ] : 99;
			return $pa - $pb;
		}
	);
	return $terms;
}

/**
 * 整理前のスタイル URL を新しい URL へ 301 転送する。
 *   /cases/cases_category/hawaiian/ → サーファーズハウス、stylish → ミッドセンチュリーモダン、
 *   shop / industry / manufacturing / commercial → 店舗・事業用、hawaii / aloha → 特徴「ハワイアンスタイル」
 *   旧テンプレートの /cases-category/?term_slug=◯◯ も同じ転送先へ。
 */
function aloha_case_style_redirect_target( $old_slug ) {
	$map = array(
		'hawaiian'      => 'surfershouse',
		'stylish'       => 'midcentury',
		'smart'         => 'midcentury',
		'shop'          => 'business',
		'industry'      => 'business',
		'manufacturing' => 'business',
		'commercial'    => 'business',
	);
	$term = false;
	if ( in_array( $old_slug, array( 'hawaii', 'aloha' ), true ) ) {
		$term = get_term_by( 'name', 'ハワイアンスタイル', 'feature' );
	} else {
		$slug = isset( $map[ $old_slug ] ) ? $map[ $old_slug ] : $old_slug;
		$term = get_term_by( 'slug', $slug, 'cases_category' );
	}
	$link = $term ? get_term_link( $term ) : '';
	return $link && ! is_wp_error( $link ) ? $link : '';
}

function aloha_redirect_old_case_styles() {
	if ( ! is_404() ) {
		return;
	}
	$path = isset( $_SERVER['REQUEST_URI'] ) ? wp_unslash( $_SERVER['REQUEST_URI'] ) : ''; // phpcs:ignore WordPress.Security.ValidatedSanitizedInput.InputNotSanitized
	if ( preg_match( '#cases_category[/=]([a-z0-9_-]+)#', $path, $m ) ) {
		$link = aloha_case_style_redirect_target( $m[1] );
		if ( $link ) {
			wp_safe_redirect( $link, 301 );
			exit;
		}
	}
}
add_action( 'template_redirect', 'aloha_redirect_old_case_styles' );
