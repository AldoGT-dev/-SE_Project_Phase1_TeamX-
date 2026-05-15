<?php 
    #PHP INCLUDES
    include "connect.php";
    include "Includes/templates/header.php";
    include "Includes/templates/navbar.php";
?>

<!-- Home Section -->
<section class = "home_section">
    <div class="section-header">
        <div class="section-title" style = "font-size:50px; color:white">
            Find Best Car & Limousine
        </div>
        <hr class="separator">
        <div class="section-tagline">
            From as low as $10 per day with limited time offer discounts
        </div>                  
    </div>
</section>



<!-- Our Services Section -->
<section class = "our-services" id = "services">
    <div class = "container">
        <div class="section-header">
            <div class="section-title">
                What Services we offer to our clients
            </div>
            <hr class="separator">
            <div class="section-tagline">
                Who are in extremely love with eco friendly system.
            </div>
        </div>
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="single-feature">
                    <h4>
                        <span>
                            <i class="far fa-user"></i>
                        </span>
                        Expert Technicians
                    </h4>
                    <p>
                        Usage of the Internet is becoming more common due to rapid advancement of technology and power.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="single-feature">
                    <h4>
                        <span>
                            <i class="fas fa-certificate"></i>
                        </span>
                        Professional Service
                    </h4>
                    <p>
                        Usage of the Internet is becoming more common due to rapid advancement of technology and power.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="single-feature">
                    <h4>
                        <span>
                            <i class="fas fa-phone-alt"></i>
                        </span>
                        Great Support
                    </h4>
                    <p>
                        Usage of the Internet is becoming more common due to rapid advancement of technology and power.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="single-feature">
                    <h4>
                        <span>
                            <i class="fas fa-rocket"></i>
                        </span>
                        Technical Skills
                    </h4>
                    <p>
                        Usage of the Internet is becoming more common due to rapid advancement of technology and power.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="single-feature">
                    <h4>
                        <span>
                            <i class="fas fa-gem"></i>
                        </span>
                        Highly Recomended
                    </h4>
                    <p>
                        Usage of the Internet is becoming more common due to rapid advancement of technology and power.
                    </p>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="single-feature">
                    <h4>
                        <span>
                            <i class="far fa-comments"></i>
                        </span>
                        Positive Reviews
                    </h4>
                    <p>
                        Usage of the Internet is becoming more common due to rapid advancement of technology and power.
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Area Section -->
<section class = "about-area">
    <div class = "container-fluid">
        <div class = "row">
            <div class = "col-md-6 left-area" style = "padding:0px">
                <img src="Design/images/about-img.jpg" alt="Car Rental Image">
            </div>
            <div class = "col-md-6 right-area" style = "padding:50px">
                <h1>
                    Globally Connected <br>
                    by Large Network
                </h1>
                <p>
                    <span>
                        We are here to listen from you deliver exellence
                    </span>
                </p>
                <p>
                    Best of the bests.
                </p>
                <a class="my-btn bttn" href="#">get details</a>
            </div>
        </div>
    </div>
</section>

<!-- Our Brands Section -->
<section class = "our-brands" id = "brands">
    <div class = "container">
        <div class="section-header">
            <div class="section-title">
                First Class Car Rental & Limousine Services
            </div>
            <hr class="separator">
            <div class="section-tagline">
                We offer professional car rental & limousine services in our range of high-end vehicles
            </div>
        </div>
        <div class = "car-brands">
            <div class = "row">
            <?php

                $stmt = $con->prepare("Select * from car_brands");
                $stmt->execute();
                $car_brands = $stmt->fetchAll();

                foreach($car_brands as $car_brand)
                {
                    $car_brand_img = "admin/Uploads/images/".$car_brand['brand_image'];
                    ?>
                    <div class = "col-md-4">
                        <div class = "car-brand" style = "background-image: url(<?php echo $car_brand_img ?>);">
                            <div class = "brand_name">
                                <h3>
                                    <?php echo $car_brand['brand_name']; ?>
                                </h3>
                                <button class="btn btn-primary view-details-btn" data-brand-id="<?php echo $car_brand['brand_id']; ?>">
                                    View Details
                                </button>
                            </div>
                        </div>
                    </div>
                    <?php
                }

            ?>
            </div>
        </div>
    </div>
</section>

