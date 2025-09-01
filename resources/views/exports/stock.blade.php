<table>
    <thead>
        <tr>
            <th>الكود</th><th>الصنف</th><th>الوحدة</th><th>القسم</th><th>المورد</th>
            <th>إجمالي وارد</th><th>إجمالي صرف</th><th>الرصيد</th>
        </tr>
    </thead>
    <tbody>
        @foreach($rows as $r)
        <tr>
            <td>{{ $r->code }}</td>
            <td>{{ $r->item }}</td>
            <td>{{ $r->unit }}</td>
            <td>{{ $r->department }}</td>
            <td>{{ $r->supplier }}</td>
            <td>{{ $r->total_in }}</td>
            <td>{{ $r->total_out }}</td>
            <td>{{ $r->balance }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
