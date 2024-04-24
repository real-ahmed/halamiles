<?php
header("Content-Type:text/css");
function checkhexcolor($color){
    return preg_match('/^#[a-f0-9]{6}$/i', $color);
}

if (isset($_GET['color']) AND $_GET['color'] != '') {
    $color = "#" . $_GET['color'];
}

if (!$color OR !checkhexcolor($color)) {
    $color = "#336699";
}

if (isset($_GET['secondColor']) AND $_GET['secondColor'] != '') {
    $secondColor = "#" . $_GET['secondColor'];
}

if (!$secondColor OR !checkhexcolor($secondColor)) {
    $secondColor = "#336699";
}


?>


.header .main-menu li a:hover, .header .main-menu li a:focus, .text--base, .progress-wrap::after, .preloader__circle i, .social-list li a, .coupon-copy-form input, .coupon-social-share li a:hover, .custom-icon-field .form--control:focus ~ i, a:hover, .forgot-pass{
  color: <?php echo $color; ?> !important;
}
.section--bg2{
    background-color: #4D3092 !important;
}

.dark--overlay::before, .preloader, .inner-hero::before {
    background-color: #4D3092 !important;
}
.header__top, .category-item, .testimonial-item, .contact-info .icon, .btn--base, .social-list li a:hover, body::-webkit-scrollbar-thumb, .custom--checkbox input:checked ~ label::before, .bg--base, .custom--file-upload::before, .custom--table thead th, .profile-thumb .avatar-edit label, .pagination .page-item.active .page-link, .pagination .page-item .page-link:hover, .dashboard__item .dashboard__icon, .post-share li a:hover, .custom--card .card-header {
    background-color: <?php echo $color; ?> !important;
}

.form--control:focus, .form-control:focus, .pagination .page-item.active .page-link, .pagination .page-item .page-link:hover, .dashboard__item, .post-share li a:hover, .border--base{
    border-color: <?php echo $color; ?> !important;
}

.social-list li a, .custom--checkbox label::before, .input-group-text {
    border: 1px solid <?php echo $color; ?>;
}

.progress-wrap{
    box-shadow: inset 0 0 0 2px <?php echo $color; ?>33;
}

.progress-wrap svg.progress-circle path {
  stroke: <?php echo $color; ?>;
}

.spinner {
    border-top: 4px <?php echo $color; ?> solid;
}

.hero-section__feature li::before, .category-item i, .counter-item__amount, .testimonial-item h6, .footer-inline-link li a:hover{
    color: <?php echo $secondColor; ?> !important;
}

.testimonial-item__quote::before, .testimonial-item__quote, .coupon-item__thumb .coupon-label, .coupon-copy-form .text-copy-btn{
background-color: <?php echo $secondColor; ?>;
}

.footer-section{
    background-color: <?php echo $color; ?>;
}

.preloader__circle::after {
    border: 3px solid <?php echo $secondColor; ?>;
}

