import api from '@wordpress/api';
import { store } from 'react-notifications-component';

export const apiFetchedAction = (cb) => {
	api.loadPromise.then(() => {
		const model = new api.models.Settings();
		model.fetch().then(cb);
	});
};

export const saveAction = (newSettings, onSuccess, onError) => {
	api.loadPromise.then(() => {
		const model = new api.models.Settings(newSettings);
		const save = model.save();

		save.success((response, status) => {
			onSuccess(response, status);
		});
		save.error((response, status) => {
			onError(response, status);
		});
	});
};

export const addNotification = (message, type, duration = 2000) => {
	store.addNotification({
		message,
		type,
		animation: 'bounce-in',
		insert: 'bottom',
		container: 'top-left',
		isMobile: true,
		dismiss: {
			duration,
			showIcon: true,
		},
		dismissable: {
			click: true,
			touch: true,
		},
	});
};
