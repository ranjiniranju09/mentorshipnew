<style>
     body {
            font-family: "Ubuntu", sans-serif;
            background-color: #f4f7f6;
        }
    #sidebar {
        position: fixed;
        width: 270px;
        height: 100%;
        background: var(--black1);
        transition: 0.5s;
        overflow: hidden;
    }
    .c-sidebar ul {
    padding: 0;
    margin: 0;
    list-style: none;
    }

    .c-sidebar ul li {
        position: relative;
    }

    .c-sidebar ul li a {
        display: flex;
        align-items: center;
        padding: 10px 20px;
        text-decoration: none;
        color: var(--white);
        transition: background 0.3s, color 0.3s;
    }

    .c-sidebar ul li a:hover {
        color: var(--black1);
        background: white;
    }

    .c-sidebar ul li a .c-sidebar-nav-icon {
        margin-right: 10px;
        font-size: 18px;
    }

    .c-sidebar-nav-dropdown .c-sidebar-nav-dropdown-items {
        margin-left: 20px;
        padding-left: 10px;
        border-left: 1px solid var(--gray);
    }

    .c-sidebar-nav-dropdown-items .c-sidebar-nav-item {
        padding-left: 10px;
    }

    .c-sidebar-nav-dropdown-toggle::after {
        content: '\f107'; /* Font Awesome down arrow */
        font-family: 'Font Awesome 5 Free';
        font-weight: 900;
        margin-left: auto;
        transition: transform 0.3s;
    }

    .c-sidebar-nav-dropdown.c-show .c-sidebar-nav-dropdown-toggle::after {
        transform: rotate(180deg);
    }

</style>


<div id="sidebar" class="c-sidebar c-sidebar-fixed c-sidebar-lg-show">
    <div class="c-sidebar-brand d-md-down-none">
        <a class="c-sidebar-brand-full h4" href="#">
            Mentor Dashboard
        </a>
    </div>
    <ul class="c-sidebar-nav">
        <li class="c-sidebar-nav-item">
            <a href="{{ route('mentor.dashboard') }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt"></i>
                {{ trans('global.dashboard') }}
            </a>
        </li>
        <li class="c-sidebar-nav-dropdown {{ request()->is('admin/sessions*') ? 'c-show' : '' }}">
            <a class="c-sidebar-nav-dropdown-toggle" href="#">
                <i class="fa-fw fas fa-cogs c-sidebar-nav-icon"></i>
                {{ trans('cruds.oneOneSession.title') }}
            </a>
            <ul class="c-sidebar-nav-dropdown-items">
                <li class="c-sidebar-nav-item">
                    <a href="{{ route('sessions.index') }}" class="c-sidebar-nav-link {{ request()->is('admin/one-one-attendances') ? 'c-active' : '' }}">
                        <i class="fa-fw fas fa-paperclip c-sidebar-nav-icon"></i>
                        Session Details
                    </a>
                </li>
                @can('assign_task_access')
                <li class="c-sidebar-nav-item">
                    <a href="{{ route('admin.assign-tasks.index') }}" class="c-sidebar-nav-link {{ request()->is('admin/assign-tasks') ? 'c-active' : '' }}">
                        <i class="fa-fw fas fa-tasks c-sidebar-nav-icon"></i>
                        {{ trans('cruds.assignTask.title') }}
                    </a>
                </li>
                @endcan
            </ul>
        </li>
        <li class="c-sidebar-nav-item">
            <a href="{{ route('menteemoduleprogress') }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt"></i>
                Modules Completion
            </a>
        </li>
        {{--<li class="c-sidebar-nav-item">

            <a href="{{ route('mentor.markChapterCompletion') }}" class="c-sidebar-nav-link">
                <i class="c-sidebar-nav-icon fas fa-fw fa-tachometer-alt"></i>
                Mark Module Completion
            </a>
        </li>--}}
        <li class="c-sidebar-nav-item">
            <a href="#" class="c-sidebar-nav-link">
                <i class="fa-solid fa-certificate c-sidebar-nav-icon" aria-hidden="true"></i>
                Generate Certificate
            </a>
        </li>
        <li class="c-sidebar-nav-item">
            <a href="#" class="c-sidebar-nav-link">
                <i class="fa fa-ticket c-sidebar-nav-icon"></i>
                Support
            </a>
        </li>
        @if(file_exists(app_path('Http/Controllers/Auth/ChangePasswordController.php')))
        <li class="c-sidebar-nav-item">
            <a class="c-sidebar-nav-link {{ request()->is('profile/password') ? 'c-active' : '' }}" href="{{ route('profile.password.edit') }}">
                <i class="fa-fw fas fa-key c-sidebar-nav-icon"></i>
                {{ trans('global.change_password') }}
            </a>
        </li>
        @endif
        <li class="c-sidebar-nav-item">
            <a href="#" class="c-sidebar-nav-link" onclick="event.preventDefault(); document.getElementById('logoutform').submit();">
                <i class="c-sidebar-nav-icon fas fa-fw fa-sign-out-alt"></i>
                {{ trans('global.logout') }}
            </a>
        </li>
    </ul>
</div>

