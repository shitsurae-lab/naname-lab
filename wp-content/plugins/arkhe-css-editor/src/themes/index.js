/**
 * WordPress dependencies
 */
import { __ } from '@wordpress/i18n';

const themeList = {
	dawn: __('Dawn', 'arkhe-css-editor'),
	monokai: __('Monokai', 'arkhe-css-editor'),
	tomorrow: __('Tomorrow', 'arkhe-css-editor'),
};

const themes = [];

Object.keys(themeList).forEach(function (key) {
	themes.push({
		label: themeList[key],
		value: key,
		data: require(`./${key}.json`),
	});
});

export default themes;
