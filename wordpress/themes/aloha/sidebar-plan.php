<div class="Column__side Side">
                <div class="Side__block">
                  <div class="TitleContent__wrap Side__TitleContent__wrap">
                    <div class="TitleContent Side__TitleContent">
                      <p class="TitleContent__text Side__TitleContent__text">住宅プラン ［NEW PRODUCT DESIGN］</p>
                    </div>
                  </div>
                  <ul class="Side__list">
                    <?php
                    // 住宅プランの5カテゴリ（名前・説明・画像は inc/redesign.php の aloha_plan_categories() / aloha_plan_category_meta()）
                    foreach ( aloha_plan_categories() as $plan_cat ) :
                        $plan_meta = aloha_plan_category_meta( $plan_cat['slug'] );
                        $plan_img  = $plan_meta['img'] ? aloha_theme_image( $plan_meta['img'] ) : '';
                        ?>
                    <li class="Column__item">
                      <div class="Column__item-pic">
                        <a href="<?php echo esc_url( $plan_cat['url'] ); ?>">
                          <?php if ( $plan_img ) : ?>
                            <img src="<?php echo esc_url( $plan_img ); ?>" alt="<?php echo esc_attr( $plan_meta['ja'] ); ?>" loading="lazy">
                          <?php endif; ?>
                          <div class="Column__item-name-wrap Side__item-name-wrap">
                            <p class="Column__item-name Side__item-name"><?php echo esc_html( $plan_meta['en'] ); ?><?php echo 0 === $plan_cat['count'] ? '（準備中）' : ''; ?></p>
                          </div>
                        </a>
                      </div>
                      <p class="Column__lead Side__lead"><?php echo esc_html( $plan_meta['lead'] ); ?></p>
                      <div class="Column__item-btn">
                        <a href="<?php echo esc_url( $plan_cat['url'] ); ?>">READ MORE</a>
                      </div>
                    </li>
                    <?php endforeach; ?>
                  </ul>

                </div>
              </div>
