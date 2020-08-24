;(function ($) {

    $(document).ready(function () {


        let dataTable = $('#task-list').DataTable({
            ajax: {
                url: '/api/task',
                dataSrc: ''
            },
            dom: 'frti',
            paging: false,
            columns: [
                {data: 'id', visible: false},
                {
                    data: 'is_done',
                    className: 'task-done',
                    render: function (data, type, row) {
                        if (type === 'sort' || type === 'type') {
                            return data;
                        }
                        return $('<input/>', {
                            class: "task-complete",
                            type: "checkbox",
                            checked: (Number(data) === 1)
                        })
                            .prop('outerHTML');
                    }
                },
                {
                    data: 'task',
                    searchable: true,
                    className: "task-name",
                    render: function (data, type, row) {
                        if (type !== 'display') {
                            return data;
                        }

                        if (Number(row.is_done) === 0) {
                            return data;
                        }

                        return `<del>${data}</del>`
                    }
                },
                {
                    className: "dt-right task-control",
                    orderable: false,
                    data: null,
                    render: function (data, type, row) {
                        return '' +
                            '<button class="task-action button is-info" data-action="edit" title="Edit"><i class="fa fa-pencil"></i></button> ' +
                            '<button class="task-action button is-danger" data-action="delete" title="Delete"><i class="fa fa-trash"></i></button>';
                    }
                }
            ],
            order: [[1, "asc"], [0, "asc"]]
        }).on('change', 'input.task-complete', function () {
            // Complete Task
            let row = dataTable.row($(this).closest('tr'));
            let data = row.data();

            data.is_done = this.checked ? 1 : 0;
            $.ajax({
                url: '/api/task/' + data.id,
                type: 'put',
                dataType: 'json',
                data: data,
                success: function (data) {
                    row.data(data).draw(false);
                }
            });
        }).on('click', 'button.task-action', function () {
            // Edit/Delete Task
            let row = dataTable.row($(this).closest('tr'));
            let data = row.data();

            if (this.dataset.action === 'delete') {
                $.ajax({
                    url: '/api/task/' + data.id,
                    type: 'delete',
                    dataType: 'json',
                    success: function (data) {
                        row.remove().draw(false);
                    }
                });
            } else if (this.dataset.action === 'edit') {
                let newTask = $.trim(prompt('Rename Task', data.task));
                if (newTask === data.task) {
                    return;
                }
                data.task = newTask;
                $.ajax({
                    url: '/api/task/' + data.id,
                    dataType: 'json',
                    type: 'put',
                    data: data,
                    success: function (data) {
                        row.data(data).draw(false);
                    }
                });
            }
        });

        // Add Task
        $('#add-task').click(function () {
            let task = $('#new-task');
            let value = $.trim(task.val());
            if (value === '') {
                return;
            }
            $.ajax({
                url: '/api/task',
                type: 'post',
                dataType: 'json',
                data: {task: value},
                success: function (data) {
                    task.val('');
                    dataTable.row.add(data).draw(false);
                }
            });
        });

    });
})(jQuery);
