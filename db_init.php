<?php
/**
 * Database Initialization
 * Sets up database tables or JSON file storage
 */

$initialBuses = [
    [
        'id' => 1,
        'from_city' => 'Bengaluru',
        'from_code' => 'BLR',
        'to_city' => 'Kalpetta',
        'to_code' => 'KPT',
        'departure_time' => '08:15 AM',
        'arrival_time' => '04:45 PM',
        'duration' => '8 hours and 30 minutes',
        'trip_code' => 'DXB34N59012',
        'bus_type' => 'SUPER DELUX AIR BUS',
        'fare' => 275.00,
        'total_seats' => 40,
        'pickup_points' => 'Majestic Bus Stand (08:15 AM), Shantinagar TTMC (08:45 AM), Electronic City Toll (09:15 AM)',
        'drop_points' => 'Sultan Bathery (03:45 PM), Kalpetta Old Stand (04:15 PM), Kalpetta KSRTC Depot (04:45 PM)',
        'booked_seats' => '1,4,7,12,18,25'
    ],
    [
        'id' => 2,
        'from_city' => 'Bengaluru',
        'from_code' => 'BLR',
        'to_city' => 'Kalpetta',
        'to_code' => 'KPT',
        'departure_time' => '10:15 AM',
        'arrival_time' => '04:30 PM',
        'duration' => '6 hours and 30 minutes',
        'trip_code' => 'DXB34N59015',
        'bus_type' => 'SUPER DELUX AIR BUS',
        'fare' => 339.00,
        'total_seats' => 40,
        'pickup_points' => 'Majestic Bus Stand (10:15 AM), Satellite Bus Stand (10:45 AM), Kengeri (11:15 AM)',
        'drop_points' => 'Sultan Bathery (03:30 PM), Chundale (04:10 PM), Kalpetta New Stand (04:30 PM)',
        'booked_seats' => '2,3,9,14,21'
    ],
    [
        'id' => 3,
        'from_city' => 'Bengaluru',
        'from_code' => 'BLR',
        'to_city' => 'Kalpetta',
        'to_code' => 'KPT',
        'departure_time' => '10:15 AM',
        'arrival_time' => '02:30 PM',
        'duration' => '5 hours and 30 minutes',
        'trip_code' => 'DXB34N59015',
        'bus_type' => 'SCANIA A/C SEMI SLEEPER',
        'fare' => 440.00,
        'total_seats' => 36,
        'pickup_points' => 'Majestic Bus Stand (10:15 AM), Silk Board (10:45 AM), Electronic City (11:15 AM)',
        'drop_points' => 'Sultan Bathery (01:45 PM), Muttil (02:10 PM), Kalpetta KSRTC Depot (02:30 PM)',
        'booked_seats' => '5,6,11,15,16,22,23'
    ],
    [
        'id' => 4,
        'from_city' => 'Bengaluru',
        'from_code' => 'BLR',
        'to_city' => 'Kalpetta',
        'to_code' => 'KPT',
        'departure_time' => '10:15 AM',
        'arrival_time' => '02:30 PM',
        'duration' => '5 hours and 30 minutes',
        'trip_code' => 'DXB34N59015',
        'bus_type' => 'SUPER DELUX AIR BUS',
        'fare' => 480.00,
        'total_seats' => 40,
        'pickup_points' => 'Majestic Bus Stand (10:15 AM), Shantinagar TTMC (10:45 AM), Attibele Toll (11:15 AM)',
        'drop_points' => 'Sultan Bathery (01:45 PM), Meenangadi (02:10 PM), Kalpetta KSRTC Depot (02:30 PM)',
        'booked_seats' => '3,8,13,19,26,31'
    ]
];

