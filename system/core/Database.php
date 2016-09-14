<?php
/** Database */
class Database extends PDO{
    /** __construct */
    public function __construct($DB_TYPE, $DB_HOST, $DB_NAME, $DB_USER, $DB_PASS){
        parent::__construct($DB_TYPE.':host='.$DB_HOST.';dbname='.$DB_NAME, $DB_USER, $DB_PASS);
		$this->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    
    /**
     * select
     * @param string $sql An SQL string
     * @param array $array Paramters to bind
     * @param constant $fetchMode A PDO Fetch mode
     * @return mixed
     */
    public function select($sql, $array = array(), $fetchMode = PDO::FETCH_ASSOC){
        $sth = $this->prepare($sql);
        foreach ($array as $key => $value){
			if(is_int($value)){
                $sth->bindValue("$key", $value, PDO::PARAM_INT);
			}
			else{
                $sth->bindValue("$key", $value, PDO::PARAM_STR);
			}
        }
        $sth->execute();
        return $sth->fetchAll($fetchMode);
    }
	
    /**
     * count
     * @param string $sql An SQL string
     * @return int
     */
    public function countAll($sql, $array = array()){
        $sth = $this->prepare($sql);
        foreach ($array as $key => $value) {
            $sth->bindValue("$key", $value);
        }
        $sth->execute();
		$count = $sth->rowCount();
		return $count;
    }

    /**
     * insert
     * @param string $table A name of table to insert into
     * @param string $data An associative array
     */
    public function insert($table, $data){
        ksort($data);
        
        $fieldNames = implode('`, `', array_keys($data));
        $fieldValues = ':' . implode(', :', array_keys($data));
        
        $sth = $this->prepare("INSERT INTO $table (`$fieldNames`) VALUES ($fieldValues)");
        
        foreach ($data as $key => $value) {
			if(is_int($value)){
                $sth->bindValue(":$key", $value, PDO::PARAM_INT);
			}
			else{
                $sth->bindValue(":$key", $value, PDO::PARAM_STR);
			}
        }
        $sth->execute();
    }
    
    /**
     * update
     * @param string $table A name of table to insert into
     * @param string $data An associative array
     * @param string $where the WHERE query part
     */
    public function update($table, $data, $where){
        ksort($data);
        
        $fieldDetails = NULL;
        foreach($data as $key => $value) {
            $fieldDetails .= "`$key`=:$key,";
        }
        $fieldDetails = rtrim($fieldDetails, ',');
        
        $sth = $this->prepare("UPDATE $table SET $fieldDetails WHERE $where");
        
        foreach ($data as $key => $value) {
            if(is_int($value)){
                $sth->bindValue(":$key", $value, PDO::PARAM_INT);
			}
			else{
                $sth->bindValue(":$key", $value, PDO::PARAM_STR);
			}
        }
        $sth->execute();
    }
	
	/**
     * updateSort
     * @param string $table A name of table to insert into
     * @param string $data An associative array
     * @param string $where the WHERE query part
     */
    public function updateSort($table, $data, $where){
        $sth = $this->prepare("UPDATE $table SET $data WHERE $where");
        $sth->execute();
    }
    
    /**
     * delete
     * 
     * @param string $table
     * @param string $where
     * @param integer $limit
     * @return integer Affected Rows
     */
    public function delete($table, $where){
        return $this->exec("DELETE FROM $table WHERE $where");
    } 
}
?>