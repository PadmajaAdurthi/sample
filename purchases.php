<?php
session_start();
error_reporting(0);
include('includes/config.php');
if (strlen($_SESSION['login']) == 0) {
  header('location:index.php');
} else {
  ?>
  <!DOCTYPE HTML>
  <html lang="en">

  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>My Purchases</title>
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
            <h1>My Purchases</h1>
          </div>
          <ul class="coustom-breadcrumb">
            <li><a href="#">Home</a></li>
            <li>My Purchases</li>
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
                  <h6>Purchase Details</h6>
                </div>
                <div class="my_vehicles_list">
                  <ul class="vehicle_listing">
                    <?php
                      $useremail = $_SESSION['login'];
                      //$sql = "SELECT tblvehicles.Vimage1 as Vimage1,tblvehicles.VehiclesTitle,tblvehicles.id as vid,tblbrands.BrandName,tblservice.servicename,tblservice.serdesc,tblservice.Status  from tblservice join tblvehicles on tblservice.VehicleId=tblvehicles.id join tblbrands on tblbrands.id=tblvehicles.VehiclesBrand where tblservice.userEmail=:useremail";
                      $sql = "SELECT tblvehicles.Vimage1 as Vimage1,tblvehicles.VehiclesTitle,tblvehicles.id as vid,tblbrands.BrandName,vehiclebooking.message,vehiclebooking.Status  from vehiclebooking join tblvehicles on vehiclebooking.vehicleid=tblvehicles.id join tblbrands on tblbrands.id=tblvehicles.VehiclesBrand  where vehiclebooking.userEmail=:useremail";
                      $query = $dbh->prepare($sql);
                      $query->bindParam(':useremail', $useremail, PDO::PARAM_STR);
                      $query->execute();
                      $results = $query->fetchAll(PDO::FETCH_OBJ);
                      $cnt = 1;
                      if ($query->rowCount() > 0) {
                        foreach ($results as $result) {  ?>

                        <li>
                          <div class="vehicle_img"> <a href="vehical-details.php?vhid=<?php echo htmlentities($result->vid); ?>""><img src=" admin/img/vehicleimages/<?php echo htmlentities($result->Vimage1); ?>" alt="image"></a> </div>
                          <div class="vehicle_title">
                            <h6><a href="vehical-details.php?vhid=<?php echo htmlentities($result->vid); ?>""> <?php echo htmlentities($result->BrandName); ?> , <?php echo htmlentities($result->VehiclesTitle); ?></a></h6>
                  
                </div>
                <?php if ($result->Status == 1) { ?>
                <div class=" vehicle_status"> <a href="#" class="btn outline btn-xs active-btn">Confirmed</a>
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
                        <div style="float: left">
                          <p><b>Message:</b> <?php echo htmlentities($result->message); ?> </p>
                        </div>
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