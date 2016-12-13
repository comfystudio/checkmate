<?php
class Payments extends Model{
	/** __construct */
	public function __construct(){
		parent::__construct();
	}

    /**
	 * FUNCTION: selectDataByID
	 * This function gets payments information for backoffice
	 * @param int $id
	 */
	public function selectDataByID($id){
        $sql = "SELECT t1.*, t2.firstname, t2.surname, t2.email
				FROM payments t1
					LEFT JOIN users t2 ON t1.user_id = t2.id
				WHERE t1.id = :id
				GROUP BY t1.id";

		return $this->_db->select($sql, array(':id' => $id));
	}

    /**
	 * FUNCTION: getAllData
	 * This function returns the details for All payments
	 * @param int $limit, $keywords
	 */
	public function getAllData($limit = false, $keywords = false, $active = false){
        $optLimit = $limit != false ? " LIMIT $limit" : "";
        $optActive = $active != false ? " AND t1.is_active = 1" : "";
        $optKeywords = $keywords != false ? " AND CONCAT(IF(isnull(t2.email),' ',CONCAT(LOWER(t2.email),' ')),IF(isnull(t2.firstname),' ',CONCAT(LOWER(t2.firstname),' ')),IF(isnull(t2.surname),' ',CONCAT(LOWER(t2.surname),' '))) LIKE '%$keywords%'" : "";

        $sql = "SELECT t1.*, t2.firstname, t2.surname, t2.email
				FROM payments t1
					LEFT JOIN users t2 ON t1.user_id = t2.id
				WHERE 1 = 1
				".$optKeywords."
				".$optActive."
				GROUP BY t1.id
				ORDER BY t1.id DESC
				".$optLimit;

		return $this->_db->select($sql);
	}

    /**
	 * FUNCTION: countAllData
	 * This function returns the count for All payments
	 * @param int $keywords
	 */
	public function countAllData($keywords = false, $active = false){
        $optKeywords = $keywords != false ? " AND CONCAT(IF(isnull(t2.email),' ',CONCAT(LOWER(t2.email),' ')),IF(isnull(t2.firstname),' ',CONCAT(LOWER(t2.firstname),' ')),IF(isnull(t2.surname),' ',CONCAT(LOWER(t2.surname),' '))) LIKE '%$keywords%'" : "";
        $optActive = $active != false ? " AND t1.is_active = 1" : "";

		$sql = "SELECT COUNT(t1.id) AS total
				FROM payments t1
					LEFT JOIN users t2 ON t1.user_id = t2.id
				WHERE 1 = 1
				".$optActive."
				".$optKeywords;
		return $this->_db->select($sql);
	}

    /**
	 * FUNCTION: deleteData
	 * This function deletes an payments
	 * @param Int $id of an payments
	 */
    public function deleteData($id){
        $dbTable = 'payments';
        $where = "`id` = $id";
        $this->_db->delete($dbTable, $where);
		return true;
    }

    /**
     * FUNCTION: deleteByUserId
     * This function deletes an payments bu user id
     * @param Int $user_id of an payments
     */
    public function deleteByUserId($user_id){
        $dbTable = 'payments';
        $where = "`user_id` = $user_id";
        $this->_db->delete($dbTable, $where);
        return true;
    }

    /**
	 * FUNCTION: getPaymentByStripeCusId
	 * This function gets payments infomation based on the strip customer id.
	 * @param int $id
	 */
	public function getPaymentByStripeCusId($cus_id){
        $sql = "SELECT t1.*, t2.firstname, t2.surname, t2.email
				FROM payments t1
					LEFT JOIN users t2 ON t1.user_id = t2.id
				WHERE t1.stripe_cus_id = :cus_id
				GROUP BY t1.id";

		return $this->_db->select($sql, array(':cus_id' => $cus_id));
	}

	/**
	 * FUNCTION: updatePaymentTime
	 * This function updates a users payment date.
	 * @param int $id
	 */
	public function updatePaymentTime($data){
            $dbTable = 'payments';
            $postData = array(
            	'last_payment' => $data['last_payment'],
            	'active_until' => $data['active_until']
            );
            $where = "`id` = {$data['id']}";

            $this->_db->update($dbTable, $postData, $where);
            return true;
	}

	/**
	 * FUNCTION: createData
	 * This function adds a new payment
	 * @param mixed $data Array
	 */
	public function createData($data){
        $dbTable = 'payments';
        $postData = array(
            'user_id' => $data['user_id'],
            'stripe_cus_id' => $data['stripe_cus_id'],
            'stripe_sub_id' => $data['stripe_sub_id'],
            'type' => $data['type'],
            'last_payment' => $data['last_payment'],
            'active_until' => $data['active_until'],
            'remaining_credits' => $data['remaining_credits'],
            'bonus_credits' => $data['bonus_credits']
        );

        $this->_db->insert($dbTable, $postData);

        // Gets Last Insert ID
        return $lastInsertID = $this->_db->lastInsertId('id');
	}

	/**
	 * FUNCTION: updateData
	 * This function updates payment
	 * @param mixed $data An array of data passed from the Controller
	 */
	public function updateData($data){
        $dbTable = 'payments';
        $postData = array(
            'type' => $data['type'],
            'last_payment' => $data['last_payment'],
            'active_until' => $data['active_until'],
            'remaining_credits' => $data['remaining_credits']

        );
        $where = "`id` = {$data['id']}";

        $this->_db->update($dbTable, $postData, $where);
        return true;
	}
}?>