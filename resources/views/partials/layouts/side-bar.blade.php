@php
   

if (!Cache::has('permission')) {
    $permissionsCollection = DB::table('module_permissions')
        ->where('Role_ID', $currentUser->Role_ID)
        ->get();

    // Check if the collection has at least one item
    if ($permissionsCollection->isNotEmpty()) {
        // Access the first item in the collection (assuming it's a single result)
        $permission = $permissionsCollection->first();
    } else {
        // Set a default permission if there are no results
        $permission = (object)[
            'Research_list' => 0,
            // Add other properties as needed
        ];
    }
    
    // Cache the permission
    Cache::forever('permission', $permission);
} else {
    // The 'permission' key is present in the cache, retrieve it
    $permission = Cache::get('permission');
}

   if (!Cache::has('other_permission')) {
    $permissionsCollection = DB::table('other_permissions')
        ->where('Role_ID', $currentUser->Role_ID)
        ->first();

    // Check if the other permission exists in the database
    if ($permissionsCollection !== null) {
        // Cache the other permission object
        Cache::forever('other_permission', $permissionsCollection);
        $other_permission = $permissionsCollection;
    } else {
        // If no other permissions found, set a default other permission object
        $other_permission = (object)[
            'Research_list' => 0,
            // Add other properties as needed
        ];
    }
} else {
    // The 'other_permission' key is present in the cache, retrieve it
    $other_permission = Cache::get('other_permission');
    }

@endphp

