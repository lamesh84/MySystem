# Kerala State Road Transport Corporation (KSRTC) - Online Bus Ticket Booking System

A modern, full-stack online bus reservation system designed to **100% match the Kerala State Road Transport Corporation design layout**, built using **HTML5, CSS3, JavaScript, PHP, and Bootstrap 5**.

---

## 🌟 Key Features

1. **Pixel-Perfect Reference Design Clone**:
   - **Hero Header**: Vibrant gradient (`#ff3358` to `#ff2949`), subtle geometric watermark motifs on the top-left.
   - **Branding**: Vector SVG Kerala RTC Emblem with "Kerala State Road Transport Corporation".
   - **Top Navigation**: Search icon, *Top Destinations*, *View ticket*, *Cancellation*, *Register*, and *Sign in*.
   - **Guest / Sign-in Pill**: "Continue booking as guest or [X] Click here to sign in and book &rarr;".
   - **Floating Route Strip**: Bengaluru &rarr; Kalpetta, `[Modify]` button, and date navigation `[<] Friday, 24 August [>]`.
   - **Direct Bus Count & Sort**: "4 Direct buses available" with live `Sort by Price` / `Departure Time` dropdown.
   - **Exact Bus Cards**:
     - **Card 1**: Super Delux Air Bus (08:15 AM - 04:45 PM, 8h 30m, Trip DXB34N59012, ₹275.00)
     - **Card 2**: Super Delux Air Bus (10:15 AM - 04:30 PM, 6h 30m, Trip DXB34N59015, ₹339.00)
     - **Card 3**: Scania A/C Semi Sleeper (10:15 AM - 02:30 PM, 5h 30m, Trip DXB34N59015, ₹440.00)
     - **Card 4**: Super Delux Air Bus (10:15 AM - 02:30 PM, 5h 30m, Trip DXB34N59015, ₹480.00)

2. **Interactive Seat Selection Engine**:
   - Clicking **"Select"** on any bus card smoothly slides down an interactive luxury coach seat layout.
   - 2+2 seat configuration with Driver cabin indicator, Ladies-reserved seats, Booked seats, and real-time Available/Selected states.
   - Boarding point and Dropping point radio selector.
   - Real-time live fare and seat counter updates.

3. **Complete End-to-End Booking Workflow**:
   - **Passenger Details (`checkout.php`)**: Collects individual passenger names, ages, genders, and primary contact info.
   - **Payment Simulation (`payment.php`)**: UPI, Debit/Credit Card, and Net Banking checkout simulation.
   - **Printable E-Ticket (`confirmation.php`)**: Generates an official Kerala RTC Electronic Reservation Slip (ERS) with unique PNR, scannable QR Code, passenger list, boarding instructions, and a print stylesheet.
   - **View Ticket (`view_ticket.php`)**: Quick search and print by PNR.
   - **Ticket Cancellation (`cancel_ticket.php`)**: PNR lookup with cancellation rules, 10% deduction calculation, and instant refund processing.
   - **User Accounts (`auth.php`, `my_bookings.php`)**: Seamless guest checkout OR account registration & login with personal booking history.

4. **Zero-Configuration Backend**:
   - Uses **SQLite with PDO** (`data/ksrtc_ticket.sqlite`) with automated fallback to JSON persistence (`data/storage.json`).
   - Works immediately out-of-the-box on XAMPP with no manual SQL import needed!

---

## 🚀 How to Run in XAMPP

1. Ensure **Apache** is running in your **XAMPP Control Panel**.
2. Open your web browser and navigate to:
   ```
   http://localhost/online%20ticket/
   ```
3. Test credentials (optional):
   - **Email:** `user@ksrtc.in`
   - **Password:** `password123`
   *(Or book immediately as guest!)*

