<?php
require_once __DIR__ . '/config.php';

$busId = isset($_GET['bus_id']) ? intval($_GET['bus_id']) : 0;
$seatsParam = isset($_GET['seats']) ? sanitize($_GET['seats']) : '';
$pickupPoint = isset($_GET['pickup']) ? sanitize($_GET['pickup']) : '';
$dropPoint = isset($_GET['drop']) ? sanitize($_GET['drop']) : '';
$journeyDate = isset($_GET['date']) ? sanitize($_GET['date']) : '2026-08-24';

$bus = get_bus_by_id($busId);

if (!$bus || empty($seatsParam)) {
    header("Location: index.php");
    exit;
}

$seatList = array_filter(explode(',', $seatsParam));
$seatCount = count($seatList);
$baseFare = $seatCount * floatval($bus['fare']);
$taxAmount = round($baseFare * 0.05, 2); // 5% GST
$totalAmount = $baseFare + $taxAmount;

$currentUser = get_current_user_data();

// Handle form submission to payment
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $customerName = sanitize($_POST['customer_name'] ?? '');
    $customerEmail = sanitize($_POST['customer_email'] ?? '');
    $customerPhone = sanitize($_POST['customer_phone'] ?? '');

    $passengers = [];
    foreach ($seatList as $index => $seat) {
        $pName = sanitize($_POST['passenger_name_' . $index] ?? 'Passenger ' . ($index + 1));
        $pAge = intval($_POST['passenger_age_' . $index] ?? 25);
        $pGender = sanitize($_POST['passenger_gender_' . $index] ?? 'Male');
        $passengers[] = [
            'seat' => $seat,
            'name' => $pName,
            'age' => $pAge,
            'gender' => $pGender
        ];
    }

    // Save temporary booking session
    $_SESSION['checkout_order'] = [
        'pnr' => generate_pnr(),
        'bus_id' => $busId,
        'journey_date' => $journeyDate,
        'passengers' => $passengers,
        'seat_numbers' => implode(', ', $seatList),
        'total_seats' => $seatCount,
        'fare_amount' => $baseFare,
        'tax_amount' => $taxAmount,
        'total_amount' => $totalAmount,
        'pickup_point' => $pickupPoint,
        'drop_point' => $dropPoint,
        'customer_name' => $customerName,
        'customer_email' => $customerEmail,
        'customer_phone' => $customerPhone
    ];

    header("Location: payment.php");
    exit;
}

require_once __DIR__ . '/header.php';
?>

