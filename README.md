## Ambiente Docker

Foi utlizado a arquitetura abaixo para concepção do projeto.


Estilo arquitetural: Hexagonal

Conceitos utilizados: Services, Repository, DI, IoC, Acl, Docker, Cache e outros padrões.

OBS: Portas 80, 8081, 3306 e 6379 precisam estar liberadas.

1. Clonar o repositório:
     ```
    git clone https://github.com/nilbertooliveira/gosat-test.git
     ```

2. Rodar o comando abaixo para fazer o build do projeto, pulling das images, criar rede externa e hosts:
   ```
   ./entrypoint.sh 
   docker-compose up -d
   ```
3. Instalar as dependências e permissões:
    ```
    docker exec app composer install
    docker exec app npm run build
    sudo chmod -R 777 storage/
    ```

4. Configurar a base de dados
    ```
    docker exec app php artisan migrate
    docker exec app php artisan db:seed
    ```
5. Executar testes
    ```
    docker exec app php artisan test
    ```

##### Usuário:
```
Host: https://54.198.116.11/
Email: administrator@test.com.br
Password: 123456
```

#### Documentação API
[Postman](https://documenter.getpostman.com/view/10569259/2sA3e5c7Lv)
