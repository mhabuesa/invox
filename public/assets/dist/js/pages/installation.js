let currentStep = 1;
        const totalSteps = 5;

        document.addEventListener('DOMContentLoaded', updateProgress);

        // Step Navigation
        function updateProgress() {
            const progressPercentage = (currentStep / totalSteps) * 100;
            document.getElementById('progress').style.width = `${progressPercentage}%`;
            document.getElementById('current-step').textContent = currentStep;
        }

        function changeStep(step) {
            document.querySelector('.step-content.active')?.classList.remove('active');
            document.querySelector(`#step-${step}`)?.classList.add('active');
            currentStep = step;
            updateProgress();
        }

        function nextStep() {
            if (currentStep < totalSteps) changeStep(currentStep + 1);
        }

        function prevStep() {
            if (currentStep > 1) changeStep(currentStep - 1);
        }

        // Show success/error message
        function showMessage(id, message, type) {
            const div = document.getElementById(id);
            div.innerHTML = `
        <div class="p-4 mb-4 text-sm rounded-lg ${type === 'success'
            ? 'text-green-800 bg-green-100 border border-green-300'
            : 'text-red-800 bg-red-100 border border-red-300'}" role="alert">
            ${message}
        </div>
    `;
            div.classList.remove('hidden');
        }

        function clearMessage(id) {
            const div = document.getElementById(id);
            div.classList.add('hidden');
            div.innerHTML = '';
        }

        // License Verification
        function verifyLicense() {
            const code = document.getElementById('purchase-code').value.trim();
            if (!code) {
                showMessage('license_error', 'Purchase code cannot be empty. Please enter your purchase code.', 'error');
                return;
            }

            const btn = document.getElementById('verify-btn');
            const spinner = document.getElementById('verify-spinner');
            const text = document.getElementById('verify-text');

            spinner.classList.remove('hidden');
            text.textContent = 'Verifying...';
            btn.disabled = true;

            fetch('/verify-license', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    },
                    body: JSON.stringify({
                        purchase_code: code
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        showMessage('license_success', data.message || 'License verified successfully!', 'success');
                        nextStep();
                    } else {
                        showMessage('license_error', data.message || 'License verification failed.', 'error');
                    }
                })
                .catch(err => {
                    showMessage('license_error', 'An error occurred during verification.', 'error');
                    console.error('AJAX Error:', err);
                })
                .finally(() => {
                    spinner.classList.add('hidden');
                    text.textContent = 'Verify License';
                    btn.disabled = false;
                });
        }

        // DB Connection Check
        function checkDbConnection() {
            const name = document.getElementById('db-name').value;
            const user = document.getElementById('db-user').value;
            const pass = document.getElementById('db-pass').value;

            clearMessage('dbStatus_error');
            clearMessage('license_success');

            const btn = document.getElementById('checkDbBtn');
            const spinner = document.getElementById('db-spinner');
            const text = document.getElementById('checkDbText');

            spinner.classList.remove('hidden');
            text.textContent = 'Checking...';
            btn.disabled = true;

            fetch('/check-db', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        db_name: name,
                        db_user: user,
                        db_pass: pass
                    })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        showMessage('dbStatus_success', '✅ Database connection successful!', 'success');
                        nextStep();
                    } else {
                        showMessage('dbStatus_error', '❌ Database connection failed: ' + data.message, 'error');
                    }
                })
                .catch(error => {
                    showMessage('dbStatus_error', '❌ Unexpected error occurred!', 'error');
                    console.error('DB Check Error:', error);
                })
                .finally(() => {
                    spinner.classList.add('hidden');
                    text.textContent = 'Test Connection →';
                    btn.disabled = false;
                });
        }

        // Admin Setup Step
        function nextStepFromAdminSetup() {
            const name = document.getElementById('admin-name');
            const email = document.getElementById('admin-email');
            const pass = document.getElementById('admin-pass');
            const confirm = document.getElementById('admin-pass-confirm');

            const btn = document.getElementById('admin-setup-btn');
            const spinner = document.getElementById('admin-spinner');
            const text = document.getElementById('admin-setup-text');

            clearAdminFormErrors();

            let hasError = false;

            if (!name.value.trim()) {
                showFieldError(name, 'admin-name-error', 'Full Name is required');
                hasError = true;
            }

            if (!email.value.trim()) {
                showFieldError(email, 'admin-email-error', 'Email is required');
                hasError = true;
            }

            if (!pass.value.trim()) {
                showFieldError(pass, 'admin-pass-error', 'Password is required');
                hasError = true;
            }

            if (!confirm.value.trim()) {
                showFieldError(confirm, 'admin-pass-confirm-error', 'Confirm Password is required');
                hasError = true;
            }

            if (pass.value.trim() && confirm.value.trim() && pass.value !== confirm.value) {
                showFieldError(confirm, 'admin-pass-confirm-error', 'Passwords do not match');
                hasError = true;
            }

            if (hasError) return;

            // Show spinner and disable button
            spinner.classList.remove('hidden');
            text.textContent = 'Processing...';
            btn.disabled = true;

            fetch("/admin/setup", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "Accept": "application/json",
                        "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        name: name.value.trim(),
                        email: email.value.trim(),
                        password: pass.value.trim(),
                        password_confirmation: confirm.value.trim()
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        nextStep();
                    } else if (data.errors) {
                        Object.keys(data.errors).forEach(key => {
                            const input = document.getElementById(`admin-${key}`);
                            const errorId = `admin-${key}-error`;
                            showFieldError(input, errorId, data.errors[key][0]);
                        });
                    } else {
                        alert(data.message || 'Something went wrong!');
                    }
                })
                .catch(error => {
                    alert('Server error occurred!');
                    console.error(error);
                })
                .finally(() => {
                    // Hide spinner and enable button
                    spinner.classList.add('hidden');
                    text.textContent = 'Complete Setup →';
                    btn.disabled = false;
                });
        }


        function showFieldError(inputEl, errorId, message) {
            inputEl.classList.remove('border-gray-300');
            inputEl.classList.add('border-red-500');

            const errorEl = document.getElementById(errorId);
            errorEl.textContent = message;
            errorEl.classList.remove('hidden');
        }

        function clearAdminFormErrors() {
            const inputs = ['admin-name', 'admin-email', 'admin-pass', 'admin-pass-confirm'];
            inputs.forEach(id => {
                const inputEl = document.getElementById(id);
                inputEl.classList.remove('border-red-500');
                inputEl.classList.add('border-gray-300');

                const errorEl = document.getElementById(`${id}-error`);
                errorEl.classList.add('hidden');
                errorEl.textContent = '';
            });
        }