<div class="container py-5">
    <div class="row g-4">
        <!-- Left Column: Passenger Info Form -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-people-fill text-danger me-2"></i>Passenger Information</h5>
                    <span class="badge bg-danger-subtle text-danger px-3 py-2 rounded-pill fw-bold"><?= $seatCount ?> <?= $seatCount > 1 ? 'Seats' : 'Seat' ?> (<?= htmlspecialchars(implode(', ', $seatList)) ?>)</span>
                </div>
                <div class="card-body p-4">
                    <form action="checkout.php?bus_id=<?= $busId ?>&seats=<?= urlencode($seatsParam) ?>&pickup=<?= urlencode($pickupPoint) ?>&drop=<?= urlencode($dropPoint) ?>&date=<?= urlencode($journeyDate) ?>" method="POST">
                        
                        <!-- Primary Contact Details -->
                        <h6 class="fw-bold text-secondary mb-3"><i class="bi bi-telephone-fill me-2 text-danger"></i>Contact Details (Ticket will be sent here)</h6>
                        <div class="row g-3 mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Contact Name *</label>
                                <input type="text" name="customer_name" class="form-control" required value="<?= $currentUser ? htmlspecialchars($currentUser['name']) : '' ?>" placeholder="Full Name">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">Mobile Number *</label>
                                <input type="tel" name="customer_phone" class="form-control" required value="<?= $currentUser ? htmlspecialchars($currentUser['phone']) : '' ?>" placeholder="10-digit mobile number">
                            </div>
                            <div class="col-md-12">
                                <label class="form-label small fw-bold">Email Address *</label>
                                <input type="email" name="customer_email" class="form-control" required value="<?= $currentUser ? htmlspecialchars($currentUser['email']) : '' ?>" placeholder="you@example.com">
                            </div>
                        </div>

                        <hr class="my-4 text-muted">

                        <!-- Passenger List per Seat -->
                        <h6 class="fw-bold text-secondary mb-3"><i class="bi bi-person-badge-fill me-2 text-danger"></i>Passenger Details</h6>
                        <?php foreach ($seatList as $index => $seat): ?>
                            <div class="p-3 mb-3 bg-light rounded-3 border">
                                <div class="d-flex align-items-center justify-content-between mb-2">
                                    <span class="fw-bold text-dark">Passenger <?= $index + 1 ?></span>
                                    <span class="badge bg-danger text-white">Seat <?= htmlspecialchars($seat) ?></span>
                                </div>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label small fw-semibold">Passenger Name *</label>
                                        <input type="text" name="passenger_name_<?= $index ?>" class="form-control" required placeholder="Name as in Govt ID">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold">Age *</label>
                                        <input type="number" name="passenger_age_<?= $index ?>" class="form-control" min="1" max="120" required value="28">
                                    </div>
                                    <div class="col-md-3">
                                        <label class="form-label small fw-semibold">Gender *</label>
                                        <select name="passenger_gender_<?= $index ?>" class="form-select">
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                            <option value="Other">Other</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>

                        <div class="mt-4 text-end">
                            <a href="index.php" class="btn btn-outline-secondary me-2">Cancel</a>
                            <button type="submit" class="btn btn-ksrtc-primary px-4 py-2">
                                Continue to Payment <i class="bi bi-arrow-right ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Right Column: Trip Summary & Fare Card -->
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-3 mb-4 sticky-top" style="top: 20px;">
                <div class="card-header bg-white py-3 border-bottom">
                    <h5 class="fw-bold mb-0 text-dark">Trip Summary</h5>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex align-items-center justify-content-between mb-2">
                        <span class="fw-bold text-dark fs-5"><?= htmlspecialchars($bus['from_city']) ?></span>
                        <i class="bi bi-arrow-right text-danger fs-5"></i>
                        <span class="fw-bold text-dark fs-5"><?= htmlspecialchars($bus['to_city']) ?></span>
                    </div>
                    <div class="badge bg-secondary-subtle text-secondary mb-3"><?= htmlspecialchars($bus['bus_type']) ?></div>

                    <div class="small text-muted mb-2">
                        <i class="bi bi-calendar3 me-2 text-danger"></i> Date: <strong><?= date('D, d M Y', strtotime($journeyDate)) ?></strong>
                    </div>
                    <div class="small text-muted mb-2">
                        <i class="bi bi-clock me-2 text-danger"></i> Departure: <strong><?= htmlspecialchars($bus['departure_time']) ?></strong>
                    </div>
                    <div class="small text-muted mb-3">
                        <i class="bi bi-upc-scan me-2 text-danger"></i> Trip Code: <strong><?= htmlspecialchars($bus['trip_code']) ?></strong>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <div class="small fw-bold text-secondary">Boarding Point:</div>
                        <div class="small text-dark"><?= htmlspecialchars($pickupPoint) ?></div>
                    </div>
                    <div class="mb-3">
                        <div class="small fw-bold text-secondary">Dropping Point:</div>
                        <div class="small text-dark"><?= htmlspecialchars($dropPoint) ?></div>
                    </div>

                    <hr>

                    <!-- Fare Breakdown -->
                    <h6 class="fw-bold text-dark mb-3">Fare Breakdown</h6>
                    <div class="d-flex justify-content-between small text-muted mb-2">
                        <span>Base Fare (<?= $seatCount ?> x ₹<?= number_format($bus['fare'], 2) ?>)</span>
                        <span class="fw-bold text-dark">₹<?= number_format($baseFare, 2) ?></span>
                    </div>
                    <div class="d-flex justify-content-between small text-muted mb-2">
                        <span>GST / Service Tax (5%)</span>
                        <span class="fw-bold text-dark">₹<?= number_format($taxAmount, 2) ?></span>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <span class="fw-bold text-dark fs-6">Total Payable</span>
                        <span class="fw-bold text-danger fs-4">₹<?= number_format($totalAmount, 2) ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>

