<head>
    <meta charset="utf-8">
    <title>Receipt No. {{ $receipt->id }}</title>
    <style>
        .table {
            border-collapse: collapse;
            width: 100%;
        }
        .table td, .table th {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .table tr:nth-child(even){background-color: #f2f2f2;}
        .table tr:hover {background-color: #ddd;}
        .table th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #4CAF50;
            color: white;
        }
        .table tfoot {
            font-weight: bold;
        }

        /** text-end */
        .text-end {
            text-align: right!important;
        }
    </style>
</head>
<body>
    <p>Receipt No. {{ $receipt->id }}</p>
    <p>Student Name: {{ $receipt->enrolledStudent->student->getName() }}</p>
    <p>Student ID: {{ $receipt->enrolledStudent->student->id }}</p>
    <p>Program & Year: {{ $receipt->enrolledStudent->degreeProgram->abbr }} {{ $receipt->enrolledStudent->year_level }}</p>

    <table class="table">
        <thead>
            <tr>
                <th>Description</th>
                <th>Category</th>
                <th class="text-end">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($receipt->transactions as $item)
                <tr>
                    <td>{{ $item->description }}</td>
                    <td>{{ ucwords($item->category) }}</td>
                    <td align="right">{{ number_format($item->amount, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <th colspan="2">Total</th>
            <th class="text-end">{{ number_format($receipt->total, 2) }}</th>
        </tfoot>
    </table>

</body>
