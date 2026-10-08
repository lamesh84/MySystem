<?php
require_once __DIR__ . '/config.php';

// Get search filters
$fromCity = isset($_GET['from']) ? sanitize($_GET['from']) : 'Bengaluru';
$toCity = isset($_GET['to']) ? sanitize($_GET['to']) : 'Kalpetta';
$dateInput = isset($_GET['date']) ? sanitize($_GET['date']) : '2026-08-24';
$busTypeFilter = isset($_GET['bus_type']) ? sanitize($_GET['bus_type']) : 'ALL';

// Format human date
$timestamp = strtotime($dateInput);
$displayDate = ($dateInput === '2026-08-24' || !$timestamp) ? 'Friday, 24 August' : date('l, d F', $timestamp);

// Retrieve buses
$allBuses = get_all_buses();
$filteredBuses = [];

foreach ($allBuses as $b) {
    if ($busTypeFilter !== 'ALL' && strcasecmp($b['bus_type'], $busTypeFilter) !== 0) {
        continue;
    }
    $filteredBuses[] = $b;
}

// Fallback to initial 4 buses if none found
if (empty($filteredBuses)) {
    $filteredBuses = $allBuses;
}

$directCount = count($filteredBuses);

require_once __DIR__ . '/header.php';
?>

<!-- Hidden input for selected date tracker -->
<input type="hidden" id="currentJourneyDate" value="<?= htmlspecialchars($dateInput) ?>">

<!-- ========================================================
     FLOATING ROUTE MODIFIER STRIP
     ======================================================== -->
<section class="route-strip-wrapper">
    <div class="container">
        <div class="route-strip-card">
            <!-- Left Side: Route and Modify Button -->
            <div class="route-destinations">
                <span class="route-city"><?= htmlspecialchars($fromCity) ?></span>
                <span class="route-arrow">&rarr;</span>
                <span class="route-city"><?= htmlspecialchars($toCity) ?></span>
                <button type="button" class="btn-modify" data-bs-toggle="modal" data-bs-target="#modifySearchModal">
                    Modify
                </button>
            </div>

            <!-- Right Side: Date Navigation -->
            <div class="date-pagination">
                <button type="button" class="date-btn" id="prevDateBtn" title="Previous Day">
                    <i class="bi bi-chevron-left"></i>
                </button>
                <span class="date-display-text" id="displayDateText"><?= htmlspecialchars($displayDate) ?></span>
                <button type="button" class="date-btn" id="nextDateBtn" title="Next Day">
                    <i class="bi bi-chevron-right"></i>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- ========================================================
     MAIN BUS LISTINGS SECTION
     ======================================================== -->
