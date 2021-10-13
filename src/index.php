<?php

    // echo $_SERVER["DOCUMENT_ROOT"];
    // require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/model/model.php');
    // require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/useCases/citizen/VO/citizenVO.php');
    // require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/useCases/citizen/VO/addressVO.php');
    // require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/useCases/citizen/VO/contactVO.php');
    require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/useCases/citizen/DAO/citizenDAO.php');
    
    echo "<pre >";
    
    $citizenDAO = new CitizenDAO();

    $data = [
        "name" => "José",
        "lastname" => "Fabiano",
        "identification_number" => "22222222222",
        "contact_information" => [
            "email" => "jeso@uol.com.br",
            "cellphone" => "1111111112"
        ],
        "address" => [
            "zip_code" => "111111111"
        ]
    ];

    $citizenDAO->insert_citizen_data( $data );




    // $citizenVO = new CitizenVO();

    // $citizenVO->name = 'Paulo';
    // $citizenVO->lastname = 'Nogueira';
    // $citizenVO->identification_number = '5555';

    // print_r($citizenVO);

    
    // $addressVO = new AddressVO();

    // $addressVO->zip_code = 111111;

    // print_r($addressVO);

    // $contactVO = new ContactVO();

    // $contactVO->email = 'paulo@gmail.com';
    // $contactVO->cellphone = '11225588833';

    // print_r($contactVO);
    // print_r($contactVO->toArray());

    // print_r($contactVO->toJson());

    // print_r($contactVO->__get('email'));

    // echo "index - testes do model <br />";

    // $model = new Model();

    // $model->insert('tb_teste',[
    //     'nome' => 'Ana Maria',
    //     'idade' => 66,
    // ]);

    // $l = $model->select('tb_teste');
    // print_r($l);

    // $d = $model->delete('tb_teste',[ '>=;id' => 4 ]);

    // $u = $model->update('tb_teste',[
    //     'nome' => 'José',
    //     'idade' => 35,
    // ] ,['id' => 6] );