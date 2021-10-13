lembrar de colocar um composer no final do desenvolvimento com namespace pro projeto fica mais bunitinhu
lembrar de escrever a documentação no readme
lembrar de padronizar todas os nomes de variaveis pro inglês
lembrar de criar rota que cria as tabelas no banco de dados - create table if not exists
lembrar de criar index no bando de dados para que os cidadoes sejam retornados por ordem de nome
lembrar de implementar o viacep

pastinhas

src
    api/routes
        routes.php
    config/environment
        db.php
        environment.php
    model/
        connection.php
        model.php
    providers/
        viaCEP.php
    public/
        indes.php
    schemas/
        api_dev.sql
    useCases/citizen/
        citizen.php
    useCases/citizen/DAO/
        citizenDAO.php
    useCases/citizen/VO/
        citizenVO.php    
        contactVO.php    
        addressVO.php    
    utils/services/