<?php
/**
 * 施工例のスタイル（cases_category）再編（本番データを Local に取り込んだ後に1回だけ実行する移行スクリプト）
 *
 * 使い方（Local の「Open site shell」で、このファイルを置いたフォルダから）:
 *   お試し（何も変更しない）: wp eval-file migrate-case-categories.php
 *   本番実行             : wp eval-file migrate-case-categories.php apply
 *
 * 実行内容:
 *   1. スタイルを住宅プランと同じ並びに整理（＋店舗・事業用）
 *        サーファーズハウス       ← サーファーズハウス / ハワイアンハウス
 *        リゾートモダン           ← リゾートモダン
 *        ミッドセンチュリーモダン ← スタイリッシュモダン（URL: stylish → midcentury）
 *        リノベーション           ← リノベーション
 *        アパート                 ← アパート
 *        店舗・事業用（business） ← 店舗付き住宅 / 工場 / 製造業 / 商業建築
 *   2. 「ハワイ」「ALOHA」はスタイルから外し、特徴「ハワイアンスタイル」に移す
 *   3. タイトルと合っていない施工例のスタイルを修正
 *        7826 サーファーズハウス店舗併用住宅      → サーファーズハウスを追加
 *        9643 ミッドセンチュリーデザインのヘアサロン → リゾートモダンをミッドセンチュリーモダンに
 *        4710 "ミッドセンチュリーハウス"           → ミッドセンチュリーモダンに
 *        4733 平屋のプランテーションハウス         → リゾートモダンをサーファーズハウスに
 *   4. 使わなくなったスタイル（ハワイアンハウス・店舗付き住宅など）を削除
 *      ※旧URLはテーマ側（inc/redesign.php）で新しいURLへ転送する
 *
 * 何度実行しても同じ結果になる。想定外のスタイルは変更せずに残し、[注意] として表示する。
 */

if ( ! defined( 'WP_CLI' ) ) {
	exit;
}

$apply = isset( $args[0] ) && 'apply' === $args[0];
$tax   = 'cases_category';

WP_CLI::log( $apply ? '=== 本番実行（データを変更します） ===' : '=== お試し表示（データは変更しません。実行するには末尾に apply を付けてください） ===' );

foreach ( array( $tax, 'feature' ) as $required ) {
	if ( ! taxonomy_exists( $required ) ) {
		WP_CLI::error( "{$required} タクソノミーがありません。テーマが有効か確認してください。" );
	}
}

// 整理後のスタイル（並び順もこの通り）
$styles = array(
	'surfershouse' => 'サーファーズハウス',
	'resort'       => 'リゾートモダン',
	'midcentury'   => 'ミッドセンチュリーモダン',
	'renovation'   => 'リノベーション',
	'apart'        => 'アパート',
	'business'     => '店舗・事業用',
);

// 旧スラッグ → 新スラッグ（null はスタイルから外して特徴「ハワイアンスタイル」へ）
$map = array(
	'hawaiian'      => 'surfershouse',
	'stylish'       => 'midcentury',
	'shop'          => 'business',
	'industry'      => 'business',
	'manufacturing' => 'business',
	'commercial'    => 'business',
	'hawaii'        => null,
	'aloha'         => null,
);
foreach ( array_keys( $styles ) as $slug ) {
	$map[ $slug ] = $slug;
}

// タイトルに合わせた個別修正（タイトルに含まれる語で本人確認してから適用）
$fixes = array(
	7826 => array( 'check' => 'サーファーズハウス', 'remove' => array(), 'add' => array( 'surfershouse' ) ),
	9643 => array( 'check' => 'ミッドセンチュリー', 'remove' => array( 'resort' ), 'add' => array( 'midcentury' ) ),
	4710 => array( 'check' => 'ミッドセンチュリー', 'remove' => array( 'surfershouse' ), 'add' => array( 'midcentury' ) ),
	4733 => array( 'check' => 'プランテーション', 'remove' => array( 'resort' ), 'add' => array( 'surfershouse' ) ),
);

