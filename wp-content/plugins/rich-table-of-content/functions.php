<?php
/*
Plugin Name: Rich Table of Contents
Plugin URI: https://croover.co.jp/rtoc
Description: "RTOC -Rich Table of Contents-" is a table of contents generation plugin from Japan that allows anyone to easily create a table of contents.
Version: 1.3.96
Author: CROOVER.inc
Text Domain: rich-table-of-content
Domain Path: /languages/
Author URI: https://croover.co.jp/
License: GPL2
*/
/*  Copyright 2019 CROOVER.inc (email: croover.inc@gmail.com )
	This program is free software; you can redistribute it and/or modify
	it under the terms of the GNU General Public License, version 2, as
	published by the Free Software Foundation.

	This program is distributed in the hope that it will be useful,
	but WITHOUT ANY WARRANTY; without even the implied warranty of
	MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
	GNU General Public License for more details.

	You should have received a copy of the GNU General Public License
	along with this program; if not, write to the Free Software
	Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/

if (!defined('ABSPATH')) {
	exit;
}

add_action('plugins_loaded', 'book_stealth_load_textdomain');
function book_stealth_load_textdomain()
{
	load_plugin_textdomain('rich-table-of-content', false, plugin_basename(dirname(__FILE__)) . '/languages');
}

// 管理画面PHPの読み込み
include_once(dirname(__FILE__) . '/include/rtoc_admin.php');
include_once(dirname(__FILE__) . '/include/rtoc_inline.php');
include_once(dirname(__FILE__) . '/include/rtoc_shortcode.php');
include_once(dirname(__FILE__) . '/include/rtoc_edit.php');
include_once(dirname(__FILE__) . '/include/rtoc_userate.php');

// カラーピッカーのスタイルを読み込む
add_action('admin_print_styles', 'rtoc_admin_color_picker');
function rtoc_admin_color_picker()
{
	wp_enqueue_style('wp-color-picker');
}


// 目次を生成する関数
function rtoc_get_index($content)
{
	if (get_option('rtoc_display_top') == 1 && is_front_page()) {
		return;
	}
	if(empty($content)){
		$post_data = get_post();
		$content = $post_data->post_content;
		$content = strip_shortcodes($content);
	}
	$contentCheck = mb_strstr($content, 'jinr-blocks/paidpost');
	if ($contentCheck !== false) {
		$template = get_template();
		if($template == 'jinr'){
			// 非ログインユーザーもしくはログインユーザーだが有料会員ではない場合
			if(!is_user_logged_in()){
				preg_match_all('/<h[2-4](.*?)>(.*?)<\/h[2-4]>/', $contentCheck, $heading_lists);
				if (is_array($heading_lists)) {
					foreach ($heading_lists[0] as $heading_list) {
						$content = str_replace($heading_list, '', $content);
					}
				}
			} else {
				if(current_user_can('subscriber')){
					$user = wp_get_current_user();
					$current_user_product_ids = get_user_meta($user->ID, 'productID', true);
					if (!empty($current_user_product_ids)) {
						preg_match_all('/"([^"]+)"/', $contentCheck, $matches);
						if (!empty($matches[1])) {
							$resultArray = [];
							$count = count($matches[1]);
							for ($i = 0; $i < $count; $i += 2) {
								$key = $matches[1][$i];
								$value = ($i + 1 < $count) ? $matches[1][$i + 1] : null;
								$resultArray[$key] = $value;
							}
							$productID = $resultArray['productID'];
						}
						$current_user_product_ids = explode(',', $current_user_product_ids);
						foreach ($current_user_product_ids as $current_user_product_id) {
							if ($productID !== $current_user_product_id) {
								preg_match_all('/<h[2-4](.*?)>(.*?)<\/h[2-4]>/', $contentCheck, $heading_lists);
								if (is_array($heading_lists)) {
									foreach ($heading_lists[0] as $heading_list) {
										$content = str_replace($heading_list, '', $content);
									}
								}
							}
						}
					}
				}
			}
		}
	}
	preg_match_all('/<h2(.*?)>(.*?)<\/h2>/', $content, $h2_list);
	preg_match_all('/<h[2-3](.*?)>(.*?)<\/h[2-3]>/', $content, $h3_list);
	preg_match_all('/<h[2-4](.*?)>(.*?)<\/h[2-4]>/', $content, $h4_list);

	if (is_array($h2_list)) {
		$h2_count = count($h2_list[0]);
	} else {
		$h2_count = 0;
	}
	if (is_array($h3_list)) {
		$h3_count = count($h3_list[0]);
	} else {
		$h3_count = 0;
	}
	if (is_array($h4_list)) {
		$h4_count = count($h4_list[0]);
	} else {
		$h4_count = 0;
	}
	$currentlevel = 0;
	$idcount = 1;
	$rtoc = '';
	$previous_level = null;

	// 設定項目を取得して変数化
	$rtoc_title            = get_option('rtoc_title');
	$rtoc_headline_display = get_option('rtoc_headline_display');
	$rtoc_admin_count      = get_option('rtoc_display_headline_amount');
	$rtoc_font             = get_option('rtoc_font');

	$rtoc_title_display    = get_option('rtoc_title_display');
	if (get_option('rtoc_title_display') == 'left') {
		$rtoc_title_display = 'rtoc_left';
	} elseif (get_option('rtoc_title_display') == 'center') {
		$rtoc_title_display = 'rtoc_center';
	}
	$rtoc_initial_display    = get_option('rtoc_initial_display');
	if (get_option('rtoc_initial_display') == 'open') {
		$rtoc_initial_display = 'rtoc_open';
	} elseif (get_option('rtoc_initial_display') == 'close') {
		$rtoc_initial_display = 'rtoc_close';
	}

	$rtoc_list_h2_type = get_option('rtoc_list_h2_type');
	$rtoc_list_h3_type = get_option('rtoc_list_h3_type');
	$rtoc_frame_design = get_option('rtoc_frame_design');
	$rtoc_animation    = get_option('rtoc_animation');
	$rtoc_color        = get_option('rtoc_color');
	if ($rtoc_headline_display == 'h2') {
		for ($i = 0; $i < $h2_count; $i++) {
			preg_match('/<h2.*?>/u', $h2_list[0][$i], $matches2);

			$rtocLink = "rtoc-" . $idcount;
			$idcount++;
			$level = 0;
			if (preg_match("/<h2.*?>/", $matches2[0])) {
				if (strpos($matches2[0], 'id=') !== false) {
					preg_match('/id=("|\'|([ぁ-んァ-ヶー一-龠a-zA-Z0-9０-９、。①②③④⑤⑥⑦⑧⑨!#:;&~@%+$\*\^\(\)\[\]\|\/\.,_-]+))+/', $h2_list[0][$i], $rtocExi);
					$number_h2 = preg_replace('/<h2.*?>(.*?)<\/h2>/', '<li class="rtoc-item"><a href="#' . $rtocExi[2] . '">$1</a></li>', $h2_list[0][$i]);
					$level = 1;
				} else {
					$number_h2 = preg_replace('/<h2.*?>(.*?)<\/h2>/', '<li class="rtoc-item"><a href="#' . $rtocLink . '">$1</a></li>', $h2_list[0][$i]);
					if (strpos($h2_list[0][$i], '<h2') !== false) {
						$level = 1;
					}
				}
				// 不要タグを空文字へ.
				$number_h2 = rtoc_unnecessary_tags_delete($number_h2);

				if ($currentlevel < $level) {
					if ($rtoc_list_h2_type == 'ul') {
						$rtoc .= '<ul class="rtoc-mokuji mokuji_ul level-1">';
					} elseif ($rtoc_list_h2_type == 'ol') {
						$rtoc .= '<ol class="rtoc-mokuji mokuji_ol level-1">';
					} elseif ($rtoc_list_h2_type == 'ol2') {
						$rtoc .= '<ol class="rtoc-mokuji decimal_ol level-1">';
					} else {
						$rtoc .= '<ul class="rtoc-mokuji mokuji_none level-1">';
					}
					$currentlevel++;
				}
				$rtoc .= $number_h2;
			}
		}
		// ループ後
		if ($rtoc_list_h2_type == 'ul' || $rtoc_list_h2_type == 'none') {
			$rtoc .= '</ul>';
		} elseif ($rtoc_list_h2_type == 'ol' || $rtoc_list_h2_type == 'ol2') {
			$rtoc .= '</ol>';
		}
	} elseif ($rtoc_headline_display == 'h3') {
		for ($i = 0; $i < $h3_count; $i++) {
			preg_match('/<h[2-3].*?>/u', $h3_list[0][$i], $matches2);
			$rtocLink = "rtoc-" . $idcount;
			$idcount++;
			$level = 0;
			if (preg_match("/<h[2-3].*?>/", $matches2[0])) {
				if (strpos($matches2[0], 'id=') !== false) {
					preg_match('/id=("|\'|([ぁ-んァ-ヶー一-龠a-zA-Z0-9０-９、。①②③④⑤⑥⑦⑧⑨!#:;&~@%+$\*\^\(\)\[\]\|\/\.,_-]+))+/', $h3_list[0][$i], $rtocExi);
					$number_h3_li = preg_replace('/<h[2-3].*?>(.*?)<\/h[2-4]>/', '<li class="rtoc-item"><a href="#' . $rtocExi[2] . '">$1</a>', $h3_list[0][$i]);
				} else {
					$number_h3_li = preg_replace('/<h[2-3].*?>(.*?)<\/h[2-4]>/', '<li class="rtoc-item"><a href="#' . $rtocLink . '">$1</a>', $h3_list[0][$i]);
				}
				if (strpos($h3_list[0][$i], '<h2') !== false) {
					$level = 1;
				} elseif (strpos($h3_list[0][$i], '<h3') !== false) {
					$level = 2;
				}
				// 同じ level の見出しが続いた場合.
				if ($currentlevel === $level) {
					$number_h3 = '</li>' . $number_h3_li;
				} else {
					$number_h3 = $number_h3_li;
				}

				if ($currentlevel < $level) {
					if ($level === 1) {
						if ($rtoc_list_h2_type == 'ul') {
							$rtoc .= '<ul class="rtoc-mokuji mokuji_ul level-1">';
						} elseif ($rtoc_list_h2_type == 'ol') {
							$rtoc .= '<ol class="rtoc-mokuji mokuji_ol level-1">';
						} elseif ($rtoc_list_h2_type == 'ol2') {
							$rtoc .= '<ol class="rtoc-mokuji decimal_ol level-1">';
						} else {
							$rtoc .= '<ul class="rtoc-mokuji mokuji_none level-1">';
						}
					} elseif ($level === 2) {
						if ($rtoc_list_h3_type == 'ul') {
							$rtoc .= '<ul class="rtoc-mokuji mokuji_ul level-2">';
						} elseif ($rtoc_list_h3_type == 'ol') {
							$rtoc .= '<ol class="rtoc-mokuji mokuji_ol level-2">';
						} elseif ($rtoc_list_h3_type == 'ol2') {
							$rtoc .= '<ol class="rtoc-mokuji decimal_ol level-2">';
						} else {
							$rtoc .= '<ul class="rtoc-mokuji mokuji_none level-2">';
						}
					}
					$currentlevel++;
				}
				// $currentlevel =2, $level =1（Previous =h3, Now =h2) .
				if ($currentlevel > $level) {
					if ($level === 1) {
						if ($rtoc_list_h3_type == 'ul' || $rtoc_list_h3_type == 'none') {
							$rtoc .= '</li></ul></li>';
						} elseif ($rtoc_list_h3_type == 'ol' || $rtoc_list_h3_type == 'ol2') {
							$rtoc .= '</li></ol></li>';
						}
						$currentlevel--;
					}
				}
				$rtoc .= $number_h3;
			}
		}
		// ループ後
		if ($currentlevel === 2) {
			if ($rtoc_list_h3_type == 'ul' || $rtoc_list_h3_type == 'none') {
				$rtoc .= '</li></ul>';
			} elseif ($rtoc_list_h3_type == 'ol' || $rtoc_list_h3_type == 'ol2') {
				$rtoc .= '</li></ol>';
			}
			$currentlevel--;
		}
		if ($currentlevel === 1) {
			if ($rtoc_list_h2_type == 'ul' || $rtoc_list_h2_type == 'none') {
				$rtoc .= '</li></ul>';
			} elseif ($rtoc_list_h2_type == 'ol' || $rtoc_list_h2_type == 'ol2') {
				$rtoc .= '</li></ol>';
			}
		}
	} elseif ($rtoc_headline_display == 'h4') {
		for ($i = 0; $i < $h4_count; $i++) {
			preg_match('/<h[2-4].*?>/u', $h4_list[0][$i], $matches2);
			$rtocLink = "rtoc-" . $idcount;
			$idcount++;
			$level = 0;
			if (preg_match("/<h[2-4].*?>/", $matches2[0])) {
				if (strpos($matches2[0], 'id=') !== false) {
					preg_match('/id=("|\'|([ぁ-んァ-ヶー一-龠a-zA-Z0-9０-９、。①②③④⑤⑥⑦⑧⑨!#:;&~@%+$\*\^\(\)\[\]\|\/\.,_-]+))+/', $h4_list[0][$i], $rtocExi);
					$number_h4_li = preg_replace('/<h[2-4].*?>(.*?)<\/h[2-4]>/', '<li class="rtoc-item"><a href="#' . $rtocExi[2] . '">$1</a>', $h4_list[0][$i]);
				} else {
					$number_h4_li = preg_replace('/<h[2-4].*?>(.*?)<\/h[2-4]>/', '<li class="rtoc-item"><a href="#' . $rtocLink . '">$1</a>', $h4_list[0][$i]);
				}
				// 不要タグを空文字へ.
				$number_h4_li = rtoc_unnecessary_tags_delete($number_h4_li);

				if (strpos($h4_list[0][$i], '<h2') !== false) {
					$level = 1;
				} elseif (strpos($h4_list[0][$i], '<h3') !== false) {
					$level = 2;
				} elseif (strpos($h4_list[0][$i], '<h4') !== false) {
					$level = 3;
				}
				$previous_level = $level;
				// 同じ level の見出しが続いた場合.
				if ($currentlevel === $level) {
					$number_h4 = '</li>' . $number_h4_li;
				} else {
					$number_h4 = $number_h4_li;
				}

				if ($currentlevel < $level) {
					if ($level === 1) {
						if ($rtoc_list_h2_type == 'ul') {
							$rtoc .= '<ul class="rtoc-mokuji mokuji_ul level-1">';
						} elseif ($rtoc_list_h2_type == 'ol') {
							$rtoc .= '<ol class="rtoc-mokuji mokuji_ol level-1">';
						} elseif ($rtoc_list_h2_type == 'ol2') {
							$rtoc .= '<ol class="rtoc-mokuji decimal_ol level-1">';
						} else {
							$rtoc .= '<ul class="rtoc-mokuji mokuji_none level-1">';
						}
					} elseif ($level === 2) {
						if ($rtoc_list_h3_type == 'ul') {
							$rtoc .= '<ul class="rtoc-mokuji mokuji_ul level-2">';
						} elseif ($rtoc_list_h3_type == 'ol') {
							$rtoc .= '<ol class="rtoc-mokuji mokuji_ol level-2">';
						} elseif ($rtoc_list_h3_type == 'ol2') {
							$rtoc .= '<ol class="rtoc-mokuji decimal_ol level-2">';
						} else {
							$rtoc .= '<ul class="rtoc-mokuji mokuji_none level-2">';
						}
					} elseif ($level === 3) {
						$rtoc .= '<ul class="rtoc-mokuji mokuji_none level-3">';
					}
					$currentlevel++;
				}

				if ($currentlevel > $level) {
					if ($currentlevel === 3) {
						$rtoc .= '</li></ul></li>';
						$currentlevel--;

						if ($level === 1) {
							if ($rtoc_list_h3_type == 'ul' || $rtoc_list_h3_type == 'none') {
								$rtoc .= '</ul></li>';
							} elseif ($rtoc_list_h3_type == 'ol' || $rtoc_list_h3_type == 'ol2') {
								$rtoc .= '</ol></li>';
							}
							$currentlevel--;
						}
					} elseif ($currentlevel === 2) {
						if ($rtoc_list_h3_type == 'ul' || $rtoc_list_h3_type == 'none') {
							$rtoc .= '</li></ul></li>';
						} elseif ($rtoc_list_h3_type == 'ol' || $rtoc_list_h3_type == 'ol2') {
							$rtoc .= '</li></ol></li>';
						}
						$currentlevel--;
					}
				}
				$rtoc .= $number_h4;
			}
		}
		// ループ後.
		if ($currentlevel === 3) {
			$rtoc .= '</li></ul>';
			$currentlevel--;
		}
		if ($currentlevel === 2) {
			if ($rtoc_list_h3_type == 'ul' || $rtoc_list_h3_type == 'none') {
				$rtoc .= '</li></ul>';
			} elseif ($rtoc_list_h3_type == 'ol' || $rtoc_list_h3_type == 'ol2') {
				$rtoc .= '</li></ol>';
			}
			$currentlevel--;
		}
		if ($currentlevel === 1) {
			if ($rtoc_list_h2_type == 'ul' || $rtoc_list_h2_type == 'none') {
				$rtoc .= '</li></ul>';
			} elseif ($rtoc_list_h2_type == 'ol' || $rtoc_list_h2_type == 'ol2') {
				$rtoc .= '</li></ol>';
			}
		}
	}

	$post_id = get_the_ID();

	// 目次開閉ボタンを非表示にするかどうか
	$RtocHideButton = get_option('rtoc_exclude_openclose');
	$RtocButton = '<button class="rtoc_open_close ' . $rtoc_initial_display . '"></button>';
	$RtocPaddingButton = '';
	if (!empty($RtocHideButton)) {
		$RtocButton = '';
		$RtocPaddingButton = 'rtoc_btn_none';
	}

	// テーマ名を取得しテーマ別に最適化する
	$get_theme_name    = wp_get_theme();
	$theme_name  = $get_theme_name->get('Name');

	if (!is_plugin_active('rich-table-of-content-addon/rtoc-addon.php')) {
		$content_inside = '<div id="rtoc-mokuji-wrapper" class="rtoc-mokuji-content ' . $rtoc_frame_design . ' ' . $rtoc_color . ' ' . $rtoc_animation . ' ' . $rtoc_initial_display . ' ' . $rtoc_font . '" data-id="' . $post_id . '" data-theme="' . $theme_name . '">
			<div id="rtoc-mokuji-title" class="' . $RtocPaddingButton . ' ' . $rtoc_title_display . '">
			' . $RtocButton . '
			<span>' . $rtoc_title . '</span>
			</div>' . $rtoc . '</div>';
	}

	$content = '';
	if ($rtoc_headline_display == 'h2' && $h2_count >= $rtoc_admin_count) {
		$content = $content_inside;
	} elseif ($rtoc_headline_display == 'h3' && $h3_count >= $rtoc_admin_count) {
		$content = $content_inside;
	} elseif ($rtoc_headline_display == 'h4' && $h4_count >= $rtoc_admin_count) {
		$content = $content_inside;
	}
	return $content;
}
/**
 * 目次に表示される見出しテキストから不要なタグを空文字へ置き換える（strong / em / img）
 *
 * @param string $heading_list_text - 不要なタグを削除したいテキスト（<li class="rtoc-item"><a href="#rtoc-◯">hoge</a>）.
 */
