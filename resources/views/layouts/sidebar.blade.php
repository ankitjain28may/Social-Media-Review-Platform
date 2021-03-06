

                                <div class="navbar-default sidebar" role="navigation">
                                    <div class="sidebar-nav navbar-collapse">
                                        <ul class="nav" id="side-menu">
                                            <li class="sidebar-search">
                                                <div class="input-group custom-search-form">
                                                    <input type="text" class="form-control" placeholder="Search...">
                                                    <span class="input-group-btn">
                                                    <button class="btn btn-default" type="button">
                                                        <i class="fa fa-search"></i>
                                                    </button>
                                                </span>
                                                </div>
                                                <!-- /input-group -->
                                            </li>
                                            @if(Auth::check())
                                                @if(App\User::getSlug(Auth::id())[0]->slug == "admin")
                                                <li><a href="{{ route('register') }}"> <i class="fa fa-edit fa-fw"></i> Register New Page Admin</a>
                                                </li>

                                                <li>
                                                    <a href="{{ url('/pages') }}"><i class="fa fa-files-o fa-fw"></i> My Pages</a>
                                                </li> 
                                                <li>
                                                    <a href="{{ url('/hashtags') }}"><i class="fa fa-tags fa-fw"></i> Hashtags </a>
                                                </li>
                                                <li>
                                                    <a href="#"><i class="fa fa-users fa-fw"></i> Handles</a>
                                                        <ul class="nav nav-second-level">
                                                            <li>
                                                                <a href="{{ url('/handles?type=main') }}"><i class="glyphicon glyphicon-user fa-fw"></i>Main Handles</a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ url('/handles?type=user') }}"><i class="fa fa-user fa-fw"></i>User Handles</a>
                                                            </li>
                                                            <li>
                                                        </ul>
                                                </li>

                                                <li>
                                                    <a href="#"><i class="fa fa-users fa-fw"></i> Fb Users</a>
                                                        <ul class="nav nav-second-level">
                                                            <li>
                                                                <a href="{{ url('/users/1') }}"><i class="glyphicon glyphicon-user fa-fw"></i>Admins</a>
                                                            </li>
                                                            <li>
                                                                <a href="{{ url('/users/2') }}"><i class="fa fa-user fa-fw"></i>Users</a>
                                                            </li>
                                                            <li>
                                                        </ul>
                                                </li>

                                                <li>
                                                    <a href="{{ url('/login/1/facebook') }}">
                                                        <i class="fa fa-facebook fa-fw"></i> Link Facebook
                                                    </a>
                                                </li>
                                                @endif
                                            @endif
                                            <!-- <li>
                                                <a href="#"><i class="fa fa-table fa-fw"></i>Clusters</a>
                                            </li>
                                            <li>
                                                <a href="#"><i class="fa fa-edit fa-fw"></i> Forms</a>
                                            </li> -->
                                            <!-- <li>
                                                <a href="#"><i class="fa fa-wrench fa-fw"></i> UI Elements<span class="fa arrow"></span></a> -->
                                                <!-- <ul class="nav nav-second-level">
                                                    <li>
                                                        <a href="panels-wells.html">Panels and Wells</a>
                                                    </li>
                                                    <li>
                                                        <a href="buttons.html">Buttons</a>
                                                    </li>
                                                    <li>
                                                        <a href="notifications.html">Notifications</a>
                                                    </li>
                                                    <li>
                                                        <a href="typography.html">Typography</a>
                                                    </li>
                                                    <li>
                                                        <a href="icons.html"> Icons</a>
                                                    </li>
                                                    <li>
                                                        <a href="grid.html">Grid</a>
                                                    </li>
                                                </ul>
 -->                                                <!-- /.nav-second-level -->
                                            <!-- </li> -->
                                            <!-- <li> -->
                                                <!-- <a href="#"><i class="fa fa-sitemap fa-fw"></i> Multi-Level Dropdown<span class="fa arrow"></span></a>
                                                <ul class="nav nav-second-level">
                                                    <li>
                                                        <a href="#">Second Level Item</a>
                                                    </li>
                                                    <li>
                                                        <a href="#">Second Level Item</a>
                                                    </li>
                                                    <li>
                                                        <a href="#">Third Level <span class="fa arrow"></span></a>
                                                        <ul class="nav nav-third-level">
                                                            <li>
                                                                <a href="#">Third Level Item</a>
                                                            </li>
                                                            <li>
                                                                <a href="#">Third Level Item</a>
                                                            </li>
                                                            <li>
                                                                <a href="#">Third Level Item</a>
                                                            </li>
                                                            <li>
                                                                <a href="#">Third Level Item</a>
                                                            </li>
                                                        </ul> -->
                                                        <!-- /.nav-third-level -->
                                                  <!--   </li>
                                                </ul> -->
                                                <!-- /.nav-second-level -->
                                            <!-- </li>
                                            <li>
                                                <a href="#"><i class="fa fa-files-o fa-fw"></i> Sample Pages<span class="fa arrow"></span></a>
                                                <ul class="nav nav-second-level">
                                                    <li>
                                                        <a href="blank.html">Blank Page</a>
                                                    </li>
                                                    <li>
                                                        <a href="login.html">Login Page</a>
                                                    </li>
                                                </ul>
 -->                                                <!-- /.nav-second-level -->
                                            <!-- </li> -->
                                        </ul>
                                    </div>
                                    <!-- /.sidebar-collapse -->
                                </div>
