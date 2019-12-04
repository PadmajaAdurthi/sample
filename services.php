<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['login']) == 0) {
  header('location:index.php');
} else {
  if (isset($_POST['submit'])) {
    $fromdate = $_POST['fromdate'];
    $message = $_POST['serdesc'];
    $vehicleName = $_POST['vehicleName'];
    $useremail = $_SESSION['login'];
    $status = 0;
    $vhid = $_GET['vhid'];
    $sql = "INSERT INTO  tblservice(userEmail,VehicleName,servicename,serdesc,Status) VALUES(:useremail,:vehicleName,:sername,:serdesc,:status)";
    $query = $dbh->prepare($sql);
    $query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
    $query->bindParam(':vehicleName', $vehicleName, PDO::PARAM_STR);
    $query->bindParam(':sername', $fromdate, PDO::PARAM_STR);
    $query->bindParam(':serdesc', $message, PDO::PARAM_STR);
    $query->bindParam(':status', $status, PDO::PARAM_STR);
    $query->execute();
    $lastInsertId = $dbh->lastInsertId();
    if ($lastInsertId) {
      echo "<script>alert('Service successful.');</script>";
    } else {
      echo "<script>alert('Something went wrong. Please try again');</script>";
    }
  }
  ?>
  <!DOCTYPE HTML>
  <html lang="en">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>My Services</title>
    <!--Bootstrap -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css">
    <!--Custome Style -->
    <link rel="stylesheet" href="assets/css/style.css" type="text/css">
    <!--FontAwesome Font Style -->
    <link href="assets/css/font-awesome.min.css" rel="stylesheet">
  </head>

  <body>


    <!--Header-->
    <?php include('includes/header.php'); ?>
    <!--Page Header-->
    <!-- /Header -->

    <!--Page Header-->
    <section class="page-header profile_page">
      <div class="container">
        <div class="page-header_wrap">
          <div class="page-heading">
            <h1>My Services</h1>
          </div>
          <ul class="coustom-breadcrumb">
            <li><a href="#">Home</a></li>
            <li>My Services</li>
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
                <div class="gray-bg field-title text-center">
                  <h6>Service Details</h6>
                </div>
                <div class="my_vehicles_list">
                  <ul class="vehicle_listing">
                    <?php
                      $useremail = $_SESSION['login'];
                      $sql = "select tblservice.VehicleName, tblservice.servicename, tblservice.serdesc, tblservice.Status, tblservice.RequestDate from tblservice join tblusers on tblservice.userEmail = tblusers.EmailId where tblservice.userEmail=:useremail";
                      $query = $dbh->prepare($sql);
                      $query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
                      $query->execute();
                      $results = $query->fetchAll(PDO::FETCH_OBJ);
                      $cnt = 1;
                      if ($query->rowCount() > 0) {
                        foreach ($results as $result) {  ?>

                        <li>

                          <div class="vehicle_title">
                            <h6><?php echo htmlentities($result->VehicleName); ?></h6>
                            <p><b>Service Name:</b> <?php echo htmlentities($result->servicename); ?><br /> </p>
                            <p><b>Request Date:</b> <?php echo htmlentities($result->RequestDate); ?><br /> </p>
                            <p><b>Message:</b> <?php echo htmlentities($result->serdesc); ?> </p>
                          </div>
                          <?php if ($result->Status == 1) { ?>
                            <div class="vehicle_status"> <a href="#" class="btn outline btn-xs active-btn">Confirmed</a>
                              <div class="clearfix"></div>
                            </div>

                          <?php } else if ($result->Status == 2) { ?>
                            <div class="vehicle_status"> <a href="#" class="btn outline btn-xs">Cancelled</a>
                              <div class="clearfix"></div>
                            </div>



                          <?php } else { ?>
                            <div class="vehicle_status"> <a href="#" class="btn outline btn-xs">Not Confirm yet</a>
                              <div class="clearfix"></div>
                            </div>
                          <?php } ?>

                        </li>
                    <?php }
                      } ?>


                  </ul>
                </div>

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

              <div class="sidebar_widget">
                <div class="widget_heading">
                  <h5><i class="fa fa-envelope" aria-hidden="true"></i>Request Service</h5>
                </div>
                <form method="post">
                  <div class="form-group">
                    <input type="text" class="form-control" name="vehicleName" placeholder="Vehicle Name" required>
                  </div>
                  <div class="form-group">
                    <input type="text" class="form-control" name="fromdate" placeholder="Service Name" required>
                  </div>
                  <div class="form-group">
                    <textarea rows="4" class="form-control" name="serdesc" placeholder="Message" required></textarea>
                  </div>
                  <?php if ($_SESSION['login']) { ?>
                    <div class="form-group">
                      <input type="submit" class="btn" name="submit" value="Request Service">
                    </div>
                  <?php } else { ?>
                    <a href="#loginform" class="btn btn-xs uppercase" data-toggle="modal" data-dismiss="modal">Login For Book</a>

                  <?php } ?>
                </form>
              </div>
            </div>
            <!--/Side-Bar-->

          </div>
        </div>
      </div>
    </section>

    <!--/my-vehicles-->
    <?php include('includes/footer.php'); ?>

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