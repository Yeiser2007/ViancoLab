<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>VianCo Lab</title>
    
    <!-- Fonts - Usando una combinación moderna -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Favicon -->
    <link rel="icon" href="{{ asset('images/favicon.ico') }}">
    
    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        /* Animaciones mejoradas */
        @keyframes float {
            0%, 100% { transform: translateY(0) rotate(0deg); }
            25% { transform: translateY(-15px) rotate(2deg); }
            50% { transform: translateY(-25px) rotate(0deg); }
            75% { transform: translateY(-15px) rotate(-2deg); }
        }
        
        .animate-float {
            animation: float 4s ease-in-out infinite;
        }
        
        /* Efecto hover para botones */
        .btn-hover {
            transition: all 0.3s cubic-bezier(0.25, 0.8, 0.25, 1);
        }
        .btn-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        /* Fondo sutil */
        body {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            font-family: 'Inter', sans-serif;
        }
        
        /* Efecto de tarjeta */
        .card {
            backdrop-filter: blur(10px);
            background: rgba(255, 255, 255, 0.85);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="min-h-screen flex flex-col">
    <!-- Header con gradiente y sombra -->
    <header class="w-full py-4 px-6 flex justify-end bg-white shadow-sm">
        <nav class="flex items-center gap-4">
            @auth
                <a href="{{ url('/dashboard') }}" 
                   class="px-5 py-2.5 text-sm font-medium text-indigo-600 bg-white border border-indigo-100 rounded-lg btn-hover hover:bg-indigo-50 transition-all">
                    Dashboard
                </a>
            @else
                <a href="{{ route('login') }}" 
                   class="px-5 py-2.5 text-sm font-medium text-primary-700 hover:text-secondary-600 transition-colors">
                    Iniciar sesión
                </a>

                @if (Route::has('register'))
                    <a href="{{ route('register') }}" 
                       class="px-5 py-2.5 text-sm font-medium text-white bg-primary-600 rounded-lg btn-hover hover:bg-secondary-700 transition-all">
                        Registrarse
                    </a>
                @endif
            @endauth
        </nav>
    </header>

    <!-- Contenido principal con diseño centrado y atractivo -->
    <main class="flex-grow flex items-center justify-center px-4">
        <div class="text-center max-w-2xl w-full">
            <!-- Logo con animación y efecto de profundidad -->
            <div class="relative mx-auto w-48 h-48 mb-8">
                <div class="absolute inset-0 bg-indigo-100 rounded-full opacity-20 blur-lg animate-pulse"></div>
                <img src="{{ asset('images/imagen-vianco.png') }}" 
                     class="relative w-full h-full object-contain mx-auto animate-float" 
                     alt="Logo VianCo Lab">
            </div>
            
            <!-- Texto principal con animación de aparición -->
            <div class="space-y-6">
                <h1 class="text-4xl md:text-5xl font-bold text-primary-900 leading-tight">
                    Bienvenido a <span class="text-secondary-600">VianCo Lab</span>
                </h1>
                
                <p class="text-lg text-gray-600 max-w-lg mx-auto">
                    Innovación y tecnología al servicio de tus ideas. Descubre un mundo de posibilidades.
                </p>
                
                <!-- Llamado a la acción -->
                <div class="pt-6 flex justify-center gap-4">
                    @auth
                        <a href="{{ url('/dashboard') }}" 
                           class="px-6 py-3 bg-primary-600 text-white font-medium rounded-lg btn-hover hover:bg-secondary-700 transition-all">
                            Ir al Dashboard
                        </a>
                    @else
                        <a href="{{ route('login') }}" 
                           class="px-6 py-3 bg-white text-gray-800 border border-gray-200 font-medium rounded-lg btn-hover hover:border-indigo-300 hover:bg-gray-50 transition-all">
                            Iniciar sesión
                        </a>
                        <a href="{{ route('register') }}" 
                           class="px-6 py-3 bg-primary-600 text-white font-medium rounded-lg btn-hover hover:bg-secondary-700 transition-all">
                            Comenzar ahora
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </main>

    <footer class="py-6 text-center text-sm text-gray-500">
        <p>© {{ date('Y') }} VianCo Lab. Todos los derechos reservados.</p>
    </footer>
</body>
</html>