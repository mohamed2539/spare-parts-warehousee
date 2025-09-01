<?php

// app/Http/Controllers/AuthController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Http\Controllers\DepartmentController;
use App\Models\Department;


class AuthController extends Controller
{
    // صفحة تسجيل الدخول
    public function loginForm()
    {
        return view('auth.login');
    }

    // تنفيذ تسجيل الدخول
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);
    
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->intended('imports/create');
        }
    
        return back()->withErrors([
            'username' => 'اسم المستخدم أو كلمة المرور غير صحيحة',
        ]);
    }
    
    public function register(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users',
            'password' => 'required|min:6|confirmed',
            'role' => 'required|in:admin,user',
            'department' => 'required'
        ]);
    
        User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'department' => $request->department
        ]);
    
        return redirect('/login')->with('success', 'تم إنشاء الحساب بنجاح!');
    }

    // تسجيل الخروج
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // صفحة إضافة مستخدم جديد
    // public function registerForm()
    // {
    //     return view('auth.register');
    // }

    public function registerForm()
    {
        $departments = \App\Models\Department::all(); // جيب الأقسام
        return view('auth.register', compact('departments'));
    }

    // // تنفيذ إضافة مستخدم
    // public function register(Request $request)
    // {
    //     $request->validate([
    //         'name' => 'required|min:3',
    //         'email' => 'required|email|unique:users',
    //         'password' => 'required|min:6|confirmed',
    //         'role' => 'required|in:admin,user'
    //     ]);

    //     User::create([
    //         'name' => $request->name,
    //         'email' => $request->email,
    //         'password' => Hash::make($request->password),
    //         'role' => $request->role
    //     ]);

    //     return redirect('/login')->with('success', 'تم إنشاء الحساب بنجاح!');
    // }

    // public function create()
    // {
    //     $departments = Department::all(); // بيجيب كل الأقسام
    //     return view('auth.register', compact('departments'));
    // }



}


?>