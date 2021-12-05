<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
      <a class="navbar-brand" href="#">Printerous</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="{{url('organization')}}">Organization</a>
          </li>
          @can('show person')
          <li class="nav-item">
            <a class="nav-link" href="{{url('person')}}">Person</a>
          </li>
          @endcan
          @can('create user')
          <li class="nav-item">
            <a class="nav-link" href="{{url('users')}}">User</a>
          </li>
          @endcan
        </ul>
      </div>
      <div class="d-flex">
        <div class="dropdown">
            <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                {{Auth::user()->name}}{{count(Auth::user()->getRoleNames()) > 0 ? ' - '.Auth::user()->getRoleNames()[0] : ''}}
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
              <li><a class="dropdown-item" href="{{url('logout')}}">Logout</a></li>
            </ul>
          </div>
      </div>
    </div>
  </nav>