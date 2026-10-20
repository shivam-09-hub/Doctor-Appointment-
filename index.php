<?php
/**
 * Doctor Appointment Booking System
 * Main Single-Page Application (index.php)
 * 
 * Features:
 * - Dynamic MySQL doctor retrieval
 * - Glassmorphism UI layout
 * - Interactive hero, doctor cards, appointment form, and confirmation modal
 */

require_once 'db.php';

// Fetch all doctors from database
$doctors_query = "SELECT id, name, specialization, consultation_fee, image FROM doctors ORDER BY id ASC";
$doctors_result = $conn->query($doctors_query);

$doctors = [];
if ($doctors_result && $doctors_result->num_rows > 0) {
    while ($row = $doctors_result->fetch_assoc()) {
        $doctors[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MediCare - Doctor Appointment Booking System</title>
    <!-- Google Fonts for Modern Typography -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Main Glassmorphism Stylesheet -->
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <!-- Ambient Glowing Gradient Orbs for Glassmorphism Depth -->
    <div class="bg-orb orb-1"></div>
    <div class="bg-orb orb-2"></div>
    <div class="bg-orb orb-3"></div>

    <!-- 1. Navigation Bar -->
    <header class="navbar-wrapper">
        <nav class="navbar glass-card">
            <a href="#home" class="logo">
                <span class="logo-icon">✚</span>
                <span class="logo-text">Medi<span class="highlight">Care</span></span>
            </a>
            <ul class="nav-links">
                <li><a href="#home" class="nav-link active">Home</a></li>
                <li><a href="#doctors" class="nav-link">Doctors</a></li>
                <li><a href="#booking" class="nav-link">Book Appointment</a></li>
            </ul>
            <a href="#booking" class="btn btn-nav">Book Now</a>
        </nav>
    </header>

    <main class="main-container">

        <!-- 2. Hero Section -->
        <section id="home" class="hero-section">
            <div class="hero-content glass-card hero-glass">
                <div class="badge-pill">
                    <span class="badge-dot"></span>
                    <span>Trusted Healthcare Specialist Network</span>
                </div>
                <h1 class="hero-title">
                    Your Health, <br>
                    <span class="gradient-text">Our Priority</span>
                </h1>
                <p class="hero-desc">
                    Connect with certified medical specialists in cardiology, neurology, and pediatrics. 
                    Schedule confidential, hassle-free consultations with instant booking confirmation.
                </p>
                <div class="hero-actions">
                    <a href="#booking" class="btn btn-primary">
                        <span>Book Appointment</span>
                        <svg class="btn-icon" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="5" y1="12" x2="19" y2="12"></line>
                            <polyline points="12 5 19 12 12 19"></polyline>
                        </svg>
                    </a>
                    <a href="#doctors" class="btn btn-secondary">
                        <span>Meet Doctors</span>
                    </a>
                </div>
                <!-- Trust & Feature Badges -->
                <div class="hero-features">
                    <div class="feature-item">
                        <span class="feature-icon">🛡️</span>
                        <div>
                            <strong>Verified Doctors</strong>
                            <small>Top Medical Boards</small>
                        </div>
                    </div>
                    <div class="feature-divider"></div>
                    <div class="feature-item">
                        <span class="feature-icon">⚡</span>
                        <div>
                            <strong>Instant Slot</strong>
                            <small>Zero Wait Guarantee</small>
                        </div>
                    </div>
                    <div class="feature-divider"></div>
                    <div class="feature-item">
                        <span class="feature-icon">💳</span>
                        <div>
                            <strong>Transparent Fee</strong>
                            <small>No Hidden Charges</small>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- 3. Doctor Profiles Section -->
        <section id="doctors" class="section-container">
            <div class="section-header text-center">
                <span class="section-tag">Qualified Medical Staff</span>
                <h2 class="section-title">Meet Our Specialists</h2>
                <p class="section-subtitle">Choose from experienced professionals dedicated to providing personalized medical care.</p>
            </div>

            <div class="doctors-grid">
                <?php if (!empty($doctors)): ?>
                    <?php foreach ($doctors as $doctor): ?>
                        <div class="doctor-card glass-card">
                            <div class="doctor-img-wrap">
                                <img 
                                    src="<?= htmlspecialchars($doctor['image']) ?>" 
                                    alt="<?= htmlspecialchars($doctor['name']) ?>" 
                                    class="doctor-img"
                                    onerror="this.onerror=null; this.src='https://images.unsplash.com/photo-1622253692010-333f2da6031d?w=500&auto=format&fit=crop&q=80';"
                                >
                                <span class="doctor-specialization-badge"><?= htmlspecialchars($doctor['specialization']) ?></span>
                            </div>
                            <div class="doctor-info">
                                <h3 class="doctor-name"><?= htmlspecialchars($doctor['name']) ?></h3>
                                <p class="doctor-role"><?= htmlspecialchars($doctor['specialization']) ?></p>
                                
                                <div class="doctor-fee-box">
                                    <span class="fee-label">Consultation Fee</span>
                                    <span class="fee-amount">₹<?= number_format((float)$doctor['consultation_fee'], 0) ?></span>
                                </div>

                                <button 
                                    type="button" 
                                    class="btn btn-outline btn-block select-doctor-btn" 
                                    data-id="<?= $doctor['id'] ?>"
                                    data-name="<?= htmlspecialchars($doctor['name']) ?>"
                                    data-fee="<?= htmlspecialchars($doctor['consultation_fee']) ?>"
                                >
                                    Select & Book
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="glass-card text-center full-span p-4">
                        <p>No doctor records found in the database. Please verify MySQL import.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>

        <!-- 4. Appointment Booking Section -->
        <section id="booking" class="section-container">
            <div class="section-header text-center">
                <span class="section-tag">Fast & Confidential</span>
                <h2 class="section-title">Book Your Appointment</h2>
                <p class="section-subtitle">Fill in your information to reserve a confirmed time slot with your selected doctor.</p>
            </div>

            <div class="booking-wrapper">
                <div class="booking-card glass-card">

                    <!-- Alert message banner (for validation errors or notifications) -->
                    <div id="formAlert" class="alert-box hidden" role="alert"></div>

                    <form id="appointmentForm" action="save_appointment.php" method="POST" novalidate>
                        
                        <div class="form-grid">
                            <!-- Patient Full Name -->
                            <div class="form-group">
                                <label for="patient_name" class="form-label">Patient Full Name <span class="required">*</span></label>
                                <div class="input-wrapper">
                                    <span class="input-icon">👤</span>
                                    <input 
                                        type="text" 
                                        id="patient_name" 
                                        name="patient_name" 
                                        class="form-control" 
                                        placeholder="e.g. Rahul Sharma" 
                                        required 
                                        maxlength="100"
                                    >
                                </div>
                                <small class="field-error" id="nameError"></small>
                            </div>

                            <!-- Patient Phone Number -->
                            <div class="form-group">
                                <label for="phone" class="form-label">Phone Number <span class="required">*</span></label>
                                <div class="input-wrapper">
                                    <span class="input-icon">📞</span>
                                    <input 
                                        type="tel" 
                                        id="phone" 
                                        name="phone" 
                                        class="form-control" 
                                        placeholder="e.g. 9876543210" 
                                        required 
                                        maxlength="15"
                                    >
                                </div>
                                <small class="field-error" id="phoneError"></small>
                            </div>

                            <!-- Doctor Selection -->
                            <div class="form-group">
                                <label for="doctor_id" class="form-label">Choose Doctor <span class="required">*</span></label>
                                <div class="input-wrapper">
                                    <span class="input-icon">🩺</span>
                                    <select id="doctor_id" name="doctor_id" class="form-control" required>
                                        <option value="" disabled selected>-- Select a Doctor --</option>
                                        <?php foreach ($doctors as $doc): ?>
                                            <option 
                                                value="<?= $doc['id'] ?>" 
                                                data-fee="<?= htmlspecialchars($doc['consultation_fee']) ?>"
                                                data-spec="<?= htmlspecialchars($doc['specialization']) ?>"
                                            >
                                                <?= htmlspecialchars($doc['name']) ?> (<?= htmlspecialchars($doc['specialization']) ?>)
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <small class="field-error" id="doctorError"></small>
                            </div>

                            <!-- Dynamic Fee Display Indicator -->
                            <div class="form-group">
                                <label class="form-label">Consultation Fee</label>
                                <div class="fee-display-card">
                                    <span class="fee-display-title">Doctor Consultation:</span>
                                    <span id="feeDisplay" class="fee-display-value">Select doctor to view</span>
                                </div>
                            </div>

                            <!-- Appointment Date -->
                            <div class="form-group">
                                <label for="appointment_date" class="form-label">Appointment Date <span class="required">*</span></label>
                                <div class="input-wrapper">
                                    <span class="input-icon">📅</span>
                                    <input 
                                        type="date" 
                                        id="appointment_date" 
                                        name="appointment_date" 
                                        class="form-control" 
                                        min="<?= date('Y-m-d') ?>" 
                                        required
                                    >
                                </div>
                                <small class="field-error" id="dateError"></small>
                            </div>

                            <!-- Appointment Time Slot -->
                            <div class="form-group">
                                <label for="appointment_time" class="form-label">Select Time Slot <span class="required">*</span></label>
                                <div class="input-wrapper">
                                    <span class="input-icon">⏰</span>
                                    <select id="appointment_time" name="appointment_time" class="form-control" required>
                                        <option value="" disabled selected>-- Select a Slot --</option>
                                        <option value="09:00 AM - 10:00 AM">09:00 AM - 10:00 AM (Morning)</option>
                                        <option value="10:30 AM - 11:30 AM">10:30 AM - 11:30 AM (Morning)</option>
                                        <option value="12:00 PM - 01:00 PM">12:00 PM - 01:00 PM (Afternoon)</option>
                                        <option value="02:30 PM - 03:30 PM">02:30 PM - 03:30 PM (Afternoon)</option>
                                        <option value="04:00 PM - 05:00 PM">04:00 PM - 05:00 PM (Evening)</option>
                                        <option value="05:30 PM - 06:30 PM">05:30 PM - 06:30 PM (Evening)</option>
                                        <option value="07:00 PM - 08:00 PM">07:00 PM - 08:00 PM (Night)</option>
                                    </select>
                                </div>
                                <small class="field-error" id="timeError"></small>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="form-submit-row">
                            <button type="submit" id="submitBtn" class="btn btn-primary btn-submit">
                                <span class="btn-text">Confirm & Book Appointment</span>
                                <div class="spinner hidden" id="btnSpinner"></div>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </section>

    </main>

    <!-- 5. Booking Confirmation Modal -->
    <div id="confirmationModal" class="modal-overlay hidden" aria-hidden="true" role="dialog">
        <div class="modal-card glass-card">
            <div class="modal-badge-success">
                <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                    <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                    <polyline points="22 4 12 14.01 9 11.01"></polyline>
                </svg>
            </div>
            
            <h3 class="modal-title">Appointment Confirmed!</h3>
            <p class="modal-subtitle">Your appointment has been registered with the hospital system.</p>

            <!-- Detailed Confirmation Receipt -->
            <div class="receipt-card">
                <div class="receipt-row">
                    <span class="receipt-label">Booking Reference:</span>
                    <strong class="receipt-value highlight-token" id="confirmId">#APT-000</strong>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Patient Name:</span>
                    <strong class="receipt-value" id="confirmPatient">-</strong>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Contact Phone:</span>
                    <span class="receipt-value" id="confirmPhone">-</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Consulting Doctor:</span>
                    <strong class="receipt-value" id="confirmDoctor">-</strong>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Specialization:</span>
                    <span class="receipt-value" id="confirmSpec">-</span>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Scheduled Date:</span>
                    <strong class="receipt-value" id="confirmDate">-</strong>
                </div>
                <div class="receipt-row">
                    <span class="receipt-label">Time Slot:</span>
                    <strong class="receipt-value" id="confirmTime">-</strong>
                </div>
                <div class="receipt-row total-row">
                    <span class="receipt-label">Consultation Fee:</span>
                    <strong class="receipt-value fee-price" id="confirmFee">₹0</strong>
                </div>
            </div>

            <!-- Modal Action Buttons -->
            <div class="modal-actions">
                <button type="button" class="btn btn-primary" id="bookAnotherBtn">Book Another Appointment</button>
                <button type="button" class="btn btn-secondary" onclick="window.print()">Print Receipt</button>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer glass-card">
        <div class="footer-content">
            <p>© <?= date('Y') ?> MediCare Clinic. College Capstone Project. All Rights Reserved.</p>
            <p class="footer-sub">Designed with Glassmorphism UI &amp; PHP / MySQL.</p>
        </div>
    </footer>

    <!-- Client-Side JavaScript -->
    <script src="script.js"></script>
</body>
</html>