<main class="listing-section">
    <div class="container">
        <!-- Results Meta Header -->
        <div class="results-meta-row">
            <h2 class="results-title mb-0"><?= $directCount ?> Direct buses available</h2>

            <!-- Sort Dropdown -->
            <div class="dropdown">
                <button class="sort-dropdown-btn dropdown-toggle" type="button" id="sortDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <span id="currentSortLabel">Sort by Price</span>
                </button>
                <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0" aria-labelledby="sortDropdown">
                    <li><a class="dropdown-item sort-option" href="#" data-sort="price-low">Price: Low to High</a></li>
                    <li><a class="dropdown-item sort-option" href="#" data-sort="price-high">Price: High to Low</a></li>
                    <li><a class="dropdown-item sort-option" href="#" data-sort="departure">Departure Time</a></li>
                </ul>
            </div>
        </div>

        <!-- Bus Cards Container -->
        <div id="busCardsContainer">
            <?php foreach ($filteredBuses as $bus): 
                $pickupList = array_map('trim', explode(',', $bus['pickup_points']));
                $dropList = array_map('trim', explode(',', $bus['drop_points']));
                $pickupCount = count($pickupList);
                $dropCount = count($dropList);
            ?>
            <article class="bus-card" id="bus-card-<?= $bus['id'] ?>" data-fare="<?= $bus['fare'] ?>" data-dep="<?= $bus['departure_time'] ?>">
                <div class="bus-card-body">
                    <!-- Column 1: Departure -->
                    <div class="departure-col">
                        <span class="meta-label">Departure</span>
                        <div class="city-line-origin">
                            <span class="station-title"><?= strtoupper(htmlspecialchars($bus['from_city'])) ?></span>
                            <span class="station-code"><?= htmlspecialchars($bus['from_code']) ?></span>
                            <div class="origin-circle"></div>
                        </div>
                        <div class="time-val"><?= htmlspecialchars($bus['departure_time']) ?></div>
                        <span class="point-count" data-type="pickup" data-points="<?= htmlspecialchars($bus['pickup_points']) ?>">
                            <?= $pickupCount ?> pickup points
                        </span>
                    </div>

                    <!-- Column 2: Journey Timeline & Bus Info -->
                    <div class="timeline-col">
                        <div class="duration-text"><?= htmlspecialchars($bus['duration']) ?></div>
                        
                        <!-- Visual Timeline Diagram -->
                        <div class="timeline-track-bar">
                            <div class="timeline-red-line"></div>
                            <div class="timeline-dot"></div>
                            <div class="timeline-dot"></div>
                        </div>

                        <!-- Trip Code -->
                        <div class="trip-code-box">
                            <span class="trip-code-label">Trip Code</span>
                            <span class="trip-code-val"><?= htmlspecialchars($bus['trip_code']) ?></span>
                        </div>

                        <!-- Bus Type -->
                        <div class="bus-type-text"><?= htmlspecialchars($bus['bus_type']) ?></div>
                    </div>

                    <!-- Column 3: Arrival -->
                    <div class="arrival-col">
                        <span class="meta-label">Arrival</span>
                        <div class="city-line-dest">
                            <div class="dest-circle"></div>
                            <span class="station-title"><?= strtoupper(htmlspecialchars($bus['to_city'])) ?></span>
                            <span class="station-code"><?= htmlspecialchars($bus['to_code']) ?></span>
                        </div>
                        <div class="time-val"><?= htmlspecialchars($bus['arrival_time']) ?></div>
                        <span class="point-count" data-type="drop" data-points="<?= htmlspecialchars($bus['drop_points']) ?>">
                            <?= $dropCount ?> drop points
                        </span>
                    </div>

                    <!-- Column 4: Price & Select Action -->
                    <div class="action-col">
                        <div class="price-text">₹<?= number_format($bus['fare'], 2) ?></div>
                        <div class="price-subtext">
                            <i class="bi bi-info-circle-fill"></i>
                            <span>Exclusive of booking and payment gateway charges</span>
                        </div>
                        <button type="button" class="btn-select btn-select-toggle" data-bus-id="<?= $bus['id'] ?>" data-fare="<?= $bus['fare'] ?>">
                            Select
                        </button>
                    </div>
                </div>

                <!-- ========================================================
                     EXPANDABLE INTERACTIVE SEAT SELECTION DRAWER
                     ======================================================== -->
                <div class="seat-picker-drawer" id="seatDrawer-<?= $bus['id'] ?>">
                    <div class="seat-drawer-inner">
                        <!-- Left: Bus Seat Layout Frame -->
                        <div class="bus-chassis">
                            <div class="bus-front-header">
                                <span class="badge bg-light text-secondary border me-auto small">Front / Entry</span>
                                <i class="bi bi-compass steering-icon" title="Driver Cabin"></i>
                            </div>

                            <!-- Seats Grid -->
                            <div class="seats-grid" id="seatsContainer-<?= $bus['id'] ?>" data-booked="<?= htmlspecialchars($bus['booked_seats']) ?>">
                                <!-- Generated by JavaScript -->
                            </div>

                            <!-- Legend -->
                            <div class="seat-legend">
                                <div class="legend-item">
                                    <div class="legend-box legend-avail"></div>
                                    <span>Available</span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-box legend-sel"></div>
                                    <span>Selected</span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-box legend-booked"></div>
                                    <span>Booked</span>
                                </div>
                                <div class="legend-item">
                                    <div class="legend-box legend-ladies"></div>
                                    <span>Ladies</span>
                                </div>
                            </div>
                        </div>

                        <!-- Right: Boarding / Dropping & Fare Breakdown -->
                        <div class="seat-summary-panel">
                            <div>
                                <h6 class="fw-bold text-dark mb-3">Select Boarding & Dropping Point</h6>
                                <div class="selection-points-row">
                                    <!-- Boarding Point Selector -->
                                    <div class="point-select-card">
                                        <div class="point-select-title">
                                            <i class="bi bi-geo-alt-fill text-danger"></i> Boarding Point
                                        </div>
                                        <div class="point-radio-group">
                                            <?php foreach ($pickupList as $idx => $pt): ?>
                                                <label class="point-radio-label">
                                                    <input type="radio" name="pickup_<?= $bus['id'] ?>" value="<?= htmlspecialchars($pt) ?>" <?= $idx === 0 ? 'checked' : '' ?>>
                                                    <span><?= htmlspecialchars($pt) ?></span>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>

                                    <!-- Dropping Point Selector -->
                                    <div class="point-select-card">
                                        <div class="point-select-title">
                                            <i class="bi bi-geo-fill text-success"></i> Dropping Point
                                        </div>
                                        <div class="point-radio-group">
                                            <?php foreach ($dropList as $idx => $pt): ?>
                                                <label class="point-radio-label">
                                                    <input type="radio" name="drop_<?= $bus['id'] ?>" value="<?= htmlspecialchars($pt) ?>" <?= $idx === 0 ? 'checked' : '' ?>>
                                                    <span><?= htmlspecialchars($pt) ?></span>
                                                </label>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Bottom Fare & Booking Checkout CTA -->
                            <div class="fare-summary-card">
                                <div>
                                    <div class="fare-calc-text">
                                        Selected Seats: <strong id="selectedSeatsList-<?= $bus['id'] ?>" class="text-dark">None</strong>
                                        (<span id="selectedSeatsCount-<?= $bus['id'] ?>">0</span> seats)
                                    </div>
                                    <div class="small text-muted mt-1">Bus: <?= htmlspecialchars($bus['bus_type']) ?></div>
                                </div>

                                <div class="d-flex align-items-center gap-3">
                                    <div class="text-end">
                                        <div class="small text-muted">Total Fare</div>
                                        <div class="fare-calc-amount" id="selectedTotalAmount-<?= $bus['id'] ?>">₹0.00</div>
                                    </div>

                                    <a href="#" class="btn-proceed-book" id="btnProceed-<?= $bus['id'] ?>" disabled>
                                        <span>Proceed to Book</span>
                                        <i class="bi bi-arrow-right"></i>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
    </div>
</main>

<?php
require_once __DIR__ . '/footer.php';
?>

