<!DOCTYPE html>
<html>

<head>

</head>

<body>
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
