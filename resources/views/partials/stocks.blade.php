<table class="table table-bordered">
    <tr>
        <th>Item Name</th>
        <th>Unit Name</th>
        <th>Whole</th>
        <th>Pieces</th>
        <th>Last Updated</th>

    </tr>
    @foreach ($stocks as $stock)
        <tr>
            <td>{{ $stock->item_name }}</td>
            <td>{{ $stock->unit_name }}</td>
            <td>{{ intval($stock->totalqtyWHOLE) }}</td>
            <td>{{ $stock->totalqtyREMAINDER }}</td>
            <td>{{ $stock->created_at }}</td>
        </tr>
    @endforeach
</table>
