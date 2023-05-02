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
    <div class="stocks">
        <h3>INVENTORY STOCKS REPORT</h3>
        <table width="100%">
            <thead>
                <tr>
                    <th>Item Name</th>
                    <th>Quantity</th>
                    <th>Date Received</th>
                </tr>
            </thead>
            @foreach ($stocks as $stock)
                <tr>
                    <td>{{ $stock->item_name }}</td>
                    <td>{{ $stock->totalqty }}</td>
                    <td>{{ $stock->created_at }}</td>
                </tr>
            @endforeach
        </table>

</body>

</html>
