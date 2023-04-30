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
      'show_in_rest' => true,  //Gutenberg で表示
      'rewrite' => array(
        'slug' => 'achievement',
        'hierarchical' => true
      )
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
      'show_in_rest' => true, //Gutenberg で表示
      'rewrite' => array(
        'slug' => 'achievement',
        'hierarchical' => true
      )
    )
  );
  register_taxonomy_for_object_type('achievement_cat', 'achievement');
  register_taxonomy_for_object_type('achievement_tag', 'achievement');



  //カスタム投稿タイプ③(スキル)
  register_post_type(
    'skill', //投稿タイプ名識別子
    [
      //START: $args(第2パラメーター)
      'label' => 'スキル', //カスタム投稿タイプ名称(管理画面に表示)
      'labels' => array(
        'add_new' => '新規追加',
        'edit_item' => '編集',
        'view_item' => '表示',
        'search_items' => '検索'
      ),
      'description' => 'カスタム投稿タイプ("skill")に関する説明',
      'public' => true,
      'show_ui' => true,
      'show_in_nav_menus' => true,
      'show_in_menu' => true,
      'hierarchical' => true, //カスタム投稿タイプに階層構造をもたせる
      'has_archive' => true, //投稿した記事の一覧ページ作成
      'show_in_rest' => true, //REST APIを有効化 *Gutenbergには必須
      'menu_icon' => 'dashicons-art',
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
      'taxonomies' => array('skill_cat', 'skill_tag')
      //END: $args(第2パラメーター)
    ],
  );

  //カスタムタクソノミー(「実績」カテゴリー: カテゴリー形式)の登録
  register_taxonomy(
    'skill_cat', //カスタム分類名(カスタムタクソノミースラッグ)
    'skill', //上記のカスタム分類名が使用される投稿タイプ名(ターム?)
    array(
      'label' => 'スキルカテゴリー', //カスタムタクソノミーラベル名
      'labels'
      => array(
        'popular_items' => 'スキルカテゴリー',
        'edit_item' => 'スキルカテゴリーを編集',
        'add_new_item' => '新規スキルカテゴリーを追加',
        'search_items' => 'スキルカテゴリーを検索'
      ),
      'public' => true,  // 管理画面及びサイト上に公開
      'description' => 'スキルカテゴリーの説明文です。',  //説明文
      'hierarchical' => true,  //カテゴリー形式
      'show_in_rest' => true,  //Gutenberg で表示
      'rewrite' => array(
        'slug' => 'skill',
        'hierarchical' => true
      )
    )
  );
  //カスタムタクソノミー(「実績」タグ: タグ形式)の登録
  register_taxonomy(
    'skill_tag', //カスタム分類名(カスタムタクソノミースラッグ)
    'skill', //上記のカスタム分類名が使用される投稿タイプ名
    array(
      'label' => 'スキルタグ', //カスタムタクソノミーラベル名
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
      'show_in_rest' => true,  //Gutenberg で表示
      'rewrite' => array(
        'slug' => 'skill',
        'hierarchical' => true
      )
    )
  );
  register_taxonomy_for_object_type('skill_cat', 'skill');
  register_taxonomy_for_object_type('skill_tag', 'skill');
}

add_action('init', 'register_custom_post_type');
