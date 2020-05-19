<?php

if (!isset($_SESSION['admin_email'])) {

  echo "<script>window.open('login.php','_self')</script>";

} else {

  ?>

    <div class="row"><!-- 1 row Starts -->

        <div class="col-lg-12"><!-- col-lg-12 Starts -->

            <ol class="breadcrumb"><!-- breadcrumb Starts -->

                <li class="active">

                    <i class="fa fa-dashboard"></i> Dashboard / View Shipping Zones

                </li>

            </ol><!-- breadcrumb Ends -->

        </div><!-- col-lg-12 Ends -->

    </div><!-- 1 row Ends -->

    <div class="row"><!-- 2 row Starts -->

        <div class="col-lg-12"><!-- col-lg-12 Starts -->

            <div class="panel panel-default"><!-- panel panel-default Starts -->

                <div class="panel-heading"><!-- panel-heading Starts -->

                    <h3 class="panel-title"><!-- panel-title Starts -->

                        <i class="fa fa-money fa-fw"> </i> View Shipping Zones

                    </h3><!-- panel-title Ends -->

                </div><!-- panel-heading Ends -->

                <div class="panel-body"><!-- panel-body Starts -->

                    <p class="lead"> Shipping Zones <a href="index.php?insert_shipping_zone" class="btn btn-default">
                            Add Shipping Zone </a></p>

                    A shipping zone is a geographic region where a certain set of shipping methods are offered. System
                    will match a customer to a single zone using their shipping address and present the shipping types
                    within that zone to them.

                    <br><br>

                    <div class="table-responsive"><!-- table-responsive Starts -->

                        <table class="table table-hover table-bordered table-striped">
                            <!-- table table-hover table-bordered table-striped Starts -->

                            <thead><!-- thead Starts -->

                            <tr>

                                <th>Zone Order:</th>
                                <th>Zone Name:</th>
                                <th>Zone Regions:</th>
                                <th>Actions:</th>

                            </tr>

                            </thead><!-- thead Ends -->

                            <tbody><!-- tbody Starts -->

                            <?php

                            $get_zones = "select * from zones order by zone_order";

                            $run_zones = mysqli_query($con, $get_zones);

                            while ($row_zones = mysqli_fetch_array($run_zones)) {

                              $zone_id = $row_zones['zone_id'];

                              $zone_name = $row_zones['zone_name'];

                              $zone_order = $row_zones['zone_order'];

                              ?>

                                <tr id="<?php echo $zone_id; ?>">

                                    <td><?php echo $zone_order; ?></td>

                                    <td><?php echo $zone_name; ?></td>

                                    <td>
                                      <?php

                                      $result = "";

                                      $get_zones_locations = "select * from zones_locations where zone_id='$zone_id'";

                                      $run_zones_locations = mysqli_query($con, $get_zones_locations);

                                      while ($row_zones_locations = mysqli_fetch_array($run_zones_locations)) {

                                        $location_code = $row_zones_locations["location_code"];

                                        $location_type = $row_zones_locations["location_type"];

                                        if ($location_type == "country") {

                                          $get_country = "select * from countries where country_id='$location_code'";

                                          $run_country = mysqli_query($con, $get_country);

                                          $row_country = mysqli_fetch_array($run_country);

                                          $country_name = $row_country['country_name'];

                                          $result .= "$country_name, ";

                                        } else {

                                          $result .= "$location_code, ";

                                        }

                                      }

                                      echo rtrim($result, ", ");

                                      ?>
                                    </td>

                                    <td>

                                        <div class="dropdown">

                                            <button class="btn btn-success dropdown-toggle" id="dropdownMenu1"
                                                    data-toggle="dropdown">
                                                <span class="caret"></span>
                                            </button>

                                            <ul class="dropdown-menu dropdown-menu-right"
                                                aria-labelledby="dropdownMenu1">

                                                <li>
                                                    <a href="index.php?edit_shipping_zone=<?php echo $zone_id; ?>">

                                                        <i class="fa fa-pencil"> </i> Edit

                                                    </a>
                                                </li>

                                                <li>
                                                    <a href="index.php?delete_shipping_zone=<?php echo $zone_id; ?>">

                                                        <i class="fa fa-trash-o"></i> Delete

                                                    </a>
                                                </li>

                                            </ul>

                                        </div>

                                    </td>

                                </tr>

                            <?php } ?>

                            </tbody><!-- tbody Ends -->

                        </table><!-- table table-hover table-bordered table-striped Ends -->

                    </div><!-- table-responsive Ends -->

                </div><!-- panel-body Ends -->

            </div><!-- panel panel-default Ends -->

        </div><!-- col-lg-12 Ends -->

    </div><!-- 2 row Ends -->

    <script>

        $(document).ready(function () {

            $(document).on("mouseenter", "tr td:first-child", function () {

                $(this).css("cursor", "move");

                $("tbody").sortable({

                    helper: fixWidthHelper,
                    placeholder: "placeholder-highlight",
                    containment: "tbody"

                }).disableSelection();

                function fixWidthHelper(e, ui) {

                    ui.children().each(function () {

                        $(this).width($(this).width());

                    });

                    return ui;

                }

            });

        });

    </script>

<?php } ?>