<!DOCTYPE html>
<html lang="ja">
<head>
    <!-- Google Tag Manager -->
    <script>
        ( function ( w, d, s, l, i ) {
            w[ l ] = w[ l ] || [];
            w[ l ].push( {
                'gtm.start': new Date().getTime(),
                event: 'gtm.js'
            } );
            var f = d.getElementsByTagName( s )[ 0 ],
                j = d.createElement( s ),
                dl = l != 'dataLayer' ? '&l=' + l : '';
            j.async = true;
            j.src =
                'https://www.googletagmanager.com/gtm.js?id=' + i + dl;
            f.parentNode.insertBefore( j, f );
        } )( window, document, 'script', 'dataLayer', 'GTM-P23TRX8' );
    </script>
    <!-- End Google Tag Manager -->
    <meta charset="utf-8"/>
    <meta http-equiv="X-UA-Compatible" content="ie=edge"/>
    <meta name="format-detection" content="telephone=no, address=no, email=no"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0"/>
    <meta name="description" content="岡山の風土・生活スタイルを考慮した家造りを基本に、一級建築士 湯浅康則による新しい技術とモダンデザインのご提案いたします。「熟練した職人の経験」＋「最新のデザインを提案する若さ」でお客様に満足していただけるサービスを提供いたします。">
    <meta name="msvalidate.01" content="FB1E82999312B2F038F574D0B43CADB0"/>
    <title>ALOHA & STYLE Inc.
        <?php wp_title( $sep = '|', $display = true, $seplocation = '' ) ?>
    </title>
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/css/styles.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/style-ms.css">
    <!-- CSS By MS -->
    <!-- GoogleFonts -->
    <link href="https://fonts.googleapis.com/css?family=Poppins" rel="stylesheet">
    <!-- Fontawesome -->
    <script defer src="https://use.fontawesome.com/releases/v5.0.6/js/all.js"></script>
    <!-- Icomoon -->
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/fonts/icomoon/style.css">
    <!-- Slick -->
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/js/slick/slick.css">
    <link rel="stylesheet" href="<?php echo get_template_directory_uri(); ?>/assets/js/slick/slick-theme.css">
    <link rel="shortcut icon" href="<?php echo get_template_directory_uri(); ?>/favicon.ico"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.5.0/css/all.css" integrity="sha384-B4dIYHKNBt8Bc12p+WXckhzcICo0wtJAoU8YZTY5qE0Id1GSseTk6S+L3BlXeVIU" crossorigin="anonymous">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <!-- Google Tag Manager (noscript) -->
    <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-P23TRX8"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
    <!-- End Google Tag Manager (noscript) -->
    <div class="PageContainer">
        <header class="Header">
            <div class="Header__head">
                <h1 class="Header__logo">
          <a href="<?php echo home_url(); ?>">
            <picture>
              <source media="(max-width: 767px)" srcset="<?php echo get_template_directory_uri(); ?>/assets/images/Header_logo.png 1x, <?php echo get_template_directory_uri(); ?>/assets/images/Header_logo@2x.png 2x">
              <source media="(min-width: 768px)" srcset="<?php echo get_template_directory_uri(); ?>/assets/images/Header_logo.png 1x, <?php echo get_template_directory_uri(); ?>/assets/images/Header_logo@2x.png 2x">
              <img src="<?php echo get_template_directory_uri(); ?>/assets/images/Header_logo.png" alt="Aloha & Style - Hawaiian life of longing">
            </picture>
          </a>
        </h1>
            
                <div class="Header__menu-icon">
                    <a class="menu-trigger" href="#">
            <span></span>
            <span></span>
            <span></span>
          </a>
                
                </div>
                <?php
                // グローバルナビ（項目は inc/redesign.php の aloha_global_nav() で管理）
                $aloha_nav = aloha_global_nav();
                ?>
                <?php if ( wp_is_mobile() ) : ?>
                <div class="Header__list">
                    <ul class="accordion">
                        <?php foreach ( $aloha_nav as $item ) : ?>
                            <?php if ( ! empty( $item['cta'] ) ) { continue; } ?>
                            <li>
                                <?php if ( ! empty( $item['children'] ) ) : ?>
                                    <button class="button" type="button"><?php echo esc_html( $item['label'] ); ?></button>
                                    <div class="nest">
                                        <ul class="accordion_1">
                                            <?php foreach ( $item['children'] as $child ) : ?>
                                                <li><a href="<?php echo esc_url( $child['url'] ); ?>"><?php echo esc_html( $child['label'] ); ?></a></li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                <?php else : ?>
                                    <a href="<?php echo esc_url( $item['url'] ); ?>"><?php echo esc_html( $item['label'] ); ?></a>
                                <?php endif; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <div class="Header__sp-actions">
                        <?php foreach ( $aloha_nav as $item ) : ?>
                            <?php if ( empty( $item['cta'] ) ) { continue; } ?>
                            <a href="<?php echo esc_url( $item['url'] ); ?>" class="<?php echo 'reserve' === $item['cta'] ? 'is-reserve' : ''; ?>"<?php echo ! empty( $item['external'] ) ? ' target="_blank" rel="noopener"' : ''; ?>><?php echo esc_html( $item['label'] ); ?></a>
                        <?php endforeach; ?>
                        <a href="<?php echo esc_url( home_url( '/line/' ) ); ?>">LINEで相談</a>
                    </div>
                </div>
                <?php else : ?>
                <ul class="Header__list">
                    <?php foreach ( $aloha_nav as $item ) : ?>
                        <?php
                        $classes = array( 'Header__item' );
                        if ( ! empty( $item['children'] ) ) {
                            $classes[] = 'Header__item-under-menu';
                        }
                        if ( ! empty( $item['cta'] ) ) {
                            $classes[] = 'Header__item--cta';
                            $classes[] = 'Header__item--' . $item['cta'];
                        }
                        ?>
                        <li class="<?php echo esc_attr( implode( ' ', $classes ) ); ?>">
                            <?php if ( ! empty( $item['children'] ) ) : ?>
                                <a href="<?php echo esc_url( $item['url'] ); ?>" class="Header__under-link"><?php echo esc_html( $item['label'] ); ?></a>
                                <a class="Header__under-icon" aria-hidden="true"></a>
                                <ul class="Header__under-list">
                                    <?php foreach ( $item['children'] as $child ) : ?>
                                        <li class="Header__under-item"><object><a href="<?php echo esc_url( $child['url'] ); ?>"><?php echo esc_html( $child['label'] ); ?></a></object></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else : ?>
                                <a href="<?php echo esc_url( $item['url'] ); ?>"<?php echo ! empty( $item['external'] ) ? ' target="_blank" rel="noopener"' : ''; ?>><?php echo esc_html( $item['label'] ); ?></a>
                            <?php endif; ?>
                        </li>
                    <?php endforeach; ?>
                </ul>
                <?php endif; ?>
            </div>
        </header>