if ($useSqlite && $pdo) {
    // Create buses table
    $pdo->exec("CREATE TABLE IF NOT EXISTS buses (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        from_city TEXT NOT NULL,
        from_code TEXT NOT NULL,
        to_city TEXT NOT NULL,
        to_code TEXT NOT NULL,
        departure_time TEXT NOT NULL,
        arrival_time TEXT NOT NULL,
        duration TEXT NOT NULL,
        trip_code TEXT NOT NULL,
        bus_type TEXT NOT NULL,
        fare REAL NOT NULL,
        total_seats INTEGER NOT NULL,
        pickup_points TEXT NOT NULL,
        drop_points TEXT NOT NULL,
        booked_seats TEXT DEFAULT ''
    )");

    // Create bookings table
    $pdo->exec("CREATE TABLE IF NOT EXISTS bookings (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        pnr TEXT UNIQUE NOT NULL,
        bus_id INTEGER NOT NULL,
        journey_date TEXT NOT NULL,
        passengers_data TEXT NOT NULL,
        seat_numbers TEXT NOT NULL,
        total_seats INTEGER NOT NULL,
        fare_amount REAL NOT NULL,
        tax_amount REAL NOT NULL,
        total_amount REAL NOT NULL,
        pickup_point TEXT NOT NULL,
        drop_point TEXT NOT NULL,
        customer_name TEXT NOT NULL,
        customer_email TEXT NOT NULL,
        customer_phone TEXT NOT NULL,
        booking_status TEXT DEFAULT 'CONFIRMED',
        payment_method TEXT DEFAULT 'ONLINE',
        booking_time DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Create users table
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT NOT NULL,
        email TEXT UNIQUE NOT NULL,
        phone TEXT NOT NULL,
        password TEXT NOT NULL,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    // Check if buses table is empty, seed if so
    $count = $pdo->query("SELECT COUNT(*) FROM buses")->fetchColumn();
    if ($count == 0) {
        $stmt = $pdo->prepare("INSERT INTO buses (id, from_city, from_code, to_city, to_code, departure_time, arrival_time, duration, trip_code, bus_type, fare, total_seats, pickup_points, drop_points, booked_seats) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        foreach ($initialBuses as $b) {
            $stmt->execute([
                $b['id'], $b['from_city'], $b['from_code'], $b['to_city'], $b['to_code'],
                $b['departure_time'], $b['arrival_time'], $b['duration'], $b['trip_code'],
                $b['bus_type'], $b['fare'], $b['total_seats'], $b['pickup_points'],
                $b['drop_points'], $b['booked_seats']
            ]);
        }
    }
} else {
    // JSON Storage Fallback
    if (!file_exists($jsonStorageFile)) {
        $data = [
            'buses' => $initialBuses,
            'bookings' => [],
            'users' => [
                [
                    'id' => 1,
                    'name' => 'Demo Passenger',
                    'email' => 'user@ksrtc.in',
                    'phone' => '9876543210',
                    'password' => password_hash('password123', PASSWORD_DEFAULT),
                    'created_at' => date('Y-m-d H:i:s')
                ]
            ]
        ];
        file_put_contents($jsonStorageFile, json_encode($data, JSON_PRETTY_PRINT));
    }
}

// Data access helper functions
function get_all_buses() {
    global $useSqlite, $pdo, $jsonStorageFile;
    if ($useSqlite && $pdo) {
        $stmt = $pdo->query("SELECT * FROM buses ORDER BY id ASC");
        return $stmt->fetchAll();
    } else {
        $data = json_decode(file_get_contents($jsonStorageFile), true);
        return $data['buses'] ?? [];
    }
}

function get_bus_by_id($id) {
    global $useSqlite, $pdo, $jsonStorageFile;
    if ($useSqlite && $pdo) {
        $stmt = $pdo->prepare("SELECT * FROM buses WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    } else {
        $data = json_decode(file_get_contents($jsonStorageFile), true);
        foreach ($data['buses'] as $bus) {
            if ($bus['id'] == $id) return $bus;
        }
        return null;
    }
}

function create_booking($bookingData) {
    global $useSqlite, $pdo, $jsonStorageFile;
    if ($useSqlite && $pdo) {
        $stmt = $pdo->prepare("INSERT INTO bookings (
            pnr, bus_id, journey_date, passengers_data, seat_numbers,
            total_seats, fare_amount, tax_amount, total_amount,
            pickup_point, drop_point, customer_name, customer_email,
            customer_phone, booking_status, payment_method
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        $stmt->execute([
            $bookingData['pnr'],
            $bookingData['bus_id'],
            $bookingData['journey_date'],
            json_encode($bookingData['passengers']),
            $bookingData['seat_numbers'],
            $bookingData['total_seats'],
            $bookingData['fare_amount'],
            $bookingData['tax_amount'],
            $bookingData['total_amount'],
            $bookingData['pickup_point'],
            $bookingData['drop_point'],
            $bookingData['customer_name'],
            $bookingData['customer_email'],
            $bookingData['customer_phone'],
            $bookingData['booking_status'] ?? 'CONFIRMED',
            $bookingData['payment_method'] ?? 'ONLINE'
        ]);

        // Update booked seats on bus
        $bus = get_bus_by_id($bookingData['bus_id']);
        if ($bus) {
            $existing = array_filter(explode(',', $bus['booked_seats']));
            $newSeats = explode(',', $bookingData['seat_numbers']);
            $merged = array_unique(array_merge($existing, $newSeats));
            $updStmt = $pdo->prepare("UPDATE buses SET booked_seats = ? WHERE id = ?");
            $updStmt->execute([implode(',', $merged), $bookingData['bus_id']]);
        }

        return $bookingData['pnr'];
    } else {
        $data = json_decode(file_get_contents($jsonStorageFile), true);
        $bookingData['id'] = count($data['bookings']) + 1;
        $bookingData['passengers_data'] = json_encode($bookingData['passengers']);
        $bookingData['booking_status'] = $bookingData['booking_status'] ?? 'CONFIRMED';
        $bookingData['payment_method'] = $bookingData['payment_method'] ?? 'ONLINE';
        $bookingData['booking_time'] = date('Y-m-d H:i:s');
        $data['bookings'][] = $bookingData;

        // Update booked seats on bus
        foreach ($data['buses'] as &$bus) {
            if ($bus['id'] == $bookingData['bus_id']) {
                $existing = array_filter(explode(',', $bus['booked_seats']));
                $newSeats = explode(',', $bookingData['seat_numbers']);
                $merged = array_unique(array_merge($existing, $newSeats));
                $bus['booked_seats'] = implode(',', $merged);
                break;
            }
        }

        file_put_contents($jsonStorageFile, json_encode($data, JSON_PRETTY_PRINT));
        return $bookingData['pnr'];
    }
}

function get_booking_by_pnr($pnr) {
    global $useSqlite, $pdo, $jsonStorageFile;
    if ($useSqlite && $pdo) {
        $stmt = $pdo->prepare("SELECT b.*, bus.trip_code, bus.bus_type, bus.from_city, bus.from_code, bus.to_city, bus.to_code, bus.departure_time, bus.arrival_time, bus.duration FROM bookings b JOIN buses bus ON b.bus_id = bus.id WHERE b.pnr = ?");
        $stmt->execute([$pnr]);
        return $stmt->fetch();
    } else {
        $data = json_decode(file_get_contents($jsonStorageFile), true);
        foreach ($data['bookings'] as $b) {
            if (strtoupper($b['pnr']) === strtoupper($pnr)) {
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
                    $b['duration'] = $bus['duration'];
                }
                return $b;
            }
        }
        return null;
    }
}

function cancel_booking_by_pnr($pnr) {
    global $useSqlite, $pdo, $jsonStorageFile;
    if ($useSqlite && $pdo) {
        $stmt = $pdo->prepare("UPDATE bookings SET booking_status = 'CANCELLED' WHERE pnr = ?");
        $stmt->execute([$pnr]);
        return $stmt->rowCount() > 0;
    } else {
        $data = json_decode(file_get_contents($jsonStorageFile), true);
        foreach ($data['bookings'] as &$b) {
            if (strtoupper($b['pnr']) === strtoupper($pnr)) {
                $b['booking_status'] = 'CANCELLED';
                file_put_contents($jsonStorageFile, json_encode($data, JSON_PRETTY_PRINT));
                return true;
            }
        }
        return false;
    }
}

