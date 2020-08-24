<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Task List</title>
    <link rel="stylesheet" href="/assets/css/bulma.min.css"/>

    <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">


    <link rel="stylesheet" href="//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css"/>
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css"/>
    <link rel="stylesheet" href="//cdn.datatables.net/1.10.21/css/dataTables.bootstrap4.min.css"/>


    <link rel="stylesheet" href="//stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">


</head>
<body>
<section class="section">
    <div class="container">
        <label>Add Task
            <input type="text" id="new-task"/>
            <button class="button" id="add-task"><i class="fa fa-plus" aria-hidden="true"></i></button>
        </label>
        <table id="task-list" class="table">
            <thead>
            <tr>
                <th>Id</th>
                <th>Complete</th>
                <th>Task</th>
                <th></th>
            </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</section>

<script src="//code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
<script src="/assets/js/todo-list.js"></script>

</body>
</html>
