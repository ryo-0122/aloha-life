<?php
/**
 * トップページ（front-page.php）・施工事例詳細（single-cases.php）リニューアル用の設定と関数。
 *
 * functions.php の末尾に次の1行を追加して読み込む:
 *   require_once get_theme_file_path( 'inc/redesign.php' );
 *
 * 既存テーマに合わせて変更が必要になりやすい値は、このファイル上部の定数・関数にまとめている。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/** 施工事例のカスタム投稿タイプ名（single-cases.php に対応） */
if ( ! defined( 'ALOHA_CASES_POST_TYPE' ) ) {
	define( 'ALOHA_CASES_POST_TYPE', 'cases' );
}

/**
 * 施工事例の「建物概要」表の項目。
 * 表示ラベル => カスタムフィールド名（ACF のフィールド名、または投稿メタのキー）。
 * 既存サイトのフィールド名に合わせて右側を書き換えること。
 */
function aloha_case_overview_fields() {
	return apply_filters(
		'aloha_case_overview_fields',
		array(
			'建築場所'   => 'location',
			'主要用途'   => 'usage',
			'工事種別'   => 'construction_type',
			'用途地域'   => 'zoning',
			'容積率'    => 'floor_area_ratio',
			'建ぺい率'   => 'building_coverage',
			'前面道路'   => 'front_road',
			'敷地面積'   => 'site_area',
			'建物構造'   => 'structure',
			'階数'     => 'floors',
			'1階床面積'  => 'floor_area_1f',
			'2階床面積'  => 'floor_area_2f',
			'延床面積'   => 'total_floor_area',
			'施工面積'   => 'construction_area',
			'家族構成'   => 'family',
		)
	);
}

/** 施工事例のギャラリー画像に使う ACF ギャラリーフィールド名 */
if ( ! defined( 'ALOHA_CASE_GALLERY_FIELD' ) ) {
	define( 'ALOHA_CASE_GALLERY_FIELD', 'gallery' );
}

/**
 * トップページ・施工事例詳細でのみ CSS / JS / フォントを読み込む。
 */
function aloha_redesign_enqueue() {
	$is_top  = is_front_page();
	$is_case = is_singular( ALOHA_CASES_POST_TYPE );
	if ( ! $is_top && ! $is_case ) {
		return;
	}

	wp_enqueue_style(
		'aloha-redesign-fonts',
		'https://fonts.googleapis.com/css2?family=Archivo:wght@500;700;900&family=Zen+Kaku+Gothic+New:wght@400;500;700;900&display=swap',
		array(),
		null
	);

	if ( $is_top ) {
		$path = 'assets/css/top.css';
		wp_enqueue_style( 'aloha-top', get_theme_file_uri( $path ), array( 'aloha-redesign-fonts' ), filemtime( get_theme_file_path( $path ) ) );
	}

	if ( $is_case ) {
		$path = 'assets/css/case-detail.css';
		wp_enqueue_style( 'aloha-case-detail', get_theme_file_uri( $path ), array( 'aloha-redesign-fonts' ), filemtime( get_theme_file_path( $path ) ) );

		$js = 'assets/js/case-detail.js';
		wp_enqueue_script( 'aloha-case-detail', get_theme_file_uri( $js ), array(), filemtime( get_theme_file_path( $js ) ), true );
	}
}
add_action( 'wp_enqueue_scripts', 'aloha_redesign_enqueue', 20 );

/** Google Fonts への preconnect */
function aloha_redesign_resource_hints( $urls, $relation_type ) {
	if ( 'preconnect' === $relation_type && ( is_front_page() || is_singular( ALOHA_CASES_POST_TYPE ) ) ) {
		$urls[] = 'https://fonts.googleapis.com';
		$urls[] = array(
			'href'        => 'https://fonts.gstatic.com',
			'crossorigin' => 'anonymous',
		);
	}
	return $urls;
}
add_filter( 'wp_resource_hints', 'aloha_redesign_resource_hints', 10, 2 );

/**
 * テーマ内画像（assets/images/top/ 配下）の URL を返す。
 * ファイルが未配置の場合は空文字を返し、テンプレート側でプレースホルダーを表示する。
 */
function aloha_top_image_url( $filename ) {
	$rel = 'assets/images/top/' . $filename;
	return file_exists( get_theme_file_path( $rel ) ) ? get_theme_file_uri( $rel ) : '';
}

/**
 * <img> またはプレースホルダーを出力する。
 */
function aloha_image_or_placeholder( $url, $alt, $placeholder_class, $loading = 'lazy' ) {
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
 * カスタムフィールドの値を取得（ACF があれば get_field、なければ投稿メタ）。
 */
function aloha_get_field( $name, $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	if ( function_exists( 'get_field' ) ) {
		$value = get_field( $name, $post_id );
	} else {
		$value = get_post_meta( $post_id, $name, true );
	}
	return $value;
}

/**
 * 施工事例に紐づくタクソノミーを「ラベル => タームの配列」で返す（タームのあるものだけ）。
 */
function aloha_case_term_groups( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$groups  = array();
	foreach ( get_object_taxonomies( get_post_type( $post_id ), 'objects' ) as $tax ) {
		if ( ! $tax->public ) {
			continue;
		}
		$terms = get_the_terms( $post_id, $tax->name );
		if ( $terms && ! is_wp_error( $terms ) ) {
			$groups[ $tax->name ] = array(
				'label' => $tax->labels->singular_name ? $tax->labels->singular_name : $tax->label,
				'terms' => $terms,
			);
		}
	}
	return $groups;
}

/**
 * 施工事例カード用の「#タグ #タグ」文字列。
 */
function aloha_case_hashtags( $post_id = null ) {
	$names = array();
	foreach ( aloha_case_term_groups( $post_id ) as $group ) {
		foreach ( $group['terms'] as $term ) {
			$names[] = '#' . $term->name;
		}
	}
	return implode( ' ', array_unique( $names ) );
}

/**
 * 施工事例ギャラリーの画像 ID 配列。
 * 優先順: ACF ギャラリーフィールド → アイキャッチ + 投稿に添付された画像。
 */
function aloha_case_gallery_ids( $post_id = null ) {
	$post_id = $post_id ? $post_id : get_the_ID();
	$ids     = array();

	$gallery = aloha_get_field( ALOHA_CASE_GALLERY_FIELD, $post_id );
	if ( is_array( $gallery ) ) {
		foreach ( $gallery as $item ) {
			if ( is_array( $item ) && isset( $item['ID'] ) ) {
				$ids[] = (int) $item['ID'];
			} elseif ( is_numeric( $item ) ) {
				$ids[] = (int) $item;
			}
		}
	}

	if ( ! $ids ) {
		if ( has_post_thumbnail( $post_id ) ) {
			$ids[] = (int) get_post_thumbnail_id( $post_id );
		}
		foreach ( get_attached_media( 'image', $post_id ) as $attachment ) {
			$ids[] = (int) $attachment->ID;
		}
	}

	return array_values( array_unique( array_filter( $ids ) ) );
}
