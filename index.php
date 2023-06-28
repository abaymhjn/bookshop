<!DOCTYPE html>
<html>
<head>
    <title>Sales Data</title>
    <link id="style" href="js/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Data table css -->
    <link href="js/datatables/DataTables/css/dataTables.bootstrap5.css" rel="stylesheet" />
    <link href="js/datatables/Buttons/css/buttons.bootstrap5.min.css"  rel="stylesheet">
    <link href="js/datatables/Responsive/css/responsive.bootstrap5.min.css" rel="stylesheet" />
    <style>
        .row {
            margin : 1%;
        }
    </style>    
</head>
<body>
    <div class="row">
        <div class="col-12">
            <h1>Sales</h1>
            <a href="read_and_save_json_data.php" class="btn btn-info" target="_blank">Import Data from Json File</a>
        </div>
    </div>
    <div class="row">
        <div class="col-3">
            <label for="customer-filter">Customer:</label>
            <input type="text" id="customer-filter" class="filter-input">
        </div>
        <div class="col-3">
            <label for="customer-mail-filter">Customer mail:</label>
            <input type="text" id="customer-mail-filter" class="filter-input">
        </div>
        <div class="col-3">
            <label for="product-filter">Product:</label>
            <input type="text" id="product-filter" class="filter-input">
        </div>
        <div class="col-3">
            <label for="price-filter">Price:</label>
            <input type="text" id="price-filter" class="filter-input">
        </div>
    </div>
    <div class="row">
        <table id="sales-table" class="display table table-bordered text-nowrap" style="width:100%">
            <thead>
                <tr>
                    <th>Sale ID</th>
                    <th>Customer Name</th>
                    <th>Customer Mail</th>
                    <th>Product Name</th>
                    <th>Product Price</th>
                    <th>Sale Date</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th colspan="4"></th>
                    <th></th>
                    <th></th>
                </tr>
            </tfoot>
        </table>
    </div>    
    <script src="js/jquery.min.js"></script>
    <script src="js/bootstrap/popper.min.js"></script>
    <script src="js/bootstrap/js/bootstrap.min.js"></script>
    <!-- INTERNAL Data tables -->
    <script src="js/datatables/DataTables/js/jquery.dataTables.js"></script>
    <script src="js/datatables/DataTables/js/dataTables.bootstrap5.js"></script>
    <script src="js/datatables/Buttons/js/dataTables.buttons.min.js"></script>
    <script src="js/datatables/Buttons/js/buttons.bootstrap4.min.js"></script>
    <script src="js/datatables/JSZip/jszip.min.js"></script>
    <script src="js/datatables/pdfmake/pdfmake.min.js"></script>
    <script src="js/datatables/pdfmake/vfs_fonts.js"></script>
    <script src="js/datatables/Buttons/js/buttons.html5.min.js"></script>
    <script src="js/datatables/Buttons/js/buttons.print.min.js"></script>
    <script src="js/datatables/Buttons/js/buttons.colVis.min.js"></script>
    <script src="js/datatables/Responsive/js/dataTables.responsive.min.js"></script>
    <script src="js/datatables/Responsive/js/responsive.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            // Initialize DataTables
            var table = $('#sales-table').DataTable({
                bProcessing	: true,
                serverSide: true,
                lengthMenu: [
                    [ 10, 25, 50, -1 ],
                    [ '10 rows', '25 rows', '50 rows', 'Show all' ]
                ],
                ajax: {
                    url: '_ajax_get_sales.php',
                    type: 'POST',
                    dataType:'json',
                    data: function (d){
                        d.customer_name = $('#customer-filter').val();
                        d.customer_mail = $('#customer-mail-filter').val();
                        d.product_name = $('#product-filter').val();
                        d.product_price = $('#price-filter').val();
                    }
                },
                aoColumns  	 	: [
                    {
                        mData		: "sale_id",
                        sName		: "sale_id",
                        sTitle		: "Sale Id",
                        searchable 	: true,
                        orderable  	: true
                    },
                    {
                        mData		: "sale_date",
                        sName		: "sale_date",
                        sTitle		: "Sale Date",
                        searchable 	: true,
                        orderable  	: true
                    },
                    {
                        mData		: "customer_name",
                        sName		: "customer_name",
                        sTitle		: "Customer Name",
                        searchable 	: true,
                        orderable  	: true
                    },
                    {
                        mData		: "customer_mail",
                        sName		: "customer_mail",
                        sTitle		: "Customer Mail",
                        searchable 	: true,
                        orderable  	: true
                    },
                    {
                        mData		: "product_price",
                        sName		: "product_price",
                        sTitle		: "Product Price",
                        searchable 	: true,
                        orderable  	: true
                    },
                    {
                        mData		: "product_name",
                        sName		: "product_name",
                        sTitle		: "Product Name",
                        searchable 	: true,
                        orderable  	: true
                    }
                ],
                order: [[1, 'desc']],
		        responsive: !0,
                language: {
                    searchPlaceholder: 'Search...',
                    scrollX: "100%",
                    sSearch: '',
                    lengthMenu: '_MENU_',
                    sEmptyTable: "No data available in table"
                },
                footerCallback: function (row, data, start, end, display) {
                    var api = this.api();
                    var intVal = function (i) {
                        return typeof i === 'string' ? i.replace(/[\$,]/g, '') * 1 : typeof i === 'number' ? i : 0;
                    };
                    pageTotal = api.column(4, { page: 'current' }).data().reduce(function (a, b) {
                        return intVal(a) + intVal(b);
                    }, 0);
                    var total = api.ajax.json().total_price;;
                    $(api.column(4).footer()).html('Page Total '+parseFloat(pageTotal.toFixed(2))+' (Total Price: ' + parseFloat(total.toFixed(2))+')');
                }
            });

            // Apply filters when filter values change
            $('.filter-input').on('change', function () {
                table.ajax.reload();
            });
            $("#sales-table thead th input[type=text]").on( 'keyup change', function () {
                $("#sales-table").DataTable()
                .search( this.value )
                .draw();
                
            });
        });
    </script>
</body>
</html>
