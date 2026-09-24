<?php
/**
 * 住宅プランのカテゴリ再編（1回だけ実行する移行スクリプト）
 *
 * 使い方（Local の「Open site shell」で、このファイルを置いたフォルダから）:
 *   お試し（何も変更しない）: wp eval-file migrate-plan-categories.php
 *   本番実行             : wp eval-file migrate-plan-categories.php apply
 *
 * 実行内容:
 *   1. カテゴリ名とURLの変更
 *        HAWAIIAN HOUSE (hawaiian_house) → SURFER'S HOUSE (surfers_house)
 *        STYLISH MODERN (stylish_modern) → MID-CENTURY MODERN (midcentury_modern)
 *   2. SMART MODERN のプランを MID-CENTURY MODERN に移し、SMART MODERN カテゴリを削除
 *   3. RENOVATION (renovation)・APARTMENT (apartment) カテゴリを作成（プラン0件＝サイトでは「準備中」表示）
 *   4. プラン名の変更（URL＝スラッグは変えない）
 *        HAWAIIAN HOUSE No.N → SURFER'S HOUSE No.N
 *        SMART MODERN No.N   → MID-CENTURY MODERN No.N（No.1〜5）
 *        STYLISH MODERN No.N → MID-CENTURY MODERN No.(N+5)（No.6〜10）
 *      ※一覧は古い順に並ぶため、先に作られた SMART MODERN を No.1〜5 にしている
 *
 * 何度実行しても同じ結果になるよう、実施済みの項目は飛ばす。
 */

if ( ! defined( 'WP_CLI' ) ) {
	exit;
}

$apply = isset( $args[0] ) && 'apply' === $args[0];
$tax   = 'plan_category';

WP_CLI::log( $apply ? '=== 本番実行（データを変更します） ===' : '=== お試し表示（データは変更しません。実行するには末尾に apply を付けてください） ===' );

if ( ! taxonomy_exists( $tax ) ) {
	WP_CLI::error( 'plan_category タクソノミーがありません。テーマが有効か確認してください。' );
}

// ---------------------------------------------------------------
// 1. カテゴリ名とURLの変更
// ---------------------------------------------------------------
$renames = array(
	'hawaiian_house' => array( 'name' => "SURFER'S HOUSE", 'slug' => 'surfers_house' ),
	'stylish_modern' => array( 'name' => 'MID-CENTURY MODERN', 'slug' => 'midcentury_modern' ),
);
foreach ( $renames as $old_slug => $new ) {
	$term = get_term_by( 'slug', $old_slug, $tax );
	if ( ! $term ) {
		$done = get_term_by( 'slug', $new['slug'], $tax );
		WP_CLI::log( $done ? "[済] {$new['name']} は変更済み" : "[注意] {$old_slug} が見つかりません" );
		continue;
	}
	WP_CLI::log( "[カテゴリ名変更] {$term->name} ({$old_slug}) → {$new['name']} ({$new['slug']})" );
	if ( $apply ) {
		$result = wp_update_term( $term->term_id, $tax, $new );
		if ( is_wp_error( $result ) ) {
			WP_CLI::error( $result->get_error_message() );
		}
	}
}

// MID-CENTURY MODERN のターム（お試し時はまだ旧スラッグ）
$mid = get_term_by( 'slug', 'midcentury_modern', $tax );
if ( ! $mid ) {
	$mid = get_term_by( 'slug', 'stylish_modern', $tax );
}
if ( ! $mid ) {
	WP_CLI::error( 'MID-CENTURY MODERN（旧 STYLISH MODERN）のカテゴリが見つかりません。' );
}

// ---------------------------------------------------------------
// 2. SMART MODERN のプランを MID-CENTURY MODERN へ移動し、カテゴリを削除
// ---------------------------------------------------------------
$smart = get_term_by( 'slug', 'smart_modern', $tax );
if ( $smart ) {
	$smart_posts = get_posts(
		array(
			'post_type'   => 'plan',
			'post_status' => 'any',
			'numberposts' => -1,
			'tax_query'   => array( // phpcs:ignore WordPress.DB.SlowDBQuery.slow_db_query_tax_query
				array(
					'taxonomy' => $tax,
					'field'    => 'term_id',
					'terms'    => $smart->term_id,
				),
			),
		)
	);
	foreach ( $smart_posts as $p ) {
		WP_CLI::log( "[カテゴリ移動] #{$p->ID} {$p->post_title}: SMART MODERN → MID-CENTURY MODERN" );
		if ( $apply ) {
			wp_remove_object_terms( $p->ID, (int) $smart->term_id, $tax );
			wp_add_object_terms( $p->ID, (int) $mid->term_id, $tax );
		}
	}
	WP_CLI::log( '[カテゴリ削除] SMART MODERN (smart_modern)' );
	if ( $apply ) {
		wp_delete_term( $smart->term_id, $tax );
	}
} else {
	WP_CLI::log( '[済] SMART MODERN は削除済み' );
}

// ---------------------------------------------------------------
// 3. RENOVATION・APARTMENT を作成
// ---------------------------------------------------------------
foreach ( array( 'renovation' => 'RENOVATION', 'apartment' => 'APARTMENT' ) as $slug => $name ) {
	if ( get_term_by( 'slug', $slug, $tax ) ) {
		WP_CLI::log( "[済] {$name} は作成済み" );
		continue;
	}
	WP_CLI::log( "[カテゴリ作成] {$name} ({$slug})" );
	if ( $apply ) {
		$result = wp_insert_term( $name, $tax, array( 'slug' => $slug ) );
		if ( is_wp_error( $result ) ) {
			WP_CLI::error( $result->get_error_message() );
		}
	}
}

// ---------------------------------------------------------------
// 4. プラン名の変更（スラッグ＝URLはそのまま）
// ---------------------------------------------------------------
$title_rules = array(
	'HAWAIIAN HOUSE' => array( "SURFER'S HOUSE", 0 ),
	'SMART MODERN'   => array( 'MID-CENTURY MODERN', 0 ),
	'STYLISH MODERN' => array( 'MID-CENTURY MODERN', 5 ),
);
$plans = get_posts(
	array(
		'post_type'   => 'plan',
		'post_status' => 'any',
		'numberposts' => -1,
		'orderby'     => 'ID',
		'order'       => 'ASC',
	)
);
foreach ( $plans as $p ) {
	if ( ! preg_match( '/^(HAWAIIAN HOUSE|SMART MODERN|STYLISH MODERN)\s*No\.\s*(\d+)$/u', trim( $p->post_title ), $m ) ) {
		continue;
	}
	list( $new_name, $offset ) = $title_rules[ $m[1] ];
	$new_title                 = sprintf( '%s No.%d', $new_name, (int) $m[2] + $offset );
	WP_CLI::log( "[プラン名変更] #{$p->ID} {$p->post_title} → {$new_title}（URL: /plan/{$p->post_name}/ はそのまま）" );
	if ( $apply ) {
		wp_update_post(
			array(
				'ID'         => $p->ID,
				'post_title' => $new_title,
				'post_name'  => $p->post_name, // スラッグを明示して URL を固定
			)
		);
	}
}

WP_CLI::success( $apply ? '完了しました。' : 'お試し表示が終わりました。内容に問題がなければ apply を付けて実行してください。' );
