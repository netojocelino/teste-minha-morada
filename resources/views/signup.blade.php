<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Cadastrar</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">

</head>
<body class="bg-light">
    <div class="container">
        <main>
            <div class="py-5 text-center">
                <h1>Cadastrar</h1>


                <form class="card p-2">
                    <div class="input-group">
                        <label for="name" class="form-label mt-2 col-12 col-md-4">Nome</label>
                        <input type="text" required name="full_name" class="form-control col-12 col-md-8" required>
                    </div>

                    <div class="input-group mt-4">
                        <label for="email" class="form-label mt-2 col-12 col-md-4">Endereço de email</label>
                        <input type="email" required name="email" class="form-control col-12 col-md-8" required>
                    </div>

                    <div class="input-group mt-4">
                        <label for="password" class="form-label mt-2 col-12 col-md-4">Senha</label>
                        <input type="password" required name="password" class="form-control col-12 col-md-8" required>
                    </div>

                    <div class="input-group mt-4">
                        <label for="password" class="form-label mt-2 col-12 col-md-4">Confirmar Senha</label>
                        <input type="password" required name="password_confirmation" class="form-control col-12 col-md-8" required>
                    </div>

                    <div class="col-12 mt-4">
                        <button type="button" name="signup" class="btn btn-primary col-8">
                            Cadastrar
                        </button>
                    </div>
                </form>

            </div>

        </main>
    </div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>

    <script>
        const $btn = document.querySelector("[name=signup]");

        const signBtn = (e) => {
            e.preventDefault();

            $btn.disabled = true;

            const $name = document.querySelector("input[name=full_name]");
            const $email = document.querySelector("input[name=email]");
            const $password_confirmation = document.querySelector("input[name=password_confirmation]");
            const $password = document.querySelector("input[name=password]");

            if (
                $name.value.trim().length === 0 ||
                $email.value.trim().length === 0 ||
                $password_confirmation.value.trim().length === 0 ||
                $password.value.trim().length === 0
                ) {
                    window.alert('Todos os campos são obrigatórios')
                    $btn.disabled = false;
                    return;
                }

            if (
                $password_confirmation.value !== $password.value
            ) {
                    window.alert('Senhas não coincidem')
                    $btn.disabled = false;
                    return;
            }

            $btn.disabled = false;

        }

        $btn.addEventListener('click', signBtn)
    </script>
</body>
</html>
