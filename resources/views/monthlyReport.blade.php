<!DOCTYPE html>
<html>

<head>
    <style type="text/css">
        @page {
            margin: 0px;
        }

        body {
            margin: 30px;
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
        <h3>MONTHLY REPORT</h3>
        <table width="100%">
            <thead>
                <tr>
                    <th style="text-align: left">BRANCH NAME</th>
                    <th style="text-align: center">YEAR</th>
                    <th style="text-align: center">MONTH</th>
                    <th style="text-align: right">TOTAL AMOUNT</th>
                </tr>
            </thead>

            @foreach ($monthlyreport as $report)
                <tr>
                    <td style="text-align: left">{{ $report->branch_name }}</td>
                    <td style="text-align: center">{{ $report->Year }}</td>
                    <td style="text-align: center">{{ $report->MonthName }}</td>
                    <td style="text-align: right">{{ $report->Total }}</td>
                </tr>
            @endforeach

        </table>
    </div>
</body>

</html>
