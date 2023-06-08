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
            margin: 20px;
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
        <table width="100%" cellpadding="1" cellspacing="0">
            <thead>
                <tr>
                    <th style="text-align: left;">Item Name</th>
                    <th style="text-align: left;">Unit Name</th>
                    <th style="text-align: right;">Whole</th>
                    <th style="text-align: right;">Pieces</th>
                    <th>Last Updated</th>
                </tr>
            </thead>

            @foreach ($stocks as $stock)
                <tr>
                    <td style="text-align: left;">{{ $stock->item_name }}</td>
                    <td>{{ $stock->unit_name }}</td>
                    <td style="text-align: right;">{{ intval($stock->totalqtyWHOLE) }}</td>
                    <td style="text-align: right;">{{ $stock->totalqtyREMAINDER }}</td>
                    <td style="text-align: center;">{{ $stock->created_at }}</td>
                </tr>
            @endforeach

        </table>
    </div>
</body>

</html>
