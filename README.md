# Projeto Minha Morada

> Deve ter a página de cadastro, login e recuperar senha.

A página de cadastro deve receber os dados: nome completo, e-mail, senha e confirmar a senha e endereço (Rua, Bairro, Número, Cidade, Estado, CEP). Deve haver validação do CEP e deve-se utilizar uma API para puxar os dados do endereço através do CEP.

Na página de recuperação de senha, deve haver somente o campo de e-mail.

O envio deve ser feito através do Notifications, a recuperação de senha deve-se dar com um token direcionando para uma página onde o usuário já deve colocar a senha e confirmar ela, e já deve ser direcionado para dentro da Home, o email deverá ocorrer utilizando o [MailHog].

Para a Home, deve-se mostrar todos os usuários cadastrados no sistema.

[MailHog]: https://github.com/mailhog/MailHog
