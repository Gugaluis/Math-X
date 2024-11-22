<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ env('APP_NAME') }}</title>
    <!-- favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.png') }}" type="image/png">
    <!-- bootstrap -->
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/bootstrap.min.css') }}">
    <!-- main css -->
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
</head>

<body>

    <!-- logo -->
    <div class="text-center my-3">
        <img src="assets/images/logo.jpg" alt="logo" class="img-fluid" width="250px">
    </div>

    <h3 class="text-center text-secondary mb-5">
        Selecione as opções para gerar<br><span class="text-info">Exercícios de matemática</span>.
    </h3>

    <!-- operations -->
    <div class="container">
        <hr>
        <div class="row">

            @foreach ($exercises as $exercise)
                <div class="col-3 display-6 mb-3">
                    <span class="badge bg-dark">{{ $exercise['exercise_number'] }}</span>
                    <span>{{ $exercise['exercise'] }}</span>
                </div>
            @endforeach

        </div>
        <hr>
    </div>

    <div class="container mt-5">
        <div class="row">
            <div class="col">
                <a href="{{ route('home') }}" class="btn btn-primary px-5">VOLTAR</a>
            </div>
            <div class="col text-end">
                <a href="{{ route('exportarExercicios') }}" class="btn btn-secondary px-5 mt-1">DESCARREGAR
                    EXERCÍCIOS</a>
                <a href="{{ route('printExercicios') }}" class="btn btn-secondary px-5 mt-1">IMPRIMIR EXERCÍCIOS</a>
            </div>
        </div>
    </div>

    <!-- footer -->
    <footer class="text-center mt-5">
        <p class="text-secondary">MathX &copy; <span class="text-info">{{ date('Y') }}</span></p>
    </footer>

    <!-- bootstrap -->
    <script src="{{ asset('assets/bootstrap/bootstrap.bundle.min.js') }}"></script>
</body>

</html>