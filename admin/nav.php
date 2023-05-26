<nav class="navigation">
      <!-- top nav -->
      <div class="account-info">
            <a href=""><img src="assets/images/author.jpg" height="80" width="80" alt=""></a>
            <a href="">Md Alim Khan</a>
            <a href="">Logout</a>
      </div>
      <!-- Side navigation -->
      <ul class="nav-menu">
            <?php 
                  $nav_walker->get_site_nav();
            ?>
      </ul>
</nav>