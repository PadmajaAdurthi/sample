<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['login']) == 0) {
  header('location:index.php');
} else {
  if (isset($_POST['update'])) {
    $password = md5($_POST['password']);
    $newpassword = md5($_POST['newpassword']);
    $email = $_SESSION['login'];
    $sql = "SELECT Password FROM tblusers WHERE EmailId=:email and Password=:password";
    $query = $dbh->prepare($sql);
    $query->bindParam(':email', $email, PDO::PARAM_STR);
    $query->bindParam(':password', $password, PDO::PARAM_STR);
    $query->execute();
    $results = $query->fetchAll(PDO::FETCH_OBJ);
    if ($query->rowCount() > 0) {
      $con = "update tblusers set Password=:newpassword where EmailId=:email";
      $chngpwd1 = $dbh->prepare($con);
      $chngpwd1->bindParam(':email', $email, PDO::PARAM_STR);
      $chngpwd1->bindParam(':newpassword', $newpassword, PDO::PARAM_STR);
      $chngpwd1->execute();
      $msg = "Your Password succesfully changed";
    } else {
      $error = "Your current password is wrong";
    }
  }

  ?>
  <!DOCTYPE HTML>
  <html lang="en">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Update Password</title>
    <!--Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
    <!--Custome Style -->
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
    <!--FontAwesome Font Style -->
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">


    <script type="text/javascript">
      function valid() {
        if (document.chngpwd.newpassword.value != document.chngpwd.confirmpassword.value) {
          alert("New Password and Confirm Password Field do not match  !!");
          document.chngpwd.confirmpassword.focus();
          return false;
        }
        return true;
      }
    </script>
    <style>
      .errorWrap {
        padding: 10px;
        margin: 0 0 20px 0;
        background: #fff;
        border-left: 4px solid #dd3d36;
        -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
      }

      .succWrap {
        padding: 10px;
        margin: 0 0 20px 0;
        background: #fff;
        border-left: 4px solid #5cb85c;
        -webkit-box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
        box-shadow: 0 1px 1px 0 rgba(0, 0, 0, .1);
      }
    </style>
  </head>

  <body>


    <!--Header-->
    <?php include('includes/header.php'); ?>
    <!-- /Header -->
    <!--Page Header-->
    <section class="page-header profile_page">
      <div class="container">
        <div class="page-header_wrap">
          <div class="page-heading">
            <h1>Update Password</h1>
          </div>
          <ul class="coustom-breadcrumb">
            <li><a href="#">Home</a></li>
            <li>Update Password</li>
          </ul>
        </div>
      </div>
      <!-- Dark Overlay-->
      <div class="dark-overlay"></div>
    </section>
    <!-- /Page Header-->

    <section class="user_profile inner_pages">
      <div class="container">
        <div class="flexDisplay">
          <div class="inlineDisplay leftSideWidth">
            <?php include('includes/sidebar.php'); ?>
            <div class="inlineDisplay midSideWidth">
              <div class="profile_wrap">
                <form name="chngpwd" method="post" onSubmit="return valid();">

                  <div class="gray-bg field-title text-center">
                    <h6>Password Details</h6>
                  </div>
                  <?php if ($error) { ?><div class="errorWrap"><strong>ERROR</strong>:<?php echo htmlentities($error); ?> </div><?php } else if ($msg) { ?><div class="succWrap"><strong>SUCCESS</strong>:<?php echo htmlentities($msg); ?> </div><?php } ?>
                  <div class="form-group">
                    <label class="control-label">Current Password</label>
                    <input class="form-control white_bg" id="password" name="password" type="password" required>
                  </div>
                  <div cl <div class="form-group">
                    <label class="control-label">Password</label>
                    <input class="form-control white_bg" id="newpassword" type="password" name="newpassword" required>
                  </div>
                  <div class="form-group">
                    <label class="control-label">Confirm Password</label>
                    <input class="form-control white_bg" id="confirmpassword" type="password" name="confirmpassword" required>
                  </div>

                  <div class="form-group">
                    <input type="submit" value="Update" name="update" id="submit" class="btn btn-block">
                  </div>
                </form>
              </div>
            </div>
            <!--Side-Bar-->
            <div class="inlineDisplay rightSideWidth">
              <div class="user_profile_info gray-bg">

                <?php
                  $useremail = $_SESSION['login'];
                  $sql = "SELECT * from tblusers where EmailId=:useremail";
                  $query = $dbh->prepare($sql);
                  $query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
                  $query->execute();
                  $results = $query->fetchAll(PDO::FETCH_OBJ);
                  $cnt = 1;
                  if ($query->rowCount() > 0) {
                    foreach ($results as $result) { ?>
                    <div>
                      <h5><?php echo htmlentities($result->FullName); ?></h5>
                      <p><?php echo htmlentities($result->Address); ?><br>
                        <?php echo htmlentities($result->City); ?>&nbsp;<?php echo htmlentities($result->Country);
                                                                              ?></p>
                    </div>
                <?php }
                  } ?>
              </div>

            </div>
            <!--/Side-Bar-->
          </div>

        </div>
      </div>
    </section>
    <!--/Profile-setting-->

    <<!--Footer -->
      <?php include('includes/footer.php'); ?>
      <!-- /Footer-->

      <!--Back to top-->
      <div id="back-top" class="back-top"> <a href="#top"><i class="fa fa-angle-up" aria-hidden="true"></i> </a> </div>
      <!--/Back to top-->

      <!--Login-Form -->
      <?php include('includes/login.php'); ?>
      <!--/Login-Form -->

      <!--Register-Form -->
      <?php include('includes/registration.php'); ?>

      <!--/Register-Form -->

      <!--Forgot-password-Form -->
      <?php include('includes/forgotpassword.php'); ?>
      <!--/Forgot-password-Form -->

      <!-- Scripts -->
      <script src="assets/js/jquery.min.js"></script>
      <script src="assets/js/bootstrap.min.js"></script>
      <script src="assets/js/interface.js"></script>
      <!--Switcher-->
      <script src="assets/switcher/js/switcher.js"></script>
      <!--bootstrap-slider-JS-->
      <script src="assets/js/bootstrap-slider.min.js"></script>
      <!--Slider-JS-->
      <script src="assets/js/slick.min.js"></script>
      <script src="assets/js/owl.carousel.min.js"></script>

  </body>

  </html>
<?php } ?>