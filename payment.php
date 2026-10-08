<?php
require_once __DIR__ . '/config.php';

if (!isset($_SESSION['checkout_order'])) {
    header("Location: index.php");
    exit;
}

$order = $_SESSION['checkout_order'];
$bus = get_bus_by_id($order['bus_id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $payMethod = sanitize($_POST['payment_method'] ?? 'UPI');
    $order['payment_method'] = $payMethod;

    // Create the booking in DB/storage
    $pnr = create_booking($order);

    // Clear checkout session
    unset($_SESSION['checkout_order']);

    header("Location: confirmation.php?pnr=" . urlencode($pnr));
    exit;
}

require_once __DIR__ . '/header.php';
?>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
                <div class="card-header bg-white py-3 border-bottom d-flex align-items-center justify-content-between">
                    <div>
                        <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-shield-check text-success me-2"></i>Secure Payment Gateway</h5>
                        <div class="small text-muted">256-bit SSL Encrypted Transaction</div>
                    </div>
                    <div class="text-end">
                        <div class="small text-muted">Amount to Pay</div>
                        <div class="fw-bold text-danger fs-4">₹<?= number_format($order['total_amount'], 2) ?></div>
                    </div>
                </div>

                <div class="card-body p-4">
                    <form action="payment.php" method="POST" id="paymentForm">
                        <input type="hidden" name="payment_method" id="selectedPaymentMethod" value="UPI">

                        <!-- Payment Method Tabs -->
                        <h6 class="fw-bold text-secondary mb-3">Choose Payment Mode</h6>
                        
                        <div class="list-group mb-4">
                            <!-- Option 1: UPI -->
                            <label class="list-group-item d-flex gap-3 py-3 align-items-center border rounded mb-2">
                                <input class="form-check-input flex-shrink-0" type="radio" name="pay_type" value="UPI" checked onchange="document.getElementById('selectedPaymentMethod').value='UPI'">
                                <div class="d-flex align-items-center justify-content-between w-100">
                                    <div>
                                        <div class="fw-bold text-dark">UPI (Google Pay, PhonePe, Paytm, BHIM)</div>
                                        <div class="small text-muted">Instant & Zero Payment Gateway fee</div>
                                    </div>
                                    <span class="badge bg-success-subtle text-success">Recommended</span>
                                </div>
                            </label>

                            <!-- Option 2: Cards -->
                            <label class="list-group-item d-flex gap-3 py-3 align-items-center border rounded mb-2">
                                <input class="form-check-input flex-shrink-0" type="radio" name="pay_type" value="CARD" onchange="document.getElementById('selectedPaymentMethod').value='CARD'">
                                <div>
                                    <div class="fw-bold text-dark">Debit / Credit Card</div>
                                    <div class="small text-muted">Visa, MasterCard, RuPay, Maestro</div>
                                </div>
                            </label>

                            <!-- Option 3: Net Banking -->
                            <label class="list-group-item d-flex gap-3 py-3 align-items-center border rounded">
                                <input class="form-check-input flex-shrink-0" type="radio" name="pay_type" value="NETBANKING" onchange="document.getElementById('selectedPaymentMethod').value='NETBANKING'">
                                <div>
                                    <div class="fw-bold text-dark">Net Banking</div>
                                    <div class="small text-muted">SBI, Canara Bank, Federal Bank, Kerala Bank & 50+ banks</div>
                                </div>
                            </label>
                        </div>

                        <!-- Card details mock input -->
                        <div class="p-3 bg-light rounded-3 mb-4">
                            <div class="d-flex align-items-center gap-2 mb-2 text-secondary small fw-bold">
                                <i class="bi bi-info-circle-fill text-primary"></i> Simulation Mode:
                            </div>
                            <div class="small text-muted">
                                This is a simulation sandbox. Click the button below to instantly complete payment and generate your verified KSRTC e-Ticket with PNR and QR Code.
                            </div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-ksrtc-primary py-3 fs-5" id="btnSubmitPayment">
                                <i class="bi bi-lock-fill me-2"></i> Pay ₹<?= number_format($order['total_amount'], 2) ?> & Confirm Ticket
                            </button>
                            <a href="checkout.php?bus_id=<?= $order['bus_id'] ?>&seats=<?= urlencode($order['seat_numbers']) ?>" class="btn btn-link text-secondary text-center text-decoration-none">
                                <i class="bi bi-arrow-left me-1"></i> Back to Passenger Details
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/footer.php'; ?>

