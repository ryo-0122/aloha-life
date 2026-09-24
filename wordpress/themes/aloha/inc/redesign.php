<?php
/**
 * トップページ（front-page.php）・施工例詳細（single-cases.php）リニューアル用の読み込みと共通関数。
 *
 * functions.php の末尾で読み込む:
 *   require_once get_template_directory() . '/inc/redesign.php';
 *
 * CSS は styles.css とは別ファイル（assets/css/home.css / case-detail.css）にして、
 * 該当ページでのみ読み込む。styles.css には Sass に無い直接編集が含まれているため、
 * styles.scss を再コンパイルせずに済むようにしている。
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * 該当ページでのみ CSS / JS / フォントを読み込む。
 * wp_head() は header.php の styles.css・style-ms.css より後に出力されるため、既存の指定を上書きできる。
 */
function aloha_redesign_enqueue() {
	$is_home = is_front_page();
	$is_case = is_singular( 'cases' );
	if ( ! $is_home && ! $is_case ) {
		return;
	}

	// 欧文見出し用。読み込めない場合も游ゴシック等で表示される（外部フォントは必須ではない）。
	wp_enqueue_style(
		'aloha-redesign-fonts',
		'https://fonts.googleapis.com/css2?family=Archivo:wght@500;700;900&family=Zen+Kaku+Gothic+New:wght@400;500;700;900&display=swap',
		array(),
		null
	);

	$files = array();
	if ( $is_home ) {
		$files['aloha-home'] = 'assets/css/home.css';
	}
	if ( $is_case ) {
		$files['aloha-case-detail'] = 'assets/css/case-detail.css';
		wp_enqueue_script( 'aloha-case-detail', get_template_directory_uri() . '/assets/js/case-detail.js', array(), aloha_asset_version( 'assets/js/case-detail.js' ), true );
	}
	foreach ( $files as $handle => $path ) {
		wp_enqueue_style( $handle, get_template_directory_uri() . '/' . $path, array(), aloha_asset_version( $path ) );
	}
}
add_action( 'wp_enqueue_scripts', 'aloha_redesign_enqueue' );

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
			$groups[ $taxonomy ] = $terms;
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
