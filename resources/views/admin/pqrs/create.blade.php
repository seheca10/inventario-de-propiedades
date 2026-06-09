<!DOCTYPE html>
<html lang="es" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte de Averías y Daños - Cartagena Norte Inmobiliaria</title>
    
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
    @livewireStyles()
</head>
<body class="h-full antialiased">

    <div class="min-h-screen flex flex-col justify-center py-12 px-4 sm:px-6 lg:px-8">
        
        <div class="sm:mx-auto sm:w-full sm:max-w-md text-center mb-8">
            
            <div class="inline-flex items-center justify-center mb-6 transition-transform duration-300 hover:scale-105">
                <img src="{{ asset('assets/images/LOGO-3D-PAGINA-CARTAGENA-NORTE-color.png') }}"
                     alt="Logo Cartagena Norte Inmobiliaria"
                     class="h-24 w-auto object-contain drop-shadow-sm">
            </div>
            
            <h2 class="text-3xl font-extrabold text-[#002855] tracking-tight">
                Reporte de Averías y Daños
            </h2>
            <p class="mt-2 text-sm text-gray-500 max-w-sm mx-auto">
                Registra tu requerimiento de forma exprés en 30 segundos. Te conectaremos de inmediato con nuestro equipo.
            </p>
        </div>

        @livewire('pqrs.create-form')

    </div>

    @livewireScripts
</body>
</html>