<?php include "header.php";?>

<!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-transparent bg-primary navbar-absolute">
    <div class="container-fluid">
      <div class="navbar-wrapper">
        <a class="navbar-brand" href="<?php echo BASE_URL;?>"><?php echo JAK_TITLE;?></a>
      </div>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-controls="navigation-index" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-bar navbar-kebab"></span>
        <span class="navbar-toggler-bar navbar-kebab"></span>
        <span class="navbar-toggler-bar navbar-kebab"></span>
      </button>
      <div class="collapse navbar-collapse justify-content-end" id="navigation">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a href="javascript:void(0)" class="nav-link">Front End</a>
          </li>
          <li class="nav-item active">
            <a href="<?php echo BASE_URL;?>" class="nav-link">Operator Login</a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
  <!-- End Navbar -->
  <div class="wrapper wrapper-full-page ">
    <div class="full-page login-page section-image" filter-color="green" style="background: url('img/login.jpg') no-repeat center center fixed;background-size: cover;height: 100%;overflow: hidden;">
      <!--   you can change the color of the filter page using: data-color="blue | purple | green | orange | red | rose " -->

      <div class="content">
        <div class="container">
          <div class="col-md-4 ml-auto mr-auto">
              <div class="card card-login card-plain">
                <div class="loginF">
                  <form class="form" id="login_form" method="post" action="<?php echo $_SERVER['REQUEST_URI'];?>">
                <div class="card-header text-center">
                    <h3><?php echo $jkl['l3'];?></h3>
                </div>
                <div class="card-body">
                  <?php if (isset($ErrLogin)) { ?>
                  <div class="alert alert-danger">
                  <?php echo $jkl["l"];?>
                  </div>
                  <?php } ?>
                  <div class="input-group no-border form-control-lg">
                    <span class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fa fa-user"></i>
                      </div>
                    </span>
                    <input type="text" class="form-control<?php if (isset($ErrLogin)) echo " is-invalid";?>" name="username" id="username" placeholder="<?php echo $jkl["l1"].' / '.$jkl["l5"];?>">
                  </div>
                  <div class="input-group no-border form-control-lg">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fa fa-key"></i>
                      </div>
                    </div>
                     <input type="password" name="password" class="form-control<?php if (isset($ErrLogin)) echo " is-invalid";?>" id="password" placeholder="<?php echo $jkl["l2"];?>">
                  </div>
                <div class="form-check text-left mt-4">
                      <label class="form-check-label">
                        <input class="form-check-input" type="checkbox" name="lcookies">
                        <span class="form-check-sign"></span>
                        <?php echo $jkl["l4"];?>
                      </label>
                </div>
              </div>
                <div class="card-footer">
                  <button type="submit" name="logID" class="btn btn-primary btn-round btn-lg btn-block mb-3"><?php echo $jkl["l3"];?></button>
                  <input type="hidden" name="action" value="login">
                  <div class="pull-right">
                    <h6>
                      <a href="javascript:void(0)" class="link footer-link lost-pwd"><?php echo $jkl["f"];?></a>
                    </h6>
                  </div>
                </div>
              </form>
              </div>
              <div class="forgotP">
                <form class="form" action="<?php echo $_SERVER['REQUEST_URI'];?>" method="post">
                <div class="card-header text-center">
                    <h3><?php echo $jkl['l13'];?></h3>
                </div>
                <div class="card-body">
                <?php if (isset($errorfp)) { ?><div class="alert alert-danger"><?php echo $errorfp["e"];?></div><?php } ?>
                  <div class="input-group no-border form-control-lg">
                    <div class="input-group-prepend">
                      <div class="input-group-text">
                        <i class="fa fa-envelope"></i>
                      </div>
                    </div>
                     <input type="text" name="lsE" class="form-control<?php if (isset($errorfp)) echo " is-invalid";?>" id="email" placeholder="<?php echo $jkl["l5"];?>">
                  </div>
                </div>
                <div class="card-footer">
                  <button type="submit" name="forgotP" class="btn btn-primary btn-round btn-lg btn-block mb-3"><?php echo $jkl["g4"];?></button>
                  <div class="pull-right">
                    <h6>
                      <a href="javascript:void(0)" class="link footer-link lost-pwd"><?php echo $jkl["g3"];?></a>
                    </h6>
                  </div>
                </div>
                </form>
                </div>
              </div>

          </div>
        </div>
      </div>

  <footer class="footer">
        <div class=" container-fluid ">
          <nav>
            <ul>
              <li>
                <a href="javascript:void(0)">
                  About Us
                </a>
              </li>
              <li>
                <a href="javascript:void(0)">
                  Blog
                </a>
              </li>
            </ul>
          </nav>
          <?php if (isset($jakhs['copyright']) && !empty($jakhs['copyright'])) { ?>
          <div class="copyright" id="copyright">
            <i class="fa fa-copyright"></i>
            <?php echo date("Y");?>, Designed with <i class="fa fa-heart"></i>. Coded by
            <a href="https://www.jakweb.ch" target="_blank">JAKWEB</a>.
          </div>
        <?php } ?>
        </div>
      </footer>

</div>

</div>

<?php include "footer.php";?>