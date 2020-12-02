
<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar shadow"  >
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar  ">
        <!-- Sidebar user panel -->
        <div class="user-panel" style="background: url({{ url('/dist/img/patterns/user-panel-bg_green.jpg') }});background-size: cover;bakcground-repeat: no-repeat!important;height: 150px;padding-top: 50px;" >
            <div class="pull-left image">
                @if (Auth::user()->photo)
                <img src="{{ url('/') }}/image/users/{{ Auth::user()->photo }}" class="img-circle" alt="User Image">
                @else
                <img src="{{ url('/') }}/image/user.png" class="img-circle" alt="User Image">
                @endif
            </div>
            <div class="pull-left info w3-padding">
                <a href="#"  onclick="showPage('dashboard/profile')" class="w3-text-white w3-large"  ><b>{{ Auth::user()->name }}</b></a>
            </div>
        </div>

        <ul class="sidebar-menu font" data-widget="tree">
            <li class="header text-uppercase">{{ __('main navigation') }}</li>

            <li class="treeview font w3-text-pink" onclick="showPage('dashboard/profile')" >
                <a href="#">
                    <i class="fa fa-user"></i> <span>{{ __('profile') }}</span>
                </a>
            </li>

            @if (Auth::user()->type == 'admin')
            @if (Auth::user()->_can('level'))
            <li class="treeview font w3-text-pink" onclick="showPage('dashboard/level')" >
                <a href="#">
                    <i class="fa fa-level-up"></i> <span>{{ __('levels') }}</span>
                </a>
            </li>
            @endif
            @if (Auth::user()->_can('department'))
            <li class="treeview font w3-text-pink" onclick="showPage('dashboard/department')" >
                <a href="#">
                    <i class="fa fa-bank"></i> <span>{{ __('departments') }}</span>
                </a>
            </li>
            @endif
            @if (Auth::user()->_can('controlmanager'))
            <li class="treeview font w3-text-pink" onclick="showPage('dashboard/controlmanager')" >
                <a href="#">
                    <i class="fa fa-user-secret"></i> <span>{{ __('controlmanager') }}</span>
                </a>
            </li>
            @endif
            @if (Auth::user()->_can('doctor'))
            <li class="treeview font w3-text-pink" onclick="showPage('dashboard/doctor')" >
                <a href="#">
                    <i class="fa fa-user"></i> <span>{{ __('doctors') }}</span>
                </a>
            </li>
            @endif
            @if (Auth::user()->_can('student'))
            <li class="treeview font w3-text-pink" onclick="showPage('dashboard/student')" >
                <a href="#">
                    <i class="fa fa-user"></i> <span>{{ __('students') }}</span>
                </a>
            </li>
            @endif
            @if (Auth::user()->_can('admin'))
            <li class="treeview font w3-text-pink" onclick="showPage('dashboard/admin')" >
                <a href="#">
                    <i class="fa fa-user"></i> <span>{{ __('admins') }}</span>
                </a>
            </li>
            @endif
            @endif

            @if (Auth::user()->type == 'admin')
            @if (Auth::user()->_can('student_complaint'))
            <li class="treeview font w3-text-pink" onclick="showPage('dashboard/student-problem')" >
                <a href="#">
                    <i class="fa fa-frown-o"></i> <span>{{ __('student complaints') }}</span>
                </a>
            </li>
            @endif
            @if (Auth::user()->_can('doctor_complaint'))
            <li class="treeview font w3-text-pink" onclick="showPage('dashboard/doctor-problem')" >
                <a href="#">
                    <i class="fa fa-frown-o"></i> <span>{{ __('doctor complaints') }}</span>
                </a>
            </li>
            @endif
            @endif

            @if (Auth::user()->type == 'doctor')
            <li class="treeview font w3-text-pink" onclick="showPage('dashboard/course')" >
                <a href="#">
                    <i class="fa fa-graduation-cap"></i> <span>{{ __('courses') }}</span>
                </a>
            </li>
            @endif

            @if (Auth::user()->type == 'student')
            <li class="treeview font w3-text-pink" onclick="showPage('dashboard/course')" >
                <a href="#">
                    <i class="fa fa-graduation-cap"></i> <span>{{ __('courses') }}</span>
                </a>
            </li>
            @endif

            @if (Auth::user()->_can('course'))
            <li class="treeview font w3-text-pink" onclick="showPage('dashboard/course')" >
                <a href="#">
                    <i class="fa fa-graduation-cap"></i> <span>{{ __('courses') }}</span>
                </a>
            </li>
            @endif

            @if (Auth::user()->type == 'admin')
            @if (Auth::user()->_can('student_course'))
            <li class="treeview font w3-text-pink" onclick="showPage('dashboard/studentcourse')" >
                <a href="#">
                    <i class="fa fa-th-list"></i> <span>{{ __('student assign courses') }}</span>
                </a>
            </li>
            @endif
            @endif

            @if (Auth::user()->type == 'admin')
            @if (Auth::user()->_can('research'))
            <li class="treeview font w3-text-pink" onclick="showPage('dashboard/research')" >
                <a href="#">
                    <i class="fa fa-newspaper-o"></i> <span>{{ __('researches') }}</span>
                </a>
            </li>
            @endif

            @if (Auth::user()->_can('student_research'))
            <li class="treeview font w3-text-pink" onclick="showPage('dashboard/studentresearch')" >
                <a href="#">
                    <i class="fa fa-newspaper-o"></i> <span>{{ __('student researches') }}</span>
                </a>
            </li>
            <li class="treeview font w3-text-pink" onclick="showPage('dashboard/studentresearch2')" >
                <a href="#">
                    <i class="fa fa-newspaper-o"></i> <span>{{ __('new student researches') }}</span>
                </a>
            </li>
            @endif

            @if (Auth::user()->_can('studentgrade'))
            <li class="treeview font w3-text-pink" onclick="showPage('dashboard/studentgrade')" >
                <a href="#">
                    <i class="fa fa-newspaper-o"></i> <span>{{ __('studentgrade') }}</span>
                </a>
            </li>
            @endif

            @endif

            @if (Auth::user()->type == 'doctor')
            <li class="treeview font w3-text-pink" onclick="showPage('dashboard/research')" >
                <a href="#">
                    <i class="fa fa-newspaper-o"></i> <span>{{ __('researches') }}</span>
                </a>
            </li>

            <li class="treeview font w3-text-pink" onclick="showPage('dashboard/studentresearch')" >
                <a href="#">
                    <i class="fa fa-newspaper-o"></i> <span>{{ __('student researches') }}</span>
                </a>
            </li>
            @endif

            @if (Auth::user()->type == 'student')
            <li class="treeview font w3-text-pink" onclick="showPage('dashboard/myresearch')" >
                <a href="#">
                    <i class="fa fa-newspaper-o"></i> <span>{{ __('my researchs') }}</span>
                </a>
            </li>
            @endif

            @if (Auth::user()->type == 'admin')

            @if (Auth::user()->_can('publish_result'))
            <li class="treeview font w3-text-pink" onclick="showPage('dashboard/studentresearch/admin-publish')" >
                <a href="#">
                    <i class="fa fa-send-o"></i> <span>{{ __('publish_result') }}</span>
                </a>
            </li>
            @endif

            @if (Auth::user()->_can('role'))
            <li class="treeview font w3-text-pink" onclick="showPage('dashboard/role')" >
                <a href="#">
                    <i class="fa fa-th-list"></i> <span>{{ __('roles') }}</span>
                </a>
            </li>
            @endif

            @if (Auth::user()->_can('option'))
            <li class="treeview font w3-text-pink" onclick="showPage('dashboard/option')" >
                <a href="#">
                    <i class="fa fa-cogs"></i> <span>{{ __('option') }}</span>
                </a>
            </li>
            @endif

            <li class="treeview font w3-text-brown" >
                <a href="#">
                    <i class="fa fa-bar-chart"></i> <span>{{ __('reports') }}</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">

                    @if (Auth::user()->_can('course_details_report'))
                    <li  onclick="showPage('dashboard/report/course-details')" ><a href="#"><i class="fa fa-circle-o"></i>{{ __('course_details_report') }}</a></li>
                    @endif

                    @if (Auth::user()->_can('course_details_report2'))
                    <li  onclick="showPage('dashboard/report/course-details2')" ><a href="#"><i class="fa fa-circle-o"></i>{{ __('course_details_report2') }}</a></li>
                    @endif

                    @if (Auth::user()->_can('student_course_report'))
                    <!--
                    <li  onclick="showPage('dashboard/report/studentcourse')" ><a href="#"><i class="fa fa-circle-o"></i>{{ __('student courses') }}</a></li>
                    -->
                    @endif

                    @if (Auth::user()->_can('student_result_report'))
                    <li  onclick="showPage('dashboard/report/studentresult')" ><a href="#"><i class="fa fa-circle-o"></i>{{ __('student results') }}</a></li>
                    @endif

                    @if (Auth::user()->_can('student_result_report2'))
                    <li  onclick="showPage('dashboard/report/studentresult2')" ><a href="#"><i class="fa fa-circle-o"></i>{{ __('student results2') }}</a></li>
                    @endif

                    @if (Auth::user()->_can('course_report'))
                    <li  onclick="showPage('dashboard/report/course')" ><a href="#"><i class="fa fa-circle-o"></i>{{ __('course report') }}</a></li>
                    @endif


                    @if (Auth::user()->_can('student_report'))
                    <li  onclick="showPage('dashboard/report/student')" ><a href="#"><i class="fa fa-circle-o"></i>{{ __('un registered student report') }}</a></li>
                    @endif

                    @if (Auth::user()->_can('student_course_research_report'))
                    <li  onclick="showPage('dashboard/report/student-course-research')" ><a href="#"><i class="fa fa-circle-o"></i>{{ __('student_course_research_report') }}</a></li>
                    @endif

                    @if (Auth::user()->_can('courseResultCountReport'))
                    <li  onclick="showPage('dashboard/report/course-result-count')" ><a href="#"><i class="fa fa-circle-o"></i>{{ __('courseResultCountReport') }}</a></li>
                    @endif

                    @if (Auth::user()->_can('courseResultCountReport2'))
                    <li  onclick="showPage('dashboard/report/course-result-count2')" ><a href="#"><i class="fa fa-circle-o"></i>{{ __('courseResultCountReport2') }}</a></li>
                    @endif

                    @if (Auth::user()->_can('oneStudentResultReport'))
                    <li  onclick="showPage('dashboard/report/one-student-result')" ><a href="#"><i class="fa fa-circle-o"></i>{{ __('oneStudentResultReport') }}</a></li>
                    @endif

                    @if (Auth::user()->_can('student_not_upload_research_report'))
                    <!--
                    <li  onclick="showPage('dashboard/report/student-not-upload-research')" ><a href="#"><i class="fa fa-circle-o"></i>{{ __('student_not_upload_research_report') }}</a></li>
                    -->
                    @endif

                </ul>
            </li>
            @endif

            @if (Auth::user()->type == 'doctor')
            <li class="treeview font w3-text-pink" onclick="showPage('dashboard/report/student-course-research')" >
                <a href="#">
                    <i class="fa fa-line-chart"></i> <span>{{ __('student course research report') }}</span>
                </a>
            </li>
            <li class="treeview font w3-text-pink" onclick="showPage('dashboard/report/course-details')" >
                <a href="#">
                    <i class="fa fa-line-chart"></i> <span>{{ __('course_details_report') }}</span>
                </a>
            </li>
            @endif

            @if (Auth::user()->type == 'student')
            @if (optional(App\Setting::find(10))->value == 1)
            <li class="treeview font w3-text-pink" onclick="showPage('dashboard/result/show')" >
                <a href="#">
                    <i class="fa fa-newspaper-o"></i> <span>{{ __('results') }}</span>
                </a>
            </li>
            @endif
            @if (Auth::user()->toStudent()->grades()->count() > 0)
            @if (optional(App\Setting::find(10))->value == 1)
            <li class="treeview font w3-text-pink" onclick="showPage('dashboard/studentgrade/show')" >
                <a href="#">
                    <i class="fa fa-newspaper-o"></i> <span>{{ __('student grade') }}</span>
                </a>
            </li>
            @endif
            @endif
            @endif
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
