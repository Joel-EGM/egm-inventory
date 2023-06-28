<!DOCTYPE html>
<html>

<head>
    <style type="text/css">
        @page {
            margin: 0px;
        }

        body {
            margin: 0px;
        }

        * {
            font-family: Verdana, Arial, sans-serif;
        }

        a {
            color: #fff;
            text-decoration: none;
        }

        table {
            font-size: x-small;
        }

        .stocks table {
            margin: 15px;
            text-align: center;

        }

        .stocks h3 {
            margin-left: 15px;
            text-align: center;
        }
    </style>

</head>

<body>
    <div>
        Printed Date: {{ now()->format('Y-m-d H:i') }}
    </div>
    <div class="stocks">
        <h3>MONTHLY REPORT</h3>
        <table width="100%">
            <thead>
                <tr>
                    <th>BRANCH NAME</th>
                    <th>YEAR</th>
                    <th>MONTH</th>
                    <th>TOTAL AMOUNT</th>
                </tr>
            </thead>

            @foreach ($monthlyreport as $report)
                <tr>
                    <td>{{ $report->branch_name }}</td>
                    <td>{{ $report->Year }}</td>
                    <td>{{ $report->MonthName }}</td>
                    <td>{{ $report->Total }}</td>
                </tr>
            @endforeach

        </table>
    </div>
</body>

</html>
