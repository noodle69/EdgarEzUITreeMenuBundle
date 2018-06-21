;(function ($, window, document, undefined) {
    const getClosest = function (elem, selector) {
        for ( ; elem && elem !== document; elem = elem.parentNode ) {
            if ( elem.matches( selector ) ) return elem;
        }
        return null;
    };

    const btnTreeMenu = document.querySelector('#sidebar_left__browse_tree-tab');
    const contentSideBar = getClosest(btnTreeMenu, '.ez-side-menu .ez-sticky-container');
    const treeMenuWidget = document.querySelector('#treemenu-sidebar-widget');

    contentSideBar.insertBefore(treeMenuWidget, null);
})(jQuery, window, document);
