{{--@formatter:off--}}
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <style>

        body {
            font-family: 'Arial', sans-serif;
            font-weight: normal;
            font-size: 10pt;
            color: #373737;
            line-height: 1em;
            text-rendering: geometricPrecision;
            padding: 0;
            margin: 0;
        }

        @page {
            margin: 1cm;
        }

    </style>
</head>
<body>
<table style="width: 100%">
    @php $index = 0; @endphp

    @foreach($party->guests as $guest)
        @if($index === 0  || is_integer(($index) / 4))
            <tr>
        @endif


        <td style="padding: 2mm; text-align: center; font-size: 8pt;">
            <div style="text-transform: uppercase; padding-bottom: 2mm; color: #aaaaaa">
                {{ Str::random(7) }}@foreach($guest->tableImages as $tableImage){{ $tableImage->table_id }}@endforeach{{ Str::random(3) }}
            </div>

            <div>
                <img src="{{ public_path($guest->qr()) }}" style="width: 2.5cm; height: auto;" />
            </div>
        </td>



        @if($index > 0 && (is_integer(($index+1) / 4)) || $index === ($party->guests->count() * $party->rounds) - 1)
            </tr>
        @endif

            @php $index++; @endphp
    @endforeach
</table>
</body>
</html>
