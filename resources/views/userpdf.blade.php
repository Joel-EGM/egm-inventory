<!DOCTYPE html>
<html>

<head>
    <style type="text/css">
        * {
            font-family: Verdana, Arial, sans-serif;
        }

        a {
            color: #fff;
        }

        /* table {
            margin: 15px;
            font-size: x-small;
        } */

        .stocks table {
            text-align: left;
            font-size: x-small;
        }

        .stocks th {
            text-align: left;
        }

        .stocks h3 {
            text-align: center;
        }
    </style>
</head>

<body>
    <div>
        Printed Date: {{ now()->format('Y-m-d H:i') }}
    </div>
    <div class="stocks">
        <h3>USER ACCOUNT</h3>
        <table width="100%" border="1px" cellpadding="1" cellspacing="0">
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
