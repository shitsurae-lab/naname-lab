/**
 * External dependencies
 */
import MonacoEditor, { useMonaco } from '@monaco-editor/react';
import { emmetCSS } from 'emmet-monaco-es';

/**
 * Internal dependencies
 */
import themes from '../themes';

/**
 * WordPress dependencies
 */
// import { __ } from '@wordpress/i18n';
import { Spinner } from '@wordpress/components';
import { useEffect } from '@wordpress/element';

// Monaco Editor 固定オプション
const defaultOptions = {
	// 折り返し（あり）
	wordWrap: 'on',
	// ミニマップ（無効）
	minimap: {
		enabled: false,
	},
	// ホバーヒント（無効）
	hover: false,
};

export default ({ css, setCSS, settings, options, setEdited }) => {
	const monacoInstance = useMonaco();

	const newOptions = {
		...defaultOptions,
		...options,
	};

	const onMount = (editor, monaco) => {
		// カラーテーマの適用
		setTheme(monaco, settings.theme);

		// Emmetを適用（1度だけ有効化）
		if (!monaco.enabledEmmet) {
			monaco.enabledEmmet = true;
			emmetCSS(monaco);
		}

		// Ctrl(Cmd) + S で保存処理を行う
		// eslint-disable-next-line no-bitwise
		editor.addCommand(monaco.KeyMod.CtrlCmd | monaco.KeyCode.KEY_S, () => {
			document.getElementById('arkhe_css_submit').click();
		});
	};

	const onChange = (value) => {
		setEdited(true);
		setCSS(value);
	};

	// カラーテーマ変更
	useEffect(() => {
		if (monacoInstance) {
			setTheme(monacoInstance, settings.theme);
		}
	}, [settings.theme]);

	const setTheme = (instance, newTheme) => {
		if ('vs-dark' !== newTheme) {
			const theme = themes.find((data) => settings.theme === data.value);
			if (undefined !== theme) {
				instance.editor.defineTheme(theme.value, theme.data);
				instance.editor.setTheme(theme.value);
			}
		}
	};

	return (
		<div className='arkcss-editor'>
			<MonacoEditor
				theme={settings.theme}
				language='css'
				loading={<Spinner />}
				options={newOptions}
				value={css}
				onMount={onMount}
				onChange={onChange}
			/>
		</div>
	);
};
