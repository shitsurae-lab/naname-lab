<?php

/**
 * カスタム投稿タイプ・タクソノミーの登録と、REST APIの項目追加
 *
 * - init のときに実行:     投稿タイプ・タクソノミーの登録(register_*_post_type / register_*_taxonomies)
 *
 * - rest_api_init のとき:  APIが返す項目の追加(register_rest_fields)
 *
 * 投稿タイプ: achievement(実績) / skill(スキル。未使用。後日削除予定) / news（お知らせ）
 */

// =============================
// 登録(init)
// =============================

// ---- 実績 ----
function register_achievement_post_type()
{
  register_post_type(
    'achievement', // 投稿タイプ名識別子
    [
      'label' => '実績',
      'labels' => array(
        'add_new' => '実績の追加',
        'edit_item' => '実績の編集',
        'view_item' => '実績を表示',
        'search_items' => '実績を検索'
      ),
      'description' => 'カスタム投稿タイプ("achievement")に関する説明',
      'public' => true,
      'show_in_nav_menus' => true,
      'show_in_menu' => true,
      'hierarchical' => true,   // 階層構造をもたせる
      'has_archive' => false,   // 一覧ページを作らない
      'show_in_rest' => true,   // REST APIを有効化 *Gutenbergには必須
      'menu_icon' => 'dashicons-hammer',
      'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'excerpt',
        'custom-fields',
        'revisions',
        'page-attributes'
      ),
      'menu_position' => 5,
      'taxonomies' => array('achievement_cat', 'achievement_tag')
    ]
  );
}
add_action('init', 'register_achievement_post_type');

function register_achievement_taxonomies()
{
  // 実績カテゴリー(カテゴリー形式)
  register_taxonomy(
    'achievement_cat',
    'achievement',
    array(
      'label' => '実績カテゴリー',
      'labels' => array(
        'popular_items' => '実績カテゴリー',
        'edit_item' => '実績カテゴリーを編集',
        'add_new_item' => '新規実績カテゴリーを追加',
        'search_items' => '実績カテゴリーを検索'
      ),
      'public' => true,
      'description' => '実績カテゴリーの説明文です。',
      'hierarchical' => true,   // カテゴリー形式
      'show_in_rest' => true,
    )
  );

  // 実績タグ(タグ形式)
  register_taxonomy(
    'achievement_tag',
    'achievement',
    array(
      'label' => '実績タグ',
      'labels' => array(
        'popular_items' => 'タグ',
        'edit_item' => 'タグを編集',
        'add_new_item' => 'タグを追加',
        'search_items' => 'タグを検索'
      ),
      'public' => true,
      'description' => 'タグの説明文です。',
      'hierarchical' => false,  // タグ形式
      'show_in_rest' => true,
    )
  );
}
add_action('init', 'register_achievement_taxonomies');

// ---- スキル(未使用。後日削除予定) ----
function register_skill_post_type()
{
  register_post_type(
    'skill',
    [
      'label' => 'スキル',
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
      'hierarchical' => true,
      'has_archive' => true,
      'show_in_rest' => true,
      'menu_icon' => 'dashicons-art',
      'supports' => array(
        'title',
        'editor',
        'thumbnail',
        'excerpt',
        'custom-fields',
        'revisions',
        'page-attributes'
      ),
      'menu_position' => 5,
      'taxonomies' => array('skill_cat', 'skill_tag')
    ]
  );
}
add_action('init', 'register_skill_post_type');

