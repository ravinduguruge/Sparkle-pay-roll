<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Modern Login Page</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />
    <style>
      @import url("https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap");

      body {
        font-family: "Poppins", sans-serif;
        background-color: #dcdddf;
      }

      .gradient-bg {
        background: linear-gradient(135deg,#f02b2b 60%,#1E2A4A 60%);
      }

      .input-focus:focus {
        box-shadow: 0 0 0 3px rgba(118, 75, 162, 0.2);
      }

      .btn-hover:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 20px -10px rgba(118, 75, 162, 0.6);
      }

      .transition-all {
        transition: all 0.3s ease;
      }
    </style>
  </head>
  <body class="min-h-screen flex items-center justify-center p-4">
    <div
      class="w-full max-w-6xl bg-white rounded-2xl shadow-xl overflow-hidden flex flex-col md:flex-row"
    >
      <!-- Logo Section -->
      <div
        class="gradient-bg w-full md:w-1/2 p-12 flex flex-col items-center justify-center text-white"
      >

      <!-- L -->
        <div class="mb-8">
          
            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
          </svg>
        </div>
        <div class="mb-4">
        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-64 h-64 items-center justify-center ">
        </div>
        <h1 class="text-4xl font-bold mb-4">Sparkle Electrical</h1>
        <p class="text-center opacity-90">
          powered by Ravindu Guruge.
        </p>
      </div>

      <!-- Login Form Section -->
      <div class="w-full md:w-1/2 p-12 flex flex-col justify-center">
        <h2 class="text-3xl font-bold text-gray-800 mb-2">Sign In</h2>
        <p class="text-gray-600 mb-8">
          Enter your credentials to access your account
        </p>


    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

        {{-- form  --}}
    <form method="POST" class="space-y-6" action="{{ route('login') }}">
        @csrf

          <!-- email  -->
          <div>
            <label
              for="email" :value="__('Email')"
              class="block text-sm font-medium text-gray-700 mb-1"
              >Email</label
            >
          
            <div class="relative">
              <div
                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
              >
                <i class="fas fa-user text-gray-400"></i>
              </div>
              <input
                type="email"
                id="email"
                name="email"
                :value="old('email')" required autofocus autocomplete="username"
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none input-focus transition-all"
                placeholder="Enter your email"
              />
              <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
          </div>
          <!-- end email  -->

          <!-- password  -->
          <div>
            <label
              for="password" :value="__('Password')"
              class="block text-sm font-medium text-gray-700 mb-1"
              >Password</label
            >
            <div class="relative">
              <div
                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none"
              >
                <i class="fas fa-lock text-gray-400"></i>
              </div>
              <input
                type="password"
                id="password"
                name="password"
                required autocomplete="current-password"
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none input-focus transition-all"
                placeholder="Enter your password"
              />
              <x-input-error :messages="$errors->get('password')" class="mt-2" />

              <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                <button type="button" class="text-gray-400 hover:text-gray-600">
                  <i class="fas fa-eye"></i>
                </button>
              </div>
            </div>
          </div>
          <!-- end password  -->

          <!-- remember me  -->
          <div class="flex items-center justify-between">
            <div class="flex items-center">
              <input
                id="remember_me"
                name="remember"
                type="checkbox"
                class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
              />
              <label for="remember_me" class="ml-2 block text-sm text-gray-700"
                >{{ __('Remember me') }}</label
              >
            </div>
            <div class="text-sm">
              @if (Route::has('password.request'))
                <a class="font-medium text-purple-600 hover:text-purple-500" href="{{ route('password.request') }}">
                    {{ __('Forgot your password?') }}
                </a>
            @endif
            </div>
            
          </div>

          <div>
            <button
              type="submit"
              class="w-full gradient-bg text-white py-3 px-4 rounded-lg font-medium btn-hover transition-all shadow-lg"
            >
              {{ __('Log in') }}
            </button>
          </div>
        </form>
        <!-- end form  -->
        
      </div>
    </div>

    <script>
      // Toggle password visibility
      document
        .querySelector("#password + div button")
        .addEventListener("click", function () {
          const passwordInput = document.getElementById("password");
          const icon = this.querySelector("i");

          if (passwordInput.type === "password") {
            passwordInput.type = "text";
            icon.classList.replace("fa-eye", "fa-eye-slash");
          } else {
            passwordInput.type = "password";
            icon.classList.replace("fa-eye-slash", "fa-eye");
          }
        });

      // Add animation on load
      document.addEventListener("DOMContentLoaded", function () {
        const formElements = document.querySelectorAll(
          "input, button, label, p, h2"
        );
        formElements.forEach((el, index) => {
          setTimeout(() => {
            el.classList.add("opacity-100");
          }, index * 100);
        });
      });
    </script>
  </body>
</html>
