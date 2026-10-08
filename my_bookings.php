<?php
require_once __DIR__ . '/config.php';

$currentUser = get_current_user_data();
if (!$currentUser) {
    header("Location: index.php");
    exit;
}

$userBookings = [];
if ($useSqlite && $pdo) {
    $stmt = $pdo->prepare("SELECT b.*, bus.trip_code, bus.bus_type, bus.from_city, bus.from_code, bus.to_city, bus.to_code, bus.departure_time, bus.arrival_time FROM bookings b JOIN buses bus ON b.bus_id = bus.id WHERE b.customer_email = ? OR b.customer_phone = ? ORDER BY b.id DESC");
    $stmt->execute([$currentUser['email'], $currentUser['phone']]);
    $userBookings = $stmt->fetchAll();
} else {
    $data = json_decode(file_get_contents($jsonStorageFile), true);
    foreach ($data['bookings'] as $b) {
        if ($b['customer_email'] === $currentUser['email'] || $b['customer_phone'] === $currentUser['phone']) {
            $bus = get_bus_by_id($b['bus_id']);
            if ($bus) {
                $b['trip_code'] = $bus['trip_code'];
                $b['bus_type'] = $bus['bus_type'];
                $b['from_city'] = $bus['from_city'];
                $b['from_code'] = $bus['from_code'];
                $b['to_city'] = $bus['to_city'];
                $b['to_code'] = $bus['to_code'];
                $b['departure_time'] = $bus['departure_time'];
                $b['arrival_time'] = $bus['arrival_time'];
            }
            $userBookings[] = $b;
        }
    }
}

require_once __DIR__ . '/header.php';
?>

<div class="container py-5">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <div>
            <h4 class="fw-bold text-dark mb-1">My Bookings</h4>
            <div class="text-secondary small">Logged in as <?= htmlspecialchars($currentUser['name']) ?> (<?= htmlspecialchars($currentUser['email']) ?>)</div>
        </div>
        <a href="index.php" class="btn btn-ksrtc-primary">
            <i class="bi bi-plus-circle me-1"></i> Book New Bus Ticket
        </a>
    </div>

    <?php if (empty($userBookings)): ?>
        <div class="card border-0 shadow-sm p-5 text-center">
            <i class="bi bi-ticket-detailed text-muted fs-1 mb-3"></i>
            <h5 class="fw-bold text-dark">No Bookings Found</h5>
            <p class="text-secondary small">You haven't made any bus reservations yet.</p>
            <div>
                <a href="index.php" class="btn btn-ksrtc-primary mt-2">Search Buses & Book Now</a>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($userBookings as $bk): ?>
                <div class="col-12">
                    <div class="card border shadow-sm rounded-3">
                        <div class="card-body p-4">
                            <div class="row g-3 align-items-center">
                                <div class="col-md-2">
                                    <span class="small text-muted fw-bold text-uppercase d-block">PNR Number</span>
                                    <span class="fs-5 fw-bold text-danger"><?= htmlspecialchars($bk['pnr']) ?></span>
                                    <div>
                                        <span class="badge <?= $bk['booking_status'] === 'CONFIRMED' ? 'bg-success' : 'bg-danger' ?>"><?= htmlspecialchars($bk['booking_status']) ?></span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <span class="small text-muted fw-bold text-uppercase d-block">Route</span>
                                    <span class="fw-bold text-dark fs-6"><?= htmlspecialchars($bk['from_city']) ?> &rarr; <?= htmlspecialchars($bk['to_city']) ?></span>
                                    <div class="small text-secondary"><?= htmlspecialchars($bk['bus_type']) ?> (<?= htmlspecialchars($bk['trip_code']) ?>)</div>
                                </div>
                                <div class="col-md-2">
                                    <span class="small text-muted fw-bold text-uppercase d-block">Journey Date</span>
                                    <span class="fw-bold text-dark"><?= date('d M Y', strtotime($bk['journey_date'])) ?></span>
                                    <div class="small text-danger fw-bold"><?= htmlspecialchars($bk['departure_time']) ?></div>
                                </div>
                                <div class="col-md-2">
                                    <span class="small text-muted fw-bold text-uppercase d-block">Seats / Fare</span>
                                    <span class="fw-bold text-dark"><?= htmlspecialchars($bk['seat_numbers']) ?></span>
                                    <div class="fw-bold text-danger">₹<?= number_format($bk['total_amount'], 2) ?></div>
                                </div>
                                <div class="col-md-2 text-md-end">
                                    <a href="confirmation.php?pnr=<?= urlencode($bk['pnr']) ?>" class="btn btn-outline-danger btn-sm mb-1 d-block">
                                        <i class="bi bi-printer me-1"></i> View Ticket
                                    </a>
                                    <?php if ($bk['booking_status'] === 'CONFIRMED'): ?>
                                    <a href="cancel_ticket.php?pnr=<?= urlencode($bk['pnr']) ?>" class="btn btn-outline-secondary btn-sm d-block">
                                        Cancel
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>

