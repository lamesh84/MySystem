    <!-- ========================================================
         FOOTER SECTION
         ======================================================== -->
    <footer class="ksrtc-footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-lg-4 col-md-6">
                    <div class="d-flex align-items-center gap-2 mb-3">
                        <img src="assets/images/ksrtc-logo.svg" alt="KSRTC Logo" style="width: 34px; height: 34px;">
                        <span class="footer-brand-title mb-0">Kerala State Road Transport Corporation</span>
                    </div>
                    <p class="small text-secondary">
                        Government of Kerala undertaking providing safe, reliable, and punctual public passenger road transport services across Kerala and neighboring states.
                    </p>
                    <div class="d-flex gap-3 text-white fs-5 mt-3">
                        <a href="#" class="text-secondary"><i class="bi bi-facebook"></i></a>
                        <a href="#" class="text-secondary"><i class="bi bi-twitter-x"></i></a>
                        <a href="#" class="text-secondary"><i class="bi bi-instagram"></i></a>
                        <a href="#" class="text-secondary"><i class="bi bi-youtube"></i></a>
                    </div>
                </div>

                <div class="col-lg-2 col-md-3 col-6">
                    <h6 class="text-white fw-bold mb-3">Quick Links</h6>
                    <a href="index.php" class="footer-link">Home</a>
                    <a href="view_ticket.php" class="footer-link">Print Ticket</a>
                    <a href="cancel_ticket.php" class="footer-link">Cancel Ticket</a>
                    <a href="#" class="footer-link" data-bs-toggle="modal" data-bs-target="#topDestinationsModal">Top Routes</a>
                </div>

                <div class="col-lg-2 col-md-3 col-6">
                    <h6 class="text-white fw-bold mb-3">Bus Services</h6>
                    <span class="footer-link">Super Deluxe Air Bus</span>
                    <span class="footer-link">Scania A/C Multi Axle</span>
                    <span class="footer-link">Minar Semi Sleeper</span>
                    <span class="footer-link">Garuda Maharaja A/C</span>
                </div>

                <div class="col-lg-4 col-md-6">
                    <h6 class="text-white fw-bold mb-3">24x7 Customer Support</h6>
                    <p class="small text-secondary mb-1"><i class="bi bi-telephone-fill me-2 text-danger"></i> Toll Free: 1800-599-4011 / 0471-2463799</p>
                    <p class="small text-secondary mb-1"><i class="bi bi-envelope-fill me-2 text-danger"></i> Support: support@onlineksrtcswift.com</p>
                    <p class="small text-secondary mb-3"><i class="bi bi-geo-alt-fill me-2 text-danger"></i> Transport Bhavan, Fort, Thiruvananthapuram - 695023</p>
                    <div class="p-2 rounded bg-dark border border-secondary border-opacity-25 small text-secondary">
                        🔒 Safe & Secure 256-Bit SSL Encrypted Payment Gateway
                    </div>
                </div>
            </div>

            <div class="footer-bottom">
                <p class="mb-0">&copy; <?= date('Y') ?> Kerala State Road Transport Corporation (KSRTC). All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- ========================================================
         MODALS
         ======================================================== -->

    <!-- 1. AUTH MODAL (Sign In & Register) -->
    <div class="modal fade" id="authModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header-ksrtc d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <img src="assets/images/ksrtc-logo.svg" style="width: 28px; height: 28px;">
                        <h5 class="modal-title fw-bold text-white mb-0" id="authModalTitle">Sign in to KSRTC</h5>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <ul class="nav nav-pills nav-fill mb-4 p-1 bg-light rounded" id="authTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active fw-bold py-2" id="login-tab" data-bs-toggle="pill" data-bs-target="#login-tab-pane" type="button" role="tab">Sign In</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-bold py-2" id="register-tab" data-bs-toggle="pill" data-bs-target="#register-tab-pane" type="button" role="tab">Create Account</button>
                        </li>
                    </ul>

                    <div class="tab-content" id="authTabsContent">
                        <!-- Sign In Tab -->
                        <div class="tab-pane fade show active" id="login-tab-pane" role="tabpanel">
                            <form action="auth.php" method="POST">
                                <input type="hidden" name="action" value="login">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Email or Phone Number</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                                        <input type="text" name="identifier" class="form-control" placeholder="user@ksrtc.in" required value="user@ksrtc.in">
                                    </div>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Password</label>
                                    <div class="input-group">
                                        <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                                        <input type="password" name="password" class="form-control" placeholder="••••••••" required value="password123">
                                    </div>
                                    <div class="form-text small text-end"><a href="#" class="text-decoration-none text-danger">Forgot Password?</a></div>
                                </div>
                                <button type="submit" class="btn btn-ksrtc-primary w-100 py-2 fs-6">Sign In & Continue</button>
                            </form>
                        </div>

                        <!-- Register Tab -->
                        <div class="tab-pane fade" id="register-tab-pane" role="tabpanel">
                            <form action="auth.php" method="POST">
                                <input type="hidden" name="action" value="register">
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Full Name</label>
                                    <input type="text" name="name" class="form-control" placeholder="John Doe" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Email Address</label>
                                    <input type="email" name="email" class="form-control" placeholder="john@example.com" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Mobile Number</label>
                                    <input type="tel" name="phone" class="form-control" placeholder="9876543210" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small fw-bold text-secondary">Create Password</label>
                                    <input type="password" name="password" class="form-control" placeholder="••••••••" required>
                                </div>
                                <button type="submit" class="btn btn-ksrtc-primary w-100 py-2 fs-6">Register Account</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 2. MODIFY SEARCH MODAL -->
    <div class="modal fade" id="modifySearchModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header-ksrtc d-flex justify-content-between align-items-center">
                    <h5 class="modal-title fw-bold text-white mb-0"><i class="bi bi-sliders2 me-2"></i>Modify Search</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form action="index.php" method="GET">
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Leaving From</label>
                            <select name="from" class="form-select py-2">
                                <option value="Bengaluru" selected>Bengaluru (BLR)</option>
                                <option value="Kozhikode">Kozhikode (CCJ)</option>
                                <option value="Ernakulam">Ernakulam (ERS)</option>
                                <option value="Thiruvananthapuram">Thiruvananthapuram (TVC)</option>
                                <option value="Chennai">Chennai (MAS)</option>
                                <option value="Mysuru">Mysuru (MYS)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Going To</label>
                            <select name="to" class="form-select py-2">
                                <option value="Kalpetta" selected>Kalpetta (KPT)</option>
                                <option value="Bengaluru">Bengaluru (BLR)</option>
                                <option value="Sulthan Bathery">Sulthan Bathery (SBY)</option>
                                <option value="Mananthavady">Mananthavady (MDY)</option>
                                <option value="Kannur">Kannur (CAN)</option>
                                <option value="Thrissur">Thrissur (TCR)</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold text-secondary">Date of Journey</label>
                            <input type="date" name="date" class="form-control py-2" value="2026-08-24">
                        </div>
                        <div class="mb-4">
                            <label class="form-label small fw-bold text-secondary">Bus Category</label>
                            <select name="bus_type" class="form-select py-2">
                                <option value="ALL">All Categories</option>
                                <option value="SUPER DELUX AIR BUS">Super Delux Air Bus</option>
                                <option value="SCANIA A/C SEMI SLEEPER">Scania A/C Semi Sleeper</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-ksrtc-primary w-100 py-2">Search Buses</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- 3. TOP DESTINATIONS MODAL -->
    <div class="modal fade" id="topDestinationsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header-ksrtc d-flex justify-content-between align-items-center">
                    <h5 class="modal-title fw-bold text-white mb-0"><i class="bi bi-pin-map-fill me-2"></i>Top KSRTC Destinations</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-3">
                    <div class="list-group list-group-flush">
                        <a href="index.php?from=Bengaluru&to=Kalpetta" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                            <div>
                                <span class="fw-bold">Bengaluru &rarr; Kalpetta (Wayanad)</span>
                                <div class="small text-muted">Scenic Western Ghats route | Daily 14+ services</div>
                            </div>
                            <span class="badge bg-danger rounded-pill">Popular</span>
                        </a>
                        <a href="index.php?from=Bengaluru&to=Kozhikode" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                            <div>
                                <span class="fw-bold">Bengaluru &rarr; Kozhikode</span>
                                <div class="small text-muted">Super Deluxe Air Bus & Scania Multi-Axle</div>
                            </div>
                            <span class="badge bg-danger rounded-pill">Fast</span>
                        </a>
                        <a href="index.php?from=Bengaluru&to=Ernakulam" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                            <div>
                                <span class="fw-bold">Bengaluru &rarr; Ernakulam (Kochi)</span>
                                <div class="small text-muted">Garuda King Long & Volvo Sleeper</div>
                            </div>
                            <span class="badge bg-secondary rounded-pill">Overnight</span>
                        </a>
                        <a href="index.php?from=Bengaluru&to=Thiruvananthapuram" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3">
                            <div>
                                <span class="fw-bold">Bengaluru &rarr; Thiruvananthapuram</span>
                                <div class="small text-muted">Direct Super Fast & Swift Luxury</div>
                            </div>
                            <span class="badge bg-secondary rounded-pill">Daily</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- 4. POINTS MODAL -->
    <div class="modal fade" id="pointsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header-ksrtc d-flex justify-content-between align-items-center">
                    <h5 class="modal-title fw-bold text-white mb-0" id="pointsModalTitle">Boarding Points</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-0" id="pointsModalBody">
                    <!-- Populated dynamically -->
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <!-- Custom Main JS -->
    <script src="assets/js/main.js"></script>
    <script>
        // Tab switch handler for auth modal
        document.querySelectorAll('[data-bs-target="#authModal"]').forEach(el => {
            el.addEventListener('click', function() {
                const targetTab = this.getAttribute('data-tab');
                if (targetTab === 'register') {
                    const regBtn = document.getElementById('register-tab');
                    if (regBtn) new bootstrap.Tab(regBtn).show();
                } else {
                    const loginBtn = document.getElementById('login-tab');
                    if (loginBtn) new bootstrap.Tab(loginBtn).show();
                }
            });
        });
    </script>
</body>
</html>

