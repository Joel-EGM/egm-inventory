<html>

<head>
    <link rel="stylesheet" href="{{ public_path('css/style2.css') }}" />
</head>


<body>

    <table class="header">
        <tr>
            <td style="padding-left: 100px">{{ $orderDetails[0]->branch_name }}</td>
        </tr>
        <td></td>
        <td style="align-items: right; padding-left:300px; padding-bottom:20px">{{ now()->format('m-d-Y') }}</td>

    </table>
    <table class="topPadding" cellpadding="1" cellspacing="0" width="100%">

        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
            <th></th>
        </tr>
        @foreach ($orderDetails as $detail)
            <tr class="item">

                <td style="padding-right: 50px; text-align: center;"> </td>
                <td style="padding-right: 10px; text-align: center;">{{ $detail->totalQuantity }}</td>
                <td style="padding-left: 10px; text-align: left;">{{ $detail->itemName }}</td>
                <td style="padding-left: 10px;">{{ $detail->price }}</td>
                <td style="padding-right: 10px;">{{ $detail->totalAmount }}</td>
            </tr>
        @endforeach

    </table>
    {{-- <footer>
        <table cellpadding="1" cellspacing="0" width="100%" border="1px solid black">
            <tr class="footer">
                <td>Prepared By:</td>
                <td>Checked By:</td>
                <td>Received By:</td>
            </tr>
            <tr class="line">
                <td>____________</td>
                <td>____________</td>
                <td>____________</td>

            </tr>

        </table>
    </footer> --}}
    </div>
</body>


</html>