$feature_name = 'ハワイアンスタイル';

// ---------------------------------------------------------------
// 1. 整理後のスタイルを用意（スタイリッシュモダンは名前とURLを変更）
// ---------------------------------------------------------------
$style_ids       = array();
$stylish_renamed = false;
foreach ( $styles as $slug => $name ) {
	$term = get_term_by( 'slug', $slug, $tax );
	if ( ! $term && 'midcentury' === $slug ) {
		$old = get_term_by( 'slug', 'stylish', $tax );
		if ( $old ) {
			WP_CLI::log( "[スタイル名変更] {$old->name} (stylish) → {$name} ({$slug})" );
			if ( $apply ) {
				$result = wp_update_term( $old->term_id, $tax, array( 'name' => $name, 'slug' => $slug ) );
				if ( is_wp_error( $result ) ) {
					WP_CLI::error( $result->get_error_message() );
				}
			}
			$style_ids[ $slug ] = (int) $old->term_id;
			$stylish_renamed    = true;
			continue;
		}
	}
	if ( ! $term ) {
		WP_CLI::log( "[スタイル作成] {$name} ({$slug})" );
		if ( $apply ) {
			$result = wp_insert_term( $name, $tax, array( 'slug' => $slug ) );
			if ( is_wp_error( $result ) ) {
				WP_CLI::error( $result->get_error_message() );
			}
			$style_ids[ $slug ] = (int) $result['term_id'];
		} else {
			$style_ids[ $slug ] = 0;
		}
		continue;
	}
	if ( $term->name !== $name ) {
		WP_CLI::log( "[スタイル名変更] {$term->name} → {$name} ({$slug})" );
		if ( $apply ) {
			wp_update_term( $term->term_id, $tax, array( 'name' => $name ) );
		}
	}
	$style_ids[ $slug ] = (int) $term->term_id;
}

// 特徴「ハワイアンスタイル」
$feature = get_term_by( 'name', $feature_name, 'feature' );
if ( ! $feature ) {
	WP_CLI::log( "[特徴作成] {$feature_name} (hawaiian-style)" );
	if ( $apply ) {
		$result = wp_insert_term( $feature_name, 'feature', array( 'slug' => 'hawaiian-style' ) );
		if ( is_wp_error( $result ) ) {
			WP_CLI::error( $result->get_error_message() );
		}
		$feature_id = (int) $result['term_id'];
	} else {
		$feature_id = 0;
	}
} else {
	$feature_id = (int) $feature->term_id;
}

// ---------------------------------------------------------------
// 2. 施工例ごとのスタイルを付け直す
// ---------------------------------------------------------------
$posts   = get_posts(
	array(
		'post_type'      => 'cases',
		'post_status'    => 'any',
		'posts_per_page' => -1,
		'orderby'        => 'ID',
		'order'          => 'ASC',
	)
);
$unknown = array();
$changed = 0;

