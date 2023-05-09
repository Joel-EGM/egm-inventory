{{-- <!DOCTYPE html>
<html>

<head>

</head>

<body>
    <img src="{{ public_path('/images/egmbanner.png') }}" alt="" style="width: 200px; height: 200px" />
    <table class="table table-bordered">
        <tr>
            <th>Supplier Name</th>
            <th>Item Name</th>
            <th>Unit Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total Amount</th>

        </tr>
        @foreach ($orderDetails as $detail)
            <tr>
                <td>{{ $detail->supplier_name }}</td>
                <td>{{ $detail->item_name }}</td>
                <td>{{ $detail->unit_name }}</td>
                <td>{{ $detail->qty }}</td>
                <td>{{ $detail->price }}</td>
                <td>{{ $detail->tlamt }}</td>

            </tr>
        @endforeach
    </table>

</body>

</html> --}}

<html>

<head>
    <link rel="stylesheet" href="{{ public_path('css/style.css') }}" />
</head>

<body>
    <div class="po-box">
        <table cellpadding="0" cellspacing="0">
            <tr class="top">
                <td colspan="2">
                    <table>
                        <tr>
                            <td class="title">
                                <img src="{{ public_path('/images/egmbanner.png') }}"
                                    style="width: 100%; max-width: 250px" />
                            </td>
                            <td>
                                PO #: {{ $orderDetails[0]->order_id }}<br />
                                Created: {{ date('m-d-Y') }}
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr class="information">
                <td colspan="2">
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
            <tr class="details">
                <td></td>
                <td></td>
            </tr>
            <tr class="heading">
                <td>Item</td>
                <td>Amount</td>
            </tr>
            @foreach ($orderDetails as $detail)
                <tr class="">
                    <td>{{ $detail->itemName }}</td>

                    <td>{{ $detail->totalAmount }}</td>
                </tr>
            @endforeach

            {{-- <tr class="total">
                <td></td>
                <td>Total: Php{{ $orderDetails[0]->Total }}</td>
            </tr> --}}
        </table>
    </div>
</body>

</html>
