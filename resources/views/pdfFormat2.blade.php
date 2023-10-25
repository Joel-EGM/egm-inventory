<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="{{ public_path('css/tablelayout.css') }}" />
</head>

<body>
    <div>
        Printed Date: {{ now()->format('Y-m-d') }}
    </div>
    <div class="stocks">
        <h3>INVENTORY STOCKS REPORT</h3>
        <table id="tablelayout">
            <thead>
                <tr>
                    <th>ITEM NAME</th>
                    <th>QUANTITY</th>
                    <th>DATE RECEIVED</th>
                </tr>
            </thead>
            @foreach ($stocks as $stock)
                <tr>
                    <td class="leftalign">{{ $stock->item_name }}</td>
                    <td class="center">{{ $stock->totalqty }}</td>
                    <td class="leftalign">{{ $stock->created_at }}</td>
                </tr>
            @endforeach
        </table>

</body>

</html>
