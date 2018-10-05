(function () {
    var panel = document.getElementById('debug-panel'),
        filters = panel.querySelectorAll('.filter a'),
        queries = panel.querySelectorAll('tr[data-query-type]'),
        sorters = panel.querySelectorAll('th[data-action=sort]');

    filters.forEach(function (filter) {
        filter.addEventListener('click', function(e) {
            e.preventDefault();
            var targetQueryType = this.dataset.targetQuery;

            filters.forEach(function (filter) {
                filter.classList.remove('active')
            });

            if (!this.classList.contains('active'))
                this.classList.toggle('active');

            queries.forEach(function (query) {
                var queryType = query.dataset.queryType,
                    show = targetQueryType === queryType;

                if (targetQueryType === 'all')
                    show = true;

                query.style.display = show ? 'table-row' : 'none';
            });
        });
    });

    sorters.forEach(function (sorter) {
        sorter.addEventListener('click', function () {
            var self = this;
            sorters.forEach(function (sorter) {
                if (sorter !== self) {
                    sorter.removeAttribute('data-sort-direction');
                }
            });

            var index = this.cellIndex,
                direction = this.dataset.sortDirection = !parseInt(this.dataset.sortDirection) + 0;

            Array.from(queries).sort(function (a, b) {
                var val_a = parseFloat(a.cells[index].innerText || 0).toFixed(5),
                    val_b = parseFloat(b.cells[index].innerText || 0).toFixed(5);

                return direction ? val_a - val_b : val_b - val_a;

            }).forEach(function (query) {
                panel.querySelector('tbody').appendChild(query);
            });
        })
    })
})();