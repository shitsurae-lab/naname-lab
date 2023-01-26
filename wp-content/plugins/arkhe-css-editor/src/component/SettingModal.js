/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { SelectControl, RangeControl, Modal } from '@wordpress/components';

/**
 * Internal dependencies
 */
import themes from '../themes';

export default ({ settings, setSettings, options, setOptions, isSettingOpen, setSettingOpen }) => {
	const changeSettings = (data) => {
		setSettings({ ...settings, ...data });
	};

	const changeOptions = (data) => {
		setOptions({ ...options, ...data });
	};

	if (!isSettingOpen) {
		return null;
	}

	return (
		<Modal
			title={__('Setting', 'arkhe-css-editor')}
			className='arkcss-modal'
			overlayClassName='arkcss-overlay'
			onRequestClose={() => setSettingOpen(false)}
		>
			<SelectControl
				label={__('Color Theme', 'arkhe-css-editor')}
				value={settings.theme || 'vs-dark'}
				options={[
					{
						label: __('Visual Studio Dark', 'arkhe-css-editor'),
						value: 'vs-dark',
					},
					...themes,
				]}
				onChange={(val) => changeSettings({ theme: val })}
			/>
			<SelectControl
				label={__('Font Family', 'arkhe-css-editor')}
				value={options.fontFamily || 'Fira Code'}
				options={window.arkheCssEditorVars.fontFamilies.map(({ label, val }) => ({
					label,
					value: val,
				}))}
				onChange={(val) => changeOptions({ fontFamily: val })}
			/>
			<RangeControl
				label={__('Font Size (px)', 'arkhe-css-editor')}
				min='10'
				max='25'
				value={options.fontSize || 14}
				onChange={(val) => changeOptions({ fontSize: val })}
			/>
			<RangeControl
				label={__('Line Height (px)', 'arkhe-css-editor')}
				min='10'
				max='40'
				value={options.lineHeight || 24}
				onChange={(val) => changeOptions({ lineHeight: val })}
			/>
		</Modal>
	);
};
