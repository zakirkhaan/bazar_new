<?php
if (!isset($_SESSION['admin_email'])) {
    echo "<script>window.open('login.php','_self')</script>";
} else {
    ?>
    <div class="row"><!-- 1 row Starts -->
        <div class="col-lg-12"><!-- col-lg-12 Starts -->
            <ol class="breadcrumb"><!-- breadcrumb Starts -->
                <li class="active">
                    <i class="fa fa-dashboard"></i> Dashboard / View Countries
                </li>
            </ol><!-- breadcrumb Ends -->
        </div><!-- col-lg-12 Ends -->
    </div><!-- 1 row Ends -->

    <div class="row"><!-- 2 row Starts -->
        <div class="col-lg-12"><!-- col-lg-12 Starts -->
            <div class="panel panel-default"><!-- panel panel-default Starts -->
                <div class="panel-heading"><!-- panel-heading Starts -->
                    <h3 class="panel-title"><!-- panel-title Starts -->
                        <i class="fa fa-money fa-fw"> </i> View Countries
                    </h3><!-- panel-title Ends -->
                </div><!-- panel-heading Ends -->

                <div class="panel-body"><!-- panel-body Starts -->
                    <div class="table-responsive"><!-- table-responsive Starts -->
                        <table class="table table-hover table-bordered table-striped">
                            <!-- table table-hover table-bordered table-striped Starts -->
                            <thead><!-- thead Starts -->
                            <tr>
                                <th>Country No:</th>
                                <th>Country Name:</th>
                                <th>Delete Country:</th>
                                <th>Edit Country:</th>
                            </tr>
                            </thead><!-- thead Ends -->

                            <tbody><!-- tbody Starts -->
                            <?php
                            $i = 0;
                            $per_page = 15;
                            if (isset($_GET["countries_pagination"])) {
                              $page = $_GET["countries_pagination"];
                            } else {
                              $page = 1;
                            }
                            // Page will start from 0 and Multiple by Per Page
                            $start_from = ($page - 1) * $per_page;
                            //Selecting the data from table but with limit
                            $get_countries = "select * from countries order by 1 DESC LIMIT $start_from, $per_page";
                            $run_countries = mysqli_query($con, $get_countries);
                            while ($row_countries = mysqli_fetch_array($run_countries)) {
                              $country_id = $row_countries['country_id'];
                              $country_name = $row_countries['country_name'];
                              $i++;
                              ?>
                                <tr>
                                    <td><?php echo $i; ?></td>
                                    <td><?php echo $country_name; ?></td>
                                    <td>
                                        <a href="index.php?edit_country=<?php echo $country_id; ?>">
                                            <i class="fa fa-pencil"> </i> Edit
                                        </a>
                                    </td>

                                    <td>
                                        <a href="index.php?delete_country=<?php echo $country_id; ?>">
                                            <i class="fa fa-trash-o"></i> Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php } ?>
                            </tbody><!-- tbody Ends -->
                        </table><!-- table table-hover table-bordered table-striped Ends -->
                    </div><!-- table-responsive Ends -->

                    <div style="text-align: center;">
                        <ul class="pagination">
                          <?php
                          //Now select all from table
                          $query = "select * from countries order by 1 DESC";
                          $result = mysqli_query($con, $query);
                          // Count the total records
                          $total_records = mysqli_num_rows($result);
                          //Using ceil function to divide the total records on per page
                          $total_pages = ceil($total_records / $per_page);
                          //Going to first page
                          echo "<li class='page-item' ><a href='index.php?countries_pagination=1' class='page-link' >" . 'First Page' . "</a><li> ";
                          for ($i = 1; $i <= $total_pages; $i++) {
                              echo "<li ";
                              if ($i == $page) {
                                  echo "class='page-item active'";
                              }
                              echo "><a href='index.php?countries_pagination=" . $i . "' class='page-link' >" . $i . "</a></li>";
                          };
                          // Going to last page
                          echo "<li class='page-item'><a href='index.php?countries_pagination=$total_pages' class='page-link' >" . 'Last Page' . "</a></li> ";
                          ?>
                        </ul>
                    </div>
                </div><!-- panel-body Ends -->
            </div><!-- panel panel-default Ends -->
        </div><!-- col-lg-12 Ends -->
    </div><!-- 2 row Ends -->
<?php } ?>