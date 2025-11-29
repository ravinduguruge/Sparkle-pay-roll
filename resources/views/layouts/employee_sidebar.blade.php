<!-- Side Panel -->
<div class="flex flex-col w-64 h-full text-white bg-primary">
    <div class="p-4 border-b border-accent">
        <h1 class="text-2xl font-bold text-accent">Sparkle Electrical</h1>
        <p class="text-sm text-secondary">Employee Dashboard</p>
    </div>

    <nav class="flex-1 py-4 overflow-y-auto">
        <ul>
            <li>
                <a href="{{ route('employee.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 transition hover:bg-accent/20 {{ request()->is('employee/dashboard') ? 'bg-accent/20 rounded' : '' }}">
                    <i class="w-5 h-5 fas fa-tachometer-alt {{ request()->is('employee/dashboard') ? 'text-white' : 'text-accent' }}"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ route('employee.daily_work') }}"
                    class="flex items-center gap-3 px-4 py-3 transition hover:bg-accent/20 {{ request()->is('employee/daily-work*') ? 'bg-accent/20 rounded' : '' }}">
                    <i class="w-5 h-5 fas fa-clipboard-list {{ request()->is('employee/daily-work*') ? 'text-white' : 'text-accent' }}"></i>
                    <span>Daily Work</span>
                </a>
            </li>

            <li>
                <a href="{{ route('employee.salary.index') }}"
                    class="flex items-center gap-3 px-4 py-3 transition hover:bg-accent/20 {{ request()->is('employee/salary*') ? 'bg-accent/20 rounded' : '' }}">
                    <i class="w-5 h-5 fas fa-money-bill-wave {{ request()->is('employee/salary*') ? 'text-white' : 'text-accent' }}"></i>
                    <span>My Salary</span>
                </a>
            </li>
        </ul>
    </nav>

    <div class="px-4 py-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit"
                class="flex items-center w-full px-4 py-2 text-left transition rounded hover:bg-accent/20">
                <i class="mr-3 fas fa-sign-out-alt text-accent"></i>
                Logout
            </button>
        </form>
    </div>

    <div class="p-4 border-t border-accent">
        <div class="flex items-center">
            <div class="flex items-center justify-center w-10 h-10 font-bold rounded-full bg-accent text-primary">
                <i class="fas fa-user"></i>
            </div>
            <div class="ml-3">
                <p class="font-medium">{{ Auth::user()->name ?? 'No Name' }}</p>
                <p class="text-xs text-secondary">{{ Auth::user()->role ?? 'Role not set' }}</p>
            </div>
        </div>
    </div>
</div>
