(function () {
	let rtocList = [];
	let lastScrollY = 0;
	let lastBodyClientHeight = 0;
	let sidebar_rtoc_wrapper = document.querySelector('.sidebar #rtoc-mokuji-wrapper');
	if (!sidebar_rtoc_wrapper) {
		sidebar_rtoc_wrapper = document.querySelector('.widget #rtoc-mokuji-wrapper');
	}
	if (!sidebar_rtoc_wrapper) {
		sidebar_rtoc_wrapper = document.querySelector('.c-widget #rtoc-mokuji-wrapper');
	}
	if (!sidebar_rtoc_wrapper) {
		sidebar_rtoc_wrapper = document.querySelector('#scrollad #rtoc-mokuji-wrapper');
	}
	if (!sidebar_rtoc_wrapper) {
		sidebar_rtoc_wrapper = document.querySelector('#sideBarTracking #rtoc-mokuji-wrapper');
	}
	
	if (sidebar_rtoc_wrapper) {
		sidebar_rtoc_wrapper.classList.add('rtoc-sidebar-contents');
	} else {
		return;
	}
	function init() {
		rtocList = [];
		rtocParentList = [];
		if (sidebar_rtoc_wrapper == null) {
			return;
		}
		const itemList = sidebar_rtoc_wrapper.querySelectorAll('a');
		const itemAllList = sidebar_rtoc_wrapper.querySelectorAll('.level-1 > .rtoc-item > a');
		for (let i = 0; i < itemList.length; i++) {
			const a = itemList[i];
			const linkAnker = a.href.substring(a.href.lastIndexOf('#'));
			const itemElement = document.querySelector(decodeURI(linkAnker));
			let top = itemElement.offsetTop;
			let parent = itemElement.offsetParent;
			while (parent != null) {
				top += parent.offsetTop;
				parent = parent.offsetParent;
			}
			rtocList.push({ top: top, bottom: 1e30, itemdom: a.parentElement });
			if (i > 0) {
				rtocList[i - 1].bottom = rtocList[i].top;
			}
		}
		for (let i = 0; i < itemAllList.length; i++) {
			const a = itemAllList[i];
			const linkAnker = a.href.substring(a.href.lastIndexOf('#'));
			const itemAllElement = document.querySelector(decodeURI(linkAnker));
			const tag = itemAllElement.tagName;
			let top = itemAllElement.offsetTop;
			let parent = itemAllElement.offsetParent;
			while (parent != null) {
				top += parent.offsetTop;
				parent = parent.offsetParent;
			}
			rtocParentList.push({ top: top, bottom: 1e30, itemdom: a.parentElement });
			if (i > 0) {
				rtocParentList[i - 1].bottom = rtocParentList[i].top;
			}
		}
	}
	init();
	function updateSection(scrollY) {
		if (document.body.clientHeight !== lastBodyClientHeight) {
			init();
		}
		for (let sec of rtocList) {
			sec.itemdom.classList.remove('rtoc-current');
		}
		for (let sec of rtocParentList) {
			sec.itemdom.classList.remove('rtoc-show');
		}

		for (let i = 0; i < rtocList.length; i++) {
			const sec = rtocList[i];
			if (sec.top <= scrollY && scrollY < sec.bottom) {
				sec.itemdom.classList.add('rtoc-current');
				break;
			}
		}
		for (let i = 0; i < rtocParentList.length; i++) {
			const sec = rtocParentList[i];
			if (sec.top <= scrollY && scrollY < sec.bottom) {
				sec.itemdom.classList.add('rtoc-show');
				break;
			}
		}
	}
	function widgetScroll(hiddenDOM) {
		const highlightContents = sidebar_rtoc_wrapper.querySelector('.rtoc-current');
		if (!highlightContents) return;
	}

	let ticking = false;
	const scrollHeight = sidebar_rtoc_wrapper.querySelector('.rtoc-mokuji').scrollHeight;
	const displayHeight = sidebar_rtoc_wrapper.querySelector('.rtoc-mokuji').offsetHeight;
	document.addEventListener('scroll', function () {
		lastScrollY = window.scrollY;
		if (ticking === false) {
			window.requestAnimationFrame(function () {
				updateSection(lastScrollY + 300);
				ticking = false;
			});
			ticking = true;
		}
		if (scrollHeight > displayHeight) {
			widgetScroll(scrollHeight - displayHeight);
		}
	});
})();
