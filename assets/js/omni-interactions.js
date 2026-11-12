(function () {
	const mobile = window.matchMedia('(max-width: 1024px)');

	function enhanceMenus() {
		document.querySelectorAll('.owp-navbar').forEach((nav) => {
			const drawer = nav.querySelector('.owp-navbar__drawer');
			if (drawer && typeof drawer.showModal === 'function') {
				nav.classList.add('owp-navbar--enhanced');
				nav.querySelector('.owp-navbar__open').hidden = false;
			}
		});
	}

	document.addEventListener('click', (event) => {
		const teamControl = event.target.closest('.owp-team__card, [data-owp-team-step]');
		if (teamControl) {
			const team = teamControl.closest('.owp-team');
			const track = team.querySelector('.owp-team__track');
			const cards = Array.from(track.querySelectorAll('.owp-team__card'));
			const current = cards.findIndex((card) => card.getAttribute('aria-pressed') === 'true');
			const step = Number(teamControl.dataset.owpTeamStep);
			const selected = step ? cards[(current + step + cards.length) % cards.length] : teamControl;

			cards.forEach((card) => {
				const active = card === selected;
				card.setAttribute('aria-pressed', String(active));
				document.getElementById(card.getAttribute('aria-controls')).hidden = !active;
			});

			const cardBounds = selected.getBoundingClientRect();
			const trackBounds = track.getBoundingClientRect();
			const offset = cardBounds.left < trackBounds.left
				? cardBounds.left - trackBounds.left
				: Math.max(0, cardBounds.right - trackBounds.right);
			if (offset) {
				const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
				track.scrollBy({ left: offset, behavior: reducedMotion ? 'auto' : 'smooth' });
			}
			return;
		}

		const trigger = event.target.closest('.owp-navbar__open');
		if (trigger) {
			const drawer = document.getElementById(trigger.getAttribute('aria-controls'));
			if (drawer && !drawer.open && mobile.matches) {
				drawer.showModal();
				trigger.setAttribute('aria-expanded', 'true');
			}
			return;
		}

		const drawer = event.target.closest('.owp-navbar__drawer[open]');
		if (!drawer) {
			return;
		}

		const bounds = drawer.getBoundingClientRect();
		const clickedBackdrop = event.target === drawer && (
			event.clientX < bounds.left || event.clientX > bounds.right ||
			event.clientY < bounds.top || event.clientY > bounds.bottom
		);
		if (clickedBackdrop || event.target.closest('a')) {
			drawer.close();
		}
	});

	document.addEventListener('close', (event) => {
		if (event.target.matches('.owp-navbar__drawer')) {
			event.target.closest('.owp-navbar').querySelector('.owp-navbar__open').setAttribute('aria-expanded', 'false');
		}
	}, true);

	mobile.addEventListener('change', () => {
		if (!mobile.matches) {
			document.querySelectorAll('.owp-navbar__drawer[open]').forEach((drawer) => drawer.close());
		}
	});

	// Group open items on browsers that support details but ignore the name attribute.
	if (!('name' in document.createElement('details'))) {
		document.addEventListener('toggle', (event) => {
			const details = event.target;
			if (details.matches('.owp-accordion[data-owp-single] > details[open]')) {
				for (const sibling of details.parentElement.children) {
					if (sibling !== details && sibling.tagName === 'DETAILS') {
						sibling.open = false;
					}
				}
			}
		}, true);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', enhanceMenus);
	} else {
		enhanceMenus();
	}

	if (window.jQuery) {
		window.jQuery(window).on('elementor/frontend/init', () => {
			window.elementorFrontend.hooks.addAction('frontend/element_ready/owp-navbar.default', enhanceMenus);
		});
	}
})();