function rtoc_unnecessary_tags_delete($heading_list_text)
{
	$bold_patterns     = '/<\/*strong.*?>/';
	$heading_list_text = preg_replace($bold_patterns, '', $heading_list_text);
	$font_patterns     = '/<\/*font.*?>/';
	$heading_list_text = preg_replace($font_patterns, '', $heading_list_text);
	$italic_patterns   = '/<\/*em.*?>/';
	$heading_list_text = preg_replace($italic_patterns, '', $heading_list_text);
	$image_patterns   = '/<\/*img.*?>/';
	$heading_list_text = preg_replace($image_patterns, '', $heading_list_text);
	return $heading_list_text;
}

//目次の出力
function rtoc_switch_mokuji($the_content)
{
	global $post;
	$RtocDisplay     = get_option('rtoc_display');
	$RtocPostExclude = get_option('rtoc_exclude_post_toc');
	$RtocPageExclude = get_option('rtoc_exclude_page_toc');
	$RtocPostId      = explode(",", $RtocPostExclude);
	$RtocPageId      = explode(",", $RtocPageExclude);
	if (!has_shortcode($post->post_content, 'rtoc_mokuji')) {
		// JINのカテゴリーページの対応
		if (get_template() == 'jin') {
			if (is_category()) {
				$t_id       = get_category(intval(get_query_var('cat')))->term_id;
				$cat_class  = get_category($t_id);
				$cat_option = get_option($t_id);
				if (is_array($cat_option)) {
					$cat_option = array_merge(array('cont' => ''), $cat_option);
				}
				$Rtoc_jin_category_contents = $cat_option['cps_meta_content'];
				if (!has_shortcode($Rtoc_jin_category_contents, 'rtoc_mokuji')) {
					return $the_content;
				} else {
					if (strpos($Rtoc_jin_category_contents, 'heading=\"h2\"') !== false) {
						$rtoc_sc_h2 = '/<h2.*?>(.+?)<\/h2>/ims';
						preg_match_all($rtoc_sc_h2, $the_content, $tags);
						$idnum = 1;
						for ($i = 0; $i < count($tags[0]); $i++) {
							$idstr[1][0] = 'rtoc-' . $idnum++;
							if (strpos($tags[0][$i], 'id=') === false) {
								$search = '{' . preg_quote($tags[0][$i], '') . '}';
								$the_content = preg_replace($search, preg_replace('/(^<h2)/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $the_content, 1);
							}
						}
					} elseif (strpos($Rtoc_jin_category_contents, 'heading=\"h3\"') !== false) {
						$rtoc_sc_h3 = '/<h[2-3].*?>(.+?)<\/h[2-3]>/ims';
						preg_match_all($rtoc_sc_h3, $the_content, $tags);
						$idnum = 1;
						for ($i = 0; $i < count($tags[0]); $i++) {
							$idstr[1][0] = 'rtoc-' . $idnum++;
							if (strpos($tags[0][$i], 'id=') === false) {
								$search = '{' . preg_quote($tags[0][$i], '') . '}';
								$the_content = preg_replace($search, preg_replace('/(^<h[2-3])/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $the_content, 1);
							}
						}
					} elseif (strpos($Rtoc_jin_category_contents, 'heading=\"h4\"') !== false) {
						$rtoc_sc_h4 = '/<h[2-4].*?>(.+?)<\/h[2-4]>/ims';
						preg_match_all($rtoc_sc_h4, $the_content, $tags);
						$idnum = 1;
						for ($i = 0; $i < count($tags[0]); $i++) {
							$idstr[1][0] = 'rtoc-' . $idnum++;
							if (strpos($tags[0][$i], 'id=') === false) {
								$search = '{' . preg_quote($tags[0][$i], '') . '}';
								$the_content = preg_replace($search, preg_replace('/(^<h[2-4])/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $the_content, 1);
							}
						}
					} else {
						if (get_option('rtoc_headline_display') == 'h2') {
							$rtoc_sc_h2 = '/<h2.*?>(.+?)<\/h2>/ims';
							preg_match_all($rtoc_sc_h2, $the_content, $tags);
							$idnum = 1;
							for ($i = 0; $i < count($tags[0]); $i++) {
								$idstr[1][0] = 'rtoc-' . $idnum++;
								if (strpos($tags[0][$i], 'id=') === false) {
									$search = '{' . preg_quote($tags[0][$i], '') . '}';
									$the_content = preg_replace($search, preg_replace('/(^<h2)/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $the_content, 1);
								}
							}
						} elseif (get_option('rtoc_headline_display') == 'h3') {
							$rtoc_sc_h3 = '/<h[2-3].*?>(.+?)<\/h[2-3]>/ims';
							preg_match_all($rtoc_sc_h3, $the_content, $tags);
							$idnum = 1;
							for ($i = 0; $i < count($tags[0]); $i++) {
								$idstr[1][0] = 'rtoc-' . $idnum++;
								if (strpos($tags[0][$i], 'id=') === false) {
									$search = '{' . preg_quote($tags[0][$i], '') . '}';
									$the_content = preg_replace($search, preg_replace('/(^<h[2-3])/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $the_content, 1);
								}
							}
						} elseif (get_option('rtoc_headline_display') == 'h4') {
							$rtoc_sc_h4 = '/<h[2-4].*?>(.+?)<\/h[2-4]>/ims';
							preg_match_all($rtoc_sc_h4, $the_content, $tags);
							$idnum = 1;
							for ($i = 0; $i < count($tags[0]); $i++) {
								$idstr[1][0] = 'rtoc-' . $idnum++;
								if (strpos($tags[0][$i], 'id=') === false) {
									$search = '{' . preg_quote($tags[0][$i], '') . '}';
									$the_content = preg_replace($search, preg_replace('/(^<h[2-4])/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $the_content, 1);
								}
							}
						}
					}
					return $the_content;
				}
			}
		}
		if (get_template() == 'jinr' && is_category()) {
			$page_ids = get_posts(array(
				'posts_per_page' => -1,
				'fields'         => 'ids',
				'post_type'      => 'page',
			));

			//変数初期値設定
			$edit_category_ids = array();
			$target_page_id = "";
			$counter = 0;
			$pair_ids = array();

			foreach ($page_ids as $page_id) {
				$jinr_category = get_post_meta($page_id, '_jinr_category', true);
				//現在のカテゴリーページID
				$cat_id = get_query_var('cat');
				$edit_category_ids[] = $jinr_category;
				$pair_ids += array(
					$page_ids[$counter] => $edit_category_ids[$counter],
				);
				$counter++;
			}
			$target_page_id = array_search($cat_id, $pair_ids);
			$content = get_post($target_page_id);
			$content = $content->post_content;
			if (get_option('rtoc_headline_display') == 'h2') {
				$rtoc_h2 = '/<h2.*?>(.+?)<\/h2>/ims';
				preg_match_all($rtoc_h2, $the_content, $tags);
				$idnum = 1;
				for ($i = 0; $i < count($tags[0]); $i++) {
					$idmidashi = $idnum++;
					if (strpos($tags[0][$i], 'id=') === false) {
						$idstr[1][0] = 'rtoc-' . $idmidashi;
						$subject     = $content;
						$search      = '{' . preg_quote($tags[0][$i], '') . '}';
						$content = preg_replace($search, preg_replace('/(^<h[2])/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $subject, 1);
					}
				}
				if (preg_match('/<h2.*>/', $content, $matches, PREG_OFFSET_CAPTURE)) {
					if (strpos($matches[0][0], 'a--h2rich-maincopy') !== false) {
						$pattern = '/<section class="wp-block-jinr-blocks-designtitle/';
						preg_match($pattern, $content, $matches, PREG_OFFSET_CAPTURE);
						$pos = $matches[0][1];
						$content = substr($content, 0, $pos) . rtoc_get_index($content) . substr($content, $pos);
					} else {
						$pos = $matches[0][1];
						$content = substr($content, 0, $pos) . rtoc_get_index($content) . substr($content, $pos);
					}
				}
				return $content;
			} elseif (get_option('rtoc_headline_display') == 'h3') {
				$rtoc_h3 = '/<h[2-3].*?>(.+?)<\/h[2-3]>/ims';
				preg_match_all($rtoc_h3, $the_content, $tags);
				$idnum = 1;
				for ($i = 0; $i < count($tags[0]); $i++) {
					$idmidashi = $idnum++;
					if (strpos($tags[0][$i], 'id=') === false) {
						$idstr[1][0] = 'rtoc-' . $idmidashi;
						$subject     = $content;
						$search      = '{' . preg_quote($tags[0][$i], '') . '}';
						$content = preg_replace($search, preg_replace('/(^<h[2-3])/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $subject, 1);
					}
				}
				if (preg_match('/<h2.*>/', $content, $matches, PREG_OFFSET_CAPTURE)) {
					if (strpos($matches[0][0], 'a--h2rich-maincopy') !== false) {
						$pattern = '/<section class="wp-block-jinr-blocks-designtitle/';
						preg_match($pattern, $content, $matches, PREG_OFFSET_CAPTURE);
						$pos = $matches[0][1];
						$content = substr($content, 0, $pos) . rtoc_get_index($content) . substr($content, $pos);
					} else {
						$pos = $matches[0][1];
						$content = substr($content, 0, $pos) . rtoc_get_index($content) . substr($content, $pos);
					}

				}
				return $content;
			} elseif (get_option('rtoc_headline_display') == 'h4') {
				$rtoc_h4 = '/<h[2-4].*?>(.+?)<\/h[2-4]>/ims';
				preg_match_all($rtoc_h4, $content, $tags);
				$idnum = 1;
				for ($i = 0; $i < count($tags[0]); $i++) {
					$idmidashi = $idnum++;
					if (strpos($tags[0][$i], 'id=') === false) {
						$idstr[1][0] = 'rtoc-' . $idmidashi;
						$subject     = $content;
						$search      = '{' . preg_quote($tags[0][$i], '') . '}';
						$content = preg_replace($search, preg_replace('/(^<h[2-4])/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $subject, 1);
					}
				}
				if (preg_match('/<h2.*>/', $content, $matches, PREG_OFFSET_CAPTURE)) {
					if (strpos($matches[0][0], 'a--h2rich-maincopy') !== false) {
						$pattern = '/<section class="wp-block-jinr-blocks-designtitle/';
						preg_match($pattern, $content, $matches, PREG_OFFSET_CAPTURE);
						$pos = $matches[0][1];
						$content = substr($content, 0, $pos) . rtoc_get_index($content) . substr($content, $pos);
					} else {
						$pos = $matches[0][1];
						$content = substr($content, 0, $pos) . rtoc_get_index($content) . substr($content, $pos);
					}
				}
				return $content;
			}
		}
		if (get_option('rtoc_headline_display') == 'h2') {
			$rtoc_h2 = '/<h2.*?>(.+?)<\/h2>/ims';
			preg_match_all($rtoc_h2, $the_content, $tags);
			$idnum = 1;
			if (!empty($RtocDisplay['post']) && !empty($RtocDisplay['page'])) {
				if (is_single()) {
					if (!is_single($RtocPostId)) {
						for ($i = 0; $i < count($tags[0]); $i++) {
							$idmidashi = $idnum++;
							if (strpos($tags[0][$i], 'id=') === false) {
								$idstr[1][0] = 'rtoc-' . $idmidashi;
								$subject     = $the_content;
								$search      = '{' . preg_quote($tags[0][$i], '') . '}';
								$the_content = preg_replace($search, preg_replace('/(^<h[2])/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $subject, 1);
							}
						}
						if (preg_match('/<h2.*>/', $the_content, $matches, PREG_OFFSET_CAPTURE)) {
							if (strpos($matches[0][0], 'a--h2rich-maincopy') !== false) {
								
								$pattern = '/<section class="wp-block-jinr-blocks-designtitle/';
								preg_match($pattern, $the_content, $matches, PREG_OFFSET_CAPTURE);
								$pos = $matches[0][1];
								$the_content = substr($the_content, 0, $pos) . rtoc_get_index($the_content) . substr($the_content, $pos);
							} else {
								$pos = $matches[0][1];
								$the_content = substr($the_content, 0, $pos) . rtoc_get_index($the_content) . substr($the_content, $pos);
							}
						}
						return $the_content;
					} else {
						return $the_content;
					}
				}
				if (is_page()) {
					if (!is_page($RtocPageId)) {
						for ($i = 0; $i < count($tags[0]); $i++) {
							$idmidashi = $idnum++;
							if (strpos($tags[0][$i], 'id=') === false) {
								$idstr[1][0] = 'rtoc-' . $idmidashi;
								$subject     = $the_content;
								$search      = '{' . preg_quote($tags[0][$i], '') . '}';
								$the_content = preg_replace($search, preg_replace('/(^<h[2])/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $subject, 1);
							}
						}
						if (preg_match('/<h2.*>/', $the_content, $matches, PREG_OFFSET_CAPTURE)) {
							if (strpos($matches[0][0], 'a--h2rich-maincopy') !== false) {
								
								$pattern = '/<section class="wp-block-jinr-blocks-designtitle/';
								preg_match($pattern, $the_content, $matches, PREG_OFFSET_CAPTURE);
								$pos = $matches[0][1];
								$the_content = substr($the_content, 0, $pos) . rtoc_get_index($the_content) . substr($the_content, $pos);
							} else {
								$pos = $matches[0][1];
								$the_content = substr($the_content, 0, $pos) . rtoc_get_index($the_content) . substr($the_content, $pos);
							}
						}
						return $the_content;
					} else {
						return $the_content;
					}
				}
			} elseif (!empty($RtocDisplay['post']) && empty($RtocDisplay['page'])) {
				if (is_page()) {
					return $the_content;
				} elseif (!is_single($RtocPostId)) {
					for ($i = 0; $i < count($tags[0]); $i++) {
						if (strpos($tags[0][$i], 'id=') === false) {
							$idstr[1][0] = 'rtoc-' . $idnum++;
							$subject     = $the_content;
							$search      = '{' . preg_quote($tags[0][$i], '') . '}';
							$the_content = preg_replace($search, preg_replace('/(^<h[2])/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $subject, 1);
						}
					}
					if (preg_match('/<h2.*>/', $the_content, $matches, PREG_OFFSET_CAPTURE)) {
						if (strpos($matches[0][0], 'a--h2rich-maincopy') !== false) {
							
							$pattern = '/<section class="wp-block-jinr-blocks-designtitle/';
							preg_match($pattern, $the_content, $matches, PREG_OFFSET_CAPTURE);
							$pos = $matches[0][1];
							$the_content = substr($the_content, 0, $pos) . rtoc_get_index($the_content) . substr($the_content, $pos);
						} else {
							$pos = $matches[0][1];
							$the_content = substr($the_content, 0, $pos) . rtoc_get_index($the_content) . substr($the_content, $pos);
						}
					}
					return $the_content;
				} elseif (is_single($RtocPostId)) {
					return $the_content;
				}
			} elseif (empty($RtocDisplay['post']) && !empty($RtocDisplay['page'])) {
				if (is_single()) {
					return $the_content;
				} elseif (!is_page($RtocPageId)) {
					for ($i = 0; $i < count($tags[0]); $i++) {
						$idmidashi = $idnum++;
						if (strpos($tags[0][$i], 'id=') === false) {
							$idstr[1][0] = 'rtoc-' . $idmidashi;
							$subject     = $the_content;
							$search      = '{' . preg_quote($tags[0][$i], '') . '}';
							$the_content = preg_replace($search, preg_replace('/(^<h[2])/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $subject, 1);
						}
					}
					if (preg_match('/<h2.*>/', $the_content, $matches, PREG_OFFSET_CAPTURE)) {
						if (strpos($matches[0][0], 'a--h2rich-maincopy') !== false) {
							
							$pattern = '/<section class="wp-block-jinr-blocks-designtitle/';
							preg_match($pattern, $the_content, $matches, PREG_OFFSET_CAPTURE);
							$pos = $matches[0][1];
							$the_content = substr($the_content, 0, $pos) . rtoc_get_index($the_content) . substr($the_content, $pos);
						} else {
							$pos = $matches[0][1];
							$the_content = substr($the_content, 0, $pos) . rtoc_get_index($the_content) . substr($the_content, $pos);
						}
					}
					return $the_content;
				} elseif (is_page($RtocPageId)) {
					return $the_content;
				}
			} elseif (empty($RtocDisplay['post']) && empty($RtocDisplay['page'])) {
				if (is_single() || is_page()) {
					return $the_content;
				}
			}
		} elseif (get_option('rtoc_headline_display') == 'h3') {
			$rtoc_h3 = '/<h[2-3].*?>(.+?)<\/h[2-3]>/ims';
			preg_match_all($rtoc_h3, $the_content, $tags);
			$idnum = 1;
			if (!empty($RtocDisplay['post']) && !empty($RtocDisplay['page'])) {
				if (is_single()) {
					if (!is_single($RtocPostId)) {
						for ($i = 0; $i < count($tags[0]); $i++) {
							$idmidashi = $idnum++;
							if (strpos($tags[0][$i], 'id=') === false) {
								$idstr[1][0] = 'rtoc-' . $idmidashi;
								$subject     = $the_content;
								$search      = '{' . preg_quote($tags[0][$i], '') . '}';
								$the_content = preg_replace($search, preg_replace('/(^<h[2-3])/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $subject, 1);
							}
						}
						if (preg_match('/<h2.*>/', $the_content, $matches, PREG_OFFSET_CAPTURE)) {
							if (strpos($matches[0][0], 'a--h2rich-maincopy') !== false) {
								
								$pattern = '/<section class="wp-block-jinr-blocks-designtitle/';
								preg_match($pattern, $the_content, $matches, PREG_OFFSET_CAPTURE);
								$pos = $matches[0][1];
								$the_content = substr($the_content, 0, $pos) . rtoc_get_index($the_content) . substr($the_content, $pos);
							} else {
								$pos = $matches[0][1];
								$the_content = substr($the_content, 0, $pos) . rtoc_get_index($the_content) . substr($the_content, $pos);
							}
						}
						return $the_content;
					} else {
						return $the_content;
					}
				}
				if (is_page()) {
					if (!is_page($RtocPageId)) {
						for ($i = 0; $i < count($tags[0]); $i++) {
							$idmidashi = $idnum++;
							if (strpos($tags[0][$i], 'id=') === false) {
								$idstr[1][0] = 'rtoc-' . $idmidashi;
								$subject     = $the_content;
								$search      = '{' . preg_quote($tags[0][$i], '') . '}';
								$the_content = preg_replace($search, preg_replace('/(^<h[2-3])/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $subject, 1);
							}
						}
						if (preg_match('/<h2.*>/', $the_content, $matches, PREG_OFFSET_CAPTURE)) {
							if (strpos($matches[0][0], 'a--h2rich-maincopy') !== false) {
								
								$pattern = '/<section class="wp-block-jinr-blocks-designtitle/';
								preg_match($pattern, $the_content, $matches, PREG_OFFSET_CAPTURE);
								$pos = $matches[0][1];
								$the_content = substr($the_content, 0, $pos) . rtoc_get_index($the_content) . substr($the_content, $pos);
							} else {
								$pos = $matches[0][1];
								$the_content = substr($the_content, 0, $pos) . rtoc_get_index($the_content) . substr($the_content, $pos);
							}
						}
						return $the_content;
					} else {
						return $the_content;
					}
				}
			} elseif (!empty($RtocDisplay['post']) && empty($RtocDisplay['page'])) {
				if (is_page()) {
					return $the_content;
				} elseif (!is_single($RtocPostId)) {
					for ($i = 0; $i < count($tags[0]); $i++) {
						$idmidashi = $idnum++;
						if (strpos($tags[0][$i], 'id=') === false) {
							$idstr[1][0] = 'rtoc-' . $idmidashi;
							$subject     = $the_content;
							$search      = '{' . preg_quote($tags[0][$i], '') . '}';
							$the_content = preg_replace($search, preg_replace('/(^<h[2-3])/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $subject, 1);
						}
					}
					if (preg_match('/<h2.*>/', $the_content, $matches, PREG_OFFSET_CAPTURE)) {
						if (strpos($matches[0][0], 'a--h2rich-maincopy') !== false) {
							
							$pattern = '/<section class="wp-block-jinr-blocks-designtitle/';
							preg_match($pattern, $the_content, $matches, PREG_OFFSET_CAPTURE);
							$pos = $matches[0][1];
							$the_content = substr($the_content, 0, $pos) . rtoc_get_index($the_content) . substr($the_content, $pos);
						} else {
							$pos = $matches[0][1];
							$the_content = substr($the_content, 0, $pos) . rtoc_get_index($the_content) . substr($the_content, $pos);
						}
					}
					return $the_content;
				} elseif (is_single($RtocPostId)) {
					return $the_content;
				}
			} elseif (empty($RtocDisplay['post']) && !empty($RtocDisplay['page'])) {
				if (is_single()) {
					return $the_content;
				} elseif (!is_page($RtocPageId)) {
					for ($i = 0; $i < count($tags[0]); $i++) {
						$idmidashi = $idnum++;
						if (strpos($tags[0][$i], 'id=') === false) {
							$idstr[1][0] = 'rtoc-' . $idmidashi;
							$subject     = $the_content;
							$search      = '{' . preg_quote($tags[0][$i], '') . '}';
							$the_content = preg_replace($search, preg_replace('/(^<h[2-3])/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $subject, 1);
						}
					}
					if (preg_match('/<h2.*>/', $the_content, $matches, PREG_OFFSET_CAPTURE)) {
						if (strpos($matches[0][0], 'a--h2rich-maincopy') !== false) {
							
							$pattern = '/<section class="wp-block-jinr-blocks-designtitle/';
							preg_match($pattern, $the_content, $matches, PREG_OFFSET_CAPTURE);
							$pos = $matches[0][1];
							$the_content = substr($the_content, 0, $pos) . rtoc_get_index($the_content) . substr($the_content, $pos);
						} else {
							$pos = $matches[0][1];
							$the_content = substr($the_content, 0, $pos) . rtoc_get_index($the_content) . substr($the_content, $pos);
						}
					}
					return $the_content;
				} elseif (is_page($RtocPageId)) {
					return $the_content;
				}
			} elseif (empty($RtocDisplay['post']) && empty($RtocDisplay['page'])) {
				if (is_single() || is_page()) {
					return $the_content;
				}
			}
		} elseif (get_option('rtoc_headline_display') == 'h4') {
			$rtoc_h4 = '/<h[2-4](.*?)>(.+?)<\/h[2-4]>/ims';
			preg_match_all($rtoc_h4, $the_content, $matches);
			
			$idnum = 1;
			if (!empty($RtocDisplay['post']) && !empty($RtocDisplay['page'])) {
				if (is_single()) {
					if (!is_single($RtocPostId)) {
						for ($i = 0; $i < count($matches[0]); $i++) {
							$idmidashi = $idnum++;
							if (strpos($matches[0][$i], 'id=') === false) {
								$idstr[1][0] = 'rtoc-' . $idmidashi;
								$subject     = $the_content;
								$search      = '{' . preg_quote($matches[0][$i], '') . '}';
								$the_content = preg_replace($search, preg_replace('/(^<h[2-4])/i', '${1} id="' . $idstr[1][0] . '" ', $matches[0][$i]), $subject, 1);
							}
						}
						if (preg_match('/<h2.*>/', $the_content, $matches, PREG_OFFSET_CAPTURE)) {
							if (strpos($matches[0][0], 'a--h2rich-maincopy') !== false) {
								$pattern = '/<section class="wp-block-jinr-blocks-designtitle/';
								preg_match($pattern, $the_content, $matches, PREG_OFFSET_CAPTURE);
								$pos = $matches[0][1];
								$the_content = substr($the_content, 0, $pos) . rtoc_get_index($the_content) . substr($the_content, $pos);
							} else {
								$pos = $matches[0][1];
								$the_content = substr($the_content, 0, $pos) . rtoc_get_index($the_content) . substr($the_content, $pos);
							}
						}
						return $the_content;
					} else {
						return $the_content;
					}
				}
				if (is_page()) {
					if (!is_page($RtocPageId)) {
						for ($i = 0; $i < count($matches[0]); $i++) {
							$idmidashi = $idnum++;
							if (strpos($matches[0][$i], 'id=') === false) {
								$idstr[1][0] = 'rtoc-' . $idmidashi;
								$subject     = $the_content;
								$search      = '{' . preg_quote($matches[0][$i], '') . '}';
								$the_content = preg_replace($search, preg_replace('/(^<h[2-4])/i', '${1} id="' . $idstr[1][0] . '" ', $matches[0][$i]), $subject, 1);
							}
						}
						if (preg_match('/<h2.*>/', $the_content, $matches, PREG_OFFSET_CAPTURE)) {
							if (strpos($matches[0][0], 'a--h2rich-maincopy') !== false) {
								
								$pattern = '/<section class="wp-block-jinr-blocks-designtitle/';
								preg_match($pattern, $the_content, $matches, PREG_OFFSET_CAPTURE);
								$pos = $matches[0][1];
								$the_content = substr($the_content, 0, $pos) . rtoc_get_index($the_content) . substr($the_content, $pos);
							} else {
								$pos = $matches[0][1];
								$the_content = substr($the_content, 0, $pos) . rtoc_get_index($the_content) . substr($the_content, $pos);
							}
						}
						return $the_content;
					} else {
						return $the_content;
					}
				}
			} elseif (!empty($RtocDisplay['post']) && empty($RtocDisplay['page'])) {
				if (is_page()) {
					return $the_content;
				} elseif (!is_single($RtocPostId)) {
					for ($i = 0; $i < count($matches[0]); $i++) {
						$idmidashi = $idnum++;
						if (strpos($matches[0][$i], 'id=') === false) {
							$idstr[1][0] = 'rtoc-' . $idmidashi;
							$subject     = $the_content;
							$search      = '{' . preg_quote($matches[0][$i], '') . '}';
							$the_content = preg_replace($search, preg_replace('/(^<h[2-4])/i', '${1} id="' . $idstr[1][0] . '" ', $matches[0][$i]), $subject, 1);
						}
					}
					if (preg_match('/<h2.*>/', $the_content, $matches, PREG_OFFSET_CAPTURE)) {
						if (strpos($matches[0][0], 'a--h2rich-maincopy') !== false) {
							
							$pattern = '/<section class="wp-block-jinr-blocks-designtitle/';
							preg_match($pattern, $the_content, $matches, PREG_OFFSET_CAPTURE);
							$pos = $matches[0][1];
							$the_content = substr($the_content, 0, $pos) . rtoc_get_index($the_content) . substr($the_content, $pos);
						} else {
							$pos = $matches[0][1];
							$the_content = substr($the_content, 0, $pos) . rtoc_get_index($the_content) . substr($the_content, $pos);
						}
					}
					return $the_content;
				} elseif (is_single($RtocPostId)) {
					return $the_content;
				}
			} elseif (empty($RtocDisplay['post']) && !empty($RtocDisplay['page'])) {
				if (is_single()) {
					return $the_content;
				} elseif (!is_page($RtocPageId)) {
					for ($i = 0; $i < count($tags[0]); $i++) {
						$idmidashi = $idnum++;
						if (strpos($tags[0][$i], 'id=') === false) {
							$idstr[1][0] = 'rtoc-' . $idmidashi;
							$subject     = $the_content;
							$search      = '{' . preg_quote($tags[0][$i], '') . '}';
							$the_content = preg_replace($search, preg_replace('/(^<h[2-4])/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $subject, 1);
						}
					}
					if (preg_match('/<h2.*>/', $the_content, $matches, PREG_OFFSET_CAPTURE)) {
						if (strpos($matches[0][0], 'a--h2rich-maincopy') !== false) {
							
							$pattern = '/<section class="wp-block-jinr-blocks-designtitle/';
							preg_match($pattern, $the_content, $matches, PREG_OFFSET_CAPTURE);
							$pos = $matches[0][1];
							$the_content = substr($the_content, 0, $pos) . rtoc_get_index($the_content) . substr($the_content, $pos);
						} else {
							$pos = $matches[0][1];
							$the_content = substr($the_content, 0, $pos) . rtoc_get_index($the_content) . substr($the_content, $pos);
						}
					}
					return $the_content;
				} elseif (is_page($RtocPageId)) {
					return $the_content;
				}
			} elseif (empty($RtocDisplay['post']) && empty($RtocDisplay['page'])) {
				if (is_single() || is_page()) {
					return $the_content;
				}
			}
		}
	} else {
		if (is_category() && get_template() == 'jin') {
			$t_id = get_category(intval(get_query_var('cat')))->term_id;
			$cat_option = get_option($t_id);
			if (is_array($cat_option)) {
				$cat_option = array_merge(array('cont' => ''), $cat_option);
			}
			$Rtoc_jin_category_contents = $cat_option['cps_meta_content'];
			if (!has_shortcode($Rtoc_jin_category_contents, 'rtoc_mokuji')) {
				return $the_content;
			} else {
				if (strpos($Rtoc_jin_category_contents, 'heading=\"h2\"') !== false) {
					$rtoc_sc_h2 = '/<h2.*?>(.+?)<\/h2>/ims';
					preg_match_all($rtoc_sc_h2, $the_content, $tags);
					$idnum = 1;
					for ($i = 0; $i < count($tags[0]); $i++) {
						$idstr[1][0] = 'rtoc-' . $idnum++;
						if (strpos($tags[0][$i], 'id=') === false) {
							$search = '{' . preg_quote($tags[0][$i], '') . '}';
							$the_content = preg_replace($search, preg_replace('/(^<h2)/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $the_content, 1);
						}
					}
				} elseif (strpos($Rtoc_jin_category_contents, 'heading=\"h3\"') !== false) {
					$rtoc_sc_h3 = '/<h[2-3].*?>(.+?)<\/h[2-3]>/ims';
					preg_match_all($rtoc_sc_h3, $the_content, $tags);
					$idnum = 1;
					for ($i = 0; $i < count($tags[0]); $i++) {
						$idstr[1][0] = 'rtoc-' . $idnum++;
						if (strpos($tags[0][$i], 'id=') === false) {
							$search = '{' . preg_quote($tags[0][$i], '') . '}';
							$the_content = preg_replace($search, preg_replace('/(^<h[2-3])/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $the_content, 1);
						}
					}
				} elseif (strpos($Rtoc_jin_category_contents, 'heading=\"h4\"') !== false) {
					$rtoc_sc_h4 = '/<h[2-4].*?>(.+?)<\/h[2-4]>/ims';
					preg_match_all($rtoc_sc_h4, $the_content, $tags);
					$idnum = 1;
					for ($i = 0; $i < count($tags[0]); $i++) {
						$idstr[1][0] = 'rtoc-' . $idnum++;
						if (strpos($tags[0][$i], 'id=') === false) {
							$search = '{' . preg_quote($tags[0][$i], '') . '}';
							$the_content = preg_replace($search, preg_replace('/(^<h[2-4])/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $the_content, 1);
						}
					}
				} else {
					if (get_option('rtoc_headline_display') == 'h2') {
						$rtoc_sc_h2 = '/<h2.*?>(.+?)<\/h2>/ims';
						preg_match_all($rtoc_sc_h2, $the_content, $tags);
						$idnum = 1;
						for ($i = 0; $i < count($tags[0]); $i++) {
							$idstr[1][0] = 'rtoc-' . $idnum++;
							if (strpos($tags[0][$i], 'id=') === false) {
								$search = '{' . preg_quote($tags[0][$i], '') . '}';
								$the_content = preg_replace($search, preg_replace('/(^<h2)/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $the_content, 1);
							}
						}
					} elseif (get_option('rtoc_headline_display') == 'h3') {
						$rtoc_sc_h3 = '/<h[2-3].*?>(.+?)<\/h[2-3]>/ims';
						preg_match_all($rtoc_sc_h3, $the_content, $tags);
						$idnum = 1;
						for ($i = 0; $i < count($tags[0]); $i++) {
							$idstr[1][0] = 'rtoc-' . $idnum++;
							if (strpos($tags[0][$i], 'id=') === false) {
								$search = '{' . preg_quote($tags[0][$i], '') . '}';
								$the_content = preg_replace($search, preg_replace('/(^<h[2-3])/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $the_content, 1);
							}
						}
					} elseif (get_option('rtoc_headline_display') == 'h4') {
						$rtoc_sc_h4 = '/<h[2-4].*?>(.+?)<\/h[2-4]>/ims';
						preg_match_all($rtoc_sc_h4, $the_content, $tags);
						$idnum = 1;
						for ($i = 0; $i < count($tags[0]); $i++) {
							$idstr[1][0] = 'rtoc-' . $idnum++;
							if (strpos($tags[0][$i], 'id=') === false) {
								$search = '{' . preg_quote($tags[0][$i], '') . '}';
								$the_content = preg_replace($search, preg_replace('/(^<h[2-4])/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $the_content, 1);
							}
						}
					}
				}
				return $the_content;
			}
		} else {
			$content = get_the_content();
			if (strpos($content, 'heading="h2"') !== false) {
				$rtoc_sc_h2 = '/<h2.*?>(.+?)<\/h2>/ims';
				preg_match_all($rtoc_sc_h2, $the_content, $tags);
				$idnum = 1;
				for ($i = 0; $i < count($tags[0]); $i++) {
					$idstr[1][0] = 'rtoc-' . $idnum++;
					if (strpos($tags[0][$i], 'id=') === false) {
						$search = '{' . preg_quote($tags[0][$i], '') . '}';
						$the_content = preg_replace($search, preg_replace('/(^<h2)/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $the_content, 1);
					}
				}
			} elseif (strpos($content, 'heading="h3"') !== false) {
				$rtoc_sc_h3 = '/<h[2-3].*?>(.+?)<\/h[2-3]>/ims';
				preg_match_all($rtoc_sc_h3, $the_content, $tags);
				$idnum = 1;
				for ($i = 0; $i < count($tags[0]); $i++) {
					$idstr[1][0] = 'rtoc-' . $idnum++;
					if (strpos($tags[0][$i], 'id=') === false) {
						$search = '{' . preg_quote($tags[0][$i], '') . '}';
						$the_content = preg_replace($search, preg_replace('/(^<h[2-3])/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $the_content, 1);
					}
				}
			} elseif (strpos($content, 'heading="h4"') !== false) {
				$rtoc_sc_h4 = '/<h[2-4].*?>(.+?)<\/h[2-4]>/ims';
				preg_match_all($rtoc_sc_h4, $the_content, $tags);
				$idnum = 1;
				for ($i = 0; $i < count($tags[0]); $i++) {
					$idstr[1][0] = 'rtoc-' . $idnum++;
					if (strpos($tags[0][$i], 'id=') === false) {
						$search = '{' . preg_quote($tags[0][$i], '') . '}';
						$the_content = preg_replace($search, preg_replace('/(^<h[2-4])/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $the_content, 1);
					}
				}
			} else {
				if (get_option('rtoc_headline_display') == 'h2') {
					$rtoc_sc_h2 = '/<h2.*?>(.+?)<\/h2>/ims';
					preg_match_all($rtoc_sc_h2, $the_content, $tags);
					$idnum = 1;
					for ($i = 0; $i < count($tags[0]); $i++) {
						$idstr[1][0] = 'rtoc-' . $idnum++;
						if (strpos($tags[0][$i], 'id=') === false) {
							$search = '{' . preg_quote($tags[0][$i], '') . '}';
							$the_content = preg_replace($search, preg_replace('/(^<h2)/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $the_content, 1);
						}
					}
				} elseif (get_option('rtoc_headline_display') == 'h3') {
					$rtoc_sc_h3 = '/<h[2-3].*?>(.+?)<\/h[2-3]>/ims';
					preg_match_all($rtoc_sc_h3, $the_content, $tags);
					$idnum = 1;
					for ($i = 0; $i < count($tags[0]); $i++) {
						$idstr[1][0] = 'rtoc-' . $idnum++;
						if (strpos($tags[0][$i], 'id=') === false) {
							$search = '{' . preg_quote($tags[0][$i], '') . '}';
							$the_content = preg_replace($search, preg_replace('/(^<h[2-3])/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $the_content, 1);
						}
					}
				} elseif (get_option('rtoc_headline_display') == 'h4') {
					$rtoc_sc_h4 = '/<h[2-4].*?>(.+?)<\/h[2-4]>/ims';
					preg_match_all($rtoc_sc_h4, $the_content, $tags);
					$idnum = 1;
					for ($i = 0; $i < count($tags[0]); $i++) {
						$idstr[1][0] = 'rtoc-' . $idnum++;
						if (strpos($tags[0][$i], 'id=') === false) {
							$search = '{' . preg_quote($tags[0][$i], '') . '}';
							$the_content = preg_replace($search, preg_replace('/(^<h[2-4])/i', '${1} id="' . $idstr[1][0] . '" ', $tags[0][$i]), $the_content, 1);
						}
					}
				}
			}
		}
		return $the_content;
	}
	return $the_content;
}
add_filter('the_content', 'rtoc_switch_mokuji', 11);

// Rank Math SEOに対応する
add_filter('rank_math/researches/toc_plugins', function ($toc_plugins) {
	$toc_plugins['rich-table-of-content/functions.php'] = 'Rich Table of Contents';
	return $toc_plugins;
});

function rtoc_file_include()
{
	if (get_option('rtoc_display_top') == 1 && is_front_page()) {
		return;
	}
	wp_enqueue_script('rtoc_js', plugin_dir_url(__FILE__) . 'js/rtoc_common.js', array('jquery'), false, true);

	// スムーススクロールの値をJSに渡す
	$rtoc_scroll_animation = array(
		'rtocScrollAnimation' => get_option('rtoc_scroll_animation')
	);
	wp_localize_script('rtoc_js', 'rtocScrollAnimation', $rtoc_scroll_animation);

	// 目次に戻るボタンの値をJSに渡す
	$rtoc_back_toc_button = array(
		'rtocBackButton' => get_option('rtoc_back_toc_button')
	);
	wp_localize_script('rtoc_js', 'rtocBackButton', $rtoc_back_toc_button);

	// 目次をPCでも表示させるかの値をJSに渡す
	$rtoc_back_toc_pc = array(
		'rtocBackDisplayPC' => get_option('rtoc_back_toc_pc')
	);
	wp_localize_script('rtoc_js', 'rtocBackDisplayPC', $rtoc_back_toc_pc);

	// 開閉ボタンのテキストをJSに渡す
	$rtoc_open_text = array(
		'rtocOpenText' => get_option('rtoc_open_text')
	);
	wp_localize_script('rtoc_js', 'rtocOpenText', $rtoc_open_text);
	$rtoc_close_text = array(
		'rtocCloseText' => get_option('rtoc_close_text')
	);
	wp_localize_script('rtoc_js', 'rtocCloseText', $rtoc_close_text);
}

// CSSの読み込み
function rtoc_css_load()
{
	if (get_option('rtoc_display_top') == 1 && is_front_page()) {
		return;
	}
	// プラグインCSSの読み込み
	$RtocExclude = get_option('rtoc_exclude_css');
	if (empty($RtocExclude)) {
		// プリセット及び土台CSSの読み込み
		wp_register_style('rtoc_style', plugins_url('css/rtoc_style.css', __FILE__));
		wp_enqueue_style('rtoc_style');
		$get_theme_name    = wp_get_theme();
		$theme_name  = $get_theme_name->get('Name');
		if ($theme_name == 'SANGO' || $theme_name == 'SANGO Child') {
			wp_register_style('rtoc_sango', plugins_url('css/rtoc_for_sango.css', __FILE__));
			wp_enqueue_style('rtoc_sango');
		} elseif ($theme_name == 'Cocoon' || $theme_name == 'Cocoon Child') {
			wp_register_style('rtoc_cocoon', plugins_url('css/rtoc_for_cocoon.css', __FILE__));
			wp_enqueue_style('rtoc_cocoon');
		} elseif ($theme_name == 'SWELL' || $theme_name == 'SWELL CHILD') {
			wp_register_style('rtoc_swell', plugins_url('css/rtoc_for_swell.css', __FILE__));
			wp_enqueue_style('rtoc_swell');
		} elseif ($theme_name == 'Snow Monkey') {
			wp_register_style('rtoc_snowmonkey', plugins_url('css/rtoc_for_snowmonkey.css', __FILE__));
			wp_enqueue_style('rtoc_snowmonkey');
		}

		$rtoc_font = get_option('rtoc_font');
		if ($rtoc_font == 'noto-sans') {
			wp_enqueue_style('rtoc_nsj', 'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;400;700&display=swap');
			wp_enqueue_style('rtoc_nsj', 'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@100;400;700&display=swap');
		}

		// Addon有効時・デザインのCSS読み込み
		include_once(ABSPATH . 'wp-admin/includes/plugin.php');
		if (is_plugin_active('rich-table-of-content-addon/rtoc-addon.php')) {
			wp_register_style('rtoc_addon_style', plugins_url('../rich-table-of-content-addon/css/addon/addon_style.css', __FILE__));
			wp_enqueue_style('rtoc_addon_style');
		}
	} else {
		return;
	}
}
add_action('wp_footer', 'rtoc_css_load', 1);

// JS読み込み
function rtoc_js_load()
{
	if (get_option('rtoc_display_top') == 1 && is_front_page()) {
		return;
	}
	$RtocDisplay     = get_option('rtoc_display');
	$RtocPostExclude = get_option('rtoc_exclude_post_toc');
	$RtocPageExclude = get_option('rtoc_exclude_page_toc');
	$RtocPostId      = explode(",", $RtocPostExclude);
	$RtocPageId      = explode(",", $RtocPageExclude);

	if (!empty($RtocDisplay['post']) && !empty($RtocDisplay['page'])) {
		if (is_single()) {
			if (!is_single($RtocPostId)) {
				rtoc_file_include();
			} else {
				return;
			}
		}
		if (is_page()) {
			if (!is_page($RtocPageId)) {
				rtoc_file_include();
			} else {
				return;
			}
		}
	} elseif (!empty($RtocDisplay['post']) && empty($RtocDisplay['page'])) {
		if (!is_single($RtocPostId)) {
			rtoc_file_include();
		}
	} elseif (empty($RtocDisplay['post']) && !empty($RtocDisplay['page'])) {
		if (!is_page($RtocPageId)) {
			rtoc_file_include();
		}
	}
	if (is_category()) {
		rtoc_file_include();
	}
}
add_action('wp_enqueue_scripts', 'rtoc_js_load', 1);

// 目次へ戻るやスクロールアニメーションを読み込む
function rtoc_js_return()
{
	if (get_option('rtoc_display_top') == 1 && is_front_page()) {
		return;
	}
	// widgets.php を除外.
	global $pagenow;
	if ('widgets.php' === $pagenow) {
		return;
	}

	global $post;
	if (!$post) {
		return;
	}
	$rtoc_admin_count = get_option('rtoc_display_headline_amount');

	preg_match_all('/<h2(.*?)>(.*?)<\/h2>/', $post->post_content, $h2_list);
	preg_match_all('/<h[2-3](.*?)>(.*?)<\/h[2-3]>/', $post->post_content, $h3_list);
	preg_match_all('/<h[2-4](.*?)>(.*?)<\/h[2-4]>/', $post->post_content, $h4_list);

	if (is_array($h2_list)) {
		$h2_count = count($h2_list[0]);
	} else {
		$h2_count = 0;
	}
	if (is_array($h3_list)) {
		$h3_count = count($h3_list[0]);
	} else {
		$h3_count = 0;
	}
	if (is_array($h4_list)) {
		$h4_count = count($h4_list[0]);
	} else {
		$h4_count = 0;
	}
	$RtocDisplay = get_option('rtoc_display');
	if (!empty($RtocDisplay['post'])) {
		if (is_single()) {
			if (get_option('rtoc_back_toc_button') == 'on') {
				if (get_option('rtoc_headline_display') == 'h2' && $h2_count >= $rtoc_admin_count) {
					wp_enqueue_script('rtoc_js_return', plugin_dir_url(__FILE__) . 'js/rtoc_return.js', array('jquery'), false, true);
				} elseif (get_option('rtoc_headline_display') == 'h3' && $h3_count >= $rtoc_admin_count) {
					wp_enqueue_script('rtoc_js_return', plugin_dir_url(__FILE__) . 'js/rtoc_return.js', array('jquery'), false, true);
				} elseif (get_option('rtoc_headline_display') == 'h4' && $h3_count >= $rtoc_admin_count) {
					wp_enqueue_script('rtoc_js_return', plugin_dir_url(__FILE__) . 'js/rtoc_return.js', array('jquery'), false, true);
				}
			}
		}
	} elseif (empty($RtocDisplay['post']) && has_shortcode($post->post_content, 'rtoc_mokuji')) {
		if (is_single()) {
			if (get_option('rtoc_back_toc_button') == 'on') {
				if (get_option('rtoc_headline_display') == 'h2' && $h2_count >= $rtoc_admin_count) {
					wp_enqueue_script('rtoc_js_return', plugin_dir_url(__FILE__) . 'js/rtoc_return.js', array('jquery'), false, true);
				} elseif (get_option('rtoc_headline_display') == 'h3' && $h3_count >= $rtoc_admin_count) {
					wp_enqueue_script('rtoc_js_return', plugin_dir_url(__FILE__) . 'js/rtoc_return.js', array('jquery'), false, true);
				} elseif (get_option('rtoc_headline_display') == 'h4' && $h3_count >= $rtoc_admin_count) {
					wp_enqueue_script('rtoc_js_return', plugin_dir_url(__FILE__) . 'js/rtoc_return.js', array('jquery'), false, true);
				}
			}
		}
	}

	if (!empty($RtocDisplay['page'])) {
		if (is_page()) {
			if (get_option('rtoc_back_toc_button') == 'on') {
				if (get_option('rtoc_headline_display') == 'h2' && $h2_count >= $rtoc_admin_count) {
					wp_enqueue_script('rtoc_js_return', plugin_dir_url(__FILE__) . 'js/rtoc_return.js', array('jquery'), false, true);
				} elseif (get_option('rtoc_headline_display') == 'h3' && $h3_count >= $rtoc_admin_count) {
					wp_enqueue_script('rtoc_js_return', plugin_dir_url(__FILE__) . 'js/rtoc_return.js', array('jquery'), false, true);
				} elseif (get_option('rtoc_headline_display') == 'h4' && $h3_count >= $rtoc_admin_count) {
					wp_enqueue_script('rtoc_js_return', plugin_dir_url(__FILE__) . 'js/rtoc_return.js', array('jquery'), false, true);
				}
			}
		}
	} elseif (empty($RtocDisplay['page']) && has_shortcode($post->post_content, 'rtoc_mokuji')) {
		if (is_page()) {
			if (get_option('rtoc_back_toc_button') == 'on') {
				if (get_option('rtoc_headline_display') == 'h2' && $h2_count >= $rtoc_admin_count) {
					wp_enqueue_script('rtoc_js_return', plugin_dir_url(__FILE__) . 'js/rtoc_return.js', array('jquery'), false, true);
				} elseif (get_option('rtoc_headline_display') == 'h3' && $h3_count >= $rtoc_admin_count) {
					wp_enqueue_script('rtoc_js_return', plugin_dir_url(__FILE__) . 'js/rtoc_return.js', array('jquery'), false, true);
				} elseif (get_option('rtoc_headline_display') == 'h4' && $h3_count >= $rtoc_admin_count) {
					wp_enqueue_script('rtoc_js_return', plugin_dir_url(__FILE__) . 'js/rtoc_return.js', array('jquery'), false, true);
				}
			}
		}
	}
	$rtoc_back_button_position = array(
		'rtocButtonPosition' => get_option('rtoc_back_button_position')
	);
	wp_localize_script('rtoc_js_return', 'rtocButtonPosition', $rtoc_back_button_position);

	$rtoc_back_button_vertical_position = array(
		'rtocVerticalPosition' => get_option('rtoc_back_button_vertical_position')
	);
	wp_localize_script('rtoc_js_return', 'rtocVerticalPosition', $rtoc_back_button_vertical_position);

	// 目次へ戻るボタンのテキストをJSに渡す
	$rtoc_back_text = array(
		'rtocBackText' => get_option('rtoc_back_text')
	);
	wp_localize_script('rtoc_js_return', 'rtocBackText', $rtoc_back_text);
}
add_action('wp_enqueue_scripts', 'rtoc_js_return', 1);

function rtoc_js_scroll()
{
	if (is_singular() || is_category()) {
		if (get_option('rtoc_scroll_animation') == 'on') {
			wp_enqueue_script('rtoc_js_scroll', plugin_dir_url(__FILE__) . 'js/rtoc_scroll.js', array('jquery'), false, true);
		}
	}
}
add_action('wp_enqueue_scripts', 'rtoc_js_scroll', 1);

// サイドバーの追従ハイライトを実装する
function rtoc_js_highlight()
{
	if (get_option('rtoc_display_top') == 1 && is_front_page()) {
		return;
	}
	// JINへの対応
	$get_theme_name    = wp_get_theme();
	$theme_name  = $get_theme_name->get('Name');
	if ($theme_name == 'jin-child' || $theme_name == 'JIN') {
		if (is_single() || is_page()) {
			function followwidget_delete()
			{
				wp_deregister_script('cps-followwidget');
			}
			add_action('wp_enqueue_scripts', 'followwidget_delete', 100);
			wp_enqueue_script('rtoc_js_followwidget', plugin_dir_url(__FILE__) . 'js/rtoc_followwidget.js', array('jquery'), false, true);
		}
	}
	wp_enqueue_script('rtoc_js_highlight', plugin_dir_url(__FILE__) . 'js/rtoc_highlight.js', array('jquery'), false, true);
}
add_action('wp_enqueue_scripts', 'rtoc_js_highlight', 1);

// RTOC設定の画面にメッセージ類が出ないようにする
function rtoc_notice_display_none()
{
	$url = '';
	if (isset($_SERVER['HTTPS'])) {
		$url .= 'https://';
	} else {
		$url .= 'http://';
	}
	$url .= $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	if (strpos($url, 'page=rtoc_settings') !== false) {
		remove_all_actions('admin_notices');
	}
}
add_action('admin_init', 'rtoc_notice_display_none');
