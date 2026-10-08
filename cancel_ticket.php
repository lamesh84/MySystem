<?php
require_once __DIR__ . '/config.php';

$pnr = isset($_GET['pnr']) ? sanitize($_GET['pnr']) : '';
$booking = null;
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $cancelPnr = sanitize($_POST['pnr'] ?? '');
    if (!empty($cancelPnr)) {
        $booking = get_booking_by_pnr($cancelPnr);
        if ($booking) {
            if ($booking['booking_status'] === 'CANCELLED') {
                $error = "This ticket (PNR: $cancelPnr) has already been cancelled.";
            } else {
                $success = cancel_booking_by_pnr($cancelPnr);
                if ($success) {
                    $booking['booking_status'] = 'CANCELLED';
                    $refundAmt = round($booking['total_amount'] * 0.90, 2);
                    $message = "Ticket PNR: $cancelPnr cancelled successfully. Refund of ₹" . number_format($refundAmt, 2) . " (after 10% standard cancellation fee) will be credited to source account within 3-5 business days.";
                } else {
                    $error = "Failed to cancel ticket. Please try again or contact support.";
                }
            }
        } else {
            $error = "No ticket found for PNR: " . htmlspecialchars($cancelPnr);
        }
    }
} elseif (!empty($pnr)) {
    $booking = get_booking_by_pnr($pnr);
    if (!$booking) {
        $error = "No ticket found with PNR: " . htmlspecialchars($pnr);
    }
}

require_once __DIR__ . '/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-6 mb-4">
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-x-octagon-fill text-danger me-2"></i>Ticket Cancellation</h5>
                </div>
                <div class="card-body p-4">
                    <form action="cancel_ticket.php" method="GET">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Search PNR to Cancel</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-ticket"></i></span>
                                <input type="text" name="pnr" class="form-control text-uppercase" placeholder="e.g. KL7845129" required value="<?= htmlspecialchars($pnr) ?>">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-secondary w-100 py-2">
                            <i class="bi bi-search me-1"></i> Retrieve Ticket Details
                        </button>
                    </form>

                    <?php if ($message): ?>
                        <div class="alert alert-success mt-3 mb-0 small">
                            <i class="bi bi-check-circle-fill me-1"></i> <?= $message ?>
                        </div>
                    <?php endif; ?>

                    <?php if ($error): ?>
                        <div class="alert alert-danger mt-3 mb-0 small">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> <?= $error ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if ($booking && $booking['booking_status'] !== 'CANCELLED'): 
            $deduction = round($booking['total_amount'] * 0.10, 2);
            $refund = $booking['total_amount'] - $deduction;
        ?>
        <div class="col-lg-8">
            <div class="card border shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-danger text-white py-3">
                    <h5 class="fw-bold mb-0">Confirm Cancellation for PNR: <?= htmlspecialchars($booking['pnr']) ?></h5>
                </div>
                <div class="card-body p-4">
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <div class="small text-muted">Route</div>
                            <div class="fw-bold fs-6"><?= htmlspecialchars($booking['from_city']) ?> &rarr; <?= htmlspecialchars($booking['to_city']) ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">Journey Date & Time</div>
                            <div class="fw-bold fs-6"><?= date('D, d M Y', strtotime($booking['journey_date'])) ?> at <?= htmlspecialchars($booking['departure_time']) ?></div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">Passenger / Seats</div>
                            <div class="fw-bold"><?= htmlspecialchars($booking['customer_name']) ?> (Seats: <?= htmlspecialchars($booking['seat_numbers']) ?>)</div>
                        </div>
                        <div class="col-md-6">
                            <div class="small text-muted">Original Booking Fare</div>
                            <div class="fw-bold text-dark fs-6">₹<?= number_format($booking['total_amount'], 2) ?></div>
                        </div>
                    </div>

                    <div class="bg-light p-3 rounded-3 mb-4 border">
                        <h6 class="fw-bold text-dark small text-uppercase mb-2">Refund Calculation:</h6>
                        <div class="d-flex justify-content-between small text-muted mb-1">
                            <span>Paid Amount:</span>
                            <span>₹<?= number_format($booking['total_amount'], 2) ?></span>
                        </div>
                        <div class="d-flex justify-content-between small text-muted mb-1">
                            <span>Standard Cancellation Fee (10%):</span>
                            <span class="text-danger">- ₹<?= number_format($deduction, 2) ?></span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between fw-bold text-dark">
                            <span>Estimated Refund Amount:</span>
                            <span class="text-success fs-5">₹<?= number_format($refund, 2) ?></span>
                        </div>
                    </div>

                    <form action="cancel_ticket.php" method="POST" onsubmit="return confirm('Are you sure you want to cancel this ticket? This action cannot be undone.');">
                        <input type="hidden" name="pnr" value="<?= htmlspecialchars($booking['pnr']) ?>">
                        <div class="d-flex justify-content-end gap-2">
                            <a href="index.php" class="btn btn-outline-secondary">Keep Ticket</a>
                            <button type="submit" class="btn btn-danger px-4">
                                <i class="bi bi-x-circle me-1"></i> Confirm Cancellation & Request Refund
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>

