<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" href="{{ public_path('css/tablelayout.css') }}" />
</head>

<body>
    <div>
        Printed Date: {{ now()->format('Y-m-d H:i') }}
    </div>
    <div class="stocks">
        <h3>USER ACCOUNT</h3>
        <table id="tablelayout">
            <thead>
                <tr>
                    <th>NAME</th>
                    <th>EMAIL</th>
                </tr>
            </thead>
            @foreach ($users as $suer)
                <tr>
                    <td>{{ $suer->name }}</td>
                    <td>{{ $suer->email }}</td>
                </tr>
            @endforeach
        </table>

</body>

</html>