<!--aside open-->
<aside class="app-sidebar">
    <div class="app-sidebar__logo">
        <a class="header-brand" href="{{ route('Main.Dashboard') }}">
            <img src="{{ asset('assets/images/brand/logo.png') }}" class="header-brand-img desktop-lgo"
                alt="One Click Logo">
            <img src="{{ asset('assets/images/brand/logo-white.png') }}" class="header-brand-img dark-logo"
                alt="One Click Logo" style="height: 45px;">
            <img src="{{ asset('assets/images/brand/favicon.png') }}" class="header-brand-img mobile-logo"
                alt="One Click Logo">
            <img src="{{ asset('assets/images/brand/favicon1.png') }}" class="header-brand-img darkmobile-logo"
                alt="One Click Logo">
        </a>
    </div>
    <div class="app-sidebar3">
        <div class="app-sidebar__user">
            <div class="dropdown user-pro-body text-center">
                <div class="user-pic">
                    <img src="{{ !empty($currentUser->basic_info->profile_photo_path) ? asset($currentUser->basic_info->profile_photo_path) : asset('assets/images/users/16.jpg') }}"
                        alt="user-img" class="avatar-xxl rounded-circle mb-1">
                </div>
                <div class="user-info">
                    <h5 class=" mb-2">{{ $currentUser->basic_info->full_name }}</h5>
                    <span
                        class="text-muted app-sidebar__user-name text-sm">{{ $currentUser->designation->Designation_Name }}</span>
                </div>
            </div>
        </div>
        <ul class="side-menu mb-lg-5">
            <li class="side-item side-item-category mt-4">Dashboards</li>

            <li class="slide">
                <a class="side-menu__item" data-bs-toggle="slide" href="{{ route('Main.Dashboard') }}">
                    <i class="feather feather-home sidemenu_icon"></i>
                    <span class="side-menu__label">Dashboard</span>
                </a>
            </li>
            <li class="slide"><a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"> <i
                        class="feather feather-chrome sidemenu_icon"></i>
                    <span class="side-menu__label">Manage Orders</span><i class="angle fa fa-angle-right"></i></a>
                <ul class="slide-menu ">
                    <li class="side-menu-label1"><a href="javascript:void(0);">Manage Orders</a></li>

                    @if ((int) $permission->Research_view === 1)
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span
                                    class="sub-side-menu__label">Research
                                    Orders</span><i class="sub-angle fa fa-angle-right"></i></a>
                            <ul class="sub-slide-menu">
                                @if ((int) $permission->Research_create === 1)
                                    <li><a class="sub-slide-item" href="{{ route('Research.Create.Order') }}">Create
                                            Order</a></li>
                                @endif
                                @if ((int) $permission->Research_list === 1)
                                    <li><a class="sub-slide-item" href="{{ route('Research.Orders') }}">All
                                            Orders</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    @endif
                    @if ((int) $permission->Content_view === 1)
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span
                                    class="sub-side-menu__label">Content
                                    Orders</span><i class="sub-angle fa fa-angle-right"></i></a>
                            <ul class="sub-slide-menu">
                                @if ((int) $permission->Content_create === 1)
                                    <li><a class="sub-slide-item" href="{{ route('Content.Create.Order') }}">Create
                                            Order</a>
                                    </li>
                                @endif
                                @if ((int) $permission->Content_list === 1)
                                    <li><a class="sub-slide-item" href="{{ route('Content.Orders') }}">All
                                            Orders</a>
                                    </li>
                                @endif

                            </ul>
                        </li>
                    @endif
                    @if ((int) $permission->Website_view === 1)
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" data-bs-toggle="sub-slide" href="javascript:void(0);"><span
                                    class="sub-side-menu__label">Website Order</span><i
                                    class="sub-angle fa fa-angle-right"></i></a>
                            <ul class="sub-slide-menu">
                                <li><a class="sub-slide-item" href="#">Finance Buddy Uk</a></li>
                                <li><a class="sub-slide-item" href="#">Static Buddies</a></li>
                                <li><a class="sub-slide-item" href="#">Essay Buddies</a></li>
                                <li><a class="sub-slide-item" href="#">360 Essay help</a></li>
                            </ul>
                        </li>
                    @endif
                    @if ((int) $permission->role_id === 1 ||
                            (int) $permission->role_id === 9 ||
                            (int) $permission->role_id === 10)
                        <li><a href="{{ route('Clients.List') }}" class="slide-item">Client List</a>
                        </li>
                    @endif
                    @if (
                        (int) $permission->Research_view === 1 &&
                            (int) $permission->Research_list === 1)
                        <li><a href="{{ route('Research.Completed.Orders') }}" class="slide-item">Research Completed
                                Orders</a>
                        </li>
                    @endif
                    @if (
                        (int) $permission->Content_view === 1 &&
                            (int) $permission->Content_list === 1)
                        <li><a href="{{ route('Content.Completed.Orders') }}" class="slide-item">Content Completed
                                Orders</a>
                        </li>
                    @endif
                </ul>
            </li>

            @if ((int) $permission->role_id === 1 || (int) $permission->role_id === 2)
                <li class="slide"><a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"> <i
                            class="feather  feather-clipboard sidemenu_icon"></i>
                        <span class="side-menu__label">Permissions</span><i class="angle fa fa-angle-right"></i></a>
                    <ul class="slide-menu">
                        <li><a href="{{ route('Add.Portal.Permissions') }}" class="slide-item">Add
                                Permission</a>
                        </li>
                    </ul>
                </li>
            @endif

            @if ((int) $permission->role_id === 1)
                <li class="slide"><a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"> <i
                            class="feather  feather-clipboard sidemenu_icon"></i>
                        <span class="side-menu__label">Sample</span><i class="angle fa fa-angle-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0);">Finance Buddy Uk</a></li>
                        <li><a href="javascript:void(0);" class="slide-item">Sample List</a></li>
                    </ul>
                </li>
            @endif
            @if ((int) $other_permission->Employee_view === 1)
                <li class="slide"><a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"> <i
                            class="feather feather-users sidemenu_icon"></i>
                        <span class="side-menu__label">Employees</span><i class="angle fa fa-angle-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0);">Employees</a></li>
                        @if ((int) $other_permission->Employee_list === 1)
                            <li><a href="{{ route('All.Employee') }}" class="slide-item">View All</a></li>
                        @endif
                        @if ((int) $other_permission->Employee_create === 1)
                            <li><a href="{{ route('New.Employee') }}" class="slide-item">Add New</a></li>
                        @endif
                        @if ((int) $other_permission->role_id === 1 || (int) $other_permission->role_id === 12)
                            <li>
                                <a href="{{ route('View.Employee', ['EMP_ID' => $currentUser->EMP_ID]) }}"
                                    class="slide-item">View Profile</a>
                            </li>
                        @endif

                    </ul>
                </li>
            @endif
            @if (
                (int)  $other_permission->Sales_Performance_view === 1 ||
                    (int) $other_permission->Writer_Performance_view === 1)
                <li class="slide"><a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"> <i
                            class="feather feather-trending-up sidemenu_icon"></i>
                        <span class="side-menu__label">Performance</span><i class="angle fa fa-angle-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0);">Performance</a></li>
                        @if ((int) $other_permission->Sales_Performance_view === 1)
                            <li><a href="javascript:void(0);" class="slide-item">Sales Performance</a></li>
                        @endif
                        @if ((int) $other_permission->Writer_Performance_view    === 1)
                            <li><a href="{{ route('Research.Users.Performance') }}" class="slide-item">Writer
                                    Performance</a></li>
                        @endif
                    </ul>
                </li>
            @endif
            @if ((int) $currentUser->Role_ID === 1 || (int) $currentUser->Role_ID === 2 || (int) $currentUser->Role_ID === 15)
                <li class="slide"><a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"> <i
                            class="feather feather-trending-up sidemenu_icon"></i>
                        <span class="side-menu__label">HR Department</span><i class="angle fa fa-angle-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0);">HR Department</a></li>
                        <li><a href="{{ route('Assigned.Targets') }}" class="slide-item">Assigned Targets</a></li>
                        <li><a href="{{ route('Get.Notices') }}" class="slide-item">Manage Notice Board</a></li>
                        <li><a href="javascript:void(0);" class="slide-item">Manage Events</a></li>
                    </ul>
                </li>
            @endif

            @if (
                (int) $other_permission->role_id !== 1 &&
                    (int) $other_permission->role_id !== 2 &&
                    (int) $other_permission->role_id !== 15)
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide"
                        href="{{ route('User.Performance', ['EMP_ID' => Auth::guard('Authorized')->user()->EMP_ID, 'Role_ID' => Auth::guard('Authorized')->user()->Role_ID]) }}">
                        <i class="feather feather-book sidemenu_icon"></i>
                        <span class="side-menu__label">My Performance </span>
                    </a>

                </li>
            @endif
            @if (
                (int) $other_permission->department_view === 1 ||
                    (int) $other_permission->designation_view === 1 ||
                    (int) $other_permission->Services_view === 1 ||
                    (int) $other_permission->Website_view === 1)

                <li class="slide"><a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"> <i
                            class="feather feather-home sidemenu_icon"></i>
                        <span class="side-menu__label">Auth & Pre-Villages</span><i
                            class="angle fa fa-angle-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0);">Pre-Villages</a></li>
                        @if ((int) $other_permission->department_view === 1)
                            <li><a href="{{ route('All.Departments') }}" class="slide-item">Departments</a></li>
                        @endif
                        @if ((int) $other_permission->designation_view === 1)
                            <li><a href="{{ route('All.Designations') }}" class="slide-item">Users
                                    Designations</a>
                            </li>
                        @endif
                        <li class="sub-slide">
                            <a class="sub-side-menu__item" data-bs-toggle="sub-slide"
                                href="javascript:void(0);"><span class="sub-side-menu__label">Order Fields</span><i
                                    class="sub-angle fa fa-angle-right"></i></a>
                            <ul class="sub-slide-menu">
                                @if ((int) $other_permission->Services_view === 1)
                                    <li><a class="sub-slide-item" href="{{ route('All.Services') }}">Order
                                            Services</a>
                                    </li>
                                @endif
                                @if ((int) $other_permission->Website_view === 1)
                                    <li><a class="sub-slide-item" href="{{ route('All.Websites') }}">Order
                                            Websites</a>
                                    </li>
                                @endif
                                @if ((int) $other_permission->Services_view === 1)
                                    <li><a class="sub-slide-item" href="{{ route('All.Styles') }}">Writing Styles</a>
                                    </li>
                                @endif
                                @if ((int) $other_permission->Services_view === 1)
                                    <li><a class="sub-slide-item" href="{{ route('All.Voices') }}">Preferred
                                            Voices</a>
                                    </li>
                                @endif
                                @if ((int) $other_permission->Services_view === 1)
                                    <li><a class="sub-slide-item" href="{{ route('All.Industries') }}">Order
                                            Industries</a>
                                    </li>
                                @endif
                                @if ((int) $other_permission->Services_view === 1)
                                    <li><a class="sub-slide-item" href="{{ route('All.Generics') }}">Order
                                            Generics</a>
                                    </li>
                                @endif
                            </ul>
                        </li>
                    </ul>
                </li>
            @endif

            <li class="slide">
                <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"> <i
                        class="feather feather-trending-up sidemenu_icon"></i>
                    <span class="side-menu__label">Manage Attendance</span><i class="angle fa fa-angle-right"></i></a>
                <ul class="slide-menu">
                    <li class="side-menu-label1"><a href="javascript:void(0);">Manage Attendance</a></li>
                    @if ((int) $currentUser->Role_ID === 1 || (int) $currentUser->Role_ID === 2 || (int) $currentUser->Role_ID === 15)
                        <li><a href="{{ route('Mark.Users.Attendance') }}" class="slide-item">Mark User
                                Attendance</a></li>
                        <li><a href="{{ route('Users.Attendance') }}" class="slide-item">Overall Attendance</a></li>
                    @endif
                    <li>
                        <a href="{{ route('My.Attendance', ['User_ID' => $currentUser->id]) }}" class="slide-item">My
                            Attendance</a>
                    </li>
                </ul>
            </li>
            @if ((int) $currentUser->Role_ID === 1 || (int) $currentUser->Role_ID === 2 || (int) $currentUser->Role_ID === 15)
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"> <i
                            class="feather feather-user-plus sidemenu_icon"></i>
                        <span class="side-menu__label">Leave Entitlement</span><i
                            class="angle fa fa-angle-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0);">Leave Entitlement</a></li>
                        <li><a href="{{ route('Received.Request') }}" class="slide-item">Recent Leave Request</a>
                        </li>
                        <li><a href="{{ route('User.Leave.Quota') }}" class="slide-item">Employee Leaves Info</a>
                        </li>
                        <li><a href="{{ route('Leave.Request') }}" class="slide-item">Leave Request</a></li>
                        <li><a href="{{ route('Leave.Setting') }}" class="slide-item">Leaves Settings</a></li>
                    </ul>
                </li>
            @endif
            <li class="slide">
                <a class="side-menu__item" data-bs-toggle="slide" href="{{ route('Login.History') }}">
                    <i class="feather feather-alert-octagon sidemenu_icon"></i>
                    <span class="side-menu__label">Login History</span>
                </a>
            </li>

            @if ((int) $other_permission->report_view === 1)
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="#">
                        <i class="feather feather-home sidemenu_icon"></i>
                        <span class="side-menu__label">Report</span>
                    </a>

                </li>
            @endif
            @if ((int) $other_permission->Company_policy_view === 1)
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" target="_blank"
                        href="{{ asset('HR-Policy-Manual-2022.pdf') }}">
                        <i class="feather feather-alert-octagon sidemenu_icon"></i>
                        <span class="side-menu__label">Company Policy</span>
                    </a>

                </li>
            @endif
            @if ((int) $other_permission->Search_order_view === 1)
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="{{ route('Searching.Queries') }}">
                        <i class="feather feather-search sidemenu_icon"></i>
                        <span class="side-menu__label">Search Order</span>
                    </a>

                </li>
            @endif
            @if ((int) $currentUser->Role_ID === 1 || (int) $currentUser->Role_ID === 9)
                <li class="slide">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"> <i
                            class="feather feather-trash sidemenu_icon"></i>
                        <span class="side-menu__label">Trashed</span><i class="angle fa fa-angle-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0);">Trashed</a></li>
                        <li>
                            <a href="{{ route('Trash.Research.Orders') }}" class="slide-item">Research
                                Orders</a>
                        </li>
                        <li>
                            <a href="{{ route('Trash.Content.Orders') }}" class="slide-item">Content Orders</a>
                        </li>
                        <li>
                            <a href="{{ route('Trash.Deleted.Orders') }}" class="slide-item">Deleted Users</a>
                        </li>
                    </ul>
                </li>
            @endif

            @if ((int) $currentUser->Role_ID === 1 || (int) $currentUser->Role_ID === 9)
                <li class="slide mb-5">
                    <a class="side-menu__item" data-bs-toggle="slide" href="javascript:void(0);"> <i
                            class="feather feather-code sidemenu_icon"></i>
                        <span class="side-menu__label">Developer</span><i class="angle fa fa-angle-right"></i></a>
                    <ul class="slide-menu">
                        <li class="side-menu-label1"><a href="javascript:void(0);">Developer</a></li>
                        <li>
                            <a href="{{ route('Portal.Errors') }}" class="slide-item">Error List</a>
                        </li>
                        <li>
                            <a href="{{ route('Clear.Cache') }}" class="slide-item">Clear Cache</a>
                        </li>
                        <li>
                            <a href="{{ route('Clear.Error.Logs') }}" class="slide-item mb-5">Clear Error Logs</a>
                        </li>
                    </ul>
                </li>
            @endif

        </ul>

    </div>
</aside>
<!--aside closed-->
