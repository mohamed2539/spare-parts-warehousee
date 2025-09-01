<?php

namespace App\Http\Controllers;

use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{
    public function index()
    {
        $departments = Department::all(); // أو paginate()
        return view('departments.index', compact('departments'));
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|string|max:255',
    //         'manager' => 'nullable|string|max:255', // مسؤول القسم
    //         'notes' => 'nullable|string|max:500',    // أي معلومات إضافية
    //     ]);

    //     $department = Department::create($request->all());

    //     return response()->json(['message' => 'تم إضافة القسم', 'department' => $department]);
    // }



    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255|unique:departments,name', // منع التكرار
            'manager' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);
    
        $department = Department::create($data);
    
        return response()->json([
            'department' => $department
        ]);
    }

    public function update(Request $request, Department $department)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'manager' => 'nullable|string|max:255',
            'notes' => 'nullable|string|max:500',
        ]);

        $department->update($request->all());

        return response()->json(['message' => 'تم تعديل القسم', 'department' => $department]);
    }

    public function destroy(Department $department)
    {
        $department->delete();
        return response()->json(['message' => 'تم حذف القسم']);
    }
}
