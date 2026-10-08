<?php
require_once __DIR__ . '/config.php';

$pnr = isset($_GET['pnr']) ? sanitize($_GET['pnr']) : '';
$booking = get_booking_by_pnr($pnr);

if (!$booking) {
    header("Location: index.php");
    exit;
}

$passengers = json_decode($booking['passengers_data'], true) ?: [];

require_once __DIR__ . '/header.php';
?>

<style>
@media print {
    .ksrtc-header-wrapper, .ksrtc-footer, .no-print {
        display: none !important;
    }
    body {
        background-color: #ffffff !important;
    }
    .ticket-print-card {
        box-shadow: none !important;
        border: 1px solid #000000 !important;
        margin: 0 !important;
        width: 100% !important;
    }
}
</style>

<div class="container py-5">
    <!-- Success Banner -->
    <div class="alert alert-success border-0 shadow-sm rounded-3 d-flex align-items-center justify-content-between p-4 mb-4 no-print">
        <div class="d-flex align-items-center gap-3">
            <div class="bg-success text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; font-size: 24px;">
                <i class="bi bi-check-lg"></i>
            </div>
            <div>
                <h4 class="alert-heading fw-bold mb-1">Booking Confirmed Successfully!</h4>
                <p class="mb-0 text-success-emphasis">
                    Your e-Ticket has been generated. Confirmation SMS & Email have been sent to <strong><?= htmlspecialchars($booking['customer_phone']) ?></strong>.
                </p>
            </div>
        </div>
        <div class="d-flex gap-2">
            <button onclick="window.print()" class="btn btn-outline-success fw-bold px-3 py-2">
                <i class="bi bi-printer-fill me-1"></i> Print Ticket
            </button>
            <a href="index.php" class="btn btn-success fw-bold px-3 py-2">
                Book Another
            </a>
        </div>
    </div>

    <!-- Official KSRTC E-Ticket Card -->
    <div class="card border shadow-sm rounded-3 ticket-print-card overflow-hidden">
        <!-- Ticket Header -->
        <div class="bg-dark text-white p-4 border-bottom d-flex align-items-center justify-content-between" style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%) !important;">
            <div class="d-flex align-items-center gap-3">
                <img src="assets/images/ksrtc-logo.svg" alt="KSRTC Logo" style="width: 55px; height: 55px;">
                <div>
                    <h5 class="fw-bold mb-0 text-white">KERALA STATE ROAD TRANSPORT CORPORATION</h5>
                    <div class="small text-secondary">Official Electronic Reservation Slip (ERS)</div>
                </div>
            </div>
            <div class="text-end">
                <div class="small text-secondary text-uppercase fw-bold">PNR Number</div>
                <div class="fs-3 fw-bold text-danger"><?= htmlspecialchars($booking['pnr']) ?></div>
                <span class="badge bg-success text-white"><?= htmlspecialchars($booking['booking_status']) ?></span>
            </div>
        </div>

        <div class="card-body p-4">
            <!-- Route Strip -->
            <div class="row g-4 mb-4 align-items-center bg-light p-3 rounded-3 border">
                <div class="col-md-3">
                    <div class="small text-muted fw-bold text-uppercase">From</div>
                    <div class="fs-5 fw-bold text-dark"><?= strtoupper(htmlspecialchars($booking['from_city'])) ?> (<?= htmlspecialchars($booking['from_code']) ?>)</div>
                    <div class="text-danger fw-bold fs-6"><?= htmlspecialchars($booking['departure_time']) ?></div>
                </div>

                <div class="col-md-3 text-center">
                    <div class="small text-danger fw-bold"><?= htmlspecialchars($booking['duration']) ?></div>
                    <div class="d-flex align-items-center justify-content-center gap-2 my-1">
                        <span class="badge bg-secondary">Direct</span>
                        <i class="bi bi-arrow-right text-dark fs-5"></i>
                    </div>
                    <div class="small text-muted">Trip: <strong><?= htmlspecialchars($booking['trip_code']) ?></strong></div>
                </div>

                <div class="col-md-3">
                    <div class="small text-muted fw-bold text-uppercase">To</div>
                    <div class="fs-5 fw-bold text-dark"><?= strtoupper(htmlspecialchars($booking['to_city'])) ?> (<?= htmlspecialchars($booking['to_code']) ?>)</div>
                    <div class="text-danger fw-bold fs-6"><?= htmlspecialchars($booking['arrival_time']) ?></div>
                </div>

                <div class="col-md-3 text-md-end">
                    <div class="small text-muted fw-bold text-uppercase">Journey Date</div>
                    <div class="fs-5 fw-bold text-dark"><?= date('D, d M Y', strtotime($booking['journey_date'])) ?></div>
                    <div class="badge bg-danger text-white"><?= htmlspecialchars($booking['bus_type']) ?></div>
                </div>
            </div>

            <!-- Boarding & Dropping Points -->
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <div class="p-3 border rounded-3 bg-white h-100">
                        <div class="small fw-bold text-secondary text-uppercase mb-1"><i class="bi bi-geo-alt-fill text-danger me-1"></i> Boarding Location</div>
                        <div class="fw-bold text-dark"><?= htmlspecialchars($booking['pickup_point']) ?></div>
                        <div class="small text-muted mt-1">Please report 15 minutes before scheduled departure.</div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 border rounded-3 bg-white h-100">
                        <div class="small fw-bold text-secondary text-uppercase mb-1"><i class="bi bi-geo-fill text-success me-1"></i> Dropping Location</div>
                        <div class="fw-bold text-dark"><?= htmlspecialchars($booking['drop_point']) ?></div>
                        <div class="small text-muted mt-1">Subject to traffic and road conditions.</div>
                    </div>
                </div>
            </div>

            <!-- Passengers & QR Section -->
            <div class="row g-4 mb-4">
                <div class="col-md-8">
                    <h6 class="fw-bold text-secondary mb-2 text-uppercase small">Passenger Details</h6>
                    <div class="table-responsive">
                        <table class="table table-bordered align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>#</th>
                                    <th>Passenger Name</th>
                                    <th>Age</th>
                                    <th>Gender</th>
                                    <th>Seat No.</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($passengers as $idx => $p): ?>
                                <tr>
                                    <td><?= $idx + 1 ?></td>
                                    <td class="fw-bold"><?= htmlspecialchars($p['name']) ?></td>
                                    <td><?= htmlspecialchars($p['age']) ?></td>
                                    <td><?= htmlspecialchars($p['gender']) ?></td>
                                    <td class="fw-bold text-danger"><?= htmlspecialchars($p['seat']) ?></td>
                                    <td><span class="badge bg-success-subtle text-success">Confirmed</span></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- QR Code & Fare -->
                <div class="col-md-4 text-center d-flex flex-column justify-content-between p-3 border rounded-3 bg-light">
                    <div>
                        <div class="small fw-bold text-secondary text-uppercase mb-2">Ticket Verification QR</div>
                        <!-- High reliability QR image for scanning -->
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=130x130&data=<?= urlencode('KSRTC-PNR:' . $booking['pnr'] . '|BUS:' . $booking['trip_code'] . '|SEATS:' . $booking['seat_numbers']) ?>" alt="Ticket QR" class="img-fluid border p-1 bg-white rounded" style="width: 120px; height: 120px;">
                        <div class="small text-muted mt-1">Scan for conductor verification</div>
                    </div>
                    <div class="border-top pt-2 mt-2">
                        <div class="small text-secondary">Total Amount Paid:</div>
                        <div class="fs-4 fw-bold text-danger">₹<?= number_format($booking['total_amount'], 2) ?></div>
                        <div class="small text-muted">(Includes ₹<?= number_format($booking['tax_amount'], 2) ?> GST)</div>
                    </div>
                </div>
            </div>

            <!-- Terms & Instructions -->
            <div class="p-3 bg-light rounded-3 border small text-muted">
                <h6 class="fw-bold text-dark small text-uppercase mb-2">Important Instructions for Passengers:</h6>
                <ul class="mb-0 ps-3">
                    <li>Passengers must carry a valid original Government Photo ID (Aadhaar, Voter ID, Driving License, Passport).</li>
                    <li>Digital copy (SMS / Email) of this ticket is accepted for travel on all KSRTC services.</li>
                    <li>Cancellation is allowed up to 2 hours prior to scheduled departure from the source station.</li>
                </ul>
            </div>
        </div>

        <!-- Print Action Footer -->
        <div class="card-footer bg-white p-3 border-top d-flex justify-content-between align-items-center no-print">
            <span class="small text-muted">Booked on <?= date('d M Y, h:i A', strtotime($booking['booking_time'])) ?></span>
            <div>
                <a href="index.php" class="btn btn-outline-secondary me-2">Back to Search</a>
                <button onclick="window.print()" class="btn btn-ksrtc-primary">
                    <i class="bi bi-printer me-1"></i> Print / Save Ticket
                </button>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>