foreach ( $posts as $post ) {
	$current = wp_get_object_terms( $post->ID, $tax );
	if ( is_wp_error( $current ) ) {
		$current = array();
	}
	$before      = wp_list_pluck( $current, 'slug' );
	if ( $stylish_renamed ) { // お試し時: 名前変更で midcentury になる分は変更なしとして扱う
		$before = str_replace( 'stylish', 'midcentury', $before );
		foreach ( $current as $term ) {
			if ( 'stylish' === $term->slug ) {
				$term->slug = 'midcentury';
			}
		}
	}
	$after       = array();
	$keep_ids    = array(); // 想定外のスタイル（そのまま残す）
	$add_feature = false;

	foreach ( $current as $term ) {
		if ( ! array_key_exists( $term->slug, $map ) ) {
			$unknown[ $term->slug ] = $term->name;
			$keep_ids[]             = (int) $term->term_id;
			$after[]                = $term->slug;
			continue;
		}
		if ( null === $map[ $term->slug ] ) {
			$add_feature = true;
			continue;
		}
		$after[] = $map[ $term->slug ];
	}

	if ( isset( $fixes[ $post->ID ] ) ) {
		$fix = $fixes[ $post->ID ];
		if ( false === mb_strpos( $post->post_title, $fix['check'] ) ) {
			WP_CLI::log( "[注意] ID {$post->ID} のタイトルに「{$fix['check']}」が無いため個別修正を飛ばします: {$post->post_title}" );
		} else {
			$after = array_merge( array_diff( $after, $fix['remove'] ), $fix['add'] );
		}
	}

	$after = array_values( array_unique( $after ) );

	$has_feature = $feature_id && has_term( $feature_id, 'feature', $post->ID );
	$need_style  = (bool) ( array_diff( $before, $after ) || array_diff( $after, $before ) );
	$need_feat   = $add_feature && ! $has_feature;
	if ( ! $need_style && ! $need_feat ) {
		continue;
	}

	$changed++;
	$label = function ( $slugs ) use ( $styles, $current ) {
		$names = array();
		foreach ( $slugs as $slug ) {
			if ( isset( $styles[ $slug ] ) ) {
				$names[] = $styles[ $slug ];
				continue;
			}
			foreach ( $current as $term ) {
				if ( $term->slug === $slug ) {
					$names[] = $term->name;
				}
			}
		}
		return $names ? implode( '・', $names ) : '（なし）';
	};
	WP_CLI::log( sprintf( '[施工例] ID %d %s', $post->ID, mb_strimwidth( $post->post_title, 0, 60, '…' ) ) );
	if ( $need_style ) {
		WP_CLI::log( '         スタイル: ' . $label( $before ) . ' → ' . $label( $after ) );
	}
	if ( $need_feat ) {
		WP_CLI::log( "         特徴に「{$feature_name}」を追加" );
	}

	if ( $apply ) {
		if ( $need_style ) {
			$ids = $keep_ids;
			foreach ( $after as $slug ) {
				if ( isset( $style_ids[ $slug ] ) ) {
					$ids[] = $style_ids[ $slug ];
				}
			}
			$result = wp_set_object_terms( $post->ID, array_values( array_unique( array_map( 'intval', $ids ) ) ), $tax );
			if ( is_wp_error( $result ) ) {
				WP_CLI::error( $result->get_error_message() );
			}
		}
		if ( $need_feat ) {
			wp_add_object_terms( $post->ID, $feature_id, 'feature' );
		}
	}
}
WP_CLI::log( $changed ? "施工例 {$changed} 件のスタイル・特徴を変更" : '[済] 施工例のスタイルは整理済み' );

// ---------------------------------------------------------------
// 3. 使わなくなったスタイルを削除
// ---------------------------------------------------------------
foreach ( $map as $old_slug => $new_slug ) {
	if ( $old_slug === $new_slug || ( 'stylish' === $old_slug && $stylish_renamed ) ) { // 名前変更で midcentury になったものは消さない
		continue;
	}
	$term = get_term_by( 'slug', $old_slug, $tax );
	if ( ! $term ) {
		continue;
	}
	WP_CLI::log( "[スタイル削除] {$term->name} ({$old_slug})" );
	if ( $apply ) {
		wp_delete_term( $term->term_id, $tax );
	}
}

foreach ( $unknown as $slug => $name ) {
	WP_CLI::log( "[注意] 想定外のスタイル「{$name}」({$slug}) はそのまま残しました。必要なら管理画面で整理してください。" );
}

if ( $apply ) {
	WP_CLI::success( '完了しました。「設定 → パーマリンク」で「変更を保存」を1回押してください。' );
} else {
	WP_CLI::log( '--- 上記の内容でよければ、末尾に apply を付けて実行してください ---' );
}
