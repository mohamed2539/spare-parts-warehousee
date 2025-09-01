@include('layouts.header')
<nav class="bg-white shadow sticky top-0 z-50" x-data="{ mobileOpen: false }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- الشعار -->
            <div class="flex items-center space-x-2">
                <i class="fas fa-warehouse text-blue-600 text-2xl"></i>
                <a href="{{ route('incoming.create') }}" class="text-xl font-bold text-blue-600">مخزنك</a>
            </div>

            <!-- روابط سطح المكتب -->
            <div class="hidden md:flex items-center space-x-6 text-sm">
                <a href="{{ route('incoming.create') }}" class="text-gray-700 hover:text-blue-600 flex items-center gap-1 UlStyle">
                    <i class="fas fa-plus-circle"></i> إضافة وارد
                </a>
                <a href="{{ route('withdrawals.create') }}" class="text-gray-700 hover:text-blue-600 flex items-center gap-1 UlStyle">
                    <i class="fas fa-hand-holding-usd"></i> أذن صرف
                </a>

                <!-- Dropdown -->
                <div 
                    x-data="{
                        open: false,
                        toggle() { this.open ? this.close() : this.open = true },
                        close() { this.open = false }
                    }" 
                    @keydown.escape.window="close()" 
                    @click.outside="close()" 
                    class="relative"
                >
                    <button @click.stop="toggle()" class="flex items-center text-gray-700 hover:text-blue-600 gap-1 focus:outline-none">
                        <i class="fas fa-ellipsis-h"></i> المزيد
                        <svg class="ml-1 w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.06z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div x-show="open" x-cloak x-transition class="absolute right-0 mt-2 w-60 bg-white border rounded-md shadow-lg z-50 py-2 text-sm">
                        <a href="{{ route('imports.create') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">
                            <i class="fas fa-file-import mr-2"></i> استيراد وارد
                        </a>
                        <a href="{{ route('incoming.create') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">
                            <i class="fas fa-boxes mr-2"></i> أضافة وارد يدوي
                        </a>
                        <a href="{{ route('withdrawals.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">
                            <i class="fas fa-list mr-2"></i> عرض عمليات الصرف
                        </a>
                        <div class="border-t my-1"></div>
                        <a href="{{ route('items.searchPage') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">
                            <i class="fas fa-search mr-2"></i> بحث عن صنف
                        </a>
                        <a href="{{ route('items.addForm') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">
                            <i class="fas fa-plus mr-2"></i> إضافة كمية
                        </a>
                        <a href="{{ route('auth.registerForm') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">
                            <i class="fas fa-user-plus mr-2"></i> إضافة مستخدم
                        </a>






                        <a href="{{ route('stock.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">
                            <i class="fas fa-search mr-2"></i> الرصيد الفعلى للمخزن
                        </a>


                        <a href="{{ route('reports.incoming') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">
                            <i class="fas fa-search mr-2"></i> تقرير الوارد
                        </a>
                        <a href="{{ route('purchase-requests.index') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">
                            <i class="fas fa-plus mr-2"></i> عمل طلب شراء
                        </a>
                        <a href="{{ route('reports.withdrawals') }}" class="block px-4 py-2 text-gray-700 hover:bg-blue-50">
                            <i class="fas fa-user-plus mr-2"></i> تقرير الصرف
                        </a>






                        <div class="border-t my-1"></div>
                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                           class="block px-4 py-2 text-red-600 hover:bg-red-50">
                            <i class="fas fa-sign-out-alt mr-2"></i> تسجيل الخروج
                        </a>
                        <form action="{{ route('logout') }}" method="POST" id="logout-form" class="hidden">@csrf</form>
                    </div>
                </div>
            </div>

            <!-- زر الموبايل -->
            <div class="md:hidden flex items-center">
                <button @click="mobileOpen = !mobileOpen" class="text-gray-700 focus:outline-none">
                    <i class="fas fa-bars text-xl"></i>
                </button>
            </div>
        </div>
    </div>

    <!-- قائمة الموبايل -->
    <div class="md:hidden" x-show="mobileOpen" x-transition class="px-4 pb-4 space-y-2 text-sm">
        <a href="{{ route('incoming.create') }}" class="block text-gray-700 hover:text-blue-600"><i class="fas fa-plus-circle mr-1"></i> إضافة وارد</a>
        <a href="{{ route('withdrawals.create') }}" class="block text-gray-700 hover:text-blue-600"><i class="fas fa-hand-holding-usd mr-1"></i> صرف يدوي</a>
        <a href="{{ route('imports.create') }}" class="block text-gray-700 hover:text-blue-600"><i class="fas fa-file-import mr-1"></i> استيراد وارد</a>
        <a href="{{ route('incoming.create') }}" class="block text-gray-700 hover:text-blue-600"><i class="fas fa-boxes mr-1"></i> الوارد</a>
        <a href="{{ route('withdrawals.index') }}" class="block text-gray-700 hover:text-blue-600"><i class="fas fa-list mr-1"></i> عرض الصرف</a>
        <a href="{{ route('items.searchPage') }}" class="block text-gray-700 hover:text-blue-600"><i class="fas fa-search mr-1"></i> بحث عن صنف</a>
        <a href="{{ route('items.addForm') }}" class="block text-gray-700 hover:text-blue-600"><i class="fas fa-plus mr-1"></i> إضافة كمية</a>
        <a href="{{ route('auth.registerForm') }}" class="block text-gray-700 hover:text-blue-600"><i class="fas fa-user-plus mr-1"></i> إضافة مستخدم</a>
        <a href="{{ route('deletionLogs.index') }}" class="block text-gray-700 hover:text-blue-600"><i class="fas fa-trash mr-1"></i> سجل الحذف</a>
        <a href="{{ route('purchase-requests.index') }}" class="block px-4 py-2 hover:bg-blue-50">🛒 طلبات شراء</a>
        <a href="{{ route('stock.index') }}" class="block px-4 py-2 hover:bg-blue-50">📦 الرصيد الحالي</a>
        <a href="{{ route('reports.incoming') }}" class="block px-4 py-2 hover:bg-blue-50">📥 تقرير الوارد</a>
        <a href="{{ route('reports.withdrawals') }}" class="block px-4 py-2 hover:bg-blue-50">📤 تقرير الصرف</a>
        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="block text-red-600 hover:text-red-800"><i class="fas fa-sign-out-alt mr-1"></i> تسجيل الخروج</a>
    </div>
</nav>
