<html>

<head>
    <link rel="stylesheet" href="{{ public_path('css/style.css') }}" />
</head>
<page orientation="L">

    <body>
        <div class="po-box">
            <table cellpadding="0" cellspacing="0">
                <tr class="top">
                    <td colspan="4">
                        <table>
                            <tr class="title">
                                <td>
                                    @if (
                                        $orderDetails[0]->id === 1 ||
                                            $orderDetails[0]->id === 11 ||
                                            $orderDetails[0]->id === 12 ||
                                            $orderDetails[0]->id === 13 ||
                                            $orderDetails[0]->id === 14 ||
                                            $orderDetails[0]->id === 15 ||
                                            $orderDetails[0]->id === 16)
                                        <img src="{{ public_path('/images/egmbanner.png') }}"
                                            style="width: 100%; max-width: 160px" />
                                    @else
                                        <img src="{{ public_path('/images/gabay.png') }}"
                                            style="width: 100%; max-width: 160px" />
                                    @endif
                                </td>
                                <td>
                                    @if ($orderDetails[0]->order_status === 'received')
                                        OR #: {{ $orderDetails[0]->or_number }}<br />
                                        OR Date: {{ $orderDetails[0]->or_date }}<br />
                                    @else
                                        PO #: {{ $orderDetails[0]->order_id }}<br />
                                    @endif
                                    Branch: {{ $orderDetails[0]->branch_name }}<br />
                                    Printed Date: {{ now()->format('Y-m-d H:i') }}

                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr class="information">
                    <td colspan="4">
                        <table>
                            <tr>
                                <td>
                                    FXM8+P23,<br />
                                    Paco Roman Street,<br />
                                    Cabanatuan City, Nueva Ecija
                                </td>
                                <td>
                                    EGM GABAY<br />
                                    HR Dept.<br />
                                    hrdept@gmail.com
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr class="heading">
                    <td>Item</td>
                    <td>Quantity</td>
                    <td>Unit Price</td>
                    <td>Amount</td>
                </tr>
                @foreach ($orderDetails as $detail)
                    <tr class="item">
                        <td>{{ $detail->itemName }}</td>
                        <td style="text-align: center;">{{ $detail->totalQuantity }}</td>
                        <td style="text-align: right;">{{ $detail->price }}</td>
                        <td style="text-align: right;">{{ $detail->totalAmount }}</td>
                    </tr>
                @endforeach

            </table>
            <footer>
                <table>
                    <tr class="line">
                        <td>________________</td>
                        <td>________________</td>
                        <td>________________</td>

                    </tr>
                    <tr class="footer">
                        <td>Prepared By:</td>
                        <td>Checked By:</td>
                        <td>Received By:</td>
                    </tr>
                </table>
            </footer>
        </div>
    </body>
</page>

</html>
