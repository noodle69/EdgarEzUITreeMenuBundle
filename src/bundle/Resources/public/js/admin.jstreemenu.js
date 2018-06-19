;(function ($, window, document, undefined) {
    let treeMenuSideBarInit = false;
    const btnTreeMenu = document.querySelectorAll('.btn--tree-browse');

    const btnTreeMenuTrigger = (event) => {
        const treeMenuSideBar = document.querySelector('#treemenu-sidebar');
        if (treeMenuSideBar.style.display === 'none' && !treeMenuSideBarInit) {
            treeMenuSideBarInit = true;
            const contentSideBar = getClosest(event.target, '.ez-side-menu');
            const contentDiv = contentSideBar.nextElementSibling;

            contentSideBar.parentNode.insertBefore(treeMenuSideBar, contentSideBar.nextSibling);
            treeMenuSideBar.style.display = 'block';
            contentDiv.className = contentDiv.className.replace(/(?:^|\s)col-sm-10(?!\S)/g, 'col-sm-8');

            const treeMenuAction = treeMenuSideBar.dataset.action;
            requestData(treeMenuAction);
        } else if (treeMenuSideBar.style.display === 'none' && treeMenuSideBarInit) {
            const contentSideBar = getClosest(event.target, '.ez-side-menu');
            const contentDiv = contentSideBar.nextElementSibling.nextElementSibling;

            treeMenuSideBar.style.display = 'block';
            contentDiv.className = contentDiv.className.replace(/(?:^|\s)col-sm-10(?!\S)/g, 'col-sm-8');

            const treeMenuAction = treeMenuSideBar.dataset.action;
            requestData(treeMenuAction);
        } else if (treeMenuSideBar.style.display !== 'none') {
            const contentSideBar = getClosest(event.target, '.ez-side-menu');
            const contentDiv = contentSideBar.nextElementSibling.nextElementSibling;

            treeMenuSideBar.style.display = 'none';
            contentDiv.className = contentDiv.className.replace(/(?:^|\s)col-sm-8(?!\S)/g, 'col-sm-10');
        }
    };

    const requestData = function(action) {
        const request = new Request(action, {
            method: 'GET',
            mode: 'same-origin',
            credentials: 'same-origin'
        });

        fetch(request)
            .then(handleRequestResponse)
            .then(function(json) {
                initTreeView(json);
            })
            .catch(error => console.log('error:treemenu', error));
    };

    const requestChildren = function(action, par) {
        const request = new Request(action, {
            method: 'GET',
            mode: 'same-origin',
            credentials: 'same-origin'
        });

        fetch(request)
            .then(handleRequestResponse)
            .then(function(json) {
                $.each(json, function(i, node) {
                    $('#treemenu-view').jstree("create_node", par, node, 'last', false, false);
                });
            })
            .catch(error => console.log('error:treemenu', error));
    };

    const initTreeView = function(json) {
        const treeMenuSideBar = document.querySelector('#treemenu-sidebar');
        const treeMenuLocationId = treeMenuSideBar.dataset.locationid;
        let automaticallyExpand = false;

        $('#treemenu-view').jstree({
            'core' : {
                'multiple': false,
                'themes' : {
                    'dots' : false
                },
                'check_callback': true,
                'plugins:': ['types'],
                'data' : json
            }
        }).on('open_node.jstree', function (e, data) {
            if (data.node.children_d.length === 1 && !parseInt(data.node.children_d[0])) {
                $('#treemenu-view').jstree("delete_node", data.node.children_d[0]);
                requestChildren(data.node.a_attr.children, data.node);
            }
        }).on('select_node.jstree', function (e, data) {
            window.location.href = data.node.a_attr.href;
        });
    };

    const getClosest = function (elem, selector) {
        for ( ; elem && elem !== document; elem = elem.parentNode ) {
            if ( elem.matches( selector ) ) return elem;
        }
        return null;
    };

    const handleRequestResponse = response => {
        if (!response.ok) {
            throw Error(response.statusText);
        }

        return response.json();
    };

    btnTreeMenu.forEach(btnTreeMenu => btnTreeMenu.addEventListener('click', btnTreeMenuTrigger, false));
})(jQuery, window, document);
