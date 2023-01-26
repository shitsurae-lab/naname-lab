<?php
namespace Arkhe_CSS_Editor;

if ( ! defined( 'ABSPATH' ) ) exit;

// 設定項目の登録
add_action( 'init', function () {
	// エディタ設定のschema、default生成
	$props_settings   = [];
	$props_settings   = [];
	$default_settings = [];

	foreach ( \Arkhe_CSS_Editor::SETTINGS as $key => $data ) {
		$props_settings[ $key ]   = [ 'type' => $data['type'] ];
		$default_settings[ $key ] = $data['val'];
	}

	// エディタオプションのschema、default生成
	$props_options   = [];
	$default_options = [];

	foreach ( \Arkhe_CSS_Editor::OPTIONS as $key => $data ) {
		$props_options[ $key ]   = [ 'type' => $data['type'] ];
		$default_options[ $key ] = $data['val'];
	}

	// エディタ設定
	register_setting(
		'arkhe_css_setting_group',
		\Arkhe_CSS_Editor::DB_NAME['settings'],
		[
			'type'         => 'object',
			'show_in_rest' => [
				'schema' => [
					'type'       => 'object',
					'properties' => $props_settings,
				],
			],
			'default'      => $default_settings,
		]
	);

	// エディタオプション
	register_setting(
		'arkhe_css_setting_group',
		\Arkhe_CSS_Editor::DB_NAME['options'],
		[
			'type'         => 'object',
			'show_in_rest' => [
				'schema' => [
					'type'       => 'object',
					'properties' => $props_options,
				],
			],
			'default'      => $default_options,
		]
	);

	// CSS
	register_setting(
		'arkhe_css_setting_group',
		\Arkhe_CSS_Editor::DB_NAME['common'],
		[
			'type'         => 'string',
			'show_in_rest' => true,
			'default'      => '',
		]
	);
	register_setting(
		'arkhe_css_setting_group',
		\Arkhe_CSS_Editor::DB_NAME['front'],
		[
			'type'         => 'string',
			'show_in_rest' => true,
			'default'      => '',
		]
	);
	register_setting(
		'arkhe_css_setting_group',
		\Arkhe_CSS_Editor::DB_NAME['editor'],
		[
			'type'         => 'string',
			'show_in_rest' => true,
			'default'      => '',
		]
	);
} );
