<!DOCTYPE html>
<html>

<head>
    <style type="text/css">
        @page {
            margin: 0px;
        }

        body {
            margin: 0px;
        }

        * {
            font-family: Verdana, Arial, sans-serif;
        }

        a {
            color: #fff;
            text-decoration: none;
        }

        table {
            font-size: x-small;
        }

        .stocks table {
            margin: 15px;
            text-align: center;

        }

        .stocks h3 {
            margin-left: 15px;
            text-align: center;
        }
    </style>

</head>

<body>
    {{-- <img src="{{ public_path('/images/egmbanner.png') }}" alt="" style="width: 200px; height: 200px" /> --}}
    {{-- <table class="table table-bordered">
        <tr>
            <th>Item Name</th>
            <th>Unit Name</th>
            <th>Whole</th>
            <th>Pieces</th>
            <th>Last Updated</th>


        </tr>
        @foreach ($stocks as $stock)
            <tr>
                <td>{{ $stock->item_name }}</td>
                <td>{{ $stock->unit_name }}</td>
                <td>{{ intval($stock->totalqtyWHOLE) }}</td>
                <td>{{ $stock->totalqtyREMAINDER }}</td>
                <td>{{ $stock->created_at }}</td>
            </tr>
        @endforeach
    </table> --}}

    <div class="stocks">
        <h3>INVENTORY STOCKS REPORT</h3>
        <table width="100%">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Unit Name</th>
                    <th>Whole</th>
                    <th>Pieces</th>
                    <th>Last Updated</th>
                </tr>
            </thead>

            @foreach ($stocks as $stock)
                <tr>
                    <td>{{ $stock->item_name }}</td>
                    <td>{{ $stock->unit_name }}</td>
                    <td>{{ intval($stock->totalqtyWHOLE) }}</td>
                    <td>{{ $stock->totalqtyREMAINDER }}</td>
                    <td>{{ $stock->created_at }}</td>
                </tr>
            @endforeach

        </table>
    </div>
</body>

</html>
