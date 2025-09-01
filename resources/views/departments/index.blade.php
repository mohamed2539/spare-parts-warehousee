@extends('layouts.app')

@section('content')
<div class="max-w-5xl mx-auto px-6 py-8">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">📁 إدارة الأقسام</h1>

    {{-- الفورم --}}
    <div class="bg-white shadow-md rounded-xl p-6 mb-6">
        <h2 class="text-xl font-semibold mb-4">إضافة قسم جديد</h2>
        <form id="addDepartmentForm" class="space-y-4">
            @csrf
            <div>
                <label class="block text-gray-700 font-medium">اسم القسم</label>
                <input type="text" name="name" required
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-gray-700 font-medium">المدير</label>
                <input type="text" name="manager"
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500">
            </div>
            <div>
                <label class="block text-gray-700 font-medium">الوصف</label>
                <textarea name="description"
                    class="mt-1 block w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500"></textarea>
            </div>
            <button type="submit"
                class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow-md transition duration-150">
                ➕ إضافة القسم
            </button>
        </form>
        <p id="successMsg" class="text-green-600 mt-2 hidden">تم إضافة القسم بنجاح!</p>
        <p id="errorMsg" class="text-red-600 mt-2 hidden"></p>
    </div>

    {{-- الجدول --}}
    <div class="overflow-x-auto bg-white shadow-lg rounded-xl">
        <table id="departmentsTable" class="min-w-full divide-y divide-gray-200 text-gray-700">
            <thead class="bg-gray-100 uppercase text-gray-600 text-sm tracking-wider">
                <tr>
                    <th class="px-6 py-3">#</th>
                    <th class="px-6 py-3">اسم القسم</th>
                    <th class="px-6 py-3">المدير</th>
                    <th class="px-6 py-3">الوصف</th>
                    <th class="px-6 py-3">الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                @foreach($departments as $dept)
                <tr data-id="{{ $dept->id }}">
                    <td class="px-6 py-3">{{ $loop->iteration }}</td>
                    <td class="px-6 py-3">{{ $dept->name }}</td>
                    <td class="px-6 py-3">{{ $dept->manager }}</td>
                    <td class="px-6 py-3">{{ $dept->description }}</td>
                    <td class="px-6 py-3 space-x-2">
                        <button class="editBtn bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded">✏️ تعديل</button>
                        <button class="deleteBtn bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">🗑️ حذف</button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function(){

    // إضافة قسم جديد
    $('#addDepartmentForm').submit(function(e){
        e.preventDefault();
        let form = $(this);
        let data = form.serialize();

        $.ajax({
            url: "{{ route('departments.store') }}",
            type: "POST",
            data: data,
            success: function(res){
                if(res.error){
                    $('#errorMsg').text(res.error).removeClass('hidden').fadeIn().delay(3000).fadeOut();
                    return;
                }

                $('#successMsg').removeClass('hidden').fadeIn().delay(2000).fadeOut();

                let newRow = `<tr data-id="${res.department.id}">
                    <td class="px-6 py-3">${$('#departmentsTable tbody tr').length + 1}</td>
                    <td class="px-6 py-3">${res.department.name}</td>
                    <td class="px-6 py-3">${res.department.manager || ''}</td>
                    <td class="px-6 py-3">${res.department.description || ''}</td>
                    <td class="px-6 py-3 space-x-2">
                        <button class="editBtn bg-yellow-400 hover:bg-yellow-500 text-white px-3 py-1 rounded">✏️ تعديل</button>
                        <button class="deleteBtn bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded">🗑️ حذف</button>
                    </td>
                </tr>`;
                $('#departmentsTable tbody').append(newRow);
                form[0].reset();
            },
            error: function(err){
                alert('حدث خطأ، تحقق من البيانات وأعد المحاولة.');
            }
        });
    });

    // حذف قسم
    $(document).on('click', '.deleteBtn', function(){
        if(!confirm('هل أنت متأكد من حذف القسم؟')) return;

        let row = $(this).closest('tr');
        let id = row.data('id');

        $.ajax({
            url: "/departments/"+id,
            type: "DELETE",
            data: {_token: "{{ csrf_token() }}"},
            success: function(res){
                row.remove();
            },
            error: function(err){
                alert('حدث خطأ أثناء الحذف');
            }
        });
    });

    // تعديل قسم (تعديل سريع inline)
    $(document).on('click', '.editBtn', function(){
        let row = $(this).closest('tr');
        let id = row.data('id');
        let nameCell = row.find('td').eq(1);
        let managerCell = row.find('td').eq(2);
        let descCell = row.find('td').eq(3);

        let currentName = nameCell.text();
        let currentManager = managerCell.text();
        let currentDesc = descCell.text();

        // استبدال المحتوى inputs
        nameCell.html(`<input type="text" value="${currentName}" class="border rounded px-2 py-1 w-full">`);
        managerCell.html(`<input type="text" value="${currentManager}" class="border rounded px-2 py-1 w-full">`);
        descCell.html(`<input type="text" value="${currentDesc}" class="border rounded px-2 py-1 w-full">`);

        $(this).text('💾 حفظ').removeClass('editBtn bg-yellow-400 hover:bg-yellow-500').addClass('saveBtn bg-green-600 hover:bg-green-700');
    });

    // حفظ التعديل
    $(document).on('click', '.saveBtn', function(){
        let row = $(this).closest('tr');
        let id = row.data('id');

        let name = row.find('td').eq(1).find('input').val();
        let manager = row.find('td').eq(2).find('input').val();
        let description = row.find('td').eq(3).find('input').val();

        $.ajax({
            url: "/departments/"+id,
            type: "PUT",
            data: {
                _token: "{{ csrf_token() }}",
                name: name,
                manager: manager,
                description: description
            },
            success: function(res){
                row.find('td').eq(1).text(res.department.name);
                row.find('td').eq(2).text(res.department.manager || '');
                row.find('td').eq(3).text(res.department.description || '');
                row.find('.saveBtn').text('✏️ تعديل').removeClass('saveBtn bg-green-600 hover:bg-green-700').addClass('editBtn bg-yellow-400 hover:bg-yellow-500');
            },
            error: function(err){
                alert('حدث خطأ أثناء حفظ التعديلات');
            }
        });
    });

});
</script>
@endsection
