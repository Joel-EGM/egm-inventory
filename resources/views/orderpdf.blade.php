<!DOCTYPE html>
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

</html>
