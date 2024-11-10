<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerta com Timer</title>
    <style>
        #myAlert {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1050;
        }
    </style>
</head>

<body>

    <?php
    if (isset($_SESSION['mensagem'])):
    ?>

        <div class="alert alert-warning alert-dismissible fade show" role="alert" id="myAlert">
            <?= $_SESSION['mensagem']; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>

    <?php
        unset($_SESSION['mensagem']);
    endif;
    ?>

    <script>
        src = "https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"
        // Define o tempo em milissegundos
        const timer = 4000;

        setTimeout(() => {
            const alert = document.getElementById('myAlert');
            if (alert) {
                alert.classList.remove('show');
                alert.classList.add('fade');
            }
        }, timer);
    </script>

</body>

</html>