<!-- Car Details Modal -->
<div class="modal fade" id="carDetailsModal" tabindex="-1" role="dialog" aria-labelledby="carDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="carDetailsModalLabel">Car Details</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div id="carsContainer">
                    <!-- Car details will be loaded here dynamically -->
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">Loading...</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- CAR RESERVATION SECTION -->
<section class="reservation_section" style = "padding:50px 0px" id = "reserve">
    <div class="container">
        <div class = "row">
            <div class = "col-md-5"></div>
            <div class = "col-md-7">
                <form method="POST" action = "reserve.php" class = "car-reservation-form" id = "reservation_form" v-on:submit = "checkForm">
                    <div class="text_header">
                        <span>
                            Find your car
                        </span>
                    </div>
                    <div>
                        <div class = "form-group">
                            <label for="pickup_location">Pickup Location</label>
                            <input type = "text" class = "form-control" name = "pickup_location" placeholder = "34 Mainfield Road" v-model = 'pickup_location'>
                            <div class="invalid-feedback" style = "display:block" v-if="pickup_location === null">
                                Pickup location is required
                            </div>
                        </div>
                        <div class = "form-group">
                            <label for="return_location">Return Location</label>
                            <input type = "text" class = "form-control" name = "return_location" placeholder = "34 Mainfield Road" v-model = 'return_location'>
                            <div class="invalid-feedback" style = "display:block" v-if="return_location === null">
                                Return location is required
                            </div>
                        </div>
                        <div class = "form-group">
                            <label for="pickup_date">Pickup Date</label>
                            <input type = "date" min = "<?php echo date('Y-m-d', strtotime("+1 day"))?>" name = "pickup_date" class = "form-control" v-model = 'pickup_date'>
                            <div class="invalid-feedback" style = "display:block" v-if="pickup_date === null">
                                Pickup date is required
                            </div>
                        </div>
                        <div class = "form-group">
                            <label for="return_date">Return Date</label>
                            <input type = "date" min = "<?php echo date('Y-m-d', strtotime("+2 day"))?>" name = "return_date"  class = "form-control" v-model = 'return_date'>
                            <div class="invalid-feedback" style = "display:block" v-if="return_date === null">
                                Return date is required
                            </div>
                        </div>
                        <input type="hidden" name="car_id" id="selected_car_id" value="">
                        <!-- Submit Button -->
                        <button type="submit" class="btn sbmt-bttn" name = "reserve_car">Book Instantly</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- CONTACT SECTION -->

<section class="contact-section" id="contact-us">
    <div class="container">
        <div class="row">
            <div class="col-lg-6 sm-padding">
                <div class="contact-info">
                    <h2>
                        Get in touch with us & 
                        <br>send us message today!
                    </h2>
                    <p>
                        Getting dressed up and traveling with good friends makes for a shared, unforgettable experience.
                    </p>
                    <h3>
                        Epoka 
                        <br>
                        University, tirane
                    </h3>
                    <ul>
                        <li>
                            <span style = "font-weight: bold">Email:</span> 
                            info@epoka.com
                        </li>
                        <li>
                            <span style = "font-weight: bold">Phone:</span> 
                            +355 6978 76 124
                        </li>
                        <li>
                            <span style = "font-weight: bold">Fax:</span> 
                            +355 6978 76 124
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6 sm-padding">
                <div class="contact-form">
                    <div id="contact_ajax_form" class="contactForm">
                        <div class="form-group colum-row row">
                            <div class="col-sm-6">
                                <input type="text" id="contact_name" name="name" class="form-control" placeholder="Name">
                            </div>
                            <div class="col-sm-6">
                                <input type="email" id="contact_email" name="email" class="form-control" placeholder="Email">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <input type="text" id="contact_subject" name="subject" class="form-control" placeholder="Subject">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <textarea id="contact_message" name="message" cols="30" rows="5" class="form-control message" placeholder="Message"></textarea>
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-md-12">
                                <button id="contact_send" class="contact_send_btn">Send Message</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer Section -->
