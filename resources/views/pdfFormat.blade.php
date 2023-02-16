<!DOCTYPE html>
<html>

<head>

</head>

<body>
    <img src="{{ public_path('/images/egmbanner.png') }}" alt="" style="width: 200px; height: 200px" />
    <table class="table table-bordered">
        <tr>
            <th>Item Name</th>
            <th>Quantity</th>

        </tr>
        @foreach ($stocks as $stock)
            <tr>
                <td>{{ $stock->item_name }}</td>
                <td>{{ $stock->totalqty }}</td>

            </tr>
        @endforeach
    </table>

</body>

</html>
