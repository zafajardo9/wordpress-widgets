(function () {
	const editor = window.elementor;

	function registerContainer() {
		const NestedElement = editor.modules.elements.types.NestedElementBase;
		class OmniContainer extends NestedElement {
			getType() {
				return 'owp-container';
			}
		}

		editor.elementsManager.registerElementType(new OmniContainer());
	}

	if (typeof editor.modules.elements.types.NestedElementBase === 'function') {
		registerContainer();
	} else {
		window.elementorCommon.elements.$window.one('elementor/nested-element-type-loaded', registerContainer);
	}
})();
