# Local（WordPress）への反映手順

`front-page-preview` / `single-cases-preview` の内容を WordPress テーマ `aloha` に組み込んだファイル一式です。
既存の `header.php` / `footer.php` は変更していません（テンプレート内で `get_header()` / `get_footer()` を呼んでいます）。

## 1. ファイルをコピーする

Local でサイトを右クリック →「Reveal in Finder / Explorer」（または Site folder）を開き、
`app/public/wp-content/themes/aloha/` に、このフォルダの `themes/aloha/` の中身を**同じ階層構造で**コピーします。

```
themes/aloha/
├── front-page.php              … トップページ（新規 or 置き換え）
├── single-cases.php            … 施工事例詳細（新規 or 置き換え）
├── inc/redesign.php            … 設定・CSS/JS読み込み・共通関数
└── assets/
    ├── css/top.css             … コンパイル済みCSS（Sass不要でそのまま使える）
    ├── css/case-detail.css
    ├── js/case-detail.js       … 施工事例のスライダー
    ├── scss/_top.scss          … Sassソース（テーマでSassをビルドしている場合用）
    ├── scss/_case-detail.scss
    ├── scss/top-page.scss      … 上記をビルドするためのエントリー
    ├── scss/case-detail-page.scss
    └── images/top/             … トップページ用の写真置き場
```

> 既に `front-page.php` / `single-cases.php` がある場合は、上書き前にバックアップ（例: `front-page.php.bak`）を取ってください。

## 2. functions.php に1行追加

`themes/aloha/functions.php` の末尾に追加します。

```php
require_once get_theme_file_path( 'inc/redesign.php' );
```

これでトップページ・施工事例詳細のときだけ、Google Fonts（Archivo / Zen Kaku Gothic New）と CSS・JS が読み込まれます。

## 3. 既存サイトに合わせて確認・変更する箇所

| 項目 | 場所 | 初期値 |
| --- | --- | --- |
| 施工事例の投稿タイプ名 | `inc/redesign.php` の `ALOHA_CASES_POST_TYPE` | `cases` |
| 建物概要のフィールド名（15項目） | `inc/redesign.php` の `aloha_case_overview_fields()` | `location`, `usage` など |
| ギャラリー画像のACFフィールド名 | `inc/redesign.php` の `ALOHA_CASE_GALLERY_FIELD` | `gallery` |
| トップページ各リンクのURL | `front-page.php` 上部の `$aloha_links` | `/concept/` など仮のURL |
| カタログ請求ページのURL | `single-cases.php` の `$catalog_link` | `/catalog/` |

- 施工事例のギャラリーは ACF のギャラリーフィールドを使います。ない場合は「アイキャッチ画像＋投稿に添付された画像」を使います。
- 施工事例のタグ（スタイル／特徴／ライフスタイル）には、`cases` に登録されている公開タクソノミーを自動で表示します。1つ目のタクソノミーは黒背景で強調されます。
- 建物概要が1件も入力されていない事例では、表そのものを表示しません。未入力の項目は「—」になります。
- Works（施工事例3件）と News & Topics（投稿5件）は WordPress から自動で取得します。イベント情報・リフォームブログ・SNS はプレビューと同じ固定表示です（リフォームブログには既存の `js-news-generate` クラスを残しています）。

## 4. 写真を配置する

ヒーロー画像は既存の `assets/images/top/HeroSlide01.jpg` 〜 `HeroSlide06.jpg` を使います。
それ以外の写真は、`assets/images/top/` に下記のファイル名で置くと表示されます。未配置の写真はグレーのプレースホルダーになります。

| セクション | ファイル名 |
| --- | --- |
| About | `about-exterior.jpg` `about-living.jpg` `about-deck.jpg` `about-light.jpg` `about-detail.jpg` |
| 導線タイル | `pillar-concept.jpg` `pillar-flow.jpg` `pillar-plan.jpg` `pillar-works.jpg` |
| Lifestyle | `lifestyle.jpg` |
| Contents | `contents-works.jpg` `contents-modelhouse.jpg` `contents-hawaii.jpg` `contents-reform.jpg` `contents-service.jpg` `contents-line.jpg` |
| Event | `event-1.jpg` `event-2.jpg` `event-3.jpg` |
| バナー | `banner-vr.jpg` `banner-area.jpg` |
| SNS | `sns-1.jpg` `sns-2.jpg` `sns-3.jpg` |

## 5. 表示を確認する

- 「設定 → 表示設定」で「ホームページの表示」を**固定ページ**にしている場合でも、`front-page.php` が優先されます。
- 施工事例の URL が 404 になる場合は、「設定 → パーマリンク」を開いて何も変えずに「変更を保存」を押してください。
- CSS が反映されないときは、ブラウザをスーパーリロード（Mac: ⌘+Shift+R / Win: Ctrl+F5）してください。

## Sass をビルドしている場合

テーマで Sass をコンパイルしている場合は、`_top.scss` / `_case-detail.scss` を既存のメイン SCSS に `@use` するか、次のコマンドで CSS を作り直せます。

```sh
npx sass --no-source-map assets/scss/top-page.scss assets/css/top.css
npx sass --no-source-map assets/scss/case-detail-page.scss assets/css/case-detail.css
```
