<?php

require_once(realpath(dirname(__FILE__)) . '/connection.php');

class Model extends Database{

    protected $pdo;

    protected function __construct() {
        $this->pdo = $this->connect();   
    }

    /**
     * Retrieve data from database
     * @param $table string, table name
     * @param $values array, arrays's values represents the database fields [id, field_one]
     * @param $filters array, ['or;name;='] => ['jhon doe']  : arrays's key represents the database fields array's value represents the value - for advanced filter place in; in the begining of the key value
     * @param $join array, rrays's values represents a join command : inner join table_name a on a.id = other_table.id
     * @param $customQuery string, advanced where clause in which there is no need to write WHERE in the begining of the string
     */
        
    protected function select($table, $values = [], $join = [], $filters = [], $customQuery = '', $orderBy = '', $groupBy = '') {
        
        $query = "SELECT ";
        
        $values === [] 
            ? $query.=" * "
            : $query.=" {$this->makeValues($values)} ";

        $query.= " FROM $table";

        if ( $join != [] )
            $query.= " {$this->makeJoin($join)} ";

        if ( $filters !== [] ) 
            $query.= " WHERE {$this->makeFilters($filters)} ";

        if ( $customQuery != '' )
            $query.=" WHERE $customQuery";
        
        if ( $orderBy = '' ) 
            $query.=" $orderBy ";
        
        if ( $groupBy = '' ) 
            $query.=" $groupBy ";

        try {

            $this->stmt = $this->pdo->prepare($query);
            $this->stmt->execute();

            $result = $this->stmt->fetchAll(PDO::FETCH_ASSOC);
      
            return $result;

        } catch (PDOException $ex) {

            return [
                'err' => true,
                'sql' => $query,
                'msg' => $ex->getMessage()
            ];

        }

    }

    /**
     * Insert into $table [ 'column' => 'value' ]
     * @param $table string, table name
     * @param $column_value array, [ 'db_column' => $value ]
     */

    protected function insert($table, $column_value) {

        $query = "INSERT INTO $table {$this->prepare_insert_values($column_value)}";

        try{

            $this->stmt = $this->pdo->prepare($query);
            foreach($column_value as $column => $value){
                $this->bind_parameters(":$column", $value);
            }

            $this->stmt->execute();
            return $this->pdo->lastInsertId();

        }catch(PDOException $ex) {
            
            return [
                'err' => true,
                'sql' => $query,
                'msg' => $ex->getMessage()
            ];

        }

    }

    /**
     * delete from $table where db_column = $value
     * @param $table string, table name
     * @param $filters array, [ '=;db_column' => $value  ]
     */

    protected function delete( $table, $filters ) {

        $query = "DELETE FROM $table";

        $query.= " WHERE {$this->makeFilters($filters)} ";
    
        try {

          $this->stmt = $this->pdo->prepare($query);
          return $this->stmt->execute();

        } catch (PDOException $ex) {
          
            return [
                'err' => true,
                'sql' => $query,
                'msg' => $ex->getMessage()
            ];

        }

    }

    /**
     * update $table set $column = $value, ... where $filters
     * @param $table string, table name
     * @param $column_value array, [ 'db_column' => $value ]
     * @param $filters array, [ 'operator;db_column' => $value_condition ]
     */

    protected function update( $table, $column_values ,$filters ) {

        $query = "UPDATE $table ";
        $query .= " SET {$this->prepare_update_values( $column_values )}";
        $query .= " WHERE {$this->makeFilters($filters)} ";
        
        try {

            $this->stmt = $this->pdo->prepare($query);

            foreach($column_values as $column => $value){
                $this->bind_parameters(":$column", $value);
            }

            $result = $this->stmt->execute();
            
            return $result;

        } catch (PDOException $ex) {
          
            return [
                'err' => true,
                'sql' => $query,
                'msg' => $ex->getMessage()
            ];

        }

    }
    
    private function prepare_update_values( $column_value ) {

        $query = '';
        $i = 0;    

        foreach($column_value as $db_field => $db_value) {

            $i === 0 && $i < count($column_value) 
               ? $comma = ""
               : $comma = ", ";

            $db_value = ":$db_field";

            $i++;

            $query.= " $comma $db_field = $db_value ";

        }
        
        return $query;

    }

    private function makeValues($values) {
        return implode(', ', $values);
    }

    private function makeJoin($join) {
        return implode(' ', $join);
    }

    private function makeFilters($filters) {
        
        $query = '';

        if($filters === [])
            return;
        
        $i = 0;    

        foreach($filters as $db_field => $db_value) {

            $i === 0
                ? $and = ""
                : $and = " AND ";

            if(is_string($db_value))
                $db_value = "\"$db_value\"";

            $i++;

            $advanced_filter = explode(';', $db_field);

            if(count($advanced_filter) > 1) {

                switch(strtolower($advanced_filter[0])) {
                    case 'or':
                        
                        $or_filter = $advanced_filter[2] != null ? $advanced_filter[2] : ' = ';

                        $and != ""
                            ? $query .= "or $advanced_filter[1] $or_filter $db_value "
                            : $query .= "$advanced_filter[1] $or_filter $db_value ";

                        break;
                    case 'in':

                        $db_field = str_replace('in;', '', $db_field);
                        $query .= "$and $db_field in $db_value ";

                        break;
                    case 'is':

                        $db_field = str_replace('is;', '', $db_field);
                        $query .= "$and $db_field is $db_value ";

                        break;
                    case 'isnot':

                        $db_field = str_replace('isnot;', '', $db_field);
                        $query .= "$and $db_field is not $db_value ";

                        break;
                    case 'notin':

                        $db_field = str_replace('notin;', '', $db_field);
                        $query .= "$and $db_field not in $db_value ";

                        break;
                    case '!=':

                        $db_field = str_replace('!=;', '', $db_field);
                        $query .= "$and $db_field != $db_value ";

                        break;
                    case '>=':

                        $db_field = str_replace('>=;', '', $db_field);
                        $query .= "$and $db_field >= $db_value ";

                        break;
                    case '<=':

                        $db_field = str_replace('<=;', '', $db_field);
                        $query .= "$and $db_field <= $db_value ";
                        
                        break;
                    case '>':

                        $db_field = str_replace('>;', '', $db_field);
                        $query .= "$and $db_field > $db_value ";

                        break;
                    case '<':

                        $db_field = str_replace('<;', '', $db_field);
                        $query .= "$and $db_field < $db_value ";

                        break;
                    default:

                        $query.= " $and $advanced_filter[1] = $db_value ";

                        break;    
                }

            } else {
                $query.= " $and $db_field = $db_value ";
            }


        }
        
        return $query;

    }

    private function prepare_insert_values($column_value){

        $query = " ( ";

        $columns = array_keys($column_value);
        $string_columns = implode(', ',$columns);

        $query.=" $string_columns ) VALUES ( ";

        $param = [];

        foreach($column_value as $column => $value) {
            array_push( $param, ":$column" );
        }

        $param = implode(',', $param);

        $query.=" $param ) ";

        return $query;

    }

    private function bind_parameters($param, $value) {

        switch (true) {

            case is_int($value):
                $type = PDO::PARAM_INT;
                break;
            case is_bool($value):
                $type = PDO::PARAM_BOOL;
                break;
            case is_null($value):
                $type = PDO::PARAM_NULL;
                break;
            default:
                $type = PDO::PARAM_STR;

        }
        
        return $this->stmt->bindValue( $param, $value, $type);
        
    }
    
}