<?php
ob_start();
echo '     <div class="container-fluid p-0">
            <footer class="d-flex flex-wrap justify-content-between align-items-center px-4 py-4 mt-3 border-top border-grn bg-wht">
              <p class="col-md-4 mb-0 text-muted">© 2022 園児Enji Childcare Company, Inc</p>
              <a href="index.php" class="col-md-4 d-flex align-items-center justify-content-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
                <img src="images/logos/logo_hw_80.png" alt="">
              </a>
              
              <ul class="nav col-md-4 justify-content-end">
                <li class="nav-item"><a href="service.php" class="nav-link px-2 text-muted">Services</a></li>
                <li class="nav-item"><a href="testimonial.php" class="nav-link px-2 text-muted">Testimonials</a></li>
                <li class="nav-item"><a href="contact_us.php" class="nav-link px-2 text-muted">Contact Us</a></li>
                <li class="nav-item"><a href="#" class="nav-link px-2 text-muted">About Devs</a></li>
              </ul>
            </footer>
          </div>
          <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/mdb-ui-kit/4.0.0/mdb.min.js"></script>
          <script src="js/bootstrap.bundle.js"></script>'
          .((strcmp(basename($_SERVER['PHP_SELF']),'register.php') == 0) ? '<script src="js/calculate.js"></script>' : '')
          .((strcmp(basename($_SERVER['PHP_SELF']),'day_details_edit.php' ) == 0) ? '<script src="js/day_details.js"></script>' : '')
          .((strcmp(basename($_SERVER['PHP_SELF']),'day_details.php' ) == 0) ? '<script src="js/day_details.search.js"></script>' : '')
          .((strcmp(basename($_SERVER['PHP_SELF']),'testimonial_manage.php' ) == 0) ? '<script src="js/approve.js"></script>' : '')
          .((strcmp(basename($_SERVER['PHP_SELF']),'testimonial_manage.php' ) == 0) ? '<script src="js/unapprove.js"></script>' : '')
          .((isset($_SESSION['accessLevel']) && $_SESSION['accessLevel']) ? '<script src="js/sidebar.js"></script>' : '').
        '</body>
      </html>';
    $footer = ob_get_contents();
    ob_end_clean();
?>
