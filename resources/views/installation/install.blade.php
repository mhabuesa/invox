<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }} Installation Wizard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <style>
        .progress-bar {
            height: 6px;
            transition: width 0.3s ease;
        }

        .step-content {
            display: none;
        }

        .step-content.active {
            display: block;
            animation: fadeIn 0.5s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        input:focus {
            box-shadow: 0 0 0 3px rgba(79, 70, 229, 0.2);
        }
    </style>
</head>

<body class="bg-gray-50 min-h-screen">
    <div class="container mx-auto px-4 py-12 max-w-4xl">
        <div class="bg-white rounded-xl shadow-lg overflow-hidden">
            <!-- Header -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-white text-2xl font-bold mt-2">Installation Wizard</h1>
                    </div>
                    <div class="text-white text-sm bg-black bg-opacity-20 px-3 py-1 rounded-full">
                        Step <span id="current-step">1</span> of 5
                    </div>
                </div>
            </div>

            <!-- Progress Bar -->
            <div class="h-2 bg-gray-200 w-full">
                <div id="progress" class="progress-bar bg-indigo-500" style="width: 20%"></div>
            </div>

            <!-- Main Content -->
            <div class="p-6 md:p-8">
                <!-- Step 1 -->
                <div id="step-1" class="step-content active">
                    <div class="text-center mb-8">
                        <h2 class="text-2xl font-bold text-gray-800 mb-3">Welcome to Installation Wizard</h2>
                        <p class="text-gray-600 max-w-2xl mx-auto">This wizard will guide you through the process of
                            installing the script on your server. Please make sure you have:</p>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                            <h3 class="font-bold text-gray-700 mb-3 flex items-center">
                                <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Server Requirements
                            </h3>
                            <ul class="text-gray-600 space-y-2">
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-green-500 mt-1 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    PHP 8.2 or higher
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-green-500 mt-1 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    MySQL 5.7 or higher
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-green-500 mt-1 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    cURL Extension
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-green-500 mt-1 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Minimum 300MB Disk Space
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-green-500 mt-1 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Composer Version 2.x or higher
                                </li>
                            </ul>
                        </div>

                        <div class="bg-gray-50 rounded-lg p-5 border border-gray-200">
                            <h3 class="font-bold text-gray-700 mb-3 flex items-center">
                                <svg class="w-5 h-5 text-indigo-500 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7"></path>
                                </svg>
                                Recommended Settings
                            </h3>
                            <ul class="text-gray-600 space-y-2">
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-yellow-500 mt-1 mr-2" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    PHP memory_limit 256MB+
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-yellow-500 mt-1 mr-2" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    max_execution_time 120s
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-yellow-500 mt-1 mr-2" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    upload_max_filesize: 64M or more
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-yellow-500 mt-1 mr-2" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    Apache/Nginx with .htaccess support
                                </li>
                                <li class="flex items-start">
                                    <svg class="w-4 h-4 text-yellow-500 mt-1 mr-2" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M5 13l4 4L19 7"></path>
                                    </svg>
                                    MySQL InnoDB Storage Engine
                                </li>
                            </ul>
                        </div>
                    </div>

                    <div class="text-center mt-6">
                        <button onclick="nextStep()"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-8 rounded-lg focus:outline-none focus:shadow-outline transition-colors">
                            Continue to License Verification →
                        </button>
                    </div>
                </div>

                <!-- Step 2 -->
                <div id="step-2" class="step-content">
                    <div id="license_error" class="hidden"></div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">License Verification</h2>
                    <div class="space-y-6" id="license_form">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Purchase Code</label>
                            <input type="text" id="purchase-code"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300" name="purchase_code"
                                placeholder="XXXX-XXXX-XXXX-XXXX" />
                        </div>
                    </div>
                    <div class="flex justify-between mt-10">
                        <button onclick="prevStep()"
                            class="text-gray-600 hover:text-gray-800 font-medium py-2 px-4 rounded-lg">← Back</button>
                        <button id="verify-btn" onclick="verifyLicense()"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg flex items-center gap-2">
                            <span id="verify-text">Verify License</span>
                            <svg id="verify-spinner" class="hidden animate-spin h-5 w-5 text-white"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z">
                                </path>
                            </svg>
                        </button>

                    </div>
                </div>

                <!-- Step 3 -->
                <div id="step-3" class="step-content">
                    <div id="license_success" class="hidden"></div>
                    <div id="dbStatus_error" class="hidden"></div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Database Configuration</h2>
                    <div class="space-y-6">
                        <div>
                            <label for="db-name" class="block text-sm font-medium text-gray-700 mb-1">Database
                                Name</label>
                            <input type="text" id="db-name"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition"
                                placeholder="my_db_name" />
                        </div>

                        <div>
                            <label for="db-user" class="block text-sm font-medium text-gray-700 mb-1">Database
                                Username</label>
                            <input type="text" id="db-user"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition"
                                placeholder="my_db_user" />
                        </div>

                        <div>
                            <label for="db-pass" class="block text-sm font-medium text-gray-700 mb-1">Database
                                Password</label>
                            <input type="password" id="db-pass"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition"
                                placeholder="••••••••" />
                        </div>
                    </div>
                    <div class="flex justify-between mt-10">
                        <button onclick="prevStep()"
                            class="text-gray-600 hover:text-gray-800 font-medium py-2 px-4 rounded-lg">← Back</button>
                        <button onclick="checkDbConnection()" id="checkDbBtn"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg">
                            Test Connection →
                        </button>
                    </div>
                </div>

                <!-- Step 4 -->
                <div id="step-4" class="step-content">
                    <div id="dbStatus_success" class="hidden"></div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-6">Admin Account Setup</h2>
                    <div class="space-y-6">
                        <input type="text" id="admin-name"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300" placeholder="Full Name">
                        <input type="text" id="admin-email"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300"
                            placeholder="admin@example.com">
                        <input type="password" id="admin-pass"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300" placeholder="Password">
                        <input type="password" id="admin-pass-confirm"
                            class="w-full px-4 py-3 rounded-lg border border-gray-300" placeholder="Confirm Password">
                    </div>
                    <div class="flex justify-between mt-10">
                        <button onclick="prevStep()"
                            class="text-gray-600 hover:text-gray-800 font-medium py-2 px-4 rounded-lg">← Back</button>
                        <button onclick="nextStep()"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-6 rounded-lg">Complete
                            Setup →</button>
                    </div>
                </div>

                <!-- Step 5 -->
                <div id="step-5" class="step-content text-center">
                    <h2 class="text-3xl font-bold text-gray-800 mb-4">Installation Complete!</h2>
                    <p class="text-gray-600 mb-6">You may now login to your admin panel.</p>
                    <a href="#"
                        class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-3 px-6 rounded-lg">Go to Admin
                        Dashboard</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentStep = 1;
        const totalSteps = 5;

        function updateProgress() {
            const progressPercentage = (currentStep / totalSteps) * 100;
            document.getElementById('progress').style.width = `${progressPercentage}%`;
            document.getElementById('current-step').textContent = currentStep;
        }

        function changeStep(step) {
            document.querySelector('.step-content.active').classList.remove('active');
            document.querySelector(`#step-${step}`).classList.add('active');
            currentStep = step;
            updateProgress();
        }

        function nextStep() {
            if (currentStep < totalSteps) changeStep(currentStep + 1);
        }

        function prevStep() {
            if (currentStep > 1) changeStep(currentStep - 1);
        }

        function checkDbConnection() {
            const name = document.getElementById('db-name').value;
            const user = document.getElementById('db-user').value;
            const pass = document.getElementById('db-pass').value;

            // Hide previous messages
            document.getElementById('dbStatus_error').classList.add('hidden');
            document.getElementById('license_success').classList.add('hidden');

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
                });
        }


        function showMessage(id, message, type) {
            const div = document.getElementById(id);
            div.innerHTML = `
            <div class="p-4 mb-4 text-sm rounded-lg ${type === 'success' ? 'text-green-800 bg-green-100 border border-green-300' : 'text-red-800 bg-red-100 border border-red-300'}" role="alert">
                ${message}
            </div>
        `;
            div.classList.remove('hidden');
        }

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

        document.addEventListener('DOMContentLoaded', updateProgress);
    </script>



</body>

</html>