<section class="widget_section">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="footer_widget">
                    <a class="navbar-brand" href="">
                        Epoka<span style = "color:#04DBC0">Uni</span>&nbsp;CarRental
                    </a>
                    <p>
                        Getting dressed up and traveling with good friends makes for a shared, unforgettable experience.
                    </p>
                    <ul class="widget_social">
                        <li><a href="#" data-toggle="tooltip" title="Facebook"><i class="fab fa-facebook-f fa-2x"></i></a></li>
                        <li><a href="#" data-toggle="tooltip" title="Twitter"><i class="fab fa-twitter fa-2x"></i></a></li>
                        <li><a href="#" data-toggle="tooltip" title="Instagram"><i class="fab fa-instagram fa-2x"></i></a></li>
                        <li><a href="#" data-toggle="tooltip" title="LinkedIn"><i class="fab fa-linkedin fa-2x"></i></a></li>
                        <li><a href="#" data-toggle="tooltip" title="Google+"><i class="fab fa-google-plus-g fa-2x"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="footer_widget">
                    <h3>Contact Info</h3>
                    <ul class = "contact_info">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>Epoka, Tirane, Albania
                        </li>
                        <li>
                            <i class="far fa-envelope"></i>info@epoka.com
                        </li>
                        <li>
                            <i class="fas fa-mobile-alt"></i>+355 69 12 145
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="footer_widget">
                    <h3>Newsletter</h3>
                    <p style = "margin-bottom:0px">Don't miss a thing! Sign up to receive daily deals</p>
                    <div class="subscribe_form">
                        <form action="#" class="subscribe_form" novalidate="true">
                            <input type="email" name="EMAIL" id="subs-email" class="form_input" placeholder="Email Address...">
                            <button type="submit" class="submit">SUBSCRIBE</button>
                            <div class="clearfix"></div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- BOTTOM FOOTER -->
<?php include "Includes/templates/footer.php"; ?>

<!-- Add this JavaScript code before the closing </body> tag -->
<script>
// Vue instance for reservation form
new Vue({
    el: "#reservation_form",
    data: {
        pickup_location: '',
        return_location: '',
        pickup_date: '',
        return_date: ''
    },
    methods:{
        checkForm: function(event){
            if( this.pickup_location && this.return_location && this.pickup_date && this.return_date)
            {
                return true;
            }
            
            if (!this.pickup_location)
            {
                this.pickup_location = null;
            }

            if (!this.return_location)
            {
                this.return_location = null;
            }

            if (!this.pickup_date)
            {
                this.pickup_date = null;
            }

            if (!this.return_date)
            {
                this.return_date = null;
            }
            
            event.preventDefault();
        },
    }
});

// Car brand details functionality
$(document).ready(function() {
    // Handle click on "View Details" button
    $('.view-details-btn').click(function() {
        const brandId = $(this).data('brand-id');
        
        // Show modal
        $('#carDetailsModal').modal('show');
        
        // Fetch car details for the selected brand via AJAX
        $.ajax({
            url: 'get_cars_by_brand.php',
            type: 'GET',
            data: { brand_id: brandId },
            success: function(response) {
                // Update modal content with car details
                $('#carsContainer').html(response);
                
                // Initialize any event handlers for the newly loaded content
                initializeCarDetailsEvents();
            },
            error: function() {
                $('#carsContainer').html('<div class="alert alert-danger">Error loading car details. Please try again.</div>');
            }
        });
    });
    
    // Function to initialize events for car details content
    function initializeCarDetailsEvents() {
        // Handle click on "Reserve" buttons in the modal
        $('.reserve-car-btn').click(function() {
            const carId = $(this).data('car-id');
            
            // Set the selected car ID in the reservation form
            $('#selected_car_id').val(carId);
            
            // Close the modal
            $('#carDetailsModal').modal('hide');
            
            // Scroll to the reservation form
            $('html, body').animate({
                scrollTop: $("#reservation_form").offset().top
            }, 1000);
            
            // Flash the reservation form to draw attention
            $("#reservation_form").addClass('flash-highlight');
            setTimeout(function() {
                $("#reservation_form").removeClass('flash-highlight');
            }, 1500);
        });
    }
});
</script>

<style>
/* Styles for the car brands and modal */
.car-brand {
    position: relative;
    height: 200px;
    background-size: cover;
    background-position: center;
    margin-bottom: 30px;
    border-radius: 5px;
    overflow: hidden;
}

.brand_name {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    padding: 15px;
    background-color: rgba(0, 0, 0, 0.7);
    color: white;
    text-align: center;
}

.view-details-btn {
    margin-top: 10px;
}

.car-card {
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
}

.car-card img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.car-info {
    padding: 15px;
}

.car-features {
    list-style: none;
    padding-left: 0;
}

.car-features li {
    margin-bottom: 5px;
}

.car-price {
    font-weight: bold;
    font-size: 1.2em;
    color: #04DBC0;
}

.flash-highlight {
    animation: flashHighlight 1.5s;
}

@keyframes flashHighlight {
    0% { box-shadow: 0 0 0 0 rgba(4, 219, 192, 0.7); }
    50% { box-shadow: 0 0 10px 10px rgba(4, 219, 192, 0.7); }
    100% { box-shadow: 0 0 0 0 rgba(4, 219, 192, 0.7); }
}
</style>