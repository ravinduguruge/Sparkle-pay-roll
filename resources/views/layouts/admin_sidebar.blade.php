<!-- Side Panel -->
<div class="flex flex-col w-64 h-full text-white bg-primary">
    <div class="p-4 border-b border-accent">
        <h1 class="text-2xl font-bold text-accent">Sparkle Electrical</h1>
        <p class="text-sm text-secondary">Admin Dashboard</p>
    </div>

    <nav class="flex-1 py-4 overflow-y-auto">
        <ul>
            <li>
                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center gap-3 px-4 py-3 transition hover:bg-accent/20 {{ request()->is('admin/dashboard*') ? 'bg-accent/20 rounded' : '' }}">
                    <i class="w-5 h-5 fas fa-tachometer-alt {{ request()->is('admin/dashboard*') ? 'text-white' : 'text-accent' }}"></i>
                    <span>Dashboard</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.employees.index') }}"
                    class="flex items-center gap-3 px-4 py-3 transition hover:bg-accent/20 {{ request()->is('admin/employees*') ? 'bg-accent/20 rounded' : '' }}">
                    <i class="w-5 h-5 fas fa-users {{ request()->is('admin/employees*') ? 'text-white' : 'text-accent' }}"></i>
                    <span>Employees</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.projects.index') }}"
                    class="flex items-center gap-3 px-4 py-3 transition hover:bg-accent/20 {{ request()->is('admin/projects*') ? 'bg-accent/20 rounded' : '' }}">
                    <i class="w-5 h-5 fas fa-project-diagram {{ request()->is('admin/projects*') ? 'text-white' : 'text-accent' }}"></i>
                    <span>Projects</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.work_approvals.index') }}"
                    class="flex items-center gap-3 px-4 py-3 transition hover:bg-accent/20 {{ request()->is('admin/work-approvals*') ? 'bg-accent/20 rounded' : '' }}">
                    <i class="w-5 h-5 fas fa-clipboard-check {{ request()->is('admin/work-approvals*') ? 'text-white' : 'text-accent' }}"></i>
                    <span>Day to Day Work</span>
                </a>
            </li>

            <li>
                <a href="{{ route('admin.salary.index') }}"
                    class="flex items-center gap-3 px-4 py-3 transition hover:bg-accent/20 {{ request()->is('admin/salary*') ? 'bg-accent/20 rounded' : '' }}">
                    <i class="w-5 h-5 fas fa-money-bill-wave {{ request()->is('admin/salary*') ? 'text-white' : 'text-accent' }}"></i>
                    <span>Salary Management</span>
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
<!-- End Side Panel -->