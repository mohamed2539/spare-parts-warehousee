<table>
    <thead>
        <tr>
            <th>التاريخ</th><th>الكود</th><th>الصنف</th><th>الوحدة</th>
            <th>الكمية</th><th>سند الصرف</th><th>سبب الصرف</th>
            <th>المستلم</th><th>القسم الطالب</th>
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
            <td>{{ $r->voucher }}</td>
            <td>{{ $r->reason }}</td>
            <td>{{ $r->receiver }}</td>
            <td>{{ $r->request_department }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
