(function (global, eZ) {
    eZ = eZ || {};
    eZ.adminUiConfig = eZ.adminUiConfig || {};
    eZ.adminUiConfig.universalDiscoveryWidget = eZ.adminUiConfig.universalDiscoveryWidget || {};
    eZ.adminUiConfig.universalDiscoveryWidget.extraTabs = eZ.adminUiConfig.universalDiscoveryWidget.extraTabs || [];

    eZ.adminUiConfig.universalDiscoveryWidget.extraTabs.push({
        id: 'treemenu',
        title: 'Browse tree',
        iconIdentifier: 'list',
        panel: eZ.modules.TreeMenuPanel,
        attrs: {}
    });

})(window, window.eZ);
