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
        <td style="align-items: right; padding-left:260px; padding-bottom:20px">{{ now()->format('m-d-Y') }}</td>

    </table>
    <table class="topPadding" cellpadding="1" cellspacing="0" width="100%">

        <tr>
            <th width="5%"></th>
            <th width="15%"></th>
            <th width="50%"></th>
            <th width="15%"></th>
            <th width="0%"></th>
        </tr>
        @foreach ($orderDetails as $detail)
            <tr class="item">

                <td style="text-align: center;">&nbsp;</td>
                <td style="text-align: right;">{{ $detail->quantity }}</td>
                <td style="padding-left: 10px; text-align: left;">{{ $detail->item_name }}</td>
                <td style="text-align: right; padding-right: 20px">{{ $detail->price }}</td>
                <td style="padding-left: 10px; padding-right: 25px; text-align: right">{{ $detail->total_amount }}</td>
            </tr>
        @endforeach
    </table>
    <table width="100%">
        <tr>
            <td style="font-size: 12px; padding-left: 380px; padding-top: 20px">GRAND TOTAL:</td>
            <td style="text-align: right; padding-top: 20px; padding-right: 25px">
                {{ array_sum(array_column($orderDetails, 'total_amount')) }}.00</td>
        </tr>
    </table>

    </div>
</body>


</html>