function register_skill_taxonomies()
{
  // スキルカテゴリー(カテゴリー形式)
  register_taxonomy(
    'skill_cat',
    'skill',
    array(
      'label' => 'スキルカテゴリー',
      'labels' => array(
        'popular_items' => 'スキルカテゴリー',
        'edit_item' => 'スキルカテゴリーを編集',
        'add_new_item' => '新規スキルカテゴリーを追加',
        'search_items' => 'スキルカテゴリーを検索'
      ),
      'public' => true,
      'description' => 'スキルカテゴリーの説明文です。',
      'hierarchical' => true,
      'show_in_rest' => true,
    )
  );

  // スキルタグ(タグ形式)
  register_taxonomy(
    'skill_tag',
    'skill',
    array(
      'label' => 'スキルタグ',
      'labels' => array(
        'popular_items' => 'タグ',
        'edit_item' => 'タグを編集',
        'add_new_item' => 'タグを追加',
        'search_items' => 'タグを検索'
      ),
      'public' => true,
      'description' => 'タグの説明文です。',
      'hierarchical' => false,
      'show_in_rest' => true,
    )
  );
}
add_action('init', 'register_skill_taxonomies');

// ---- お知らせ ----
function register_news_post_type()
{
  register_post_type(
    'news', // APIの入口は /wp-json/wp/v2/news
    [
      'label' => 'お知らせ',
      'labels' => array(
        'add_new' => 'お知らせの追加',
        'edit_item' => 'お知らせの編集',
        'view_item' => 'お知らせを表示',
        'search_items' => 'お知らせを検索'
      ),
      'description' => 'お知らせ(実績の更新など)',
      'public' => true,
      'show_in_nav_menus' => false,
      'show_in_menu' => true,
      'hierarchical' => false,  // お知らせは親子にしない
      'has_archive' => false,
      'show_in_rest' => true,
      'menu_icon' => 'dashicons-megaphone',
      'supports' => array('title', 'editor', 'excerpt', 'thumbnail', 'revisions'),
      'menu_position' => 5,
    ]
  );
}
add_action('init', 'register_news_post_type');

// =============================
// APIの項目追加(rest_api_init)
// =============================
function register_rest_fields()
{
  // 実績: ACFの全フィールド
  register_rest_field('achievement', 'acf', [
    'get_callback' => function ($post) {
      return get_fields($post['id']);
    },
    'schema' => null,
  ]);

  // 実績: work_detail(ACFのグループ。APIのキー名は work_detail)
  register_rest_field('achievement', 'work_detail', [
    'get_callback' => function ($post) {
      return get_field('work_detail', $post['id']);
    },
    'schema' => null,
  ]);

  // 実績カテゴリー: ACFの全フィールド
  register_rest_field('achievement_cat', 'acf', [
    'get_callback' => function ($term) {
      return get_fields('achievement_cat_' . $term['id']);
    },
    'schema' => null,
  ]);

  // 固定ページ: ACFの全フィールド
  register_rest_field('page', 'acf', [
    'get_callback' => function ($post) {
      return get_fields($post['id']);
    },
    'schema' => null,
  ]);

  // お知らせ: ACFの全フィールド
  register_rest_field('news', 'acf', [
    'get_callback' => function ($post) {
      if (!function_exists('get_fields')) {
        return null; // ACFが無効のときにエラーにしない
      }
      return get_fields($post['id']);
    },
    'schema' => null,
  ]);

  // お知らせ: 関連する実績(Next.jsで /works/[category]/[slug] のリンクを作るため)
  register_rest_field('news', 'related_achievement', [
    'get_callback' => function ($post) {
      if (!function_exists('get_field')) {
        return null;
      }
      $value = get_field('related_achievement', $post['id']);
      $id = is_object($value) ? (int) $value->ID : (int) $value;
      // 未設定、または公開されていない実績は返さない(リンク切れ防止)
      if (!$id || get_post_status($id) !== 'publish') {
        return null;
      }
      $terms = get_the_terms($id, 'achievement_cat');
      $slugs = (!is_wp_error($terms) && !empty($terms)) ? wp_list_pluck($terms, 'slug') : [];
      return [
        'id' => $id,
        'slug' => get_post_field('post_name', $id),
        'title' => get_the_title($id),
        'category_slug' => $slugs[0] ?? null,      // 最初のカテゴリー
        'category_slugs' => array_values($slugs),  // 全カテゴリー
      ];
    },
    'schema' => null,
  ]);
}
add_action('rest_api_init', 'register_rest_fields');
