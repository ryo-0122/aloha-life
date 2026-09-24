<?php
/*
Template Name: 施工事例
*/
/**
 * 旧「施工事例」固定ページ（/cases-category/?term_slug=◯◯ など）。
 * 施工例一覧は /cases/ と /cases/cases_category/◯◯/ に一本化したため、そちらへ 301 転送する。
 * このページ自体が転送先と同じ URL の場合は、転送せずに一覧をそのまま表示する。
 */
$aloha_term_slug = isset( $_GET['term_slug'] ) && ! is_array( $_GET['term_slug'] ) ? sanitize_key( wp_unslash( $_GET['term_slug'] ) ) : ''; // phpcs:ignore WordPress.Security.NonceVerification.Recommended
$aloha_target    = $aloha_term_slug ? aloha_case_style_redirect_target( $aloha_term_slug ) : '';
if ( ! $aloha_target ) {
	$aloha_target = get_post_type_archive_link( 'cases' );
}
$aloha_here = untrailingslashit( (string) wp_parse_url( get_permalink(), PHP_URL_PATH ) );
if ( $aloha_target && untrailingslashit( (string) wp_parse_url( $aloha_target, PHP_URL_PATH ) ) !== $aloha_here ) {
	wp_safe_redirect( $aloha_target, 301 );
	exit;
}

get_header();
aloha_render_cases_list( $aloha_term_slug ? get_term_by( 'slug', $aloha_term_slug, 'cases_category' ) : null );
get_footer();
