<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="{{ public_path('css/style.css') }}" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>
<page orientation="L">

    <body>
        <div class="po-box">
            @if ($orderDetails[0]->id === 1)
                <p align="center" style="font-weight: Bold; font-size: 20px">PURCHASE ORDER</p>
            @else
                <p align="center" style="font-weight: Bold; font-size: 20px">SALES ORDER</p>
            @endif
            <table cellpadding="0" cellspacing="0">
                </thead>
                <tr class="top">
                    <td colspan="4">
                        <table>
                            <tr>
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
                                            style="width: 100%; max-width: 340px; position: relative;
                                            bottom: 10px;" />
                                    @else
                                        <img src="{{ public_path('/images/gabay.png') }}"
                                            style="width: 100%; max-width: 300px; position: relative;
                                            bottom: 10px;" />
                                    @endif
                                </td>

                                <td style="font-family: DejaVu Sans;">
                                    @if ($orderDetails[0]->order_status === 'received')
                                        OR #:
                                        {{ sprintf('%08d', $orderDetails[0]->or_number) }}<br />
                                        OR Date: {{ $orderDetails[0]->or_date }}<br />
                                    @else
                                        Ref #: {{ sprintf('%08d', $orderDetails[0]->order_id) }}<br />
                                    @endif
                                    Branch: {{ $orderDetails[0]->branch_name }}<br />

                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <tr class="heading">
                    <td style="text-align: left;">Item</td>
                    <td>Quantity</td>
                    <td style="text-align: right;">Unit Price</td>
                    <td style="text-align: right;">Amount</td>
                </tr>
                @foreach ($orderDetails as $detail)
                    <tr class="item">
                        @if ($detail->requester != '')
                            <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                {{ $detail->item_name }} ({{ $detail->requester }})</td>
                        @else
                            <td class="text-sm text-gray-900 font-light px-6 py-4 whitespace-nowrap">
                                {{ $detail->item_name }}</td>
                        @endif
                        <td style="text-align: center;">{{ $detail->quantity }} {{ $detail->unit_name }}</td>
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
                @if (collect($orderDetails)->where('order_status', 'pending')->count() > 0)
                    <tr>
                        <td>*** - Pending Item/s</td>
                    </tr>
                @endif
            </table>
            <footer>
                <table>
                    <thead>
                        <th style="width: 50%"></th>
                        <th style="width: 50%"></th>
                    </thead>
                    <tr class="footer">
                        <td style="text-align: left;">Printed Date: {{ now()->format('Y-m-d H:i') }}
                        </td>
                        <td style="text-align: right;">Printed By: {{ Auth()->user()->name }}</td>
                    </tr>
                </table>

            </footer>
        </div>
    </body>
</page>

</html>
