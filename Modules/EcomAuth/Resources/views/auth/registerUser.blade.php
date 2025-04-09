<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <div class="bg-white p-6 rounded-lg shadow-lg w-[90%] md:w-[70%]">
        <h2 class="text-2xl font-bold text-center mb-4">Register</h2>

        <form id="registerForm" action="{{ route('registerUser') }}" method="POST">
            @csrf
            @if ($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded relative mb-4">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
            <!-- First Name & Last Name -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-4">
                <div>
                    <label class="block text-gray-700 font-medium">First Name</label>
                    <input type="text" name="first_name" id="first_name"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        placeholder="First name" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium">Last Name</label>
                    <input type="text" name="last_name" id="last_name"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        placeholder="Last name" required>
                </div>
            </div>

            <!-- Mobile Number & Email -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-4">
                <div>
                    <label class="block text-gray-700 font-medium">Mobile Number</label>
                    <input type="number" name="mobile" id="mobile" inputmode="numeric" maxlength="10" 
                        class="w-full p-2 border border-gray-300 rounded-lg bg-gray-100 focus:outline-none" 
                        placeholder="Mobile number" value="{{ old('mobile', $mobile ?? '') }}" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium">Email</label>
                    <input type="email" name="email" id="email"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        placeholder="Email" required>
                </div>
            </div>

            <!-- Password & Confirm Password -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-2 mb-4">
                <div>
                    <label class="block text-gray-700 font-medium">Password</label>
                    <input type="password" name="password" id="password"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        placeholder="Password" required>
                </div>
                <div>
                    <label class="block text-gray-700 font-medium">Confirm Password</label>
                    <input type="password" name="confirm_password" id="confirm_password"
                        class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" 
                        placeholder="Confirm password" required>
                </div>
            </div>

            <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded-lg font-medium hover:bg-blue-600">
                Register
            </button>

            <p class="text-sm text-gray-600 mt-3 text-center">
                Already have an account? <a href="{{ route('login') }}" class="text-blue-500">Login here</a>
            </p>
        </form>
    </div>

    <script>
        document.getElementById("registerForm").addEventListener("submit", function(event) {
            let mobile = document.getElementById("mobile").value.trim();
            let email = document.getElementById("email").value.trim();
            let password = document.getElementById("password").value;
            let confirmPassword = document.getElementById("confirm_password").value;

            let emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

            // Validate mobile number
            if (mobile === "") {
                alert("Mobile number cannot be empty.");
                event.preventDefault();
                return;
            }
            
            // Validate email
            if (!emailPattern.test(email)) {
                alert("Please enter a valid email address.");
                event.preventDefault();
                return;
            }

            // Validate password match
            if (password !== confirmPassword) {
                alert("Passwords do not match.");
                event.preventDefault();
                return;
            }
        });
    </script>
</body>
</html>