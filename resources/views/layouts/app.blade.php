<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Sparkle Payroll')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    {{-- Compiled Tailwind CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body class="bg-slate-100 min-h-screen flex flex-col">
    <header class="bg-white shadow">
        <div class="max-w-7xl mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-full bg-indigo-600 flex items-center justify-center text-white font-bold">
                    S
                </div>
                <div>
                    <div class="font-semibold text-slate-800">Sparkle Electrical (Pvt) Ltd</div>
                    <div class="text-xs text-slate-500">Payroll & Work Tracking</div>
                </div>
            </div>

            @auth
                <div class="flex items-center gap-4">
                    <span class="text-sm text-slate-600">{{ auth()->user()->name }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button class="text-xs font-medium px-3 py-1 rounded-full border border-slate-300 hover:bg-slate-100">
                            Logout
                        </button>
                    </form>
                </div>
            @endauth
        </div>
    </header>

    <div class="flex-1 flex">
        @auth
            <aside class="w-64 bg-slate-900 text-slate-100 hidden md:block">
                <div class="p-4 text-xs font-semibold uppercase text-slate-400">
                    Menu
                </div>
                <nav class="px-2 space-y-1 text-sm">
                    @if(auth()->user()->role === 'employee')
                        <a href="{{ route('employee.dashboard') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-800">
                            Dashboard
                        </a>
                        <a href="{{ route('employee.salary.index') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-800">
                            Salary
                        </a>
                    @else
                        <a href="{{ route('admin.dashboard') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-800">
                            Dashboard
                        </a>
                        <a href="{{ route('admin.employees.index') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-800">
                            Employees
                        </a>
                        <a href="{{ route('admin.projects.index') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-800">
                            Projects
                        </a>
                        <a href="{{ route('admin.salary.index') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-800">
                            Salary Management
                        </a>
                        <a href="{{ route('admin.work_approvals.index') }}" class="block px-3 py-2 rounded-lg hover:bg-slate-800">
                            Day to Day Work
                        </a>
                    @endif
                </nav>
            </aside>
        @endauth

        <main class="flex-1 max-w-7xl mx-auto w-full px-4 py-6">
            @if(session('success'))
                <div class="mb-4 rounded-lg border border-emerald-300 bg-emerald-50 text-emerald-800 px-4 py-3 text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if($errors->any())
                <div class="mb-4 rounded-lg border border-rose-300 bg-rose-50 text-rose-800 px-4 py-3 text-sm">
                    <ul class="list-disc pl-4">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </main>
    </div>
</body>
</html>
