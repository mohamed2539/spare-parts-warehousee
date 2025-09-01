<table>
    <thead>
        <tr>
            <th>التاريخ</th><th>الكود</th><th>الصنف</th><th>الوحدة</th>
            <th>الكمية</th><th>المورد</th><th>القسم</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $r)
        <tr>
            <td>{{ optional($r->date)->format('Y-m-d') }}</td>
            <td>{{ $r->code }}</td>
            <td>{{ $r->item }}</td>
            <td>{{ $r->unit }}</td>
            <td>{{ $r->quantity }}</td>
            <td>{{ $r->supplier }}</td>
            <td>{{ $r->department }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
