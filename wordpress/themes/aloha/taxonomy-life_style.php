<?php
/**
 * 施工例のタクソノミー（life_style）一覧
 *
 * 出力は inc/cases-list.php の aloha_render_cases_list()（全件・各タクソノミーで共通）。
 */

get_header();
aloha_render_cases_list( get_queried_object() );
get_footer();
