<?php
// get_cars_by_brand.php - Script to fetch cars by brand ID and return HTML content

// Include database connection
include "connect.php";

// Get brand ID from request
$brand_id = isset($_GET['brand_id']) ? intval($_GET['brand_id']) : 0;

if ($brand_id <= 0) {
    echo '<div class="alert alert-danger">Invalid brand ID.</div>';
    exit;
}

try {
    // Get brand name first
    $brandStmt = $con->prepare("SELECT brand_name FROM car_brands WHERE brand_id = ?");
    $brandStmt->execute([$brand_id]);
    $brand = $brandStmt->fetch(PDO::FETCH_ASSOC);

    if (!$brand) {
        echo '<div class="alert alert-danger">Brand not found.</div>';
        exit;
    }

    // Get cars for this brand
    $carsStmt = $con->prepare("
        SELECT c.*, ct.type_label 
        FROM cars c
        JOIN car_types ct ON c.type_id = ct.type_id
        WHERE c.brand_id = ?
        ORDER BY c.car_name
    ");
    $carsStmt->execute([$brand_id]);
    $cars = $carsStmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($cars) === 0) {
        echo '<div class="alert alert-warning">No cars available for ' . htmlspecialchars($brand['brand_name']) . '.</div>';
        exit;
    }

    // Display cars
    echo '<h3>' . htmlspecialchars($brand['brand_name']) . ' Cars</h3>';
    echo '<div class="row">';

    foreach ($cars as $car) {
        // Get primary image for this car
        $imageStmt = $con->prepare("
            SELECT image_path 
            FROM car_images 
            WHERE car_id = ? AND is_primary = 1
            LIMIT 1
        ");
        $imageStmt->execute([$car['id']]);
        $image = $imageStmt->fetch(PDO::FETCH_ASSOC);
        
        $imagePath = $image ? 'admin/Uploads/images/' . $image['image_path'] : 'Design/images/car-placeholder.jpg';
        
        // Get features for this car
        $featuresStmt = $con->prepare("
            SELECT feature_name, feature_value 
            FROM car_features 
            WHERE car_id = ?
            LIMIT 4
        ");
        $featuresStmt->execute([$car['id']]);
        $features = $featuresStmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Check if car is available
        $availabilityClass = $car['available'] ? 'text-success' : 'text-danger';
        $availabilityText = $car['available'] ? 'Available' : 'Not Available';
        $buttonDisabled = $car['available'] ? '' : 'disabled';
        
        // Output car card
        echo '
        <div class="col-md-6">
            <div class="car-card">
                <img src="' . $imagePath . '" alt="' . htmlspecialchars($car['car_name']) . '">
                <div class="car-info">
                    <h4>' . htmlspecialchars($car['car_name']) . '</h4>
                    <p class="text-muted">' . htmlspecialchars($car['model']) . ' ' . htmlspecialchars($car['color']) . ' ' . htmlspecialchars($car['type_label']) . '</p>
                    
                    <p>' . htmlspecialchars(substr($car['description'], 0, 100)) . '...</p>
                    
                    <ul class="car-features">';
        
        // Add features if available
        foreach ($features as $feature) {
            echo '<li><strong>' . htmlspecialchars($feature['feature_name']) . ':</strong> ' . htmlspecialchars($feature['feature_value']) . '</li>';
        }
        
        echo '
                        <li><strong>Transmission:</strong> ' . htmlspecialchars($car['transmission']) . '</li>
                        <li><strong>Fuel Type:</strong> ' . htmlspecialchars($car['fuel_type']) . '</li>
                        <li><strong>Seats:</strong> ' . htmlspecialchars($car['seats']) . '</li>
                    </ul>
                    
                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="car-price">
                            <div>$' . number_format($car['daily_rate'], 2) . ' / day</div>
                            <div class="' . $availabilityClass . '">' . $availabilityText . '</div>
                        </div>
                        <button class="btn btn-primary reserve-car-btn" data-car-id="' . $car['id'] . '" ' . $buttonDisabled . '>
                            Reserve Now
                        </button>
                    </div>
                </div>
            </div>
        </div>';
    }
    
    echo '</div>';

} catch (PDOException $e) {
    echo '<div class="alert alert-danger">Database error: ' . $e->getMessage() . '</div>';
}