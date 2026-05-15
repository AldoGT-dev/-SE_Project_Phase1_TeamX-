<?php
	session_start();
	
	// Check if user is logged in
	if(!isset($_SESSION['username_yahya_car_rental']) || !isset($_SESSION['password_yahya_car_rental'])) {
		header('Location: index.php');
		exit();
	}
	
	$pageTitle = 'Payment Management';
	include 'connect.php';
	include 'Includes/functions/functions.php';
	include 'Includes/templates/header.php';
?>

<!-- Content Wrapper -->
<div id="content-wrapper" class="d-flex flex-column">
	<!-- Main Content -->
	<div id="content">
		<!-- Begin Page Content -->
		<div class="container-fluid">
			<!-- Page Heading -->
			<div class="d-sm-flex align-items-center justify-content-between mb-4">
				<h1 class="h3 mb-0 text-gray-800">Payments Management</h1>
				<a href="#" class="d-none d-sm-inline-block btn btn-sm btn-primary shadow-sm" data-toggle="modal" data-target="#addPaymentModal">
					<i class="fas fa-plus fa-sm text-white-50"></i> Add New Payment
				</a>
			</div>

			<!-- Payments List -->
			<div class="card shadow mb-4">
				<div class="card-header py-3">
					<h6 class="m0 font-weight-bold text-primary">Payment Records</h6>
				</div>
				<div class="card-body">
					<div class="table-responsive">
						<table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
							<thead>
								<tr>
									<th>ID</th>
									<th>Reservation ID</th>
									<th>Client</th>
									<th>Car</th>
									<th>Amount</th>
									<th>Payment Date</th>
									<th>Method</th>
									<th>Transaction ID</th>
									<th>Status</th>
									<th>Actions</th>
								</tr>
							</thead>
							<tbody>
								<?php
									// Get all payments with related information
									$stmt = $con->prepare("
										SELECT 
											p.*, 
											r.pickup_date, 
											r.return_date,
											c.full_name AS client_name,
											cars.car_name
										FROM 
											payments p
										JOIN 
											reservations r ON p.reservation_id = r.reservation_id
										JOIN 
											clients c ON r.client_id = c.client_id
										JOIN 
											cars ON r.car_id = cars.id
										ORDER BY 
											p.payment_date DESC
									");
									$stmt->execute();
									$rows = $stmt->fetchAll();

									foreach($rows as $row) {
										echo "<tr>";
										echo "<td>" . $row['payment_id'] . "</td>";
										echo "<td>" . $row['reservation_id'] . "</td>";
										echo "<td>" . $row['client_name'] . "</td>";
										echo "<td>" . $row['car_name'] . "</td>";
										echo "<td>$" . $row['amount'] . "</td>";
										echo "<td>" . date('Y-m-d H:i', strtotime($row['payment_date'])) . "</td>";
										echo "<td>" . $row['payment_method'] . "</td>";
										echo "<td>" . $row['transaction_id'] . "</td>";
										echo "<td>";
										// Format status with colored badge
										switch($row['status']) {
											case 'Completed':
												echo "<span class='badge badge-success'>Completed</span>";
												break;
											case 'Pending':
												echo "<span class='badge badge-warning'>Pending</span>";
												break;
											case 'Failed':
												echo "<span class='badge badge-danger'>Failed</span>";
												break;
											case 'Refunded':
												echo "<span class='badge badge-info'>Refunded</span>";
												break;
										}
										echo "</td>";
										echo "<td>
											<a href='#' class='btn btn-sm btn-info edit-btn' data-id='" . $row['payment_id'] . "' data-toggle='modal' data-target='#editPaymentModal'>
												<i class='fas fa-edit'></i> Edit
											</a>
											<a href='#' class='btn btn-sm btn-danger delete-btn' data-id='" . $row['payment_id'] . "' data-toggle='modal' data-target='#deletePaymentModal'>
												<i class='fas fa-trash'></i> Delete
											</a>
											<a href='#' class='btn btn-sm btn-success view-btn' data-id='" . $row['payment_id'] . "' data-toggle='modal' data-target='#viewPaymentModal'>
												<i class='fas fa-eye'></i> View
											</a>
										</td>";
										echo "</tr>";
									}
								?>
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
		<!-- /.container-fluid -->
	</div>
	<!-- End of Main Content -->


</div>
<!-- End of Content Wrapper -->

<!-- Add Payment Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1" role="dialog" aria-labelledby="addPaymentModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="addPaymentModalLabel">Add New Payment</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="payments.php" method="POST">
				<div class="modal-body">
					<div class="form-group">
						<label for="reservation_id">Reservation</label>
						<select class="form-control" id="reservation_id" name="reservation_id" required>
							<option value="">Select Reservation</option>
							<?php
								// Get active reservations that are not fully paid
								$stmt = $con->prepare("
									SELECT 
										r.reservation_id, 
										r.pickup_date, 
										r.return_date, 
										r.total_amount,
										c.full_name AS client_name,
										cars.car_name,
										COALESCE(SUM(p.amount), 0) as paid_amount
									FROM 
										reservations r
									JOIN 
										clients c ON r.client_id = c.client_id
									JOIN 
										cars ON r.car_id = cars.id
									LEFT JOIN 
										payments p ON r.reservation_id = p.reservation_id AND p.status = 'Completed'
									WHERE 
										r.reservation_status IN ('Pending', 'Confirmed', 'Active')
									GROUP BY 
										r.reservation_id
									HAVING 
										paid_amount < r.total_amount OR paid_amount IS NULL
								");
								$stmt->execute();
								$reservations = $stmt->fetchAll();
								
								foreach($reservations as $reservation) {
									$remaining = $reservation['total_amount'] - $reservation['paid_amount'];
									echo "<option value='" . $reservation['reservation_id'] . "' data-remaining='" . $remaining . "'>" 
										. $reservation['reservation_id'] . " - " 
										. $reservation['client_name'] . " - " 
										. $reservation['car_name'] . " ("
										. date('Y-m-d', strtotime($reservation['pickup_date'])) . " to " 
										. date('Y-m-d', strtotime($reservation['return_date'])) . ") - Remaining: $" 
										. $remaining . "</option>";
								}
							?>
						</select>
					</div>
					<div class="form-group">
						<label for="amount">Amount</label>
						<input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
						<small class="form-text text-muted">Remaining amount to be paid: <span id="remaining-amount">Select a reservation</span></small>
					</div>
					<div class="form-group">
						<label for="payment_method">Payment Method</label>
						<select class="form-control" id="payment_method" name="payment_method" required>
							<option value="Credit Card">Credit Card</option>
							<option value="Debit Card">Debit Card</option>
							<option value="Cash">Cash</option>
							<option value="Bank Transfer">Bank Transfer</option>
						</select>
					</div>
					<div class="form-group">
						<label for="transaction_id">Transaction ID</label>
						<input type="text" class="form-control" id="transaction_id" name="transaction_id">
						<small class="form-text text-muted">Required for card payments and transfers</small>
					</div>
					<div class="form-group">
						<label for="status">Status</label>
						<select class="form-control" id="status" name="status" required>
							<option value="Completed">Completed</option>
							<option value="Pending">Pending</option>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" name="add_payment" class="btn btn-primary">Add Payment</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Edit Payment Modal -->
<div class="modal fade" id="editPaymentModal" tabindex="-1" role="dialog" aria-labelledby="editPaymentModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="editPaymentModalLabel">Edit Payment</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="payments.php" method="POST">
				<input type="hidden" name="payment_id" id="edit_payment_id">
				<div class="modal-body">
					<div class="form-group">
						<label for="edit_reservation_id">Reservation ID</label>
						<input type="text" class="form-control" id="edit_reservation_id" name="edit_reservation_id" readonly>
					</div>
					<div class="form-group">
						<label for="edit_amount">Amount</label>
						<input type="number" step="0.01" class="form-control" id="edit_amount" name="amount" required>
					</div>
					<div class="form-group">
						<label for="edit_payment_method">Payment Method</label>
						<select class="form-control" id="edit_payment_method" name="payment_method" required>
							<option value="Credit Card">Credit Card</option>
							<option value="Debit Card">Debit Card</option>
							<option value="Cash">Cash</option>
							<option value="Bank Transfer">Bank Transfer</option>
						</select>
					</div>
					<div class="form-group">
						<label for="edit_transaction_id">Transaction ID</label>
						<input type="text" class="form-control" id="edit_transaction_id" name="transaction_id">
					</div>
					<div class="form-group">
						<label for="edit_status">Status</label>
						<select class="form-control" id="edit_status" name="status" required>
							<option value="Completed">Completed</option>
							<option value="Pending">Pending</option>
							<option value="Failed">Failed</option>
							<option value="Refunded">Refunded</option>
						</select>
					</div>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
					<button type="submit" name="update_payment" class="btn btn-primary">Update Payment</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- Delete Payment Modal -->
<div class="modal fade" id="deletePaymentModal" tabindex="-1" role="dialog" aria-labelledby="deletePaymentModalLabel" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="deletePaymentModalLabel">Delete Payment</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<form action="payments.php" method="POST">
				<input type="hidden" name="payment_id" id="delete_payment_id">
				<div class="modal-body">
					<p>Are you sure you want to delete this payment record? This action cannot be undone.</p>
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
					<button type="submit" name="delete_payment" class="btn btn-danger">Delete</button>
				</div>
			</form>
		</div>
	</div>
</div>

<!-- View Payment Modal -->
<div class="modal fade" id="viewPaymentModal" tabindex="-1" role="dialog" aria-labelledby="viewPaymentModalLabel" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="viewPaymentModalLabel">Payment Details</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">
				<div class="row">
					<div class="col-md-6">
						<h6 class="font-weight-bold">Payment Information</h6>
						<p><strong>Payment ID:</strong> <span id="view_payment_id"></span></p>
						<p><strong>Amount:</strong> $<span id="view_amount"></span></p>
						<p><strong>Payment Date:</strong> <span id="view_payment_date"></span></p>
						<p><strong>Method:</strong> <span id="view_payment_method"></span></p>
						<p><strong>Transaction ID:</strong> <span id="view_transaction_id"></span></p>
						<p><strong>Status:</strong> <span id="view_status"></span></p>
					</div>
					<div class="col-md-6">
						<h6 class="font-weight-bold">Reservation Details</h6>
						<p><strong>Reservation ID:</strong> <span id="view_reservation_id"></span></p>
						<p><strong>Client:</strong> <span id="view_client_name"></span></p>
						<p><strong>Car:</strong> <span id="view_car_name"></span></p>
						<p><strong>Pickup Date:</strong> <span id="view_pickup_date"></span></p>
						<p><strong>Return Date:</strong> <span id="view_return_date"></span></p>
						<p><strong>Total Amount:</strong> $<span id="view_total_amount"></span></p>
					</div>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
				<a id="print_receipt" href="#" class="btn btn-primary"><i class="fas fa-print"></i> Print Receipt</a>
			</div>
		</div>
	</div>
</div>

<?php
	// Add Payment
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add_payment'])) {
		$reservation_id = test_input($_POST['reservation_id']);
		$amount = test_input($_POST['amount']);
		$payment_method = test_input($_POST['payment_method']);
		$transaction_id = !empty($_POST['transaction_id']) ? test_input($_POST['transaction_id']) : NULL;
		$status = test_input($_POST['status']);
		
		try {
			// Begin transaction
			$con->beginTransaction();
			
			// Insert payment record
			$stmt = $con->prepare("INSERT INTO payments 
				(reservation_id, amount, payment_method, transaction_id, status) 
				VALUES (?, ?, ?, ?, ?)");
			$stmt->execute(array($reservation_id, $amount, $payment_method, $transaction_id, $status));
			
			// If payment is completed, update reservation status
			if ($status == 'Completed') {
				// Check if payment completes the full amount
				$stmt = $con->prepare("
					SELECT 
						r.total_amount, 
						SUM(p.amount) as paid_amount
					FROM 
						reservations r
					JOIN 
						payments p ON r.reservation_id = p.reservation_id
					WHERE 
						r.reservation_id = ? AND p.status = 'Completed'
					GROUP BY 
						r.reservation_id
				");
				$stmt->execute(array($reservation_id));
				$payment_info = $stmt->fetch();
				
				if ($payment_info['paid_amount'] >= $payment_info['total_amount']) {
					// Payment is complete, update reservation status if it's not already 'Active' or 'Completed'
					$stmt = $con->prepare("
						UPDATE reservations 
						SET reservation_status = 'Confirmed' 
						WHERE reservation_id = ? AND reservation_status = 'Pending'
					");
					$stmt->execute(array($reservation_id));
				}
			}
			
			// Log the activity
			$user_id = $_SESSION['user_id_yahya_car_rental'];
			$activity = "Payment Added";
			$details = "Added payment of $" . $amount . " for reservation #" . $reservation_id;
			$ip = $_SERVER['REMOTE_ADDR'];
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			
			$stmt = $con->prepare("
				INSERT INTO user_activity_log 
				(user_id, activity, details, ip_address, user_agent) 
				VALUES (?, ?, ?, ?, ?)
			");
			$stmt->execute(array($user_id, $activity, $details, $ip, $user_agent));
			
			// Commit transaction
			$con->commit();
			
			// Success message
			echo "
				<script>
					swal({
						title: 'Success!',
						text: 'Payment has been added successfully',
						icon: 'success',
						buttons: false,
						timer: 1500
					}).then(() => {
						window.location = 'payments.php';
					});
				</script>
			";
			
		} catch (Exception $e) {
			// Rollback transaction
			$con->rollBack();
			
			// Error message
			echo "
				<script>
					swal({
						title: 'Error!',
						text: 'Failed to add payment: " . $e->getMessage() . "',
						icon: 'error',
						buttons: true
					});
				</script>
			";
		}
	}
	
	// Update Payment
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_payment'])) {
		$payment_id = test_input($_POST['payment_id']);
		$amount = test_input($_POST['amount']);
		$payment_method = test_input($_POST['payment_method']);
		$transaction_id = !empty($_POST['transaction_id']) ? test_input($_POST['transaction_id']) : NULL;
		$status = test_input($_POST['status']);
		
		try {
			// Begin transaction
			$con->beginTransaction();
			
			// Get current payment data for logging
			$stmt = $con->prepare("SELECT * FROM payments WHERE payment_id = ?");
			$stmt->execute(array($payment_id));
			$old_payment = $stmt->fetch();
			
			// Update payment record
			$stmt = $con->prepare("
				UPDATE payments 
				SET amount = ?, payment_method = ?, transaction_id = ?, status = ? 
				WHERE payment_id = ?
			");
			$stmt->execute(array($amount, $payment_method, $transaction_id, $status, $payment_id));
			
			// If status changed to completed, check reservation total
			if ($status == 'Completed' && $old_payment['status'] != 'Completed') {
				$reservation_id = $old_payment['reservation_id'];
				
				// Check if payment completes the full amount
				$stmt = $con->prepare("
					SELECT 
						r.total_amount, 
						SUM(p.amount) as paid_amount
					FROM 
						reservations r
					JOIN 
						payments p ON r.reservation_id = p.reservation_id
					WHERE 
						r.reservation_id = ? AND p.status = 'Completed'
					GROUP BY 
						r.reservation_id
				");
				$stmt->execute(array($reservation_id));
				$payment_info = $stmt->fetch();
				
				if ($payment_info['paid_amount'] >= $payment_info['total_amount']) {
					// Payment is complete, update reservation status if it's not already 'Active' or 'Completed'
					$stmt = $con->prepare("
						UPDATE reservations 
						SET reservation_status = 'Confirmed' 
						WHERE reservation_id = ? AND reservation_status = 'Pending'
					");
					$stmt->execute(array($reservation_id));
				}
			}
			
			// Log the activity
			$user_id = $_SESSION['user_id_yahya_car_rental'];
			$activity = "Payment Updated";
			$details = "Updated payment #" . $payment_id . " from $" . $old_payment['amount'] . " to $" . $amount;
			$ip = $_SERVER['REMOTE_ADDR'];
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			
			$stmt = $con->prepare("
				INSERT INTO user_activity_log 
				(user_id, activity, details, ip_address, user_agent) 
				VALUES (?, ?, ?, ?, ?)
			");
			$stmt->execute(array($user_id, $activity, $details, $ip, $user_agent));
			
			// Commit transaction
			$con->commit();
			
			// Success message
			echo "
				<script>
					swal({
						title: 'Success!',
						text: 'Payment has been updated successfully',
						icon: 'success',
						buttons: false,
						timer: 1500
					}).then(() => {
						window.location = 'payments.php';
					});
				</script>
			";
			
		} catch (Exception $e) {
			// Rollback transaction
			$con->rollBack();
			
			// Error message
			echo "
				<script>
					swal({
						title: 'Error!',
						text: 'Failed to update payment: " . $e->getMessage() . "',
						icon: 'error',
						buttons: true
					});
				</script>
			";
		}
	}
	
	// Delete Payment
	if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['delete_payment'])) {
		$payment_id = test_input($_POST['payment_id']);
		
		try {
			// Begin transaction
			$con->beginTransaction();
			
			// Get payment details for logging
			$stmt = $con->prepare("SELECT * FROM payments WHERE payment_id = ?");
			$stmt->execute(array($payment_id));
			$payment = $stmt->fetch();
			
			// Delete payment
			$stmt = $con->prepare("DELETE FROM payments WHERE payment_id = ?");
			$stmt->execute(array($payment_id));
			
			// Log the activity
			$user_id = $_SESSION['user_id_yahya_car_rental'];
			$activity = "Payment Deleted";
			$details = "Deleted payment #" . $payment_id . " of $" . $payment['amount'] . " for reservation #" . $payment['reservation_id'];
			$ip = $_SERVER['REMOTE_ADDR'];
			$user_agent = $_SERVER['HTTP_USER_AGENT'];
			
			$stmt = $con->prepare("
				INSERT INTO user_activity_log 
				(user_id, activity, details, ip_address, user_agent) 
				VALUES (?, ?, ?, ?, ?)
			");
			$stmt->execute(array($user_id, $activity, $details, $ip, $user_agent));
			
			// Commit transaction
			$con->commit();
			
			// Success message
			echo "
				<script>
					swal({
						title: 'Success!',
						text: 'Payment has been deleted successfully',
						icon: 'success',
						buttons: false,
						timer: 1500
					}).then(() => {
						window.location = 'payments.php';
					});
				</script>
			";
			
		} catch (Exception $e) {
			// Rollback transaction
			$con->rollBack();
			
			// Error message
			echo "
				<script>
					swal({
						title: 'Error!',
						text: 'Failed to delete payment: " . $e->getMessage() . "',
						icon: 'error',
						buttons: true
					});
				</script>
			";
		}
	}
?>

<!-- Include JS Scripts -->
<script src="Design/js/jquery.min.js"></script>
<script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
<script src="Design/js/bootstrap.bundle.min.js"></script>
<script src="Design/js/sb-admin-2.min.js"></script>
<script src="Design/js/jquery.dataTables.min.js"></script>
<script src="Design/js/dataTables.bootstrap4.min.js"></script>

<!-- Create AJAX handlers to support View and Edit -->
<?php
// Create the AJAX handlers files if they don't exist
// This is necessary for the view and edit functionality

// Create the get_payment.php file
$get_payment_file = 'ajax_handlers/get_payment.php';
if (!file_exists('ajax_handlers/')) {
    mkdir('ajax_handlers/', 0755, true);
}

if (!file_exists($get_payment_file)) {
    $get_payment_content = '<?php
include "../connect.php";
include "../Includes/functions/functions.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["payment_id"])) {
    $payment_id = test_input($_POST["payment_id"]);
    
    $stmt = $con->prepare("SELECT * FROM payments WHERE payment_id = ?");
    $stmt->execute(array($payment_id));
    $payment = $stmt->fetch(PDO::FETCH_ASSOC);
    
    echo json_encode($payment);
}
?>';
    file_put_contents($get_payment_file, $get_payment_content);
}

// Create the get_payment_details.php file
$get_payment_details_file = 'ajax_handlers/get_payment_details.php';

if (!file_exists($get_payment_details_file)) {
    $get_payment_details_content = '<?php
include "../connect.php";
include "../Includes/functions/functions.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["payment_id"])) {
    $payment_id = test_input($_POST["payment_id"]);
    
    $stmt = $con->prepare("
        SELECT 
            p.*,
            r.pickup_date,
            r.return_date,
            r.total_amount,
            c.full_name AS client_name,
            cars.car_name
        FROM 
            payments p
        JOIN 
            reservations r ON p.reservation_id = r.reservation_id
        JOIN 
            clients c ON r.client_id = c.client_id
        JOIN 
            cars ON r.car_id = cars.id
        WHERE 
            p.payment_id = ?
    ");
    $stmt->execute(array($payment_id));
    $payment_details = $stmt->fetch(PDO::FETCH_ASSOC);
    
    // Format dates for display
    if ($payment_details) {
        $payment_details["payment_date"] = date("Y-m-d H:i", strtotime($payment_details["payment_date"]));
        $payment_details["pickup_date"] = date("Y-m-d", strtotime($payment_details["pickup_date"]));
        $payment_details["return_date"] = date("Y-m-d", strtotime($payment_details["return_date"]));
    }
    
    echo json_encode($payment_details);
}
?>';
    file_put_contents($get_payment_details_file, $get_payment_details_content);
}
?>

<script>
$(document).ready(function() {
	// Initialize DataTable
	$('#dataTable').DataTable();
	
	// Show remaining amount when reservation is selected
	$('#reservation_id').change(function() {
		const remainingAmount = $(this).find(':selected').data('remaining');
		$('#remaining-amount').text('$' + remainingAmount);
		$('#amount').val(remainingAmount);
	});
	
	// Populate edit payment modal
	$('.edit-btn').click(function() {
		const paymentId = $(this).data('id');
		
		// AJAX request to get payment details
		$.ajax({
			url: 'ajax_handlers/get_payment.php',
			type: 'POST',
			data: {payment_id: paymentId},
			dataType: 'json',
			success: function(data) {
				$('#edit_payment_id').val(data.payment_id);
				$('#edit_reservation_id').val(data.reservation_id);
				$('#edit_amount').val(data.amount);
				$('#edit_payment_method').val(data.payment_method);
				$('#edit_transaction_id').val(data.transaction_id);
				$('#edit_status').val(data.status);
			},
			error: function(xhr, status, error) {
				swal({
					title: 'Error!',
					text: 'Failed to retrieve payment details: ' + error,
					icon: 'error'
				});
				console.error("Error details:", xhr.responseText);
			}
		});
	});
	
	// Set payment ID for delete modal
	$('.delete-btn').click(function() {
		const paymentId = $(this).data('id');
		$('#delete_payment_id').val(paymentId);
	});
	
	// Populate view payment modal
	$('.view-btn').click(function() {
		const paymentId = $(this).data('id');
		
		// AJAX request to get detailed payment information
		$.ajax({
			url: 'ajax_handlers/get_payment_details.php',
			type: 'POST',
			data: {payment_id: paymentId},
			dataType: 'json',
			success: function(data) {
				// Populate payment info
				$('#view_payment_id').text(data.payment_id);
				$('#view_amount').text(data.amount);
				$('#view_payment_date').text(data.payment_date);
				$('#view_payment_method').text(data.payment_method);
				$('#view_transaction_id').text(data.transaction_id || 'N/A');
				
				// Set status with appropriate badge color
				let statusHtml = '';
				switch(data.status) {
					case 'Completed':
						statusHtml = '<span class="badge badge-success">Completed</span>';
						break;
					case 'Pending':
						statusHtml = '<span class="badge badge-warning">Pending</span>';
						break;
					case 'Failed':
						statusHtml = '<span class="badge badge-danger">Failed</span>';
						break;
					case 'Refunded':
						statusHtml = '<span class="badge badge-info">Refunded</span>';
						break;
				}
				$('#view_status').html(statusHtml);
				
				// Populate reservation details
				$('#view_reservation_id').text(data.reservation_id);
				$('#view_client_name').text(data.client_name);
				$('#view_car_name').text(data.car_name);
				$('#view_pickup_date').text(data.pickup_date);
				$('#view_return_date').text(data.return_date);
				$('#view_total_amount').text(data.total_amount);
				
				// Set receipt print link
				$('#print_receipt').attr('href', 'print_receipt.php?payment_id=' + data.payment_id);
			},
			error: function(xhr, status, error) {
				swal({
					title: 'Error!',
					text: 'Failed to retrieve payment details: ' + error,
					icon: 'error'
				});
				console.error("Error details:", xhr.responseText);
			}
		});
	});
	
	// Form validation for adding payment
	$('form').submit(function(e) {
		if ($(this).find('button[name="add_payment"]').length) {
			const reservationId = $('#reservation_id').val();
			const amount = parseFloat($('#amount').val());
			const remainingText = $('#remaining-amount').text();
			const remainingAmount = parseFloat(remainingText.replace('$', ''));
			
			if (!reservationId) {
				e.preventDefault();
				swal({
					title: 'Validation Error!',
					text: 'Please select a reservation',
					icon: 'error'
				});
				return false;
			}
			
			if (amount <= 0) {
				e.preventDefault();
				swal({
					title: 'Validation Error!',
					text: 'Amount must be greater than zero',
					icon: 'error'
				});
				return false;
			}
			
			if (amount > remainingAmount) {
				e.preventDefault();
				swal({
					title: 'Validation Error!',
					text: 'Amount cannot exceed the remaining balance of ' + remainingText,
					icon: 'error'
				});
				return false;
			}
			
			const paymentMethod = $('#payment_method').val();
			const transactionId = $('#transaction_id').val();
			
			if ((paymentMethod === 'Credit Card' || paymentMethod === 'Debit Card' || paymentMethod === 'Bank Transfer') && !transactionId) {
				e.preventDefault();
				swal({
					title: 'Validation Error!',
					text: 'Transaction ID is required for ' + paymentMethod + ' payments',
					icon: 'error'
				});
				return false;
			}
		}
	});
});
</script>

<?php
	include 'Includes/templates/footer.php';
?>