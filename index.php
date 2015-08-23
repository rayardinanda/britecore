<html>
<head><title>Feature Requests</title>
    <!--    JQuery-->
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>

    <!--    Bootstrap-->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap-theme.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>

    <!--    Moment JS-->
    <script type="application/javascript" src="bower_components/moment/moment.js"></script>

    <!--    Datetimepicker-->
    <script type="application/javascript" src="bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js"></script>
    <link rel="stylesheet" href="bower_components/eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css">

    <!--    KnockoutJS-->
    <script type="application/javascript" src="js/knockout-3.3.0.debug.js"></script>
    <script type="application/javascript" src="js/knockout.mapping-latest.debug.js"></script>

    <!-- DataTables CSS -->
    <link rel="stylesheet" type="text/css" href="//cdn.datatables.net/1.10.8/css/jquery.dataTables.css">
    <!-- DataTables -->
    <script type="text/javascript" charset="utf8" src="//cdn.datatables.net/1.10.8/js/jquery.dataTables.js"></script>


    <script type="text/javascript">
        $(document).ready(function () {
            function Option(data) {
                this.value = ko.observable(data.id);
                this.text = ko.observable(data.name);
            }

            function viewModel() {
                var self = this;
                self.clients = ko.observableArray([]);
                self.clientList = ko.observable();
                self.priorities = ko.observableArray([]);
                self.products = ko.observableArray([]);

                $.getJSON("./api/requests.php/clients", function (data) {
                    var mappedTasks = $.map(data, function (item) {
                        return new Option(item)
                    });
                    self.clients(mappedTasks);
                });

                self.clientList.subscribe(function (selected) {
                    var apiUrl = "./api/requests.php/priorities/" + selected;
                    $.getJSON(apiUrl, function (data) {
                        var mappedTasks = $.map(data, function (item) {
                            return new Option(item)
                        });
                        self.priorities(mappedTasks);
                    });
                }, self);

                $.getJSON("./api/requests.php/products", function (data) {
//                alert(data);

                    var mappedTasks = $.map(data, function (item) {
                        return new Option(item)
                    });
                    self.products(mappedTasks);
                });

            }


            ko.applyBindings(new viewModel());

        });
    </script>

    <script type="text/javascript">


        $(document).ready(function () {
            $('#datepicker').datetimepicker({
                format: 'YYYY-MM-DD'
            });

            oTable = $("#table").DataTable({
                "processing": true,
                "serverSide": false,
                "ajax": "./api/requests.php/requests/datatable",
                "columnDefs": [
                    {
                        "targets": [0],
                        "visible": false,
                        "searchable": false
                    },
                    {
                        "targets": [3],
                        "visible": false,
                        "searchable": false
                    },
                    {
                        "targets": [8],
                        "visible": false
                    }
                ]
            });

        });
    </script>

</head>


<body>
<div class="container-fluid">
    <div class="text-center col-md-4 col-md-offset-4">
        <h1>Feature Request</h1>

        <form action="controllers/featureController.php" method="post" role="form">
            <input type="hidden" name="action" value="addNewFeatureRequest"/>

            <div class="row">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" class="form-control" required/>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea name="description" class="form-control" required></textarea>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="client">Client</label>
                    <select name="client"
                            data-bind="value: clientList, options: clients, optionsText:'text', optionsValue:'value'"
                            class="form-control">

                    </select>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="priority">Client Priority</label>
                    <select name="priority"
                            data-bind="options: priorities, optionsText:'text', optionsValue:'value'"
                            class="form-control"></select>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="date">Target Date:</label>
                    <div class='input-group date' id='datepicker'>
                        <input type='text' name="date" class="form-control" required/>
                    <span class="input-group-addon">
                        <span class="glyphicon glyphicon-calendar"></span>
                    </span>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="url">Ticket URL</label>
                    <input type="text" name="url" class="form-control"/>
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <label for="nama">Product Area</label>
                    <select name="product"
                            data-bind="options: products, optionsText:'text', optionsValue:'value'"
                            class="form-control">
                </div>
            </div>
            <div class="row">
                <div class="form-group">
                    <div class="">
                        <input type="submit" class="form-login btn btn-md btn-primary btn-block"/>
                    </div>
                </div>
            </div>

        </form>

    </div>
    <div class="text-center col-md-12">
        <hr>
        <h2>Requests Submitted</h2>
    </div>

    <div class="row col-md-12">
        <table cellpadding="0" cellspacing="0" border="0" class="display" id="table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th>Desc</th>
                <th>client_id</th>
                <th>Client</th>
                <th>Priority</th>
                <th>Target Date</th>
                <th>Ticket URL</th>
                <th>product_id</th>
                <th>Product</th>
            </tr>
            </thead>
        </table>

    </div>


</div>


</body>
</html>
