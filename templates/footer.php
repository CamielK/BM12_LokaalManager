<style>
    .footer {
      position: absolute;
      bottom: 0;
      width: 100%;
      height: 60px;
      background-color: #f5f5f5;
      text-align: center;
      display: block;
      padding: 25px 0;
    }
    body {
      font-family: "Helvetica Neue", Helvetica, Arial;
      font-size: 16px;
      line-height: 20px;
      font-weight: 400;
      color: #3b3b3b;
      margin-bottom: 60px;
    }
</style>

<footer class="text-center">
  <div class="footer-below footer">
      <div class="container">
          <div class="row">
              <div class="col-lg-4">
                  <a href="/">&copy; ZUYD Lokaal Manager <?php echo date("Y"); ?></a>
              </div>
              <div class="col-lg-4">
              </div>
              <div class="col-lg-4">
                <?php
                  if(isset($_SESSION['login_user'])){
                    echo '<a href="/admin/logout.php">Logout</a>';
                  } else {
                    echo '<a href="/admin/login.php">Login</a>';
                  }
                  
                ?>
              </div>

          </div>
      </div>
  </div>
</footer>