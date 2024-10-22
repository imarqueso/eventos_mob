# Eventos Mob Solution

## Instalação

Siga os passos abaixo para configurar e executar localmente.

1. **Clone o repositório:**

   ```git clone https://github.com/imarqueso/eventos_mob.git event_mob```

2. **Abrir o terminal no diretório:**

   ```cd event_mob```
   
3. **Rodar o comando para instalar o composer:**

   ```composer install```

4. **Rodar o comando para instalar o npm:**

   ```npm install```

5. **Clonar o arquivo .env-example para o .env:**

  ```cp .env-example .env```

6. **Rodar o comando para gerar a key caso necessário:**

   ```php artisan key:generate```
   
7. Subir o dump do banco de dados postgreSQL que enviei ou criar a tabela e
inserir os dados no .env (caso decida criar a tabela sem o dump, inserir os
dados e rodar o comando php artisan migrate).

8. **Rodar o comando para iniciar o projeto com npm:**

   ```npm run dev```

9. **Rodar o comando para iniciar o servidor do projeto:**

   ```php artisan serve```

10. **Acessar o projeto no navegador.**[




