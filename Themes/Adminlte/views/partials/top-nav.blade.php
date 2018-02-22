<!-- Header Navbar: style can be found in header.less -->
<nav class="navbar navbar-static-top" role="navigation">
    <!-- Sidebar toggle button-->
    <a href="#" class="navbar-btn sidebar-toggle" data-toggle="push-menu" role="button" style="margin: 0;">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
    </a>

    <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">

            <li>
                <a href="" class="publicUrl" style="display: none">
                    <i class="fa fa-eye"></i> {{ trans('page::pages.view-page') }}
                </a>
            </li>
            <li><a href="{{ url('/') }}"><i class="fa fa-eye"></i> {{ trans('core::core.general.view website') }}</a></li>
            <li class="dropdown">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="fa fa-flag"></i>
                    <span>

                        <i class="caret"></i>
                    </span>
                </a>
                <ul class="dropdown-menu language-menu">

                </ul>
            </li>
            <!-- User Account: style can be found in dropdown.less -->
            <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                    <i class="glyphicon glyphicon-user"></i>
                    <span>

                        <i class="caret"></i>
                    </span>
                </a>
                <ul class="dropdown-menu">
                    <!-- User image -->
                    <li class="user-header bg-light-blue">

                        <p>

                        </p>
                    </li>
                    <!-- Menu Footer-->
                    <li class="user-footer">
                        <div class="pull-left">
                            <a href="#" class="btn btn-default btn-flat">
                                {{ trans('core::core.general.profile') }}
                            </a>
                        </div>
                        <div class="pull-right">
                            <a href="" class="btn btn-danger btn-flat">
                                {{ trans('core::core.general.sign out') }}
                            </a>
                        </div>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</nav>
