<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>TechEducation</title>
        <meta content="width=device-width, initial-scale=1.0" name="viewport">
        <meta content="Free Website Template" name="keywords">
        <meta content="Free Website Template" name="description">

        <!-- Favicon -->
        <link href="img/favicon.ico" rel="icon">

        <!-- Google Font -->
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        
        <!-- CSS Libraries -->
        <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" rel="stylesheet">
        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
        <link href="lib/flaticon/font/flaticon.css" rel="stylesheet">
        <link href="lib/animate/animate.min.css" rel="stylesheet">
        <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

        <!-- Template Stylesheet -->
        <link href="css/style1.css" rel="stylesheet">
    </head>

    <body>
        
        <!-- Nav Bar Start -->
        @if (Route::has('login'))
        <div class="navbar navbar-expand-lg bg-dark navbar-dark">
            <div class="container-fluid">
                @auth
                <h1 class="nav-item nav-link">Dashboard</h1>
                @else
                <div class="collapse navbar-collapse justify-content-between" id="navbarCollapse">
                    <div class="navbar-nav ml-auto">
                        <a href="{{URL('')}}" class="nav-item nav-link active">Home</a>
                        <a href="{{route('about')}}" class="nav-item nav-link">About</a>
                        <a href="{{route('contact')}}" class="nav-item nav-link">Contact</a>
                        <a href="{{ route('login') }}"class="nav-item nav-link">Login</a>
                        @if (Route::has('register'))
                        <a href="{{ route('register') }}"class="nav-item nav-link">Register</a>
                    </div>
                </div>
                @endif
                @endauth
            </div>
        </div>
                @endif
        <!-- Nav Bar End -->


        <!-- Carousel Start -->
        <div class="carousel">
            <div class="container-fluid">
                <div class="owl-carousel">
                    <div class="carousel-item">
                        <div class="carousel-img">
                            <img src="img/carousel-1.jpg" alt="Image">
                        </div>
                        <div class="carousel-text">
                            <h1>Let's be kind for children</h1>
                            <p> nothing important than a child</p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="carousel-img">
                            <img src="img/carousel-2.jpg" alt="Image">
                        </div>
                        <div class="carousel-text">
                            <h1>Get Involved with helping hand</h1>
                            <p>it is great to help </p>
                        </div>
                    </div>
                    <div class="carousel-item">
                        <div class="carousel-img">
                            <img src="img/carousel-3.jpg" alt="Image">
                        </div>
                        <div class="carousel-text">
                            <h1>Bringing smiles to millions</h1>
                            <p>To be added </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>


 <!-- About Start -->
 <div class="about">
    <div class="container">
        <div class="row align-items-center">
           </div>
                <div class="about-tab">
                    <ul class="nav nav-pills nav-justified">
                       
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="pill" href="#tab-content-2">Mission</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" data-toggle="pill" href="#tab-content-3">Vision</a>
                        </li>
                    </ul>

                    <div class="tab-content">
                        <div id="tab-content-1" class="container tab-pane active">
                            Math challenges aim to foster critical thinking, problem-solving, and creativity in students. They encourage students to apply mathematical concepts to real-world scenarios.s Evaluate students’ knowledge, comprehension, and application of math concepts. Create a competitive yet supportive 
                            environment that motivates students to excel in Mathematics. Reinforce learning and enhance retention of key mathematical ideas.
                            Provide immediate feedback to students, helping them identify areas for improvement.
                        </div>
                       
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- About End -->

 <!-- Event Start -->
 <div class="event">
    <div class="container">
        <div class="section-header text-center">
            <p>Upcoming Events</p>
            <h2>Be ready for our upcoming Mathematics Challenges</h2>
        </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="event-item">
                    <img src="img/event-1.jpg" alt="Image">
                    <div class="event-content">
                        <div class="event-meta">
                            <p><i class="fa fa-calendar-alt"></i>01-Jan-45</p>
                            <p><i class="far fa-clock"></i>8:00 - 10:00</p>
                            <p><i class="fa fa-map-marker-alt"></i>Kenya</p>
                        </div>
                        <div class="event-text">
                            <h3>3rd Edition of Math Challenge</h3>
                            <p>
                                This Challenge will take place in Nairobi for all Primary Schools
                            </p>
                            <a class="btn btn-custom" href="">Join Now</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="event-item">
                    <img src="img/event-2.jpg" alt="Image">
                    <div class="event-content">
                        <div class="event-meta">
                            <p><i class="fa fa-calendar-alt"></i>01-Jan-45</p>
                            <p><i class="far fa-clock"></i>8:00 - 10:00</p>
                            <p><i class="fa fa-map-marker-alt"></i>Tanzania</p>
                        </div>
                        <div class="event-text">
                            <h3>4th Edition of Math Challenge </h3>
                            <p>
                                This Challenge will take place in Dodoma for all Primary Schools
                            </p>
                            <a class="btn btn-custom" href="">Join Now</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Event End -->


      <!-- Footer Start -->
        <div class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="footer-contact">
                            <h2>Our Head Office</h2>
                            <p><i class="fa fa-map-marker-alt"></i>Makerere Kikoni, Kampala City, Uganda</p>
                            <p><i class="fa fa-phone-alt"></i>+256 345 67890</p>
                            <p><i class="fa fa-envelope"></i>info@example.com</p>
                            <div class="footer-social">
                                <a class="btn btn-custom" href=""><i class="fab fa-twitter"></i></a>
                                <a class="btn btn-custom" href=""><i class="fab fa-facebook-f"></i></a>
                                <a class="btn btn-custom" href=""><i class="fab fa-youtube"></i></a>
                                <a class="btn btn-custom" href=""><i class="fab fa-instagram"></i></a>
                                <a class="btn btn-custom" href=""><i class="fab fa-linkedin-in"></i></a>
                            </div>
                        </div>
                    </div>
                  
                </div>
            </div>
            <div class="container copyright">
                <div class="row">
                    <div class="col-md-6">
                        <p>&copy; <a href="#">Make children Learn</a>, "YOU MAY DELAY,BUT TIME WILL NOT".</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Footer End -->
    
            
        
        
        <!-- Back to top button -->
        <a href="#" class="back-to-top"><i class="fa fa-chevron-up"></i></a>
        
        <!-- Pre Loader -->
        <div id="loader" class="show">
            <div class="loader"></div>
        </div>

        <!-- JavaScript Libraries -->
        <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/js/bootstrap.bundle.min.js"></script>
        <script src="lib/easing/easing.min.js"></script>
        <script src="lib/owlcarousel/owl.carousel.min.js"></script>
        <script src="lib/waypoints/waypoints.min.js"></script>
        <script src="lib/counterup/counterup.min.js"></script>
        <script src="lib/parallax/parallax.min.js"></script>
        
        <!-- Contact Javascript File -->
        <script src="mail/jqBootstrapValidation.min.js"></script>
        <script src="mail/contact.js"></script>

        <!-- Template Javascript -->
        <script src="js/main1.js"></script>
    </body>
</html>