;(function ($, window, document, undefined) {
    const getClosest = function (elem, selector) {
        for ( ; elem && elem !== document; elem = elem.parentNode ) {
            if ( elem.matches( selector ) ) return elem;
        }
        return null;
    };

    const btnTreeMenu = document.querySelector('#sidebar_left__browse_tree-tab');
    const contentSideBar = getClosest(btnTreeMenu, '.ez-side-menu');
    const stickyContainer = getClosest(btnTreeMenu, '.ez-side-menu .ez-sticky-container');
    const eZExtractActionContainerLeft = document.querySelector('#ez-extra-actions-container-left');

    contentSideBar.style.zIndex = "2";
    stickyContainer.insertBefore(eZExtractActionContainerLeft, null);
})(jQuery, window, document);

(function () {
    const CLASS_HIDDEN = 'ez-extra-actions-left--hidden';
    const CLASS_PREVENT_SHOW = 'ez-extra-actions-left--prevent-show';
    const btns = [...document.querySelectorAll('.ez-btn--extra-actions-left')];

    btns.forEach(btn => {
        btn.addEventListener('click', (event) => {
console.log('click');
            const actions = document.querySelector(`.ez-extra-actions-left[data-actions="${btn.dataset.actions}"]`);
console.log(actions);
            const haveHiddenPart = (element) => {
                return element.classList.contains(CLASS_HIDDEN) && !element.classList.contains(CLASS_PREVENT_SHOW)
            };
            const methodName = haveHiddenPart(actions) ? 'remove' : 'add';
console.log(methodName);
            const clickOutsideMethodName = actions.classList.contains(CLASS_HIDDEN) ? 'addEventListener' : 'removeEventListener';
console.log(clickOutsideMethodName);
            const detectClickLeftOutside = (event) => {
console.log(event.target);
                const isNotButton = !event.target.contains(btn);
console.log(isNotButton);
                const isNotExtraActions = !event.target.closest('.ez-extra-actions-left');
console.log(isNotExtraActions);

                if (isNotButton && isNotExtraActions) {
console.log('ZZZ0');
                    actions.classList.add(CLASS_HIDDEN);
                    document.body.removeEventListener('click', detectClickLeftOutside, false);
                }
            };
console.log(btn.offsetTop);

            actions.style.top = btn.offsetTop + 'px';
            actions.classList[methodName](CLASS_HIDDEN);
            document.body[clickOutsideMethodName]('click', detectClickLeftOutside, false);
        }, false);
    });
})();

