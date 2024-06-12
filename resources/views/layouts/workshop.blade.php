<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <meta name="description" content=""/>
    <link rel="canonical" href="" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="website" />
    <meta property="og:title" content="" />
    <meta property="og:description" content="" />
    <meta property="og:url" content="" />
    <meta property="og:site_name" content="" />
    <meta name="og:image" content=""/>
    <meta name="twitter:card" content="" />
    <meta name="twitter:description" content="" />
    <meta name="twitter:title" content="" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- Custom CSS -->
    <link href="{{ asset('public/css/brembo.css') }}" rel="stylesheet">
    <link href="{{ asset('public/css/mediaquery.css') }}" rel="stylesheet">
    <!-- Custom CSS -->

    <title>Brembo | Home</title>

    <!--favicon-->
    <link rel="shortcut icon" href="{{ asset('public/images/favicon.jpg') }}" />
    <!--favicon-->

    <!-- Add icon library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Add icon library -->

    <!--loading effect-->
    <link rel="stylesheet" href="{{ asset('public/css/loading_styles.css') }}" type="text/css" media="screen"/>
    <link rel="stylesheet" href="{{ asset('public/css/aos.css') }}" type="text/css" media="screen"/>
    <!--loading effect-->

    <!-- owl carousel -->
    <link href="{{ asset('public/owl/owl.carousel.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="{{ asset('public/owl/owl_css.css') }}">
    <!-- owl carousel -->

    <!--jarallax js & css-->
    <link href="{{ asset('public/jarallax/jarallax_css.css') }}" rel="stylesheet" type="text/css" media="screen">
    <!--jarallax js & css-->

    <!-- animate -->
    <link rel='stylesheet' href="{{ asset('public/css/animate.min.css') }}">
    <!-- animate -->


    <!--scroll bar style-->
    <style>

      ::-webkit-scrollbar {
        background: #000000;
        height: 5px;
        width: 5px;
      }

      ::-webkit-scrollbar-track {
        box-shadow: inset 0 0 2px #E43038;
      }

      ::-webkit-scrollbar-thumb {
        background: #E43038;
        border-radius: 2px;
      }

      ::-webkit-scrollbar-thumb:hover {
        background: #E43038; 
      }
    </style>
     {{ $styles ?? '' }}
    <!--scroll bar style-->


  </head>
  <body style="background-color: #F1F1F1;">
        <!-- Product Cart Start -->
        <a href="{{ route('workshop.products-cart'); }}">
      <div class="cart_icon">
        <div class="position-relative">
          <i class="fa fa-shopping-cart" aria-hidden="true"></i>
          <div class="position-absolute cart_count">
            <div>
              <p class="text-white" id="no_cart_items">
              <?php 
                  echo  Exam::cart_item_count();
              ?>
              </p>
            </div>     
          </div>
        </div>
      </div>
    </a>
    <!-- Product Cart End -->
    <div class="tot_points text-center">
      <img class="mb-1" src="{{ asset('public/images/reward.png') }}" alt="" style="width: 20px;">
      <h3 class="mb-0">
        <?php 
            echo  Exam::get_all_points();
        ?>
      </h3>
      <p style="font-size: 12px;" class="mb-0 text-white">Points</p>
    </div>

    <div class="container-fluid main_header" style="background-image: url({{ asset('public/images/banner_bg.jpg') }}); ">
      <div class="container">
        <div class="row header_user">
          <div class="offset-xl-9 col-xl-3 offset-lg-8 col-lg-4 offset-md-8 col-md-4 offset-4 col-8">
            <div class="row align-items-center">
              <div class="col-xxl-6 col-lg-7 col-8 border_right">
                <div class="d-flex align-items-center gap-2">
                  <div>
                    <img src="{{ asset('public/images/user.png') }}" alt="" class="m-auto w-100">
                  </div>
                  <div>
                    <p class="mb-0 text-white">Hi.{{ auth()->user()->name }}</p>
                    <p class="mb-0"><a href="{{ route('workshop.profile') }}" class="fst-italic text-white">Edit Profile</a></p>
                  </div>
                </div>
              </div>
              <div class="col-xxl-6 col-lg-5 col-4">
                    <form method="POST" action="">
                        @csrf
                        <p class="mb-0"><a href="route('workshop/logout')" id="logout" class="text-white">Logout</a></p>   
                    </form>
                
              </div>
            </div>
          </div>
        </div>

        <div class="row align-items-center header_title">
          <div class="col-lg-2 header_left_img d-lg-block d-none">
            <img src="{{ asset('public/images/brembo_kit.png') }}" class="m-auto w-100" alt="">
          </div>
          <div class="col-lg-6">
            <div class="top_logo mb-2">
              <img class="m-auto" src="{{ asset('public/images/logo_white.svg') }}" alt="">
            </div>

            <h2 class="text-white">Only the top range models have Brembo calipers</h2>
            <p class="text-white">Brembo produces high-tech brake calipers installed as original equipment in the leading car models in every category.</p>
          </div>
        </div>
      </div>
    </div>

    <br>
    {{ $slot }}

    <div class="container-fluid py-2 bg-dark text-center">
      <p class="fst-italic mb-0" style="color: #858585;">Copyright © 2023 Brembo. All rights reserved.</p>
    </div>


    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="{{ asset('public/js/jquery-3.2.1.min.js') }}"></script>
      <script src="{{ asset('public/js/popper.min.js') }}" ></script> 
      <script src="{{ asset('public/js/bootstrap.min.js') }}" ></script>

      <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.3/jquery.validate.min.js"></script>
    <!--loading effects-->
    <script src="{{ asset('public/js/aos.js') }}"></script>

    <script>
      AOS.init({
      easing: 'ease-out-back',
              duration: 1000
      });
    </script>
    <!--loading effects-->

    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
        <script>
            $('#logout').on('click', function(event) {
                event.preventDefault();
                const url = '<?php echo  url('/')  ?>';
                var id = $(this).val();
                swal({
                    title: 'Are you sure you want to logout?',
                    //text: 'This meterial will be permanantly deleted!',
                    //icon: 'warning',
                    buttons: ["No", "Yes!"],
                }).then(function(value) {
                    if (value == true) {
                        window.location.replace(url+"/workshop/login");
                    }
                });
            });
        </script>
    <!-- owl carousel -->
    <script src="{{ asset('public/owl/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('public/owl/owl_js.js') }}"></script>
    <!-- owl carousel -->

    <!--jarallax js-->
    <script src="{{ asset('public/jarallax/jarallax_js.js') }}"></script>
    <!--jarallax js-->

    <!--jarallax-->
    <script type="text/javascript">
        /* init Jarallax */
        $('.jarallax').jarallax({
            speed: 0.5,
            imgWidth: 1366,
            imgHeight: 768
        })
    </script>
    <!--jarallax-->

    <!-- scroll top -->
    <script type="module">
      import ScrollTop from 'https://cdn.skypack.dev/smooth-scroll-top';
      const scrollTop = new ScrollTop();
      scrollTop.init();
        </script>
    <!-- scroll top -->
    {{ $scripts ?? '' }}
  </body>
</html>


