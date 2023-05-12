<?php
/**
 * @package Rich Table of Contents
 * （RTOC ver1.2〜）
 */

define( 'RTOC_USE_RATE_KEY', '_rtoc_use_rate' );

if ( ! class_exists( 'RTOC_Use_Rate' ) ) :
	class RTOC_Use_Rate {

		public function __construct() {
			add_action( 'init', array( $this, 'rtoc_event' ) );
		}

		/**
		 * Register
		 */
		public function register() {
			add_action( 'rtoc_database_delete', array( $this, 'db_auto_delete' ) );
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
			add_action( 'wp_ajax_rtoc_userate_ajax', array( $this, 'ajax_db_post_views' ) );
			add_action( 'wp_ajax_nopriv_rtoc_userate_ajax', array( $this, 'ajax_db_post_views' ) );
		}

		/**
		 * RTOC設定の除外ページIDチェック
		 */
		public static function exclude_settings() {
			$post_exclude     = rtrim( get_option( 'rtoc_exclude_post_toc' ), ',' ); // 念の為、末尾のカンマ削除.
			$page_exclude     = rtrim( get_option( 'rtoc_exclude_page_toc' ), ',' );
			$post_exclude_arr = explode( ',', $post_exclude ); // 配列形式へ（単体でも配列形式になる）.
			$page_exclude_arr = explode( ',', $page_exclude );
			$exclude_id_arr   = array_merge( $post_exclude_arr, $page_exclude_arr ); // WP_Query/get_posts用.
			$exclude_id_arr   = array_filter( $exclude_id_arr );

			return array(
				'post_exclude_arr' => $post_exclude_arr,
				'page_exclude_arr' => $page_exclude_arr,
				'exclude_id_arr'   => $exclude_id_arr,
			);
		}

		/**
		 * ファイルの読み込み / jsに変数を渡す
		 */
		public function enqueue_scripts() {
			$exclude_settings = $this->exclude_settings();
			$post_exclude_arr = $exclude_settings['post_exclude_arr'];
			$page_exclude_arr = $exclude_settings['page_exclude_arr'];

			wp_register_script( 'rtoc_userate_js', plugins_url( '/js/rtoc_userate.js', __DIR__ ), array( 'jquery' ), false, true );

			include_once ABSPATH . 'wp-admin/includes/plugin.php';
			$addon_active = is_plugin_active( 'rich-table-of-content-addon/rtoc-addon.php' );

			// 除外ページ以外の投稿/固定ページで読み込む.
			if ( ( is_single() && ! is_single( $post_exclude_arr ) ) || ( is_page() && ! is_page( $page_exclude_arr ) ) ) {

				// Addon無効化時.
				if ( $addon_active === false ) {
					// 「7日間の目次使用率を測定」がonだったら
					$rtoc_userate_measure_7 = get_option( 'rtoc_userate_measure_7' );
					if ( $rtoc_userate_measure_7 === 'on' ) {
						wp_enqueue_script( 'rtoc_userate_js' );
						wp_localize_script(
							'rtoc_userate_js',
							'rtocUseRate',
							array(
								'ajax_url' => admin_url( 'admin-ajax.php' ),
								'action'   => 'rtoc_userate_ajax',
								'nonce'    => wp_create_nonce( 'rtoc_userate_ajax' ),
							)
						);
					}
					// Addon有効化時.
				} else {
					$measure_all    = get_option( 'rtoc_addon_measure_all' );
					$measure_id_arr = RTOC_Addon::measure_settings();
					// 「全ての記事に対してクリック計測を行う」がonなら全ての記事.
					if ( $measure_all === 'on'
						// offなら「クリック計測を行う記事IDを入力」の記事のみ.
						|| ( $measure_all === 'off' && ( is_single( $measure_id_arr ) || is_page( $measure_id_arr ) ) ) ) {
						wp_enqueue_script( 'rtoc_userate_js' );
						wp_localize_script(
							'rtoc_userate_js',
							'rtocUseRate',
							array(
								'ajax_url' => admin_url( 'admin-ajax.php' ),
								'action'   => 'rtoc_userate_ajax',
								'nonce'    => wp_create_nonce( 'rtoc_userate_ajax' ),
							)
						);
					}
				}
			}
		}

		/**
		 * イベント登録
		 */
		public function rtoc_event() {
			include_once ABSPATH . 'wp-admin/includes/plugin.php';
			$addon_active = is_plugin_active( 'rich-table-of-content-addon/rtoc-addon.php' );

			// Addon無効化時.
			if ( $addon_active === false ) {
				// イベントが未登録なら登録する.
				if ( ! wp_next_scheduled( 'rtoc_database_delete' ) ) {
					wp_schedule_event( time(), 'daily', 'rtoc_database_delete' );
				}
			} else {
				// Addonが有効だったら、大元のイベントは削除.
				wp_clear_scheduled_hook( 'rtoc_database_delete' );
			}
		}

		/**
		 * DBの自動削除（目次使用率）
		 */
		public function db_auto_delete() {
			// addon使用時の一時的な無効化では削除しないように.
			$all_plugins   = get_plugins();
			$plugins_count = count( $all_plugins );
			foreach ( $all_plugins as $plugin ) {
				if ( $plugin['Name'] === 'Rich Table of Contents Addon' ) {
					$addon_exist = 1;
				}
			}
			$deactivation_delete = get_option( 'rtoc_addon_deactivation_delete' );
			if ( $addon_exist === 1 && isset( $deactivation_delete ) && $deactivation_delete === 'off' ) {
				return;
			}

			// postmetaから取得.
			global $wpdb;
			$postmeta_arr = $wpdb->get_results(
				$wpdb->prepare(
					"
					SELECT meta_id, meta_key, meta_value
					FROM {$wpdb->prefix}postmeta
					WHERE meta_key = %s
					GROUP BY meta_value
					ORDER BY meta_id ASC
					",
					RTOC_USE_RATE_KEY
				),
				ARRAY_A
			);// db call ok. no-cache ok.

			// SELECTで取り出してるのでアンシリアライズしてから処理.
			$userate_arr        = array();
			$postmeta_arr_count = count( $postmeta_arr );
			foreach ( $postmeta_arr as $postmeta ) {
				$meta_key               = $postmeta['meta_key'];
				$postmeta['meta_value'] = maybe_unserialize( $postmeta['meta_value'] );
				array_push( $userate_arr, $postmeta );
			}
			// 大元側では7日間.
			$relative_date_num = 7;
			$relative_date     = wp_date( 'Y-m-d', strtotime( '-' . $relative_date_num . ' day' ) );
			$postmeta_table    = $wpdb->prefix . 'postmeta';
			foreach ( $userate_arr as $postmeta ) {
				$meta_id   = $postmeta['meta_id'];
				$meta_date = $postmeta['meta_value']['date'];

				if ( $meta_date <= $relative_date ) {
						$wpdb->delete(
							$postmeta_table,
							array(
								'meta_id' => $meta_id,
							),
							array(
								'%s',
							)
						);// db call ok. no-cache ok.
				} elseif ( $meta_date >= $relative_date ) {
					break;
				}
			}
		}

		/**
		 * 【全体】目次使用率を計算しつつデータが無い日を0埋め + データを送る用の配列形式へ
		 * ...大元の7日間計測 / Addon の期間別用
		 *
		 * @param $date_array            - 7日間計測では7日分の日付配列. 期間別比較では入力された日付の差分の日付配列
		 * @param $userate_array_reverse - 目次使用率のデータを[古→新]順にした配列.
		 */
		public static function fill_with_zero_and_array_set_whole( $date_array, $userate_array_reverse ) {
			// データ無し日の処理後のデータを入れる配列を用意.
			$set_each_arr = array(
				'date'    => array(),
				'userate' => array(),
			);
			// データ無し日を0埋めしつつ,データを送る配列形式へ.
			foreach ( $date_array as $date ) {
				foreach ( $userate_array_reverse as $obj ) {
					if ( $date === $obj['date'] ) {
						array_push( $set_each_arr['date'], $date );
						$view     = $obj['sp_view'] + $obj['pc_view'];// 0は入ってこない.
						$oneclick = $obj['sp_oneclick'] + $obj['pc_oneclick'];// 0あり.
						$userate  = self::userate_calc( $oneclick, $view );
						array_push( $set_each_arr['userate'], $userate );
						break;
					} elseif ( $date > $obj['date'] ) {
						array_push( $set_each_arr['date'], $date );
						array_push( $set_each_arr['userate'], 0 );
						break;
					}
				}
			}
			// データが1つも無い場合は、セットした日時分の日付配列を使用.
			if ( empty( $set_each_arr['date'] ) ) {
				$set_each_arr['date'] = $date_array;
			}

			return $set_each_arr;
		}

		/**
		 * 【全体】相対日時の目次使用率の為の集計
		 * ...大元の7日間計測
		 *
		 * 7日間  ... relative_date_userate_aggregate_whole( $post_id, 7 );
		 */
		public static function relative_date_userate_aggregate_whole( $post_id, $relative_date ) {
			$today                 = strtotime( wp_date( 'Y-m-d' ) );
			$set_relative_date_arr = array();
			for ( $i = 0; $i < $relative_date; $i++ ) {
				$day = date( 'Y-m-d', $today - $i * 60 * 60 * 24 );
				array_push( $set_relative_date_arr, $day );
			}
			$userate_arr     = get_post_meta( $post_id, '_rtoc_use_rate', false );
			$input_date      = $relative_date;
			$userate_arr_rev = array_reverse( $userate_arr, true );

			// 使用率を計算しつつデータ無い日を0で埋めて,データを送る用の配列形式へ.
			$set_each_arr = self::fill_with_zero_and_array_set_whole( $set_relative_date_arr, $userate_arr_rev );

			// DBに無い過去のデータを追加.
			$set_each_arr_count = count( $set_each_arr['date'] );
			$set_each_old_index = $set_each_arr_count - 1;
			$old_date           = $set_each_arr['date'][ $set_each_old_index ];
			$difference_count   = $relative_date - $set_each_arr_count;// データが無い日の数.
			$old_target_date    = strtotime( '-1 day', strtotime( $old_date ) );// データが無くなる日(この日から0を入れていく).

			$difference_date_arr = array();
			for ( $i = 0; $i < $difference_count; $i++ ) {
				$day = date( 'Y-m-d', $old_target_date - $i * 60 * 60 * 24 );
				array_push( $difference_date_arr, $day );
			}
			$set_each_arr['date'] = array_merge( $set_each_arr['date'], $difference_date_arr );
			for ( $i = 0; $i < $difference_count; $i++ ) {
				array_push( $set_each_arr['userate'], 0 );
			}

			// 配列調整（フォーマット・順序）.
			$date_arr = array();
			foreach ( $set_each_arr['date'] as $val ) {
				$date = strtotime( $val );
				if ( $input_date !== 7 ) { // 7日間のみﾌｫｰﾏｯﾄが違う
					$format_date = date( 'Y.n.j', $date );
				} else {
					$format_date = date( 'n/j', $date );
				}
				array_unshift( $date_arr, $format_date );
			}
			$userate_arr = array();
			foreach ( $set_each_arr['userate'] as $val ) {
				array_unshift( $userate_arr, $val );
			}

			return array(
				'date_arr'    => $date_arr,
				'userate_arr' => $userate_arr,
			);
		}

		/**
		 * 目次使用率の計算（%）
		 */
		public static function userate_calc( $oneclick, $view ) {
			if ( isset( $oneclick ) && $oneclick != 0 && isset( $view ) && $view != 0 ) {
				$userate = ceil( ( $oneclick / $view ) * 100 );
				if ( $userate > 100 ) {
					$userate = 100;
				}
			} else {
				$userate = 0;
			}
			return $userate;
		}

		/**
		 * Bot
		 */
		public function is_bot() {
			if ( isset( $_SERVER['HTTP_USER_AGENT'] ) ) {
				$ua = wp_strip_all_tags( wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) );
			}
			$bots = array(
				'baiduspider', // Baidu.
				'bingbot', // Bing.
				'googlebot', // Google.
				'msnbot', // MSN.
				'spider', // Sogou.
				'yahoo', // Yahoo.
				'yandex', // Yandex.
			);
			foreach ( $bots as $bot ) {
				if ( stripos( $ua, $bot ) !== false ) {
					return true;
				}
			}
			return false;
		}

		/**
		 * アクセス/1クリック数
		 */
		public function ajax_db_post_views() {
			$isbot = $this->is_bot();
			// RTOC設定の除外ページ.
			$exclude_settings = $this->exclude_settings();
			$post_exclude_arr = $exclude_settings['post_exclude_arr'];
			$page_exclude_arr = $exclude_settings['page_exclude_arr'];

			if ( $isbot === true || is_user_logged_in() || is_preview() ) {
				return;
			}
			// 除外ページではカウントしない（JS自体を読み込ませて無いが念の為).
			if ( ( ! is_single() && is_single( $post_exclude_arr ) ) || ( ! is_page() && is_page( $page_exclude_arr ) ) ) {
				return;
			}

			check_ajax_referer( 'rtoc_userate_ajax', 'security' );

			if ( isset( $_SERVER['REQUEST_METHOD'] ) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['id'] ) && isset( $_POST['media'] ) && isset( $_POST['kind'] ) ) {
				$post_id            = wp_kses_post( wp_unslash( $_POST['id'] ) );
				$media              = wp_kses_post( wp_unslash( $_POST['media'] ) );
				$kind               = wp_kses_post( wp_unslash( $_POST['kind'] ) );
				$today              = wp_date( 'Y-m-d' );
				$userate_arr        = get_post_meta( $post_id, RTOC_USE_RATE_KEY, false );
				$userate_arr_count  = count( $userate_arr );
				$latest_index       = $userate_arr_count - 1;
				$latest_val_date    = $userate_arr[ $latest_index ]['date'];
				$sp_latest_val_view = (int) $userate_arr[ $latest_index ]['sp_view'];
				$pc_latest_val_view = (int) $userate_arr[ $latest_index ]['pc_view'];
				$sp_latest_val_one  = (int) $userate_arr[ $latest_index ]['sp_oneclick'];
				$pc_latest_val_one  = (int) $userate_arr[ $latest_index ]['pc_oneclick'];

				// view.
				if ( $kind === 'view' ) {
					if ( $media === 'sp' ) {
						$sp_view_count = 1;
						$pc_view_count = 0;
					} elseif ( $media === 'pc' ) {
						$sp_view_count = 0;
						$pc_view_count = 1;
					}
					// そのサイトで記事が初めてｱｸｾｽされる or 最新のDBの日付より今日の方が新しい.
					if ( empty( $userate_arr ) || $latest_val_date < $today ) {
						$value = array(
							'date'        => $today,
							'sp_view'     => $sp_view_count,
							'pc_view'     => $pc_view_count,
							'sp_oneclick' => 0,
							'pc_oneclick' => 0,
						);
						add_post_meta( $post_id, RTOC_USE_RATE_KEY, $value );

						// 最新のDBの日付が今日と同じ.
					} elseif ( $latest_val_date === $today ) {
						$prev = array(
							'date'        => $latest_val_date,
							'sp_view'     => $sp_latest_val_view,
							'pc_view'     => $pc_latest_val_view,
							'sp_oneclick' => $sp_latest_val_one,
							'pc_oneclick' => $pc_latest_val_one,
						);
						if ( $media === 'sp' ) {
							++$sp_latest_val_view;
						} elseif ( $media === 'pc' ) {
							++$pc_latest_val_view;
						}
						$value = array(
							'date'        => $latest_val_date,
							'sp_view'     => $sp_latest_val_view,
							'pc_view'     => $pc_latest_val_view,
							'sp_oneclick' => $sp_latest_val_one,
							'pc_oneclick' => $pc_latest_val_one,
						);
						update_post_meta( $post_id, RTOC_USE_RATE_KEY, $value, $prev );
					}
				}

				// one click.
				if ( $kind === 'oneclick' ) {
					if ( $media === 'sp' ) {
						$sp_one_count = 1;
						$pc_one_count = 0;
					} elseif ( $media === 'pc' ) {
						$sp_one_count = 0;
						$pc_one_count = 1;
					}

					// そのサイトで記事が初めてクリックされる or 最新のDBの日付より今日の方が新しい.
					if ( empty( $userate_arr ) || $latest_val_date < $today ) {
						$value = array(
							'date'        => $today,
							'sp_view'     => 0,
							'pc_view'     => 0,
							'sp_oneclick' => $sp_one_count,
							'pc_oneclick' => $pc_one_count,
						);
						add_post_meta( $post_id, RTOC_USE_RATE_KEY, $value );

						// 最新のDBの日付が今日と同じ.
					} elseif ( $latest_val_date === $today ) {
						$prev = array(
							'date'        => $latest_val_date,
							'sp_view'     => $sp_latest_val_view,
							'pc_view'     => $pc_latest_val_view,
							'sp_oneclick' => $sp_latest_val_one,
							'pc_oneclick' => $pc_latest_val_one,
						);
						if ( $media === 'sp' ) {
							++$sp_latest_val_one;
						} elseif ( $media === 'pc' ) {
							++$pc_latest_val_one;
						}
						$value = array(
							'date'        => $latest_val_date,
							'sp_view'     => $sp_latest_val_view,
							'pc_view'     => $pc_latest_val_view,
							'sp_oneclick' => $sp_latest_val_one,
							'pc_oneclick' => $pc_latest_val_one,
						);
						update_post_meta( $post_id, RTOC_USE_RATE_KEY, $value, $prev );
					}
				}
			} else {
				exit();
			}
		}

	} // end class.
endif;
$class_rtoc_userate = new RTOC_Use_Rate();
$class_rtoc_userate->register();
