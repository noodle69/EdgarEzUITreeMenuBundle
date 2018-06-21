import React from 'react';

const TreeMenuPanel = (props) => {
    const wrapperAttrs = { className: 'c-treemenu-panel' };

    if (!props.isVisible) {
        wrapperAttrs.hidden = true;
    }

    return (
        <div {...wrapperAttrs}>
<h1>Browse tree</h1>
    </div>
);
};

export default TreeMenuPanel;