<?php
// ショートコード
$atts = "";

function rtoc_shortcode($atts, $content)
{

	$RtocPostExclude = get_option('rtoc_exclude_post_toc');
	$RtocPageExclude = get_option('rtoc_exclude_page_toc');
	$RtocPostId      = explode(",", $RtocPostExclude);
	$RtocPageId      = explode(",", $RtocPageExclude);
	if (get_option('rtoc_display_top') == 1 && is_front_page()) {
		return;
	}
	if (is_single()) {
		if (is_single($RtocPostId)) {
			return;
		}
		shortcode_atts(array(
			'title'         => '',
			'title_display' => '',
			'heading'       => '',
			'list_h2_type'  => '',
			'list_h3_type'  => '',
			'display'       => '',
			'color'         => '',
			'frame_design'  => '',
			'animation'     => '',
		), $atts);

		$post_data = get_post();
		$content    = $post_data->post_content;
		$content    = strip_shortcodes($content);
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
		preg_match_all('/<h2.*?>(.*?)<\/h2>/', $content, $h2_list);
		preg_match_all('/<h[2-3].*?>(.*?)<\/h[2-3]>/', $content, $h3_list);
		preg_match_all('/<h[2-4].*?>(.*?)<\/h[2-4]>/', $content, $h4_list);

		if (is_array($h2_list) || isset($h2_list) || !empty($h2_list)) {
			$h2_count = count($h2_list[0]);
		}
		if (is_array($h3_list) || isset($h3_list) || !empty($h3_list)) {
			$h3_count = count($h3_list[0]);
		}
		if (is_array($h4_list) || isset($h4_list) || !empty($h4_list)) {
			$h4_count = count($h4_list[0]);
		}
		$currentlevel = 0;
		$idcount      = 1;
		$rtoc         = '';

		$in_flag = false;
		$trace = debug_backtrace();
		foreach ($trace as $stp) {
			if (isset($stp['function'])) {
				if ($stp['function'] === "dynamic_sidebar") {
					$in_flag = true;
					break;
				}
			}
		}

		// それぞれのショートコードの引数を取得して変数化
		if (!isset($atts['title']) || $atts['title'] == '') {
			/*
			サイドバー内でショートコードが呼び出されている場合、
			目次タイトルが空白でも
			 */
			if ($in_flag == true) {
				$rtoc_sc_title = '';
			} else {
				$rtoc_sc_title = get_option('rtoc_title');
			}
		} else {
			$rtoc_sc_title = $atts['title'];
		}
		if (!isset($atts['title_display']) || $atts['title_display'] == '') {
			$rtoc_sc_title_display = 'rtoc_' . get_option('rtoc_title_display');
		} else {
			$rtoc_sc_title_display = 'rtoc_' . $atts['title_display'];
		}
		if (isset($atts['list_h2_type'])) {
			if ($atts['list_h2_type'] == 'round') {
				$atts['list_h2_type'] = 'ul';
				$rtoc_sc_h2_list = $atts['list_h2_type'];
			} elseif ($atts['list_h2_type'] == 'number1') {
				$atts['list_h2_type'] = 'ol';
				$rtoc_sc_h2_list = $atts['list_h2_type'];
			} elseif ($atts['list_h2_type'] == 'number2') {
				$atts['list_h2_type'] = 'ol2';
				$rtoc_sc_h2_list = $atts['list_h2_type'];
			} else {
				$rtoc_sc_h2_list = get_option('rtoc_list_h2_type');
			}
		} else {
			$rtoc_sc_h2_list = get_option('rtoc_list_h2_type');
		}
		if (isset($atts['list_h3_type'])) {
			if ($atts['list_h3_type'] == 'round') {
				$atts['list_h3_type'] = 'ul';
				$rtoc_sc_h3_list = $atts['list_h3_type'];
			} elseif ($atts['list_h3_type'] == 'number1') {
				$atts['list_h3_type'] = 'ol';
				$rtoc_sc_h3_list = $atts['list_h3_type'];
			} elseif ($atts['list_h3_type'] == 'number2') {
				$atts['list_h3_type'] = 'ol2';
				$rtoc_sc_h3_list = $atts['list_h3_type'];
			} else {
				$rtoc_sc_h3_list = get_option('rtoc_list_h3_type');
			}
		} else {
			$rtoc_sc_h3_list = get_option('rtoc_list_h3_type');
		}
		if (!isset($atts['display']) || $atts['display'] == '') {
			if (get_option('rtoc_initial_display') == 'open') {
				$rtoc_sc_display = 'rtoc_open';
			} elseif (get_option('rtoc_initial_display') == 'close') {
				$rtoc_sc_display = 'rtoc_close';
			} else {
				$rtoc_sc_display = get_option('rtoc_initial_display');
			}
		} else {
			if ($atts['display'] == 'open') {
				$atts['display'] = 'rtoc_open';
				$rtoc_sc_display = $atts['display'];
			} elseif ($atts['display'] == 'close') {
				$atts['display'] = 'rtoc_close';
				$rtoc_sc_display = $atts['display'];
			} else {
				$rtoc_sc_display = get_option('rtoc_initial_display');
			}
		}
		if (!isset($atts['frame_design']) || $atts['frame_design'] == '') {
			$rtoc_sc_frame_design = get_option('rtoc_frame_design');
		} else {
			if($atts['frame_design'] == 'frame1'){
				$rtoc_sc_frame_design = $atts['frame_design'];
			} elseif ($atts['frame_design'] == 'frame2') {
				$rtoc_sc_frame_design = $atts['frame_design'];
			} elseif ($atts['frame_design'] == 'frame3') {
				$rtoc_sc_frame_design = $atts['frame_design'];
			} elseif ($atts['frame_design'] == 'frame4') {
				$rtoc_sc_frame_design = $atts['frame_design'];
			} elseif ($atts['frame_design'] == 'frame5') {
				$rtoc_sc_frame_design = $atts['frame_design'];
			} else {
				$rtoc_sc_frame_design = get_option('rtoc_frame_design');
			}
		}
		if (isset($atts['animation'])) {
			if ($atts['animation'] == 'fade') {
				$atts['animation'] = 'animation-fade';
				$rtoc_sc_animation = $atts['animation'];
			} elseif ($atts['animation'] == 'slide') {
				$atts['animation'] = 'animation-slide';
				$rtoc_sc_animation = $atts['animation'];
			} elseif ($atts['animation'] == 'none') {
				$atts['animation'] = 'animation-none';
				$rtoc_sc_animation = $atts['animation'];
			} else {
				$rtoc_sc_animation = get_option('rtoc_animation');
			}
		} else {
			$rtoc_sc_animation = get_option('rtoc_animation');
		}

		$admin_heading     = get_option('rtoc_headline_display');
		$rtoc_admin_count  = get_option('rtoc_display_headline_amount');
		$rtoc_font         = get_option('rtoc_font');
		$rtoc_list_h2_type = get_option('rtoc_list_h2_type');
		$rtoc_list_h3_type = get_option('rtoc_list_h3_type');

		// ショートコードがない場合
		if (!isset($atts['heading']) || $atts['heading'] == null) {
			if ($admin_heading == 'h2') {
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
			} elseif ($admin_heading == 'h3') {
				for ($i = 0; $i < $h3_count; $i++) {
					preg_match('/<h[2-3].*?>/u', $h3_list[0][$i], $matches2);
					$rtocLink = "rtoc-" . $idcount;
					$idcount++;
					$level = 0;
					if (preg_match("/<h[2-3].*?>/", $matches2[0])) {
						if (strpos($matches2[0], 'id=') !== false) {
							preg_match('/id=("|\'|([ぁ-んァ-ヶー一-龠a-zA-Z0-9０-９、。①②③④⑤⑥⑦⑧⑨!#:;&~@%+$\*\^\(\)\[\]\|\/\.,_-]+))+/', $h3_list[0][$i], $rtocExi);
							$number_h3_li = preg_replace('/<h[2-3].*?>(.*?)<\/h[2-3]>/', '<li class="rtoc-item"><a href="#' . $rtocExi[2] . '">$1</a>', $h3_list[0][$i]);
						} else {
							$number_h3_li = preg_replace('/<h[2-3].*?>(.*?)<\/h[2-3]>/', '<li class="rtoc-item"><a href="#' . $rtocLink . '">$1</a>', $h3_list[0][$i]);
						}
						// 不要タグを空文字へ.
						$number_h3_li = rtoc_unnecessary_tags_delete($number_h3_li);

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
			} elseif ($admin_heading == 'h4') {
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
							// $currentlevel =3, $level =2（Previous =h4, Now =h3) .
							// $currentlevel =3, $level =1（Previous =h4, Now =h2) .
							// $currentlevel =2, $level =1（Previous =h3, Now =h2).
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
				// ループ後
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
		} elseif (isset($atts['heading']) && $atts['heading'] == 'h2') {
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
					if ($currentlevel < $level) {
						if ($rtoc_sc_h2_list == 'ul') {
							$rtoc .= '<ul class="rtoc-mokuji mokuji_ul level-1">';
						} elseif ($rtoc_sc_h2_list == 'ol') {
							$rtoc .= '<ol class="rtoc-mokuji mokuji_ol level-1">';
						} elseif ($rtoc_sc_h2_list == 'ol2') {
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
			if ($rtoc_sc_h2_list == 'ul' || $rtoc_sc_h2_list == 'none') {
				$rtoc .= '</ul>';
			} elseif ($rtoc_sc_h2_list == 'ol' || $rtoc_sc_h2_list == 'ol2') {
				$rtoc .= '</ol>';
			}
		} elseif (isset($atts['heading']) && $atts['heading'] == 'h3') {
			for ($i = 0; $i < $h3_count; $i++) {
				preg_match('/<h[2-3].*?>/u', $h3_list[0][$i], $matches2);
				$rtocLink = "rtoc-" . $idcount;
				$idcount++;
				$level = 0;
				if (preg_match("/<h[2-3].*?>/", $matches2[0])) {
					if (strpos($matches2[0], 'id=') !== false) {
						preg_match('/id=("|\'|([ぁ-んァ-ヶー一-龠a-zA-Z0-9０-９、。①②③④⑤⑥⑦⑧⑨!#:;&~@%+$\*\^\(\)\[\]\|\/\.,_-]+))+/', $h3_list[0][$i], $rtocExi);
						$number_h3_li = preg_replace('/<h[2-3].*?>(.*?)<\/h[2-3]>/', '<li class="rtoc-item"><a href="#' . $rtocExi[2] . '">$1</a>', $h3_list[0][$i]);
					} else {
						$number_h3_li = preg_replace('/<h[2-3].*?>(.*?)<\/h[2-3]>/', '<li class="rtoc-item"><a href="#' . $rtocLink . '">$1</a>', $h3_list[0][$i]);
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
							if ($rtoc_sc_h2_list == 'ul') {
								$rtoc .= '<ul class="rtoc-mokuji mokuji_ul level-1">';
							} elseif ($rtoc_sc_h2_list == 'ol') {
								$rtoc .= '<ol class="rtoc-mokuji mokuji_ol level-1">';
							} elseif ($rtoc_sc_h2_list == 'ol2') {
								$rtoc .= '<ol class="rtoc-mokuji decimal_ol level-1">';
							} else {
								$rtoc .= '<ul class="rtoc-mokuji mokuji_none level-1">';
							}
						} elseif ($level === 2) {
							if ($rtoc_sc_h3_list == 'ul') {
								$rtoc .= '<ul class="rtoc-mokuji mokuji_ul level-2">';
							} elseif ($rtoc_sc_h3_list == 'ol') {
								$rtoc .= '<ol class="rtoc-mokuji mokuji_ol level-2">';
							} elseif ($rtoc_sc_h3_list == 'ol2') {
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
							if ($rtoc_sc_h3_list == 'ul' || $rtoc_sc_h3_list == 'none') {
								$rtoc .= '</li></ul></li>';
							} elseif ($rtoc_sc_h3_list == 'ol' || $rtoc_sc_h3_list == 'ol2') {
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
				if ($rtoc_sc_h3_list == 'ul' || $rtoc_sc_h3_list == 'none') {
					$rtoc .= '</li></ul>';
				} elseif ($rtoc_sc_h3_list == 'ol' || $rtoc_sc_h3_list == 'ol2') {
					$rtoc .= '</li></ol>';
				}
				$currentlevel--;
			}
			if ($currentlevel === 1) {
				if ($rtoc_sc_h2_list == 'ul' || $rtoc_sc_h2_list == 'none') {
					$rtoc .= '</li></ul>';
				} elseif ($rtoc_sc_h2_list == 'ol' || $rtoc_sc_h2_list == 'ol2') {
					$rtoc .= '</li></ol>';
				}
			}
		} elseif (isset($atts['heading']) && $atts['heading'] == 'h4') {
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
					if (strpos($h4_list[0][$i], '<h2') !== false) {
						$level = 1;
					} elseif (strpos($h4_list[0][$i], '<h3') !== false) {
						$level = 2;
					} elseif (strpos($h4_list[0][$i], '<h4') !== false) {
						$level = 3;
					}
					// 同じ level の見出しが続いた場合.
					if ($currentlevel === $level) {
						$number_h4 = '</li>' . $number_h4_li;
					} else {
						$number_h4 = $number_h4_li;
					}

					if ($currentlevel < $level) {
						if ($level === 1) {
							if ($rtoc_sc_h2_list == 'ul') {
								$rtoc .= '<ul class="rtoc-mokuji mokuji_ul level-1">';
							} elseif ($rtoc_sc_h2_list == 'ol') {
								$rtoc .= '<ol class="rtoc-mokuji mokuji_ol level-1">';
							} elseif ($rtoc_sc_h2_list == 'ol2') {
								$rtoc .= '<ol class="rtoc-mokuji decimal_ol level-1">';
							} else {
								$rtoc .= '<ul class="rtoc-mokuji mokuji_none level-1">';
							}
						} elseif ($level === 2) {
							if ($rtoc_sc_h3_list == 'ul') {
								$rtoc .= '<ul class="rtoc-mokuji mokuji_ul level-2">';
							} elseif ($rtoc_sc_h3_list == 'ol') {
								$rtoc .= '<ol class="rtoc-mokuji mokuji_ol level-2">';
							} elseif ($rtoc_sc_h3_list == 'ol2') {
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
						// $currentlevel =3, $level =2（Previous =h4, Now =h3) .
						// $currentlevel =3, $level =1（Previous =h4, Now =h2) .
						// $currentlevel =2, $level =1（Previous =h3, Now =h2).
						if ($currentlevel === 3) {
							$rtoc .= '</li></ul></li>';
							$currentlevel--;

							if ($level === 1) {
								if ($rtoc_sc_h3_list == 'ul' || $rtoc_sc_h3_list == 'none') {
									$rtoc .= '</ul></li>';
								} elseif ($rtoc_sc_h3_list == 'ol' || $rtoc_sc_h3_list == 'ol2') {
									$rtoc .= '</ol></li>';
								}
								$currentlevel--;
							}
						} elseif ($currentlevel === 2) {
							if ($rtoc_sc_h3_list == 'ul' || $rtoc_sc_h3_list == 'none') {
								$rtoc .= '</li></ul></li>';
							} elseif ($rtoc_sc_h3_list == 'ol' || $rtoc_sc_h3_list == 'ol2') {
								$rtoc .= '</li></ol></li>';
							}
							$currentlevel--;
						}
					}
					$rtoc .= $number_h4;
				}
			}
			// ループ後
			if ($currentlevel === 3) {
				$rtoc .= '</li></ul>';
				$currentlevel--;
			}
			if ($currentlevel === 2) {
				if ($rtoc_sc_h3_list == 'ul' || $rtoc_sc_h3_list == 'none') {
					$rtoc .= '</li></ul>';
				} elseif ($rtoc_sc_h3_list == 'ol' || $rtoc_sc_h3_list == 'ol2') {
					$rtoc .= '</li></ol>';
				}
				$currentlevel--;
			}
			if ($currentlevel === 1) {
				if ($rtoc_sc_h2_list == 'ul' || $rtoc_sc_h2_list == 'none') {
					$rtoc .= '</li></ul>';
				} elseif ($rtoc_sc_h2_list == 'ol' || $rtoc_sc_h2_list == 'ol2') {
					$rtoc .= '</li></ol>';
				}
			}
		}

		$post_id                  = get_the_ID();

		// 目次開閉ボタンを非表示にするかどうか
		$RtocHideButton = get_option('rtoc_exclude_openclose');
		$RtocButton = '<button class="rtoc_open_close ' . $rtoc_sc_display . '"></button>';
		if (!empty($RtocHideButton)) {
			$RtocButton = '';
		}

		// テーマ名を取得しテーマ別に最適化する
		$get_theme_name    = wp_get_theme();
		$theme_name  = $get_theme_name->get('Name');

		$shortcode_content_inside = '<div id="rtoc-mokuji-widget-wrapper" class="rtoc-mokuji-content ' . esc_attr($rtoc_sc_frame_design) . ' ' .  esc_attr($rtoc_sc_animation) . ' ' .  esc_attr($rtoc_sc_display) . ' ' .  esc_attr($rtoc_font) . '" data-id="' .  esc_attr($post_id) . '" data-theme="' .  esc_attr($theme_name) . '"><div id="rtoc-mokuji-title" class="rtoc_left">' . $RtocButton . '<span>' .  esc_html($rtoc_sc_title) . '</span></div>' . $rtoc . '</div>';

		$shortcode_content = '';
		if (!isset($atts['heading']) || $atts['heading'] == null) {
			if ($admin_heading == 'h2' && $h2_count >= $rtoc_admin_count) {
				$shortcode_content = $shortcode_content_inside;
			} elseif ($admin_heading == 'h3' && $h3_count >= $rtoc_admin_count) {
				$shortcode_content = $shortcode_content_inside;
			} elseif ($admin_heading == 'h4' && $h4_count >= $rtoc_admin_count) {
				$shortcode_content = $shortcode_content_inside;
			}
		} elseif ($atts['heading'] == 'h2' && $h2_count >= $rtoc_admin_count) {
			$shortcode_content = $shortcode_content_inside;
		} elseif ($atts['heading'] == 'h3' && $h3_count >= $rtoc_admin_count) {
			$shortcode_content = $shortcode_content_inside;
		} elseif ($atts['heading'] == 'h4' && $h4_count >= $rtoc_admin_count) {
			$shortcode_content = $shortcode_content_inside;
		}
		return $shortcode_content;
	}
	if (is_page()) {
		if (is_page($RtocPageId)) {
			return;
		}
		shortcode_atts(array(
			'title'         => '',
			'title_display' => '',
			'heading'       => '',
			'list_h2_type'  => '',
			'list_h3_type'  => '',
			'display'       => '',
			'color'         => '',
			'frame_design'  => '',
			'animation'     => '',
		), $atts);

		
		$post_data = get_post();
		$content    = $post_data->post_content;
		$content    = strip_shortcodes($content);
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
		
		preg_match_all('/<h2.*?>(.*?)<\/h2>/', $content, $h2_list);
		preg_match_all('/<h[2-3].*?>(.*?)<\/h[2-3]>/', $content, $h3_list);
		preg_match_all('/<h[2-4].*?>(.*?)<\/h[2-4]>/', $content, $h4_list);

		if (is_array($h2_list) || isset($h2_list) || !empty($h2_list)) {
			$h2_count = count($h2_list[0]);
		}
		if (is_array($h3_list) || isset($h3_list) || !empty($h3_list)) {
			$h3_count = count($h3_list[0]);
		}
		if (is_array($h4_list) || isset($h4_list) || !empty($h4_list)) {
			$h4_count = count($h4_list[0]);
		}
		$currentlevel = 0;
		$idcount      = 1;
		$rtoc         = '';

		$in_flag = false;
		$trace = debug_backtrace();
		foreach ($trace as $stp) {
			if (isset($stp['function'])) {
				if ($stp['function'] === "dynamic_sidebar") {
					$in_flag = true;
					break;
				}
			}
		}

		// それぞれのショートコードの引数を取得して変数化
		if (!isset($atts['title']) || $atts['title'] == '') {
			/*
			サイドバー内でショートコードが呼び出されている場合、
			目次タイトルが空白でも
			 */
			if ($in_flag == true) {
				$rtoc_sc_title = '';
			} else {
				$rtoc_sc_title = get_option('rtoc_title');
			}
		} else {
			$rtoc_sc_title = $atts['title'];
		}
		if (!isset($atts['title_display']) || $atts['title_display'] == '') {
			$rtoc_sc_title_display = 'rtoc_' . get_option('rtoc_title_display');
		} else {
			$rtoc_sc_title_display = 'rtoc_' . $atts['title_display'];
		}
		if (isset($atts['list_h2_type'])) {
			if ($atts['list_h2_type'] == 'round') {
				$atts['list_h2_type'] = 'ul';
				$rtoc_sc_h2_list = $atts['list_h2_type'];
			} elseif ($atts['list_h2_type'] == 'number1') {
				$atts['list_h2_type'] = 'ol';
				$rtoc_sc_h2_list = $atts['list_h2_type'];
			} elseif ($atts['list_h2_type'] == 'number2') {
				$atts['list_h2_type'] = 'ol2';
				$rtoc_sc_h2_list = $atts['list_h2_type'];
			} else {
				$rtoc_sc_h2_list = get_option('rtoc_list_h2_type');
			}
		} else {
			$rtoc_sc_h2_list = get_option('rtoc_list_h2_type');
		}
		if (isset($atts['list_h3_type'])) {
			if ($atts['list_h3_type'] == 'round') {
				$atts['list_h3_type'] = 'ul';
				$rtoc_sc_h3_list = $atts['list_h3_type'];
			} elseif ($atts['list_h3_type'] == 'number1') {
				$atts['list_h3_type'] = 'ol';
				$rtoc_sc_h3_list = $atts['list_h3_type'];
			} elseif ($atts['list_h3_type'] == 'number2') {
				$atts['list_h3_type'] = 'ol2';
				$rtoc_sc_h3_list = $atts['list_h3_type'];
			} else {
				$rtoc_sc_h3_list = get_option('rtoc_list_h3_type');
			}
		} else {
			$rtoc_sc_h3_list = get_option('rtoc_list_h3_type');
		}
		if (!isset($atts['display']) || $atts['display'] == '') {
			if (get_option('rtoc_initial_display') == 'open') {
				$rtoc_sc_display = 'rtoc_open';
			} elseif (get_option('rtoc_initial_display') == 'close') {
				$rtoc_sc_display = 'rtoc_close';
			} else {
				$rtoc_sc_display = get_option('rtoc_initial_display');
			}
		} else {
			if ($atts['display'] == 'open') {
				$atts['display'] = 'rtoc_open';
				$rtoc_sc_display = $atts['display'];
			} elseif ($atts['display'] == 'close') {
				$atts['display'] = 'rtoc_close';
				$rtoc_sc_display = $atts['display'];
			} else {
				$rtoc_sc_display = get_option('rtoc_initial_display');
			}
		}
		if (!isset($atts['frame_design']) || $atts['frame_design'] == '') {
			$rtoc_sc_frame_design = get_option('rtoc_frame_design');
		} else {
			$rtoc_sc_frame_design = $atts['frame_design'];
		}
		if (isset($atts['animation'])) {
			if ($atts['animation'] == 'fade') {
				$atts['animation'] = 'animation-fade';
				$rtoc_sc_animation = $atts['animation'];
			} elseif ($atts['animation'] == 'slide') {
				$atts['animation'] = 'animation-slide';
				$rtoc_sc_animation = $atts['animation'];
			} elseif ($atts['animation'] == 'none') {
				$atts['animation'] = 'animation-none';
				$rtoc_sc_animation = $atts['animation'];
			} else {
				$rtoc_sc_animation = get_option('rtoc_animation');
			}
		} else {
			$rtoc_sc_animation = get_option('rtoc_animation');
		}

		$admin_heading     = get_option('rtoc_headline_display');
		$rtoc_admin_count  = get_option('rtoc_display_headline_amount');
		$rtoc_font         = get_option('rtoc_font');
		$rtoc_list_h2_type = get_option('rtoc_list_h2_type');
		$rtoc_list_h3_type = get_option('rtoc_list_h3_type');

		// ショートコードがない場合
		if (!isset($atts['heading']) || $atts['heading'] == null) {
			if ($admin_heading == 'h2') {
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
			} elseif ($admin_heading == 'h3') {
				for ($i = 0; $i < $h3_count; $i++) {
					preg_match('/<h[2-3].*?>/u', $h3_list[0][$i], $matches2);
					$rtocLink = "rtoc-" . $idcount;
					$idcount++;
					$level = 0;
					if (preg_match("/<h[2-3].*?>/", $matches2[0])) {
						if (strpos($matches2[0], 'id=') !== false) {
							preg_match('/id=("|\'|([ぁ-んァ-ヶー一-龠a-zA-Z0-9０-９、。①②③④⑤⑥⑦⑧⑨!#:;&~@%+$\*\^\(\)\[\]\|\/\.,_-]+))+/', $h3_list[0][$i], $rtocExi);
							$number_h3_li = preg_replace('/<h[2-3].*?>(.*?)<\/h[2-3]>/', '<li class="rtoc-item"><a href="#' . $rtocExi[2] . '">$1</a>', $h3_list[0][$i]);
						} else {
							$number_h3_li = preg_replace('/<h[2-3].*?>(.*?)<\/h[2-3]>/', '<li class="rtoc-item"><a href="#' . $rtocLink . '">$1</a>', $h3_list[0][$i]);
						}
						// 不要タグを空文字へ.
						$number_h3_li = rtoc_unnecessary_tags_delete($number_h3_li);

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
			} elseif ($admin_heading == 'h4') {
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
							// $currentlevel =3, $level =2（Previous =h4, Now =h3) .
							// $currentlevel =3, $level =1（Previous =h4, Now =h2) .
							// $currentlevel =2, $level =1（Previous =h3, Now =h2).
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
				// ループ後
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
		} elseif (isset($atts['heading']) && $atts['heading'] == 'h2') {
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
					if ($currentlevel < $level) {
						if ($rtoc_sc_h2_list == 'ul') {
							$rtoc .= '<ul class="rtoc-mokuji mokuji_ul level-1">';
						} elseif ($rtoc_sc_h2_list == 'ol') {
							$rtoc .= '<ol class="rtoc-mokuji mokuji_ol level-1">';
						} elseif ($rtoc_sc_h2_list == 'ol2') {
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
			if ($rtoc_sc_h2_list == 'ul' || $rtoc_sc_h2_list == 'none') {
				$rtoc .= '</ul>';
			} elseif ($rtoc_sc_h2_list == 'ol' || $rtoc_sc_h2_list == 'ol2') {
				$rtoc .= '</ol>';
			}
		} elseif (isset($atts['heading']) && $atts['heading'] == 'h3') {
			for ($i = 0; $i < $h3_count; $i++) {
				preg_match('/<h[2-3].*?>/u', $h3_list[0][$i], $matches2);
				$rtocLink = "rtoc-" . $idcount;
				$idcount++;
				$level = 0;
				if (preg_match("/<h[2-3].*?>/", $matches2[0])) {
					if (strpos($matches2[0], 'id=') !== false) {
						preg_match('/id=("|\'|([ぁ-んァ-ヶー一-龠a-zA-Z0-9０-９、。①②③④⑤⑥⑦⑧⑨!#:;&~@%+$\*\^\(\)\[\]\|\/\.,_-]+))+/', $h3_list[0][$i], $rtocExi);
						$number_h3_li = preg_replace('/<h[2-3].*?>(.*?)<\/h[2-3]>/', '<li class="rtoc-item"><a href="#' . $rtocExi[2] . '">$1</a>', $h3_list[0][$i]);
					} else {
						$number_h3_li = preg_replace('/<h[2-3].*?>(.*?)<\/h[2-3]>/', '<li class="rtoc-item"><a href="#' . $rtocLink . '">$1</a>', $h3_list[0][$i]);
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
							if ($rtoc_sc_h2_list == 'ul') {
								$rtoc .= '<ul class="rtoc-mokuji mokuji_ul level-1">';
							} elseif ($rtoc_sc_h2_list == 'ol') {
								$rtoc .= '<ol class="rtoc-mokuji mokuji_ol level-1">';
							} elseif ($rtoc_sc_h2_list == 'ol2') {
								$rtoc .= '<ol class="rtoc-mokuji decimal_ol level-1">';
							} else {
								$rtoc .= '<ul class="rtoc-mokuji mokuji_none level-1">';
							}
						} elseif ($level === 2) {
							if ($rtoc_sc_h3_list == 'ul') {
								$rtoc .= '<ul class="rtoc-mokuji mokuji_ul level-2">';
							} elseif ($rtoc_sc_h3_list == 'ol') {
								$rtoc .= '<ol class="rtoc-mokuji mokuji_ol level-2">';
							} elseif ($rtoc_sc_h3_list == 'ol2') {
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
							if ($rtoc_sc_h3_list == 'ul' || $rtoc_sc_h3_list == 'none') {
								$rtoc .= '</li></ul></li>';
							} elseif ($rtoc_sc_h3_list == 'ol' || $rtoc_sc_h3_list == 'ol2') {
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
				if ($rtoc_sc_h3_list == 'ul' || $rtoc_sc_h3_list == 'none') {
					$rtoc .= '</li></ul>';
				} elseif ($rtoc_sc_h3_list == 'ol' || $rtoc_sc_h3_list == 'ol2') {
					$rtoc .= '</li></ol>';
				}
				$currentlevel--;
			}
			if ($currentlevel === 1) {
				if ($rtoc_sc_h2_list == 'ul' || $rtoc_sc_h2_list == 'none') {
					$rtoc .= '</li></ul>';
				} elseif ($rtoc_sc_h2_list == 'ol' || $rtoc_sc_h2_list == 'ol2') {
					$rtoc .= '</li></ol>';
				}
			}
		} elseif (isset($atts['heading']) && $atts['heading'] == 'h4') {
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
					if (strpos($h4_list[0][$i], '<h2') !== false) {
						$level = 1;
					} elseif (strpos($h4_list[0][$i], '<h3') !== false) {
						$level = 2;
					} elseif (strpos($h4_list[0][$i], '<h4') !== false) {
						$level = 3;
					}
					// 同じ level の見出しが続いた場合.
					if ($currentlevel === $level) {
						$number_h4 = '</li>' . $number_h4_li;
					} else {
						$number_h4 = $number_h4_li;
					}

					if ($currentlevel < $level) {
						if ($level === 1) {
							if ($rtoc_sc_h2_list == 'ul') {
								$rtoc .= '<ul class="rtoc-mokuji mokuji_ul level-1">';
							} elseif ($rtoc_sc_h2_list == 'ol') {
								$rtoc .= '<ol class="rtoc-mokuji mokuji_ol level-1">';
							} elseif ($rtoc_sc_h2_list == 'ol2') {
								$rtoc .= '<ol class="rtoc-mokuji decimal_ol level-1">';
							} else {
								$rtoc .= '<ul class="rtoc-mokuji mokuji_none level-1">';
							}
						} elseif ($level === 2) {
							if ($rtoc_sc_h3_list == 'ul') {
								$rtoc .= '<ul class="rtoc-mokuji mokuji_ul level-2">';
							} elseif ($rtoc_sc_h3_list == 'ol') {
								$rtoc .= '<ol class="rtoc-mokuji mokuji_ol level-2">';
							} elseif ($rtoc_sc_h3_list == 'ol2') {
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
						// $currentlevel =3, $level =2（Previous =h4, Now =h3) .
						// $currentlevel =3, $level =1（Previous =h4, Now =h2) .
						// $currentlevel =2, $level =1（Previous =h3, Now =h2).
						if ($currentlevel === 3) {
							$rtoc .= '</li></ul></li>';
							$currentlevel--;

							if ($level === 1) {
								if ($rtoc_sc_h3_list == 'ul' || $rtoc_sc_h3_list == 'none') {
									$rtoc .= '</ul></li>';
								} elseif ($rtoc_sc_h3_list == 'ol' || $rtoc_sc_h3_list == 'ol2') {
									$rtoc .= '</ol></li>';
								}
								$currentlevel--;
							}
						} elseif ($currentlevel === 2) {
							if ($rtoc_sc_h3_list == 'ul' || $rtoc_sc_h3_list == 'none') {
								$rtoc .= '</li></ul></li>';
							} elseif ($rtoc_sc_h3_list == 'ol' || $rtoc_sc_h3_list == 'ol2') {
								$rtoc .= '</li></ol></li>';
							}
							$currentlevel--;
						}
					}
					$rtoc .= $number_h4;
				}
			}
			// ループ後
			if ($currentlevel === 3) {
				$rtoc .= '</li></ul>';
				$currentlevel--;
			}
			if ($currentlevel === 2) {
				if ($rtoc_sc_h3_list == 'ul' || $rtoc_sc_h3_list == 'none') {
					$rtoc .= '</li></ul>';
				} elseif ($rtoc_sc_h3_list == 'ol' || $rtoc_sc_h3_list == 'ol2') {
					$rtoc .= '</li></ol>';
				}
				$currentlevel--;
			}
			if ($currentlevel === 1) {
				if ($rtoc_sc_h2_list == 'ul' || $rtoc_sc_h2_list == 'none') {
					$rtoc .= '</li></ul>';
				} elseif ($rtoc_sc_h2_list == 'ol' || $rtoc_sc_h2_list == 'ol2') {
					$rtoc .= '</li></ol>';
				}
			}
		}

		$post_id                  = get_the_ID();

		// 目次開閉ボタンを非表示にするかどうか
		$RtocHideButton = get_option('rtoc_exclude_openclose');
		$RtocButton = '<button class="rtoc_open_close ' . $rtoc_sc_display . '"></button>';
		if (!empty($RtocHideButton)) {
			$RtocButton = '';
		}

		// テーマ名を取得しテーマ別に最適化する
		$get_theme_name    = wp_get_theme();
		$theme_name  = $get_theme_name->get('Name');

		$shortcode_content_inside = '<div id="rtoc-mokuji-widget-wrapper" class="rtoc-mokuji-content ' . esc_attr($rtoc_sc_frame_design) . ' ' .  esc_attr($rtoc_sc_animation) . ' ' .  esc_attr($rtoc_sc_display) . ' ' .  esc_attr($rtoc_font) . '" data-id="' .  esc_attr($post_id) . '" data-theme="' .  esc_attr($theme_name) . '"><div id="rtoc-mokuji-title" class="rtoc_left">' . $RtocButton . '<span>' .  esc_html($rtoc_sc_title) . '</span></div>' . $rtoc . '</div>';

		$shortcode_content = '';
		if (!isset($atts['heading']) || $atts['heading'] == null) {
			if ($admin_heading == 'h2' && $h2_count >= $rtoc_admin_count) {
				$shortcode_content = $shortcode_content_inside;
			} elseif ($admin_heading == 'h3' && $h3_count >= $rtoc_admin_count) {
				$shortcode_content = $shortcode_content_inside;
			} elseif ($admin_heading == 'h4' && $h4_count >= $rtoc_admin_count) {
				$shortcode_content = $shortcode_content_inside;
			}
		} elseif ($atts['heading'] == 'h2' && $h2_count >= $rtoc_admin_count) {
			$shortcode_content = $shortcode_content_inside;
		} elseif ($atts['heading'] == 'h3' && $h3_count >= $rtoc_admin_count) {
			$shortcode_content = $shortcode_content_inside;
		} elseif ($atts['heading'] == 'h4' && $h4_count >= $rtoc_admin_count) {
			$shortcode_content = $shortcode_content_inside;
		}
		return $shortcode_content;
	}
	if (is_category() && function_exists("cps_category_description")) {
		$t_id       = get_category(intval(get_query_var('cat')))->term_id;
		$cat_class  = get_category($t_id);
		$cat_option = get_option($t_id);
		if (isset($cat_option['cps_meta_content'])) {
			$content    = stripslashes($cat_option['cps_meta_content']);
		} else {
			return;
		}
	} elseif (is_category() && 'jinr' === get_template()) {
		//全固定ページID取得
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
		$jinr_category_edit_toggle = get_post_meta($target_page_id, '_jinr_category_edit', true);
		if ($jinr_category_edit_toggle == "1") {
			$content = get_post($target_page_id);
			if (empty($content)) {
				return;
			}
			$content = $content->post_content;
		} else {
			return;
		}
		preg_match_all('/<h2.*?>(.*?)<\/h2>/', $content, $h2_list);
		preg_match_all('/<h[2-3].*?>(.*?)<\/h[2-3]>/', $content, $h3_list);
		preg_match_all('/<h[2-4].*?>(.*?)<\/h[2-4]>/', $content, $h4_list);

		if (is_array($h2_list) || isset($h2_list) || !empty($h2_list)) {
			$h2_count = count($h2_list[0]);
		}
		if (is_array($h3_list) || isset($h3_list) || !empty($h3_list)) {
			$h3_count = count($h3_list[0]);
		}
		if (is_array($h4_list) || isset($h4_list) || !empty($h4_list)) {
			$h4_count = count($h4_list[0]);
		}
		$currentlevel = 0;
		$idcount      = 1;
		$rtoc         = '';

		$in_flag = false;
		$trace = debug_backtrace();
		foreach ($trace as $stp) {
			if (isset($stp['function'])) {
				if ($stp['function'] === "dynamic_sidebar") {
					$in_flag = true;
					break;
				}
			}
		}

		// それぞれのショートコードの引数を取得して変数化
		if (!isset($atts['title']) || $atts['title'] == '') {
			/*
			サイドバー内でショートコードが呼び出されている場合、
			目次タイトルが空白でも
			 */
			if ($in_flag == true) {
				$rtoc_sc_title = '';
			} else {
				$rtoc_sc_title = get_option('rtoc_title');
			}
		} else {
			$rtoc_sc_title = $atts['title'];
		}
		if (!isset($atts['title_display']) || $atts['title_display'] == '') {
			$rtoc_sc_title_display = 'rtoc_' . get_option('rtoc_title_display');
		} else {
			$rtoc_sc_title_display = 'rtoc_' . $atts['title_display'];
		}
		if (isset($atts['list_h2_type'])) {
			if ($atts['list_h2_type'] == 'round') {
				$atts['list_h2_type'] = 'ul';
				$rtoc_sc_h2_list = $atts['list_h2_type'];
			} elseif ($atts['list_h2_type'] == 'number1') {
				$atts['list_h2_type'] = 'ol';
				$rtoc_sc_h2_list = $atts['list_h2_type'];
			} elseif ($atts['list_h2_type'] == 'number2') {
				$atts['list_h2_type'] = 'ol2';
				$rtoc_sc_h2_list = $atts['list_h2_type'];
			} else {
				$rtoc_sc_h2_list = get_option('rtoc_list_h2_type');
			}
		} else {
			$rtoc_sc_h2_list = get_option('rtoc_list_h2_type');
		}
		if (isset($atts['list_h3_type'])) {
			if ($atts['list_h3_type'] == 'round') {
				$atts['list_h3_type'] = 'ul';
				$rtoc_sc_h3_list = $atts['list_h3_type'];
			} elseif ($atts['list_h3_type'] == 'number1') {
				$atts['list_h3_type'] = 'ol';
				$rtoc_sc_h3_list = $atts['list_h3_type'];
			} elseif ($atts['list_h3_type'] == 'number2') {
				$atts['list_h3_type'] = 'ol2';
				$rtoc_sc_h3_list = $atts['list_h3_type'];
			} else {
				$rtoc_sc_h3_list = get_option('rtoc_list_h3_type');
			}
		} else {
			$rtoc_sc_h3_list = get_option('rtoc_list_h3_type');
		}
		if (!isset($atts['display']) || $atts['display'] == '') {
			if (get_option('rtoc_initial_display') == 'open') {
				$rtoc_sc_display = 'rtoc_open';
			} elseif (get_option('rtoc_initial_display') == 'close') {
				$rtoc_sc_display = 'rtoc_close';
			} else {
				$rtoc_sc_display = get_option('rtoc_initial_display');
			}
		} else {
			if ($atts['display'] == 'open') {
				$atts['display'] = 'rtoc_open';
				$rtoc_sc_display = $atts['display'];
			} elseif ($atts['display'] == 'close') {
				$atts['display'] = 'rtoc_close';
				$rtoc_sc_display = $atts['display'];
			} else {
				$rtoc_sc_display = get_option('rtoc_initial_display');
			}
		}
		if (!isset($atts['frame_design']) || $atts['frame_design'] == '') {
			$rtoc_sc_frame_design = get_option('rtoc_frame_design');
		} else {
			if($atts['frame_design'] == 'frame1'){
				$rtoc_sc_frame_design = $atts['frame_design'];
			} elseif ($atts['frame_design'] == 'frame2') {
				$rtoc_sc_frame_design = $atts['frame_design'];
			} elseif ($atts['frame_design'] == 'frame3') {
				$rtoc_sc_frame_design = $atts['frame_design'];
			} elseif ($atts['frame_design'] == 'frame4') {
				$rtoc_sc_frame_design = $atts['frame_design'];
			} elseif ($atts['frame_design'] == 'frame5') {
				$rtoc_sc_frame_design = $atts['frame_design'];
			} else {
				$rtoc_sc_frame_design = get_option('rtoc_frame_design');
			}
		}
		if (isset($atts['animation'])) {
			if ($atts['animation'] == 'fade') {
				$atts['animation'] = 'animation-fade';
				$rtoc_sc_animation = $atts['animation'];
			} elseif ($atts['animation'] == 'slide') {
				$atts['animation'] = 'animation-slide';
				$rtoc_sc_animation = $atts['animation'];
			} elseif ($atts['animation'] == 'none') {
				$atts['animation'] = 'animation-none';
				$rtoc_sc_animation = $atts['animation'];
			} else {
				$rtoc_sc_animation = get_option('rtoc_animation');
			}
		} else {
			$rtoc_sc_animation = get_option('rtoc_animation');
		}

		$admin_heading     = get_option('rtoc_headline_display');
		$rtoc_admin_count  = get_option('rtoc_display_headline_amount');
		$rtoc_font         = get_option('rtoc_font');
		$rtoc_list_h2_type = get_option('rtoc_list_h2_type');
		$rtoc_list_h3_type = get_option('rtoc_list_h3_type');

		// ショートコードがない場合
		if (!isset($atts['heading']) || $atts['heading'] == null) {
			if ($admin_heading == 'h2') {
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
			} elseif ($admin_heading == 'h3') {
				for ($i = 0; $i < $h3_count; $i++) {
					preg_match('/<h[2-3].*?>/u', $h3_list[0][$i], $matches2);
					$rtocLink = "rtoc-" . $idcount;
					$idcount++;
					$level = 0;
					if (preg_match("/<h[2-3].*?>/", $matches2[0])) {
						if (strpos($matches2[0], 'id=') !== false) {
							preg_match('/id=("|\'|([ぁ-んァ-ヶー一-龠a-zA-Z0-9０-９、。①②③④⑤⑥⑦⑧⑨!#:;&~@%+$\*\^\(\)\[\]\|\/\.,_-]+))+/', $h3_list[0][$i], $rtocExi);
							$number_h3_li = preg_replace('/<h[2-3].*?>(.*?)<\/h[2-3]>/', '<li class="rtoc-item"><a href="#' . $rtocExi[2] . '">$1</a>', $h3_list[0][$i]);
						} else {
							$number_h3_li = preg_replace('/<h[2-3].*?>(.*?)<\/h[2-3]>/', '<li class="rtoc-item"><a href="#' . $rtocLink . '">$1</a>', $h3_list[0][$i]);
						}
						// 不要タグを空文字へ.
						$number_h3_li = rtoc_unnecessary_tags_delete($number_h3_li);

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
			} elseif ($admin_heading == 'h4') {
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
							// $currentlevel =3, $level =2（Previous =h4, Now =h3) .
							// $currentlevel =3, $level =1（Previous =h4, Now =h2) .
							// $currentlevel =2, $level =1（Previous =h3, Now =h2).
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
				// ループ後
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
		} elseif (isset($atts['heading']) && $atts['heading'] == 'h2') {
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
					if ($currentlevel < $level) {
						if ($rtoc_sc_h2_list == 'ul') {
							$rtoc .= '<ul class="rtoc-mokuji mokuji_ul level-1">';
						} elseif ($rtoc_sc_h2_list == 'ol') {
							$rtoc .= '<ol class="rtoc-mokuji mokuji_ol level-1">';
						} elseif ($rtoc_sc_h2_list == 'ol2') {
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
			if ($rtoc_sc_h2_list == 'ul' || $rtoc_sc_h2_list == 'none') {
				$rtoc .= '</ul>';
			} elseif ($rtoc_sc_h2_list == 'ol' || $rtoc_sc_h2_list == 'ol2') {
				$rtoc .= '</ol>';
			}
		} elseif (isset($atts['heading']) && $atts['heading'] == 'h3') {
			for ($i = 0; $i < $h3_count; $i++) {
				preg_match('/<h[2-3].*?>/u', $h3_list[0][$i], $matches2);
				$rtocLink = "rtoc-" . $idcount;
				$idcount++;
				$level = 0;
				if (preg_match("/<h[2-3].*?>/", $matches2[0])) {
					if (strpos($matches2[0], 'id=') !== false) {
						preg_match('/id=("|\'|([ぁ-んァ-ヶー一-龠a-zA-Z0-9０-９、。①②③④⑤⑥⑦⑧⑨!#:;&~@%+$\*\^\(\)\[\]\|\/\.,_-]+))+/', $h3_list[0][$i], $rtocExi);
						$number_h3_li = preg_replace('/<h[2-3].*?>(.*?)<\/h[2-3]>/', '<li class="rtoc-item"><a href="#' . $rtocExi[2] . '">$1</a>', $h3_list[0][$i]);
					} else {
						$number_h3_li = preg_replace('/<h[2-3].*?>(.*?)<\/h[2-3]>/', '<li class="rtoc-item"><a href="#' . $rtocLink . '">$1</a>', $h3_list[0][$i]);
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
							if ($rtoc_sc_h2_list == 'ul') {
								$rtoc .= '<ul class="rtoc-mokuji mokuji_ul level-1">';
							} elseif ($rtoc_sc_h2_list == 'ol') {
								$rtoc .= '<ol class="rtoc-mokuji mokuji_ol level-1">';
							} elseif ($rtoc_sc_h2_list == 'ol2') {
								$rtoc .= '<ol class="rtoc-mokuji decimal_ol level-1">';
							} else {
								$rtoc .= '<ul class="rtoc-mokuji mokuji_none level-1">';
							}
						} elseif ($level === 2) {
							if ($rtoc_sc_h3_list == 'ul') {
								$rtoc .= '<ul class="rtoc-mokuji mokuji_ul level-2">';
							} elseif ($rtoc_sc_h3_list == 'ol') {
								$rtoc .= '<ol class="rtoc-mokuji mokuji_ol level-2">';
							} elseif ($rtoc_sc_h3_list == 'ol2') {
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
							if ($rtoc_sc_h3_list == 'ul' || $rtoc_sc_h3_list == 'none') {
								$rtoc .= '</li></ul></li>';
							} elseif ($rtoc_sc_h3_list == 'ol' || $rtoc_sc_h3_list == 'ol2') {
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
				if ($rtoc_sc_h3_list == 'ul' || $rtoc_sc_h3_list == 'none') {
					$rtoc .= '</li></ul>';
				} elseif ($rtoc_sc_h3_list == 'ol' || $rtoc_sc_h3_list == 'ol2') {
					$rtoc .= '</li></ol>';
				}
				$currentlevel--;
			}
			if ($currentlevel === 1) {
				if ($rtoc_sc_h2_list == 'ul' || $rtoc_sc_h2_list == 'none') {
					$rtoc .= '</li></ul>';
				} elseif ($rtoc_sc_h2_list == 'ol' || $rtoc_sc_h2_list == 'ol2') {
					$rtoc .= '</li></ol>';
				}
			}
		} elseif (isset($atts['heading']) && $atts['heading'] == 'h4') {
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
					if (strpos($h4_list[0][$i], '<h2') !== false) {
						$level = 1;
					} elseif (strpos($h4_list[0][$i], '<h3') !== false) {
						$level = 2;
					} elseif (strpos($h4_list[0][$i], '<h4') !== false) {
						$level = 3;
					}
					// 同じ level の見出しが続いた場合.
					if ($currentlevel === $level) {
						$number_h4 = '</li>' . $number_h4_li;
					} else {
						$number_h4 = $number_h4_li;
					}

					if ($currentlevel < $level) {
						if ($level === 1) {
							if ($rtoc_sc_h2_list == 'ul') {
								$rtoc .= '<ul class="rtoc-mokuji mokuji_ul level-1">';
							} elseif ($rtoc_sc_h2_list == 'ol') {
								$rtoc .= '<ol class="rtoc-mokuji mokuji_ol level-1">';
							} elseif ($rtoc_sc_h2_list == 'ol2') {
								$rtoc .= '<ol class="rtoc-mokuji decimal_ol level-1">';
							} else {
								$rtoc .= '<ul class="rtoc-mokuji mokuji_none level-1">';
							}
						} elseif ($level === 2) {
							if ($rtoc_sc_h3_list == 'ul') {
								$rtoc .= '<ul class="rtoc-mokuji mokuji_ul level-2">';
							} elseif ($rtoc_sc_h3_list == 'ol') {
								$rtoc .= '<ol class="rtoc-mokuji mokuji_ol level-2">';
							} elseif ($rtoc_sc_h3_list == 'ol2') {
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
						// $currentlevel =3, $level =2（Previous =h4, Now =h3) .
						// $currentlevel =3, $level =1（Previous =h4, Now =h2) .
						// $currentlevel =2, $level =1（Previous =h3, Now =h2).
						if ($currentlevel === 3) {
							$rtoc .= '</li></ul></li>';
							$currentlevel--;

							if ($level === 1) {
								if ($rtoc_sc_h3_list == 'ul' || $rtoc_sc_h3_list == 'none') {
									$rtoc .= '</ul></li>';
								} elseif ($rtoc_sc_h3_list == 'ol' || $rtoc_sc_h3_list == 'ol2') {
									$rtoc .= '</ol></li>';
								}
								$currentlevel--;
							}
						} elseif ($currentlevel === 2) {
							if ($rtoc_sc_h3_list == 'ul' || $rtoc_sc_h3_list == 'none') {
								$rtoc .= '</li></ul></li>';
							} elseif ($rtoc_sc_h3_list == 'ol' || $rtoc_sc_h3_list == 'ol2') {
								$rtoc .= '</li></ol></li>';
							}
							$currentlevel--;
						}
					}
					$rtoc .= $number_h4;
				}
			}
			// ループ後
			if ($currentlevel === 3) {
				$rtoc .= '</li></ul>';
				$currentlevel--;
			}
			if ($currentlevel === 2) {
				if ($rtoc_sc_h3_list == 'ul' || $rtoc_sc_h3_list == 'none') {
					$rtoc .= '</li></ul>';
				} elseif ($rtoc_sc_h3_list == 'ol' || $rtoc_sc_h3_list == 'ol2') {
					$rtoc .= '</li></ol>';
				}
				$currentlevel--;
			}
			if ($currentlevel === 1) {
				if ($rtoc_sc_h2_list == 'ul' || $rtoc_sc_h2_list == 'none') {
					$rtoc .= '</li></ul>';
				} elseif ($rtoc_sc_h2_list == 'ol' || $rtoc_sc_h2_list == 'ol2') {
					$rtoc .= '</li></ol>';
				}
			}
		}

		$post_id                  = get_the_ID();

		// 目次開閉ボタンを非表示にするかどうか
		$RtocHideButton = get_option('rtoc_exclude_openclose');
		$RtocButton = '<button class="rtoc_open_close ' . $rtoc_sc_display . '"></button>';
		if (!empty($RtocHideButton)) {
			$RtocButton = '';
		}

		// テーマ名を取得しテーマ別に最適化する
		$get_theme_name    = wp_get_theme();
		$theme_name  = $get_theme_name->get('Name');

		$shortcode_content_inside = '<div id="rtoc-mokuji-widget-wrapper" class="rtoc-mokuji-content ' . esc_attr($rtoc_sc_frame_design) . ' ' .  esc_attr($rtoc_sc_animation) . ' ' .  esc_attr($rtoc_sc_display) . ' ' .  esc_attr($rtoc_font) . '" data-id="' .  esc_attr($post_id) . '" data-theme="' .  esc_attr($theme_name) . '"><div id="rtoc-mokuji-title" class="rtoc_left">' . $RtocButton . '<span>' .  esc_html($rtoc_sc_title) . '</span></div>' . $rtoc . '</div>';

		$shortcode_content = '';
		if (!isset($atts['heading']) || $atts['heading'] == null) {
			if ($admin_heading == 'h2' && $h2_count >= $rtoc_admin_count) {
				$shortcode_content = $shortcode_content_inside;
			} elseif ($admin_heading == 'h3' && $h3_count >= $rtoc_admin_count) {
				$shortcode_content = $shortcode_content_inside;
			} elseif ($admin_heading == 'h4' && $h4_count >= $rtoc_admin_count) {
				$shortcode_content = $shortcode_content_inside;
			}
		} elseif ($atts['heading'] == 'h2' && $h2_count >= $rtoc_admin_count) {
			$shortcode_content = $shortcode_content_inside;
		} elseif ($atts['heading'] == 'h3' && $h3_count >= $rtoc_admin_count) {
			$shortcode_content = $shortcode_content_inside;
		} elseif ($atts['heading'] == 'h4' && $h4_count >= $rtoc_admin_count) {
			$shortcode_content = $shortcode_content_inside;
		}
		return $shortcode_content;
	}
}
add_shortcode('rtoc_mokuji', 'rtoc_shortcode');
