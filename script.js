/**
 * Doctor Appointment Booking System
 * Client-Side JavaScript Interactivity & Form Validation (script.js)
 */

document.addEventListener('DOMContentLoaded', () => {
    // -------------------------------------------------------------
    // 1. Element References
    // -------------------------------------------------------------
    const appointmentForm   = document.getElementById('appointmentForm');
    const patientNameInput  = document.getElementById('patient_name');
    const phoneInput        = document.getElementById('phone');
    const doctorSelect      = document.getElementById('doctor_id');
    const feeDisplay        = document.getElementById('feeDisplay');
    const appointmentDate   = document.getElementById('appointment_date');
    const appointmentTime   = document.getElementById('appointment_time');
    const submitBtn         = document.getElementById('submitBtn');
    const btnSpinner        = document.getElementById('btnSpinner');
    const formAlert         = document.getElementById('formAlert');

    // Error Labels
    const nameError         = document.getElementById('nameError');
    const phoneError        = document.getElementById('phoneError');
    const doctorError       = document.getElementById('doctorError');
    const dateError         = document.getElementById('dateError');
    const timeError         = document.getElementById('timeError');

    // Modal Elements
    const confirmationModal = document.getElementById('confirmationModal');
    const bookAnotherBtn    = document.getElementById('bookAnotherBtn');
    const confirmId         = document.getElementById('confirmId');
    const confirmPatient    = document.getElementById('confirmPatient');
    const confirmPhone      = document.getElementById('confirmPhone');
    const confirmDoctor     = document.getElementById('confirmDoctor');
    const confirmSpec       = document.getElementById('confirmSpec');
    const confirmDate       = document.getElementById('confirmDate');
    const confirmTime       = document.getElementById('confirmTime');
    const confirmFee        = document.getElementById('confirmFee');

    // Doctor "Select & Book" Buttons on Cards
    const selectDoctorBtns  = document.querySelectorAll('.select-doctor-btn');

    // -------------------------------------------------------------
    // 2. Prevent Past Dates in Datepicker
    // -------------------------------------------------------------
    const today = new Date();
    const yyyy = today.getFullYear();
    const mm = String(today.getMonth() + 1).padStart(2, '0');
    const dd = String(today.getDate()).padStart(2, '0');
    const minDateString = `${yyyy}-${mm}-${dd}`;
    appointmentDate.setAttribute('min', minDateString);

    // -------------------------------------------------------------
    // 3. Dynamic Consultation Fee Synchronization
    // -------------------------------------------------------------
    function updateFeeDisplay() {
        const selectedOption = doctorSelect.options[doctorSelect.selectedIndex];
        if (selectedOption && selectedOption.dataset.fee) {
            const fee = parseFloat(selectedOption.dataset.fee);
            feeDisplay.textContent = `₹${fee.toLocaleString('en-IN')}`;
            feeDisplay.style.color = 'var(--primary-dark)';
            clearFieldError(doctorSelect, doctorError);
        } else {
            feeDisplay.textContent = 'Select doctor to view';
            feeDisplay.style.color = 'var(--text-light)';
        }
    }

    doctorSelect.addEventListener('change', updateFeeDisplay);

    // -------------------------------------------------------------
    // 4. "Select & Book" Click on Doctor Cards
    // -------------------------------------------------------------
    selectDoctorBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            const docId = btn.dataset.id;
            if (docId) {
                doctorSelect.value = docId;
                updateFeeDisplay();

                // Smooth scroll to booking section
                const bookingSection = document.getElementById('booking');
                if (bookingSection) {
                    bookingSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
                }

                // Focus on patient name input with subtle glow
                setTimeout(() => {
                    patientNameInput.focus();
                }, 500);
            }
        });
    });

    // -------------------------------------------------------------
    // 5. Input Validation Helpers
    // -------------------------------------------------------------
    function setFieldError(inputEl, errorEl, message) {
        inputEl.classList.add('is-invalid');
        errorEl.textContent = message;
    }

    function clearFieldError(inputEl, errorEl) {
        inputEl.classList.remove('is-invalid');
        errorEl.textContent = '';
    }

    // Real-time clearance of errors on user typing / changing
    patientNameInput.addEventListener('input', () => {
        if (patientNameInput.value.trim().length >= 2) {
            clearFieldError(patientNameInput, nameError);
        }
    });

    phoneInput.addEventListener('input', () => {
        if (phoneInput.value.trim().length >= 7) {
            clearFieldError(phoneInput, phoneError);
        }
    });

    appointmentDate.addEventListener('change', () => {
        if (appointmentDate.value >= minDateString) {
            clearFieldError(appointmentDate, dateError);
        }
    });

    appointmentTime.addEventListener('change', () => {
        if (appointmentTime.value) {
            clearFieldError(appointmentTime, timeError);
        }
    });

    function validateForm() {
        let isValid = true;

        // Validate Patient Name
        const nameVal = patientNameInput.value.trim();
        if (!nameVal) {
            setFieldError(patientNameInput, nameError, 'Patient name is required.');
            isValid = false;
        } else if (nameVal.length < 2) {
            setFieldError(patientNameInput, nameError, 'Name must be at least 2 characters long.');
            isValid = false;
        } else {
            clearFieldError(patientNameInput, nameError);
        }

        // Validate Phone Number
        const phoneVal = phoneInput.value.trim();
        const phoneDigits = phoneVal.replace(/[^0-9]/g, '');
        if (!phoneVal) {
            setFieldError(phoneInput, phoneError, 'Phone number is required.');
            isValid = false;
        } else if (phoneDigits.length < 10 || phoneDigits.length > 15) {
            setFieldError(phoneInput, phoneError, 'Please enter a valid 10-digit mobile number.');
            isValid = false;
        } else {
            clearFieldError(phoneInput, phoneError);
        }

        // Validate Doctor Selection
        if (!doctorSelect.value) {
            setFieldError(doctorSelect, doctorError, 'Please select a doctor.');
            isValid = false;
        } else {
            clearFieldError(doctorSelect, doctorError);
        }

        // Validate Appointment Date
        const dateVal = appointmentDate.value;
        if (!dateVal) {
            setFieldError(appointmentDate, dateError, 'Please select an appointment date.');
            isValid = false;
        } else if (dateVal < minDateString) {
            setFieldError(appointmentDate, dateError, 'Appointment date cannot be in the past.');
            isValid = false;
        } else {
            clearFieldError(appointmentDate, dateError);
        }

        // Validate Appointment Time Slot
        if (!appointmentTime.value) {
            setFieldError(appointmentTime, timeError, 'Please select an appointment time slot.');
            isValid = false;
        } else {
            clearFieldError(appointmentTime, timeError);
        }

        return isValid;
    }

    // -------------------------------------------------------------
    // 6. Handle Form Submission via AJAX (Fetch API)
    // -------------------------------------------------------------
    appointmentForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        // Hide any previous alert message
        formAlert.className = 'alert-box hidden';
        formAlert.textContent = '';

        // Run validation
        if (!validateForm()) {
            formAlert.className = 'alert-box alert-danger';
            formAlert.textContent = 'Please fill out all required fields correctly.';
            return;
        }

        // Prepare Form Data for submission
        const formData = new FormData(appointmentForm);

        // Set Loading state on submit button
        submitBtn.disabled = true;
        btnSpinner.classList.remove('hidden');
        const originalBtnText = submitBtn.querySelector('.btn-text').textContent;
        submitBtn.querySelector('.btn-text').textContent = 'Processing Appointment...';

        try {
            const response = await fetch('save_appointment.php', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const result = await response.json();

            if (response.ok && result.status === 'success') {
                // Populate Confirmation Modal with details
                const data = result.data;
                confirmId.textContent      = `#APT-${String(data.appointment_id).padStart(4, '0')}`;
                confirmPatient.textContent = data.patient_name;
                confirmPhone.textContent   = data.phone;
                confirmDoctor.textContent  = data.doctor_name;
                confirmSpec.textContent    = data.specialization;
                confirmDate.textContent    = data.appointment_date;
                confirmTime.textContent    = data.appointment_time;
                confirmFee.textContent     = `₹${parseFloat(data.consultation_fee).toLocaleString('en-IN')}`;

                // Show Confirmation Modal
                confirmationModal.classList.remove('hidden');
                confirmationModal.setAttribute('aria-hidden', 'false');

                // Reset Form
                appointmentForm.reset();
                updateFeeDisplay();
            } else {
                // Display server-side error
                formAlert.className = 'alert-box alert-danger';
                formAlert.textContent = result.message || 'Booking failed. Please try again.';
                formAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        } catch (err) {
            console.error('Submission error:', err);
            formAlert.className = 'alert-box alert-danger';
            formAlert.textContent = 'A network error occurred. Please verify Apache/MySQL is running.';
            formAlert.scrollIntoView({ behavior: 'smooth', block: 'center' });
        } finally {
            // Restore Submit Button state
            submitBtn.disabled = false;
            btnSpinner.classList.add('hidden');
            submitBtn.querySelector('.btn-text').textContent = originalBtnText;
        }
    });

    // -------------------------------------------------------------
    // 7. Modal Controls
    // -------------------------------------------------------------
    function closeModal() {
        confirmationModal.classList.add('hidden');
        confirmationModal.setAttribute('aria-hidden', 'true');
    }

    if (bookAnotherBtn) {
        bookAnotherBtn.addEventListener('click', () => {
            closeModal();
            const bookingSection = document.getElementById('booking');
            if (bookingSection) {
                bookingSection.scrollIntoView({ behavior: 'smooth' });
            }
        });
    }

    // Close modal if user clicks on outside overlay backdrop
    confirmationModal.addEventListener('click', (e) => {
        if (e.target === confirmationModal) {
            closeModal();
        }
    });

    // Close modal on Escape key press
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && !confirmationModal.classList.contains('hidden')) {
            closeModal();
        }
    });

    // -------------------------------------------------------------
    // 8. Active Navigation Link on Scroll (ScrollSpy)
    // -------------------------------------------------------------
    const navLinks = document.querySelectorAll('.nav-link');
    const sections = document.querySelectorAll('section[id]');

    window.addEventListener('scroll', () => {
        let current = '';
        const scrollPosition = window.pageYOffset + 120;

        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.offsetHeight;
            if (scrollPosition >= sectionTop && scrollPosition < sectionTop + sectionHeight) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href') === `#${current}`) {
                link.classList.add('active');
            }
        });
    });
});
