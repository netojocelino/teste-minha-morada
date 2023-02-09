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

                <div class="alert alert-danger" style="{{ empty($errors->all()) ? 'display: none;' : '' }}" role="alert" name="error">
                    <span>{{ implode(" ",$errors->all()) }}</span>
                </div>

                <form class="card p-2" method="POST" action="{{ route('register.action') }}">
                    @csrf
                    <div class="input-group">
                        <label for="full_name" class="form-label mt-2 col-12 col-md-4">Nome</label>
                        <input type="text" required name="full_name" class="form-control col-12 col-md-8" >
                    </div>

                    <div class="input-group mt-4">
                        <label for="email" class="form-label mt-2 col-12 col-md-4">Endereço de email</label>
                        <input type="email" required name="email" class="form-control col-12 col-md-8">
                    </div>

                    <div class="input-group mt-4">
                        <label for="password" class="form-label mt-2 col-12 col-md-4">Senha</label>
                        <input type="password" required name="password" class="form-control col-12 col-md-8">
                    </div>

                    <div class="input-group mt-4">
                        <label for="password" class="form-label mt-2 col-12 col-md-4">Confirmar Senha</label>
                        <input type="password" required name="password_confirmation" class="form-control col-12 col-md-8">
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <div class="input-group mt-4">
                                <label for="address[cep]" class="form-label mt-2 col-12 col-md-4">Código CEP</label>
                                <input type="text" required name="address[cep]" class="form-control col-12 col-md-8">
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="input-group mt-4">
                                <label for="address[city]" class="form-label mt-2 col-12 col-md-4">Cidade</label>
                                <input type="text" required name="address[city]" class="form-control col-12 col-md-8">
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="input-group mt-4">
                                <label for="address[state]" class="form-label mt-2 col-12 col-md-4">Estado</label>
                                <input type="text" required name="address[state]" class="form-control col-12 col-md-8">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <div class="input-group mt-4">
                                <label for="address[street]" class="form-label mt-2 col-12 col-md-4">Rua</label>
                                <input type="text" required name="address[street]" class="form-control col-12 col-md-8">
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="input-group mt-4">
                                <label for="address[number]" class="form-label mt-2 col-12 col-md-4">Número</label>
                                <input type="text" required name="address[number]" class="form-control col-12 col-md-8">
                            </div>
                        </div>

                        <div class="col-4">
                            <div class="input-group mt-4">
                                <label for="address[neighborhood]" class="form-label mt-2 col-12 col-md-4">Bairro</label>
                                <input type="text" required name="address[neighborhood]" class="form-control col-12 col-md-8">
                            </div>
                        </div>

                    </div>

                    <div class="col-12 mt-4">
                        <button type="submit" class="btn btn-primary col-8">
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
        const $cep = document.querySelector("[name='address[cep]']");
        const $error = document.querySelector("[name='error']");

        let cepDebounce = null;

        $cep.addEventListener('input', function (e) {
            e.preventDefault();
            $error.style.display = 'none'
            clearTimeout(cepDebounce);

            cepDebounce = setTimeout(() => {
                if (cepDebounce !== null) {
                    clearTimeout(cepDebounce)
                }

                if (!(/^[0-9]{5}-?[0-9]{3}$/.test($cep.value))) {
                    return;
                }

                document.querySelector("[name='address[city]']").value = '';
                document.querySelector("[name='address[neighborhood]']").value = '';
                document.querySelector("[name='address[state]']").value = '';
                document.querySelector("[name='address[street]']").value = '';

                $cep.disabled = true

                fetch(`/api/cep/?cep=${$cep.value}`)
                    .then(data => data.json())
                    .then(data => {
                        document.querySelector("[name='address[city]']").value = data.city;
                        document.querySelector("[name='address[neighborhood]']").value = data.neighborhood;
                        document.querySelector("[name='address[state]']").value = data.state;
                        document.querySelector("[name='address[street]']").value = data.street;

                        return data
                    })

                    .catch((err) => {
                        $error.style.display = 'block'
                        $error.querySelector('span').innerText = `Ocorreu um erro ao carregar endereço`
                    })
                    .finally(() => {
                        $cep.disabled = false
                    })
            }, 400);
        })
    </script>
</body>
</html>
