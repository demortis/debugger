(function () {
    if (jsPanel === undefined) return false;
    var panel = document.getElementById('debug-panel');

    jsPanel.create({
        headerTitle : 'Debug panel',
        content : panel,
        panelSize: {width: '100%', height: 400},
        dragit: false,
        position: 'center-bottom 0 0',
        theme : 'danger',
        headerControls : {
            maximize : 'remove',
            smallify : 'remove'
        }
    }).minimize();

    (function (panel) {
        var exceptions = panel.querySelectorAll('.exception-row > a');

        exceptions.forEach(function (exception) {
            exception.addEventListener('click', function () {
                this.nextElementSibling.checked = !this.nextElementSibling.checked;
            })
        });
    })(panel);
})();