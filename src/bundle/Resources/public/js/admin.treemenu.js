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

    const initTreeView = function(json) {
        const treeMenuSideBar = document.querySelector('#treemenu-sidebar');
        const treeMenuLocationId = treeMenuSideBar.dataset.locationid;
        let automaticallyExpand = false;

        $('#treemenu-view').treeview({
            levels: 1,
            color: "#fff",
            backColor: "#555",
            selectedColor: "#fff",
            selectedBackColor: "#555",
            onhoverColor: "#555",
            showBorder: false,
            enableLinks: true,
            showTags: true,
            data: json,
            onNodeExpanded: function (event, data) {
                if (!automaticallyExpand) {
                    treeMenuSideBar.dataset.locationid = data.locationId;
                    requestData(data.action);
                }
            }
        });

        const locationNodes = $('#treemenu-view').treeview('findNodes', ['^' + treeMenuLocationId + '$', 'g', 'locationId']);
        if (locationNodes.length > 0) {
            const pathString = locationNodes[0]['pathString'].split('/');
            $.each(pathString, function expand(id, locationId) {
                const locationNodes = $('#treemenu-view').treeview('findNodes', ['^' + locationId + '$', 'g', 'locationId']);
                if (locationNodes.length > 0) {
                    automaticallyExpand = true;
                    $('#treemenu-view').treeview('expandNode', [locationNodes[0]['nodeId']]);
                    automaticallyExpand = false;
                }
            });
        }
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
