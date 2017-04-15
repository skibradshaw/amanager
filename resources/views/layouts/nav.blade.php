    <nav class="navbar navbar-inverse" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="/"><img src="/img/logo.png"></a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="navbar-collapse-1">
                <!-- Nav Bar Left -->
                <ul class="nav navbar-nav">
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Apartments</a>
                        <ul class="dropdown-menu" role="menu">
                          @foreach($navProperties as $property)
                          <li><a href="{{route('apartments.index',[$property])}}">{{$property->name}}</a></li>
                          <li class="divider"></li>
                          @endforeach
                          <li><a href="#">Add a New Property</a></li>                            
                        </ul>
                    </li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">Reports</a>
                        <ul class="dropdown-menu" role="menu">
                            <!-- <li><a href="#">Active Tenants</a></li>
                            <li class="divider"></li>
                            <li><a href="#">Collection Report</a></li>
                            <li class="divider"></li> -->
                            <li><a href="{{route('unpaid.balances')}}">Unpaid Balances</a></li>
                            <li class="divider"></li>
                            <li><a href="{{route('undeposited')}}">Undeposited Funds</a></li>
                            <li class="divider"></li>
                            <!-- <li><a href="#">Vacant Apartments</a></li> -->
                        </ul>
                    </li>
                    <!-- <li>
                        <a href="#">Contact</a>
                    </li> -->
                </ul>

                <!-- Navbar Right -->
                <ul class="nav navbar-nav navbar-right">
                    <!-- Authentication Links -->
                    @if (Auth::guest())
                        <li><a href="{{ route('login') }}">Login</a></li>
                    @else
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                <i class="fa fa-user fa-lg"></i> {{ Auth::user()->firstname }} <span class="caret"></span>
                            </a>

                            <ul class="dropdown-menu" role="menu">
                                <li>
                                    <a href="{{ route('logout') }}"
                                        onclick="event.preventDefault();
                                                 document.getElementById('logout-form').submit();">
                                        Logout
                                    </a>


                                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                        {{ csrf_field() }}
                                    </form>
                                </li>
                                <li><a href="{{route('bank_accounts.index')}}">Manage Bank Accounts</a> </li>
                                <li><a href="#">Manage Users</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>

            </div>


            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>