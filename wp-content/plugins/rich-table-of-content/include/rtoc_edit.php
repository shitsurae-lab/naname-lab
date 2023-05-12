<?php
/**
 * @package Rich Table of Contents
 * （RTOC ver1.2〜）
 */

if ( ! class_exists( 'RTOC_Edit' ) ) :
	class RTOC_Edit {

		public function __construct() {}

		/**
		 * Register
		 */
		public function register() {
			add_action( 'add_meta_boxes', array( $this, 'meta_box' ), 10 );
			add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );
			add_action( 'wp_ajax_rtoc_edit_ajax', array( $this, 'ajax_db_userate_delete' ) );
			add_action( 'wp_ajax_nopriv_rtoc_edit_ajax', array( $this, 'ajax_db_userate_delete' ) );
		}

		/**
		 * ファイルの読み込み / jsに変数を渡す（編集画面のみ = post.php）
		 */
		public function admin_scripts( $hook ) {
			if ( 'post.php' != $hook ) {
				return;
			}
			wp_enqueue_style( 'rtoc_edit_style', plugins_url( '/css/edit_rtoc_style.css', __DIR__ ), array(), 'all' );
			wp_enqueue_script( 'rtoc_edit_js', plugins_url( '/js/rtoc_edit.js', __DIR__ ), array( 'jquery' ), false, true );

			wp_localize_script(
				'rtoc_edit_js',
				'rtocEdit',
				array(
					'ajax_url' => admin_url( 'admin-ajax.php' ),
					'action'   => 'rtoc_edit_ajax',
					'nonce'    => wp_create_nonce( 'rtoc_edit_ajax' ),
				)
			);
		}

		/**
		 * 編集画面が除外IDで指定したページかチェック
		 */
		public function edit_exclude_check() {
			global $post;
			global $post_type;
			$RtocDisplay     = get_option( 'rtoc_display' ); // [目次を表示させるページ] .
			$RtocPostExclude = get_option( 'rtoc_exclude_post_toc' ); // [除外する投稿ID] .
			$RtocPageExclude = get_option( 'rtoc_exclude_page_toc' ); // [除外する固定ページID] .
			$RtocPostId      = explode( ',', $RtocPostExclude ); // [除外する投稿ID] に入力されたカンマ区切りの文字列を配列で返す.
			$RtocPageId      = explode( ',', $RtocPageExclude ); // [除外する固定ページID] に入力されたカンマ区切りの文字列を配列で返す.

			// 除外IDの数を数える.
			if ( is_array( $RtocPostId ) ) {
				$RtocPostId_count = count( $RtocPostId );
			} else {
				$RtocPostId_count = 0;
			}
			if ( is_array( $RtocPageId ) ) {
				$RtocPageId_count = count( $RtocPageId );
			} else {
				$RtocPageId_count = 0;
			}

			$screen_type = '';

			// [目次を表示させるページ] の [投稿] と [固定ページ] 両方チェック有り.
			if ( ! empty( $RtocDisplay['post'] ) && ! empty( $RtocDisplay['page'] ) ) {
				// 管理画面が投稿なら.
				if ( $post_type === 'post' ) {
					for ( $i = 0; $i < $RtocPostId_count; ++$i ) {
						if ( $post->ID == $RtocPostId[ $i ] ) {
							return;
						} elseif ( empty( $screen_type ) ) {
							$screen_type = 'post';
						}
					}
					// 管理画面が固定ページなら.
				} elseif ( $post_type === 'page' ) {
					for ( $i = 0; $i < $RtocPageId_count; ++$i ) {
						if ( $post->ID == $RtocPageId[ $i ] ) {
							return;
						} elseif ( empty( $screen_type ) ) {
							$screen_type = 'page';
						}
					}
				}

				// [目次を表示させるページ] の [投稿] のみチェック有り.
			} elseif ( ! empty( $RtocDisplay['post'] ) && empty( $RtocDisplay['page'] ) ) {
				if ( $post_type === 'post' ) {
					for ( $i = 0; $i < $RtocPostId_count; ++$i ) {
						if ( $post->ID == $RtocPostId[ $i ] ) {
							return;
						} elseif ( empty( $screen_type ) ) {
							$screen_type = 'post';
						}
					}
				}

				// [目次を表示させるページ] の [固定ページ] のみチェック有り.
			} elseif ( empty( $RtocDisplay['post'] ) && ! empty( $RtocDisplay['page'] ) ) {
				if ( $post_type === 'page' ) {
					for ( $i = 0; $i < $RtocPageId_count; ++$i ) {
						if ( $post->ID == $RtocPageId[ $i ] ) {
							return;
						} elseif ( empty( $screen_type ) ) {
							$screen_type = 'page';
						}
					}
				}
			}
			return $screen_type;
		}

		/**
		 * 「クリック計測を行う記事IDを入力」で指定したIDの編集画面かチェック用
		 */
		public function addon_measure_id_check( $post_id ) {
			// include_once ABSPATH . 'wp-admin/includes/plugin.php';
			if ( is_plugin_active( 'rich-table-of-content-addon/rtoc-addon.php' ) ) {
				// 個別ONで目次計測設定IDがあったら配列形式で返ってくる.
				$measure_id_arr   = RTOC_Addon::measure_settings();
				$measure_id_count = ! empty( $measure_id_arr ) ? count( $measure_id_arr ) : 0;

				if ( $measure_id_count === 0 ) {
					return;
				}

				for ( $i = 0; $i < $measure_id_count; ++$i ) {
					if ( $post_id === (int) $measure_id_arr[ $i ] ) {
						$measure_id = true;
						break;
					} else {
						$measure_id = false;
					}
				}

				return $measure_id;
			}
		}

		/**
		 * 投稿と固定ページの編集画面に目次使用率のmeta_boxを追加
		 */
		public function meta_box() {
			global $post;
			$post_id      = $post->ID;
			$addon_active = is_plugin_active( 'rich-table-of-content-addon/rtoc-addon.php' );
			$screen_type  = $this->edit_exclude_check();
			$measure_id   = $this->addon_measure_id_check( $post_id ); // true/falseで返ってくる.

			// Addon無効時.
			if ( $addon_active === false ) {
				// 「7日間の目次使用率を測定」がonだったら
				$rtoc_userate_measure_7 = get_option( 'rtoc_userate_measure_7' );
				if ( $rtoc_userate_measure_7 === 'on' ) {
					// [目次を表示させるページ] の チェックが有る編集画面にのみメタボックスを表示.
					if ( $screen_type === 'post' || $screen_type === 'page' ) {
						add_meta_box(
							'rtoc_meta_click',
							'RTOC 目次使用率',
							array( $this, 'meta_box_callback' ),
							$screen_type,
							'normal'
						);
					}
				}
				// Addon有効化時.
			} else {
				$measure_all = get_option( 'rtoc_addon_measure_all' );
				// 「全ての記事に対してクリック計測を行う」がonなら全ての記事.
				if ( $measure_all === 'on'
					// offなら「クリック計測を行う記事IDを入力」の記事のみ.
					|| ( $measure_all === 'off' && $measure_id === true ) ) {
					// [目次を表示させるページ] の チェックが有る編集画面にのみメタボックスを表示.
					if ( $screen_type === 'post' || $screen_type === 'page' ) {
						add_meta_box(
							'rtoc_meta_click',
							'RTOC 目次使用率',
							array( $this, 'meta_box_callback' ),
							$screen_type,
							'normal'
						);
					}
				}
			}
		}

		/**
		 * Add_meta_boxのコールバック
		 */
		public function meta_box_callback( $post ) {
			$post_id     = $post->ID;
			$aggregate   = RTOC_Use_Rate::relative_date_userate_aggregate_whole( $post_id, 7 );
			$date_arr    = $aggregate['date_arr'];
			$userate_arr = $aggregate['userate_arr'];

			if ( ! empty( $date_arr ) && ! empty( $userate_arr ) ) :
				?>

				<div id="rtoc-edit-table-wrapper" data-id="<?php echo esc_attr( $post_id ); ?>">
					<div id="rtoc-edit-popup">
						<div class="rtoc-edit-popup-content">
							<h3>この記事の目次使用率をリセットしてもよろしいですか？</h3>
							<div class="rtoc-edit-popup-buttons">
								<button type="button" class="rtoc-edit-reset-execute" id="js-rtoc-edit-reset-execute">リセットする</button>
								<button type="button" class="rtoc-edit-popup-close" id="js-rtoc-edit-popup-close">戻る</button>
							</div><!-- /.rtoc-edit-popup-buttons -->
						</div><!-- /.rtoc-edit-popup-content -->
					</div><!-- /#rtoc-edit-popup -->
					<table class="rtoc-edit-table">
						<tr>
							<?php for ( $i = 0; $i < 7; ++$i ) : ?>
								<th><?php echo esc_html( $date_arr[$i] ); ?></th>
							<?php endfor; ?>
						</tr>
						<tr>
							<?php for ( $i = 0; $i < 7; ++$i ) : ?>
								<td><?php echo esc_html( $userate_arr[$i] ); ?> %</td>
							<?php endfor; ?>
						</tr>
					</table>

					<?php
					// Addonが無効だったらリセットボタン.
					if ( ! is_plugin_active( 'rich-table-of-content-addon/rtoc-addon.php' ) ) :
						?>
					<p class="rtoc-edit-reset">
						<button type="button" class="rtoc-edit-reset-button" id="js-rtoc-edit-reset-button">リセット</button>
					</p>
					<?php endif; ?>
					<?php
					// Addonが有効だったら移動ボタン.
					if ( is_plugin_active( 'rich-table-of-content-addon/rtoc-addon.php' ) ) :
						$report_url        = admin_url( 'admin.php?page=rtoc_addon_report_details' );
						$report_url_add_id = $report_url . '&post=' . $post_id;
						?>
						<p class="rtoc-edit-move">
							<a href="<?php echo esc_url( $report_url_add_id ); ?>" class="rtoc-edit-move-button">解析レポートへ移動</a>
						</p>
					<?php endif; ?>
				</div><!-- /#rtoc-edit-table-wrapper -->

			<?php else : ?>
				<p class="rtoc-edit-no-click">目次は使用されていません。</p>
				<?php
			endif;
		}

		/**
		 * 該当する投稿IDのレコードを削除（編集画面でリセットをクリック時）
		 */
		public function ajax_db_userate_delete() {
			check_ajax_referer( 'rtoc_edit_ajax', 'security' );

			if ( isset( $_SERVER['REQUEST_METHOD'] ) && $_SERVER['REQUEST_METHOD'] === 'POST' && isset( $_POST['id'] ) ) {
				$post_id = wp_kses_post( wp_unslash( $_POST['id'] ) );
				delete_post_meta( $post_id, RTOC_USE_RATE_KEY );
			} else {
				exit();
			}
		}

	} // end class.
endif;
$class_rtoc_edit = new RTOC_Edit();
$class_rtoc_edit->register();
