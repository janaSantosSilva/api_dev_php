<?php

    require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/model/model.php');
    require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/useCases/citizen/VO/citizenVO.php');
    require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/useCases/citizen/VO/contactVO.php');
    require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/useCases/citizen/VO/addressVO.php');
    require_once(realpath($_SERVER["DOCUMENT_ROOT"]) .'/providers/viaCEP.php');
    
    class CitizenDAO extends Model {

        private $citizenVO;
        private $id_citizen;
        private $id_contact;
        // private $id_address;
        private $addressVO;
        private $contactVO;
        // private $validation;
        // private $viaCEP;

        public function __construct() {
            $this->citizenVO = new CitizenVO();
            $this->addressVO = new AddressVO();
            $this->contactVO = new ContactVO();
            // $this->validation = new Validation();
            // $this->viaCEP = new ViaCEP();
        }

        public function insert_citizen_data ( $data ) {


            #TODO: LEMBRAR DE ALTERAR A CLASSE MODEL PARA TRANSACTION E CASO TODAS AS OPERAÇOES SEJAM 
            #BEM SUCEDIDAS SÓ AI DAR COMMIT NO BANCO, CASO CONTRARIO DAR ROLLBACK NA TRANSACTION
            #E RETORNAR ERRO

            #TODO: MELHORAR RETORNO DE ERRO E DE SUCESSO DAS FUNÇÕES E TRATAR NAS ROTAS

            #TODO: CORRIGIR BUG NA INSERÇÃO DOS DADOS SISTEMA NÃO RECONHECE VARIÁVEL DE CONEXÃO DESSE CONTEXTO

            // $is_valid = $this->validation->validate( $data );
            // if( $is_valid['status'] === 'error' ) 
            //      die(json_encode($is_valid));
            // 

            $this->citizenVO->name = $data['name'];
            $this->citizenVO->lastname = $data['lastname'];
            $this->citizenVO->identification_number = $data['identification_number'];

            // die(json_encode($this->citizenVO->toArray()));

            $citizen = $this->citizenVO->toArray();

            $this->id_citizen = $this->insert( 'citizen', $citizen );

            if( !$this->id_citizen ) 
                return json_encode([
                    "status" => "error",
                    "message" => "Unable to insert citizen data to citizen table"
                ]);

            $this->contactVO->email = $data['contact_information']['email'];
            $this->contactVO->cellphone = $data['contact_information']['cellphone'];
            $this->contactVO->id_citizen = $this->id_citizen;

            $this->id_contact = $this->insert( 'contact',  $this->contactVO->toArray() );
         
            if( !$this->id_contact ) 
                return json_encode([
                    "status" => "error",
                    "message" => "Unable to insert citizen contact information to contact table"
                ]);

            $this->addressVO->zip_code = $data['address']['zip_code'];

            /**
             *  $viaCEP = $this->viaCEP->retrieveAddres( $data['address']['zip_code'] );
             * 
             *  if ( $viaCEP['status'] === 'error' ) 
             *      return json_encode($viaCEP);
             *  
             *   $this->addressVO->citizen_id = $this->id_citizen;
             *   $this->addressVO->street = $viaCEP['rua'];
             *   $this->addressVO->neighbourhood = $viaCEP['bairro'];
             *   $this->addressVO->city = $viaCEP['cidade'];
             *   $this->addressVO->district = $viaCEP['destrito'];
             * 
             *  $this->id_address = $this->insert( 'address',  $this->addressVO->toArray() );
             * 
             *  if( !$this->address )
             *        die(json_encode([
             *          "status" => "error",
             *          "message" => "Unable to insert citizen address information to address table"
             *         ]));
             * 
             */

             return json_encode([
                "status" => "success",
                "message" => "",
             ]);

        }

        public function update_citizen_data () {}
        public function delete_citizen () {}

        public function select_all_citizen_data() {}
        public function select_citizen_data() {}

    }