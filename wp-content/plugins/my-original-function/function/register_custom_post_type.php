<?php
function register_custom_post_type()
{
  //カスタム投稿タイプ①(実績)
  register_post_type(
    'achievement', //投稿タイプ名識別子
    [
      //START: $args(第2パラメーター)
      'label' => '実績', //カスタム投稿タイプ名称(管理画面に表示)
      'labels' => array(
        'add_new' => '実績の追加',
        'edit_item' => '実績の編集',
        'view_item' => '実績を表示',
        'search_items' => '実績を検索'
      ),
      'description' => 'カスタム投稿タイプ("achievement")に関する説明',
      'public' => true,
      'show_ui' => true,
      'show_in_nav_menus' => true,
      'show_in_menu' => true,
      'hierarchical' => true, //カスタム投稿タイプに階層構造をもたせる
      'has_archive' => true, //投稿した記事の一覧ページ作成
      'show_in_rest' => true, //REST APIを有効化 *Gutenbergには必須
      'menu_icon' => 'dashicons-hammer',
      'supports' => array( //記事編集画面に表示する項目
        'title',
        'editor',
        'thumbnail',
        'excerpt',
        'custom-fields',
        'revisions',
        'page-attributes'
      ),
      'menu_position' => 5, //投稿の下に表示
      'taxonomies' => array('achievement_cat', 'achievement_tag')
      //END: $args(第2パラメーター)
    ],
  );

  //カスタムタクソノミー(「実績」カテゴリー: カテゴリー形式)の登録
  register_taxonomy(
    'achievement_cat', //カスタム分類名(カスタムタクソノミースラッグ)
    'achievement', //上記のカスタム分類名が使用される投稿タイプ名(ターム?)
    array(
      'label' => '実績カテゴリー', //カスタムタクソノミーラベル名
      'labels'
      => array(
        'popular_items' => '実績カテゴリー',
        'edit_item' => '実績カテゴリーを編集',
        'add_new_item' => '新規実績カテゴリーを追加',
        'search_items' => '実績カテゴリーを検索'
      ),
      'public' => true,  // 管理画面及びサイト上に公開
      'description' => '実績カテゴリーの説明文です。',  //説明文
      'hierarchical' => true,  //カテゴリー形式
      'show_in_rest' => true  //Gutenberg で表示
    )
  );
  //カスタムタクソノミー(「実績」タグ: タグ形式)の登録
  register_taxonomy(
    'achievement_tag', //カスタム分類名(カスタムタクソノミースラッグ)
    'achievement', //上記のカスタム分類名が使用される投稿タイプ名
    array(
      'label' => '実績タグ', //カスタムタクソノミーラベル名
      'labels'
      => array(
        'popular_items' => 'タグ',
        'edit_item' => 'タグを編集',
        'add_new_item' => 'タグを追加',
        'search_items' => 'タグを検索'
      ),
      'public' => true,  // 管理画面及びサイト上に公開
      'description' => 'タグの説明文です。',  //説明文
      'hierarchical' => false,  //タグ形式
      'show_in_rest' => true  //Gutenberg で表示
    )
  );
  register_taxonomy_for_object_type('achievement_cat', 'achievement');
  register_taxonomy_for_object_type('achievement_tag', 'achievement');

  //カスタム投稿タイプ②(お知らせ)
  register_post_type(
    'info', //投稿タイプ名識別子
    [
      //START: $args(第2パラメーター)
      'label' => 'お知らせ', //カスタム投稿タイプ名称(管理画面に表示)
      'labels' => array(
        'add_new' => 'お知らせの追加',
        'edit_item' => 'お知らせの編集',
        'view_item' => 'お知らせを表示',
        'search_items' => 'お知らせを検索'
      ),
      'public' => true,
      'show_ui' => true,
      'show_in_nav_menus' => true,
      'show_in_menu' => true,
      'hierarchical' => true, //カスタム投稿タイプに階層構造をもたせる
      'has_archive' => true, //投稿した記事の一覧ページ作成
      'show_in_rest' => true, //REST APIを有効化 *Gutenbergには必須
      'menu_icon' => 'dashicons-welcome-write-blog
',
      'supports' => array( //記事編集画面に表示する項目
        'title',
        'editor',
        'thumbnail',
        'excerpt',
        'custom-fields',
        'revisions',
        'page-attributes'
      ),
      'menu_position' => 5, //投稿の下に表示
      'taxonomies' => array('info_cat', 'info_tag')
      //END: $args(第2パラメーター)
    ],
  );


  //カスタムタクソノミー(「お知らせ」カテゴリー: カテゴリー形式)の登録
  register_taxonomy(
    'info_cat', //カスタム分類名(カスタムタクソノミースラッグ)(カスタムタクソノミースラッグ)
    'info', //上記のカスタム分類名が使用される投稿タイプ名
    array(
      'label' => 'お知らせカテゴリー', //カスタムタクソノミーラベル名
      'labels'
      => array(
        'popular_items' => 'お知らせカテゴリー',
        'edit_item' => 'カテゴリーを編集',
        'add_new_item' => '新規カテゴリーを追加',
        'search_items' => 'カテゴリーを検索',
        'not_found' => 'カテゴリーが見つかりませんでした'
      ),
      'public' => true,  // 管理画面及びサイト上に公開
      'description' => 'お知らせカテゴリーの説明文です。',  //説明文
      'hierarchical' => true,  //カテゴリー形式
      'show_in_rest' => true  //Gutenberg で表示
    )
  );
  //カスタムタクソノミー(「お知らせ」タグ: タグ形式)の登録
  register_taxonomy(
    'info_tag', //カスタム分類名(カスタムタクソノミースラッグ)
    'info', //上記のカスタム分類名が使用される投稿タイプ名
    array(
      'label' => 'お知らせタグ', //カスタムタクソノミーラベル名
      'labels'
      => array(
        'popular_items' => 'お知らせタグ',
        'edit_item' => 'タグを編集',
        'add_new_item' => 'タグを追加',
        'search_items' => 'タグを検索',
        'not_found' => 'タグが見つかりませんでした'
      ),
      'public' => true,  // 管理画面及びサイト上に公開
      'description' => 'タグの説明文です。',  //説明文
      'hierarchical' => false,  //タグ形式
      'show_in_rest' => true  //Gutenberg で表示
    )
  );
  register_taxonomy_for_object_type('info_cat', 'info');
  register_taxonomy_for_object_type('info_tag', 'info');
}

add_action('init', 'register_custom_post_type');
