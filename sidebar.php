<?php
print ' <div class="position-fixed top-50 end-0 translate-middle-y d-flex justify-content-start">
            <ul class="d-flex flex-column rounded-pill p-0">
                <span class="border-end border-secondary" id="toggler" data-bs-toggle="tooltip" data-bs-placement="left" title="Admin tools">
                    <button class="btn bg-light border-0 rounded-pill rounded-end shadow-none fs-5" data-bs-toggle="collapse" data-bs-target="#collapse-sidebar" role="button">
                        <i class="bi bi-layout-sidebar-inset-reverse" id="icon" data-bs-toggle="offcanvas" data-bs-target="#offcanvas"></i>
                    </button>
                </span>
            </ul>
            <div class="collapse collapse-horizontal" id="collapse-sidebar">
                <div class="d-flex flex-column bg-light rounded-pill p-0">
                    <span class="rounded-pill rounded-start bg-light hover-bottom" data-bs-toggle="tooltip" data-bs-placement="left" title="Edit home page">
                        <a href="index_edit.php" class="btn bg-transparent border-0 shadow-none fs-5" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" role="button">
                            <i class="bi bi-journal-plus" data-bs-toggle="offcanvas" data-bs-target="#offcanvas"></i>
                        </a>
                    </span>
                    <span class="hover-bottom hover-top" data-bs-toggle="tooltip" data-bs-placement="left" title="Edit child registrations">
                        <a href="registration_edit.php" class="btn bg-light border-0 shadow-none fs-5" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" role="button">
                            <i class="bi bi-people" data-bs-toggle="offcanvas" data-bs-target="#offcanvas"></i>
                        </a>
                    </span>
                    <span class="hover-bottom hover-top" data-bs-toggle="tooltip" data-bs-placement="left" title="Edit daily details">
                        <a href="day_details_edit.php" class="btn bg-light border-0 shadow-none fs-5" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" role="button">
                            <i class="bi bi-cloud-sun" data-bs-toggle="offcanvas" data-bs-target="#offcanvas"></i>
                        </a>
                    </span>
                    <span class="hover-bottom hover-top" data-bs-toggle="tooltip" data-bs-placement="left" title="Approve testimonials">
                        <a href="testimonial_manage.php" class="btn bg-light border-0 shadow-none fs-5" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" role="button">
                            <i class="bi bi-chat-square-text" data-bs-toggle="offcanvas" data-bs-target="#offcanvas"></i>
                        </a>
                    </span>
                    <span class="hover-bottom hover-top" data-bs-toggle="tooltip" data-bs-placement="left" title="See messages">    
                        <a href="contact_us_manage.php" class="btn bg-light border-0 shadow-none fs-5" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" role="button">
                            <i class="bi bi-envelope-check" data-bs-toggle="offcanvas" data-bs-target="#offcanvas"></i>
                        </a>
                    </span>
                    <hr class="w-75 my-2 m-auto dropdown-divider">
                    <span class="rounded-pill rounded-top hover-top" data-bs-toggle="tooltip" data-bs-placement="left" title="Add an admin">
                        <a href="ADDmin.php" class="btn bg-transparent border-0 shadow-none fs-5" data-bs-toggle="offcanvas" data-bs-target="#offcanvas" role="button">
                            <i class="bi bi-person-plus" data-bs-toggle="offcanvas" data-bs-target="#offcanvas"></i>
                        </a>
                    </span>
                </div>
            </div>
        </div>';
?>