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
                <td>{{ $detail->supplier_id }}</td>
                {{-- <td>{{ $detail->suppliers->suppliers_name }}</td>
                <td>{{ $detail->items->item_name }}</td>
                <td>{{ $detail->items->unit_name }}</td>
                <td>{{ $detail->quantity }}</td>
                <td>{{ $detail->price }}</td>
                <td>{{ $detail->total_amount }}</td> --}}

            </tr>
        @endforeach
    </table>

</body>

</html>
