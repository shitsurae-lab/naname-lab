/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';
import { render, useState, useEffect, useRef } from '@wordpress/element';
import {
	TabPanel,
	Spinner,
	ExternalLink,
	Button,
	Slot,
	Fill,
	SlotFillProvider,
} from '@wordpress/components';
import { cog } from '@wordpress/icons';

/**
 * External dependencies
 */
import ReactNotification from 'react-notifications-component';
import webfontloader from 'webfontloader';

/**
 * Self dependencies
 */
import StyleEditor from './component/StyleEditor';
import SettingModal from './component/SettingModal';
import { apiFetchedAction, saveAction, addNotification } from './helper';

/**
 * 設定画面用スタイル
 */
import './index.scss';

// Adminコンポーネント
const Admin = () => {
	const [commonCSS, setCommonCSS] = useState(null);
	const [frontCSS, setFrontCSS] = useState(null);
	const [editorCSS, setEditorCSS] = useState(null);
	const [settings, setSettings] = useState(null);
	const [options, setOptions] = useState(null);

	const [isAPILoaded, setAPILoaded] = useState(false);
	const [isWebFontLoaded, setWebFontLoaded] = useState(false);
	const [isSaving, setSaving] = useState(false);
	const [isSettingOpen, setSettingOpen] = useState(false);

	const [isEdited, setEdited] = useState(false);
	const isEditedRef = useRef(null);
	isEditedRef.current = isEdited;

	useEffect(() => {
		// 一回読み込んだら処理しない
		if (isAPILoaded || isWebFontLoaded) return;

		// 変更があった時、遷移前に確認画面を出す
		global.window.addEventListener('beforeunload', (e) => {
			if (isEditedRef.current) {
				e.preventDefault();
				e.returnValue = '';
			} else {
				return false;
			}
		});

		// 設定情報の取得
		apiFetchedAction((data) => {
			setCommonCSS(data.arkhe_css_common);
			setFrontCSS(data.arkhe_css_front);
			setEditorCSS(data.arkhe_css_editor);
			setSettings(data.arkhe_css_settings);
			setOptions(data.arkhe_css_options);
			setAPILoaded(true);
		});

		// Webフォントの読み込み
		webfontloader.load({
			custom: {
				families: window.arkheCssEditorVars.fontFamilies.map(({ val }) => val),
				urls: [window.arkheCssEditorVars.styleSheetUrl],
			},

			timeout: 2000,
			active: () => {
				setWebFontLoaded(true);
			},
		});
	}, []);

	const savedSuccess = () => {
		setTimeout(() => {
			addNotification(__('Settings saved.', 'arkhe-css-editor'), 'success');
			setSaving(false);
		}, 500);
		setEdited(false);
	};

	const savedError = () => {
		setTimeout(() => {
			addNotification(__('An error occurred.', 'arkhe-css-editor'), 'danger');
			setSaving(false);
		}, 500);
	};

	const onSave = () => {
		setSaving(true);

		const newSettings = {
			arkhe_css_common: commonCSS,
			arkhe_css_front: frontCSS,
			arkhe_css_editor: editorCSS,
			arkhe_css_settings: settings,
			arkhe_css_options: options,
		};

		saveAction(newSettings, savedSuccess, savedError);
	};

	if (!isAPILoaded || !isWebFontLoaded) {
		return null;
	}

	return (
		<SlotFillProvider>
			<ReactNotification />
			{isSaving && (
				<div className='arkcss-savingLayer'>
					<Spinner />
				</div>
			)}
			<header className='arkcss-header'>
				<h1 className='arkcss-title'>Arkhe CSS Editor</h1>
				<TabPanel
					className='arkcss-tabs'
					tabs={[
						{
							name: 'common',
							title: __('Common', 'arkhe-css-editor'),
						},
						{
							name: 'front',
							title: __('For Front', 'arkhe-css-editor'),
						},
						{
							name: 'editor',
							title: __('For Editor', 'arkhe-css-editor'),
						},
					]}
				>
					{(tab) => {
						if ('common' === tab.name) {
							return (
								<Fill name='ArkCSS.TabContent'>
									<p className='arkcss-editor-help'>
										フロント側とエディター側の両方で読み込むCSS
									</p>
									<StyleEditor
										{...{
											css: commonCSS,
											setCSS: setCommonCSS,
											settings,
											options,
											setEdited,
										}}
									/>
								</Fill>
							);
						} else if ('front' === tab.name) {
							return (
								<Fill name='ArkCSS.TabContent'>
									<p className='arkcss-editor-help'>
										フロント側（サイト表示側）で読み込むCSS
									</p>
									<StyleEditor
										{...{
											css: frontCSS,
											setCSS: setFrontCSS,
											settings,
											options,
											setEdited,
										}}
									/>
								</Fill>
							);
						} else if ('editor' === tab.name) {
							return (
								<Fill name='ArkCSS.TabContent'>
									<p className='arkcss-editor-help'>エディター側で読み込むCSS</p>
									<StyleEditor
										{...{
											css: editorCSS,
											setCSS: setEditorCSS,
											settings,
											options,
											setEdited,
										}}
									/>
								</Fill>
							);
						}
					}}
				</TabPanel>
			</header>
			<div className='arkcss-body'>
				<Slot name='ArkCSS.TabContent' />
				<p className='arkcss-tips'>
					<ExternalLink href='https://docs.emmet.io/cheat-sheet/'>
						{__('See Emmet Cheet Sheet', 'arkhe-css-editor')}
					</ExternalLink>
				</p>
				<div className='arkcss-controls'>
					<Button
						id='arkhe_css_submit'
						className='arkhe-css-controls__submit'
						isPrimary
						disabled={isSaving}
						onClick={onSave}
					>
						{__('Save', 'arkhe-css-editor')}
					</Button>
					<Button
						className='arkhe-css-controls__setting'
						isSecondary
						icon={cog}
						label={__('Setting', 'arkhe-css-editor')}
						onClick={() => setSettingOpen(true)}
					>
						{__('Setting', 'arkhe-css-editor')}
					</Button>
				</div>
			</div>
			<SettingModal
				{...{
					settings,
					setSettings,
					options,
					setOptions,
					isSettingOpen,
					setSettingOpen,
				}}
			/>
		</SlotFillProvider>
	);
};

// AdminコンポーネントをルートDOMにレンダリング
render(<Admin />, document.getElementById('arkhe_css_editor_setting'));
