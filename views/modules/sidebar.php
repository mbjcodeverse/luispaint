    <div class="sidebar sidebar-light sidebar-main sidebar-fixed sidebar-expand-md">
      <!-- Sidebar mobile toggler -->
      <div class="sidebar-mobile-toggler text-center">
        <a href="#" class="sidebar-mobile-main-toggle">
          <i class="icon-arrow-left8"></i>
        </a>
        Navigation
        <a href="#" class="sidebar-mobile-expand">
          <i class="icon-screen-full"></i>
          <i class="icon-screen-normal"></i>
        </a>
      </div>
      <!-- /sidebar mobile toggler -->
      <!-- Sidebar content -->
      <div class="sidebar-content">
        <!-- User menu -->
        <div class="sidebar-user">
          <div class="card-body">
            <div class="media">
              <div class="mr-3">
                <?php 
                  if ($_SESSION["photo"] != "") {
                    echo '<img src="'.$_SESSION["photo"].'"class="rounded-circle" height="38" alt="">';
                  }else{
                    echo '<img class="rounded-circle" height="38" alt="" src="views/img/users/default/anonymous.png">';
                  }
                ?>                

              </div>
              <div class="media-body">
                <?php
                  $table = 'employees';
                  $item = 'empid';
                  $value = $_SESSION["empid"];
                  $employee = (new ControllerEmployees)->ctrShowEmployees($item, $value);
                  $employee_name = $employee["fname"].' '.$employee["lname"];
                ?>

                <div class="font-size-md media-title font-weight-semibold"><?php echo $employee_name; ?></div>
                <div class="font-size-sm opacity-70" style="color:gold;">
                  <i class="icon-user font-size-md"></i> &nbsp;<?php if ($_SESSION["accessprivilege"]=='Full'){                           
                                                                         echo 'Administrator';
                                                                     }else{
                                                                         echo 'Standard User';
                                                                     }
                                                                     ?>
                </div>
              </div>
              <div class="ml-3 align-self-center">
                <a href="resetloginaccount" class="text-white"><i class="icon-cog3"></i></a>
              </div>
            </div>
          </div>
        </div>
        <!-- /user menu -->

        <!-- Main navigation -->
        <div class="card card-sidebar-mobile">
          <ul class="nav nav-sidebar" data-nav-type="accordion">
            <?php
              if($_SESSION["dashboard"] != 'Restricted'){  
                echo '
                  <li class="nav-item"><a href="home" class="nav-link"><i class="icon-home4"></i> <span>Dashboard</span></a></li>     
                '; 
              }                 
            ?>
            <!-- Transactions -->
            <li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Transactions</div> <i class="icon-menu" title="Forms"></i></li>

            <?php
              if($_SESSION["invoices"] != 'Restricted'){  
                echo '
                  <li class="nav-item"><a href="sales" class="nav-link"><i class="icon-price-tags2"></i> <span>Invoices</span></a></li>     
                '; 
              }                 
            ?>

            <?php
              if($_SESSION["receivable"] != 'Restricted'){
                echo '
                  <li class="nav-item"><a href="receivable" class="nav-link"><i class="icon-drawer-in"></i> <span>Receivable</span></a></li>     
                '; 
              }
            ?>                     
      
            <?php
              if($_SESSION["reports"] != 'Restricted'){    
                echo '
                  <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-stack"></i> <span>Reports</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="Text editors">';
                      // if($_SESSION["po"] == 1){     
                      //   echo '
                      //        <li class="nav-item"><a href="" class="nav-link">Purchase Order</a></li>  
                      //   ';
                      // }

                      // if($_SESSION["inc"] == 1){     
                      //   echo '
                      //        <li class="nav-item"><a href="" class="nav-link">Incoming</a></li>  
                      //   ';
                      // }      

                      // echo '
                      //        <li class="nav-item"><a href="incomingreport" class="nav-link">Incoming Stocks</a></li>  
                      // ';                
                    echo '</ul>';
                  echo '</li>';
              }
            ?>                                              

            <?php
              if($_SESSION["clients"] != 'Restricted' || $_SESSION["employees"] != 'Restricted'){   
                echo '
                  <li class="nav-item-header"><div class="text-uppercase font-size-xs line-height-xs">Documents</div> <i class="icon-menu" title="Forms"></i></li>

                  <li class="nav-item nav-item-submenu">
                    <a href="#" class="nav-link"><i class="icon-folder-open3"></i> <span>Profile</span></a>
                    <ul class="nav nav-group-sub" data-submenu-title="Text editors">';

                      if($_SESSION["clients"] != 'Restricted'){     
                        echo '
                             <li class="nav-item"><a href="clients" class="nav-link">Clients</a></li>  
                        ';
                      }                                            

                      if($_SESSION["employees"] != 'Restricted'){     
                        echo '
                             <li class="nav-item"><a href="employees" class="nav-link">Employees</a></li>  
                        ';
                      }                    
                    echo '</ul>';
                  echo '</li>';
              }
            ?>            

            <!-- Access Privilege -->
            <?php
              if($_SESSION["accessprivilege"] != 'Restricted'){    
                echo '
                  <li class="nav-item"><a href="access" class="nav-link"><i class="icon-key"></i> <span>Access Privilege</span></a></li>     
                ';
              }
            ?>
          </ul>
        </div>
        <!-- /main navigation -->


      </div>
      <!-- /sidebar content -->
    </div>