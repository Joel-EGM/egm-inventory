<head>
    <link rel="stylesheet" href="{{ public_path('css/tablelayout.css') }}" />
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
</head>


@if (count($monthlyreport) > 0)

    <div class="stocks">


        <table id="tablelayout">
            <thead>
                <tr>
                    <th style="text-align: left; width: 20%">BRANCH NAME</th>
                    <th style="text-align: center; width: 20%">ACCOUNT NUMBER</th>
                    <th style="text-align: center; width: 20%">OR NUMBER</th>
                    <th style="text-align: left; width: 20%">OR DATE</th>
                    <th class="rightalign; width: 20%">TOTAL AMOUNT</th>
                </tr>
            </thead>
            <?php $grand_total = 0;
            ?>
            @foreach ($monthlyreport as $key => $report)
                <tr>
                    <td class="leftalign bold">{{ Str::upper($key) }}</td>
                    <td class="center">{{ $report[0]->acc_number }}</td>
                    @foreach ($report as $key => $ret)
                        @if ($key > 0)
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td class="center">{{ sprintf('%08d', $ret->or_number) }}</td>
                    <td class="leftalign">{{ $ret->or_date }}</td>
                    <td class="rightalign" style="font-family: DejaVu Sans;">
                        {{ $ret->Total }}
                    </td>
                </tr>
            @else
                <td class="center">{{ sprintf('%08d', $ret->or_number) }}</td>
                <td class="leftalign">{{ $ret->or_date }}</td>
                <td class="rightalign" style="font-family: DejaVu Sans;">{{ $ret->Total }}
                </td>
            @endif
@endforeach
@if (count($report) > 1)
    <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <td class="rightalign total">Branch Total: </td>
        <td class="rightalign total blue" style="font-family: DejaVu Sans;">
            {{ $report->sum('Total') }}</td>

    </tr>
@endif
<?php
$grand_total += $report->sum('Total');
?>
@endforeach
<tr>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
    <td class="rightalign total">GRAND TOTAL: </td>
    <td class="rightalign total green" style="font-family: DejaVu Sans;">
        {{ $grand_total }}</td>
</tr>
</table>
</div>
@endif
