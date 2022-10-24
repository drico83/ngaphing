<div class="topbar">
    <!-- Navbar -->
    <nav class="navbar-custom">
        <ul class="list-unstyled topbar-nav float-end mb-0">
            <li class="dropdown">
                <a class="nav-link dropdown-toggle waves-effect waves-light nav-user" data-bs-toggle="dropdown" href="#"
                    role="button" aria-haspopup="false" aria-expanded="false">
                    <span class="ms-1 nav-user-name hidden-sm"><?= user()->name ?></span>
                    <img src="<?= base_url('/assets/images/users/' . user()->avatar) ?>" alt="profile-user"
                        class="rounded-circle thumb-xs" />
                </a>
                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="<?= base_url('profil') ?>"><i data-feather="user"
                            class="align-self-center icon-xs icon-dual me-1"></i> Profile</a>

                    <a class="dropdown-item" href="<?= base_url('logout') ?>"><i data-feather="power"
                            class="align-self-center icon-xs icon-dual me-1"></i> Logout</a>
                </div>
            </li>
        </ul>
        <!--end topbar-nav-->

        <ul class="list-unstyled topbar-nav mb-0">
            <li>
                <button class="nav-link button-menu-mobile">
                    <i data-feather="menu" class="align-self-center topbar-icon"></i>
                </button>
            </li>
            <li class="creat-btn">
                <div class="nav-link">

                </div>
            </li>
        </ul>
    </nav>
    <!-- end navbar-->
</div>