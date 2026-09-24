# Local（aloha-test）への反映手順

トップページと施工例詳細を、プレビュー（front-page-preview / single-cases-preview）のデザインに置き換えるファイル一式です。
ヘッダー・フッター（全ページ）と施工事例の一覧ページも同じデザインに合わせています。
既存テーマ `aloha` の構成（`assets/@scss/components/`、ACF のフィールド、footer.php の外部ウィジェット）に合わせてあります。

## 1. コピーするファイル

`wordpress/themes/aloha/` の中身を、Local の `app/public/wp-content/themes/aloha/` に同じ構成でコピーします（**上書き前に既存の `front-page.php` と `single-cases.php` をバックアップ**してください）。

| ファイル | 内容 |
| --- | --- |
| `front-page.php` | トップページ（置き換え） |
| `single-cases.php` | 施工例詳細（置き換え） |
| `archive-cases.php` | 施工例一覧 `/cases/`（置き換え） |
| `taxonomy-feature.php` / `taxonomy-life_style.php` | 特徴別・ライフスタイル別の一覧（置き換え） |
| `taxonomy-cases_category.php` | スタイル別の一覧（**新規**。これまで無く、住宅プラン用の taxonomy.php が使われて空の一覧になっていた） |
| `inc/cases-list.php` | 施工例一覧の共通出力（新規。上の4テンプレートから呼び出し） |
| `page-concept.php` | コンセプト `/concept/`（**新規**。カタログの文章・写真で構成。スラッグ concept の固定ページで自動的に使われる） |
| `assets/images/concept/` | コンセプト用の写真（カタログから書き出し・Web用に圧縮） |
| `header.php` | グローバルナビを新しい構成に変更（項目は `inc/redesign.php` の `aloha_global_nav()` で管理。PC・スマホ共通。既存のクラス名はそのままなので、ハンバーガー・階層メニューの JavaScript はそのまま動作） |
| `inc/redesign.php` | CSS/JS の読み込み・共通関数（**新規**。functions.php の `require_once` が読み込む） |
| `assets/@scss/components/_home.scss` | トップページの Sass（新規） |
| `assets/@scss/components/_caseDetail.scss` | 施工例詳細の Sass（新規） |
| `assets/@scss/components/_caseList.scss` | 施工例一覧の Sass（新規） |
| `assets/@scss/components/_concept.scss` / `assets/@scss/concept.scss` / `assets/css/concept.css` | コンセプトの Sass・エントリー・コンパイル済み CSS（新規） |
| `assets/@scss/components/_palette.scss` | 決定した配色（C＋海の緑・白背景）の共通変数。home / cases / concept / site-chrome の各 CSS で使用 |
| `assets/@scss/components/_siteChrome.scss` | ヘッダー・フッターの Sass（新規・全ページ） |
| `assets/@scss/home.scss` / `cases.scss` / `site-chrome.scss` | 上記をコンパイルするためのエントリー（新規） |
| `assets/@scss/mixin/_mixin.scss` | 既存と同じ内容（コンパイルに必要なため同梱） |
| `assets/css/home.css` / `cases.css` / `site-chrome.css` | コンパイル済み CSS（新規） |
| `assets/js/case-detail.js` | 施工例のスライダー（新規） |

`functions.php`・`footer.php`・`styles.css` は**変更していません**。`header.php` はグローバルナビの部分だけ変更しています（下記）。
（functions.php にはすでに `require_once get_theme_file_path( 'inc/redesign.php' );` があるので、`inc/redesign.php` を置けば読み込まれます。）

## 2. CSS について（重要）

既存の `assets/css/styles.css` には、Sass（`styles.scss`）に無い直接編集が約1,400ルールあります（ハワイ物件ページ、お問い合わせフォーム、カテゴリ検索など）。
そのため `styles.scss` を再コンパイルすると他のページが崩れます。

今回は `styles.css` に触れず、別の CSS を追加で読み込む方式にしました。

- `site-chrome.css`：全ページ。既存の `.Header` / `.Footer` の見た目を上書き（header.php・footer.php のマークアップとクラス名は変更していないので、ハンバーガーメニュー・階層メニュー・下部の固定バー・ページトップの JavaScript はそのまま動きます）
- `home.css`：トップページのみ（`.Home` で始まるクラスだけ）
- `cases.css`：施工例の詳細・一覧・スタイル/特徴/ライフスタイル別一覧のみ（`.CaseDetail` / `.CaseList` で始まるクラスだけ）

