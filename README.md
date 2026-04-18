# Gestão de Sistema de Biblioteca - Docker Compose

Este projeto demonstra a utilização do **Docker Compose** para subir um ambiente com múltiplos containers, contendo:

* **Container APP:** aplicação PHP com Apache
* **Container DB:** banco de dados MariaDB

A aplicação consiste em um sistema simples de biblioteca, reutilizado apenas como base para implementação da conteinerização com Docker.

---

## Tecnologias utilizadas

* PHP 8.2 + Apache
* MariaDB
* Docker
* Docker Compose

---

## Como rodar o projeto

Com o docker rodando, dentro da pasta onde está localizado o arquivo `docker-compose.yml`, execute:

```bash
docker-compose up -d
```

Esse comando fará com que:

* o site seja iniciado no container **APP**
* o banco de dados seja iniciado no container **DB**
* ambos sejam executados juntos automaticamente

Além disso, o banco de dados será inicializado automaticamente através dos scripts SQL presentes na pasta db.

---

## Acesso ao sistema

Após subir os containers, aguarde uns segundos e acesse no navegador:

```bash
http://localhost:8080/
```

Se tudo estiver correto, será exibida a tela de login do sistema.

Se precisar parar os containers, execute:

```bash
docker-compose down
```

