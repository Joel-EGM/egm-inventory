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
                                            style="width: 100%; max-width: 300px; position: relative;
                                            bottom: 10px;" />
                                    @else
                                        <img src="{{ public_path('/images/gabay.png') }}"
                                            style="width: 100%; max-width: 250px; position: relative;
                                            bottom: 50px;" />
                                    @endif
                                </td>
                                <td>
                                    @if ($orderDetails[0]->order_status === 'received')
                                        OR #: {{ sprintf('%08d', $orderDetails[0]->or_number) }}<br />
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
                        <table style="position: relative;
                        bottom: 20px;">
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
                    <td style="text-align: right;">Unit Price</td>
                    <td style="text-align: right;">Amount</td>
                </tr>
                @foreach ($orderDetails as $detail)
                    <tr class="item">
                        <td>{{ $detail->item_name }}</td>
                        <td style="text-align: center;">{{ $detail->quantity }}</td>
                        <td style="text-align: right;">{{ number_format($detail->price, 2) }}</td>
                        <td style="text-align: right;">{{ number_format($detail->total_amount, 2) }}</td>
                    </tr>
                @endforeach

            </table>
            <table width="100%">
                <tr>
                    <td style="font-size: 12px; padding-left: 500px; padding-top: 20px">GRAND TOTAL:</td>
                    <td style="text-align: right; padding-top: 20px; padding-right: 3px">
                        {{ number_format(array_sum(array_column($orderDetails, 'total_amount')), 2) }}</td>
                </tr>
            </table>
            {{-- <footer>
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
            </footer> --}}
        </div>
    </body>
</page>

</html>
