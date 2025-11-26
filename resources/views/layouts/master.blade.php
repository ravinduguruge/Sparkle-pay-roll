<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sparkle Electrical Pay Roll System</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
    />

    <script>
      tailwind.config = {
        theme: {
          extend: {
            colors: {
              primary: "#19264bff", // dark blue
              secondary: "#4f545fff", // grey
              accent: "#d43737ff", // red
            },
          },
        },
      };
    </script>
    
    <style>
      /* Custom scrollbar */
      ::-webkit-scrollbar {
        width: 8px;
      }
      ::-webkit-scrollbar-track {
        background: #19264bff;
      }
      ::-webkit-scrollbar-thumb {
        background: #d43737ff;
        border-radius: 4px;
      }
      ::-webkit-scrollbar-thumb:hover {
        background: #7c2323ff;
      }

      /* Smooth scrolling */
      html {
        scroll-behavior: smooth;
      }

      /* Active nav link */
      .nav-link.active {
        background-color: rgba(212, 175, 55, 0.2);
        border-left: 4px solid #d4af37;
      }
    </style>
  </head>
  <body>

    <div class="flex h-screen overflow-hidden font-sans bg-gray-100">
        
        @if(auth()->check())
            @if(auth()->user()->isAdmin())
                @include('layouts.admin_sidebar')
            @else
                @include('layouts.employee_sidebar')
            @endif
        @endif

        <div class="flex-1 overflow-y-auto">
          @yield('content')
        </div>
     
    </div>

    <script>
      // Highlight active nav link based on scroll position
      const sections = document.querySelectorAll("section");
      const navLinks = document.querySelectorAll(".nav-link");

      window.addEventListener("scroll", () => {
        let current = "";

        sections.forEach((section) => {
          const sectionTop = section.offsetTop;
          const sectionHeight = section.clientHeight;

          if (pageYOffset >= sectionTop - 100) {
            current = section.getAttribute("id");
          }
        });

        navLinks.forEach((link) => {
          link.classList.remove("active");
          if (link.getAttribute("href") === `#${current}`) {
            link.classList.add("active");
          }
        });
      });

      // Initialize first nav link as active
      document.addEventListener("DOMContentLoaded", () => {
        navLinks[0].classList.add("active");
      });
    </script>
    
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

  </body>
</html>
  
