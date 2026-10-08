<?php
require_once __DIR__ . '/config.php';

$pnr = isset($_GET['pnr']) ? sanitize($_GET['pnr']) : '';
$booking = null;
$error = '';

if (!empty($pnr)) {
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
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-ticket-perforated-fill text-danger me-2"></i>Search & Print E-Ticket</h5>
                </div>
                <div class="card-body p-4">
                    <form action="view_ticket.php" method="GET">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Enter PNR Number</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light"><i class="bi bi-upc-scan"></i></span>
                                <input type="text" name="pnr" class="form-control text-uppercase" placeholder="e.g. KL7845129" required value="<?= htmlspecialchars($pnr) ?>">
                            </div>
                            <div class="form-text small">Enter the 9-character PNR received via SMS or email.</div>
                        </div>
                        <button type="submit" class="btn btn-ksrtc-primary w-100 py-2">
                            <i class="bi bi-search me-1"></i> Retrieve Ticket
                        </button>
                    </form>

                    <?php if ($error): ?>
                        <div class="alert alert-danger mt-3 mb-0 small">
                            <i class="bi bi-exclamation-triangle-fill me-1"></i> <?= $error ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if ($booking): 
            $passengers = json_decode($booking['passengers_data'], true) ?: [];
        ?>
        <div class="col-lg-10">
            <div class="card border shadow-sm rounded-3 overflow-hidden">
                <div class="bg-dark text-white p-4 border-bottom d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;">
                    <div class="d-flex align-items-center gap-3">
                        <img src="assets/images/ksrtc-logo.svg" alt="KSRTC Logo" style="width: 48px; height: 48px;">
                        <div>
                            <h5 class="fw-bold mb-0 text-white">KSRTC Electronic Ticket</h5>
                            <div class="small text-secondary">Booking Status: <span class="badge <?= $booking['booking_status'] === 'CONFIRMED' ? 'bg-success' : 'bg-danger' ?>"><?= htmlspecialchars($booking['booking_status']) ?></span></div>
                        </div>
                    </div>
                    <div class="text-end">
                        <div class="small text-secondary text-uppercase fw-bold">PNR</div>
                        <div class="fs-4 fw-bold text-danger"><?= htmlspecialchars($booking['pnr']) ?></div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <div class="row g-3 mb-3 bg-light p-3 rounded border">
                        <div class="col-md-3">
                            <div class="small text-muted">From</div>
                            <div class="fw-bold text-dark"><?= htmlspecialchars($booking['from_city']) ?> (<?= htmlspecialchars($booking['from_code']) ?>)</div>
                            <div class="text-danger small fw-bold"><?= htmlspecialchars($booking['departure_time']) ?></div>
                        </div>
                        <div class="col-md-3">
                            <div class="small text-muted">To</div>
                            <div class="fw-bold text-dark"><?= htmlspecialchars($booking['to_city']) ?> (<?= htmlspecialchars($booking['to_code']) ?>)</div>
                            <div class="text-danger small fw-bold"><?= htmlspecialchars($booking['arrival_time']) ?></div>
                        </div>
                        <div class="col-md-3">
                            <div class="small text-muted">Journey Date</div>
                            <div class="fw-bold text-dark"><?= date('D, d M Y', strtotime($booking['journey_date'])) ?></div>
                            <div class="small text-secondary"><?= htmlspecialchars($booking['bus_type']) ?></div>
                        </div>
                        <div class="col-md-3">
                            <div class="small text-muted">Total Seats / Fare</div>
                            <div class="fw-bold text-dark"><?= htmlspecialchars($booking['seat_numbers']) ?> (<?= $booking['total_seats'] ?> seats)</div>
                            <div class="fs-5 fw-bold text-danger">₹<?= number_format($booking['total_amount'], 2) ?></div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-3">
                        <div class="small text-muted">Customer: <strong><?= htmlspecialchars($booking['customer_name']) ?></strong> (<?= htmlspecialchars($booking['customer_phone']) ?>)</div>
                        <div class="d-flex gap-2">
                            <a href="confirmation.php?pnr=<?= urlencode($booking['pnr']) ?>" class="btn btn-outline-danger btn-sm">
                                <i class="bi bi-eye me-1"></i> View Full Ticket Slip
                            </a>
                            <?php if ($booking['booking_status'] === 'CONFIRMED'): ?>
                            <a href="cancel_ticket.php?pnr=<?= urlencode($booking['pnr']) ?>" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-x-circle me-1"></i> Cancel Ticket
                            </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>