Sass を編集したときのコンパイル方法（テーマフォルダで実行）:

```sh
npx sass --no-source-map "assets/@scss/home.scss" assets/css/home.css
npx sass --no-source-map "assets/@scss/cases.scss" assets/css/cases.css
npx sass --no-source-map "assets/@scss/site-chrome.scss" assets/css/site-chrome.css
```

## 配色（決定版：C（茶）＋元の緑・白背景）

| 役割 | 色 |
| --- | --- |
| 背景 | `#FFFFFF` |
| 面（帯・タグ・フッター） | `#F4F3F1` |
| ロゴ・ボタン | `#5E5755` |
| アクセント | `#1fa198`（元サイトの緑） |
| 本文 | `#2B2624` |

ロゴは既存の `Header_logo.png` / `Footer_logo.png` の形をマスクにして `#5E5755` で表示しています（透明背景の PNG が前提。背景が塗られた画像だと四角く表示されるので、その場合はお知らせください）。

## 3. 既存から引き継いでいるもの

- **NEWS & TOPICS**：`post` / `work` / `realestate` の最新6件（取得条件は既存のまま）
- **施工例の写真**：ACF `cases-main-pic`（無ければ `no_img.jpg`）。詳細ページは `sub01〜06_photo` と `sub01〜06_text` をスライダーとキャプションに使用
- **施工例の分類**：`cases_category`（スタイル）/ `feature`（特徴）/ `life_style`（ライフスタイル）
- **施工例一覧**：16件ずつ・wp_pagenavi でページ送り（プラグインが無い場合は WordPress 標準のページ送り）、一覧トップの `cases_main2.jpg` / 2ページ目以降の `cases_main3.jpg`
- **建物概要・間取り**：ACF `spec` / `drawing`、オーナー表記 `area_owner`
- **イベント情報**：ie-miru の外部ウィジェット（読み込み方は既存と同じ）
- **リフォームブログ**：footer.php の `reformBlogData()` が一覧を追加（`.js-news-generate` を維持）
- **Facebook**：既存のページプラグイン（SDK は footer.php）
- **オンライン相談**：既存の ie-miru 予約URL
- **画像**：`assets/images/` の既存素材（`top/HeroSlide01〜06.jpg`、`Top_menu_0N_3.jpg`、`Top_content_0N_2.jpg`、`Aloha_main.jpg`、`line_bnr_img.png` など）。見つからない画像は旧ファイル名や他の写真で代用し、それも無ければグレーのプレースホルダーになります

## 4. URL が未確定で、今は非表示にしている項目

`front-page.php` 上部の配列で `url` を入れると表示されます。

- CONTENTS：マンション、リフォーム
- バナー：VR展示場、施工エリア以外のお客様へ、住宅省エネキャンペーン
- Instagram：既存の埋め込み（instawidget）が別のアカウント（@_officialjkt48）を指していたため外しています

## 5. ヘッダー・フッターの変更点

- ヘッダー：明るい半透明の背景で画面上部に固定。ロゴは既存画像を CSS で黒く表示。最後の「お問い合わせ」をボタン化。ドロップダウンは白いカード
- スマホメニュー：黒背景のパネル（開閉の仕組みは既存のまま）
- フッター：明るいグレー背景。既存の内容（住所・リンク・外部バナー・社長ブログ・スタッフブログ）はすべて維持。ロゴは既存画像を黒く表示
- 画面下の固定バー：白地で高さを抑え、「資料請求・お問合せ」をアクセントカラーに
- ページトップボタン：固定バーに重ならない位置に移動

## 6. 確認方法

1. http://localhost:10104/ を開く
2. 施工例の一覧（`/cases/`）と、スタイル・特徴・ライフスタイルの絞り込みボタンから各一覧を開く
3. 施工例の詳細ページ（`/cases/〇〇/`）を開く
4. 他のページ（会社概要、住宅プランなど）でヘッダー・フッターを確認する
3. 表示が変わらない場合はスーパーリロード（Mac: ⌘+Shift+R）
