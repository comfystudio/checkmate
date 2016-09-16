<?php
class NewsBackoffice extends Model{
	/** __construct */
	public function __construct(){
		parent::__construct();
	}

    /**
	 * FUNCTION: validation
	 * This function validates post data, and should be called for any update or create model calls.
	 * @param mixed array $data, $type can define different standards depending on if edit for example.
	 */
    public function validation($data, $type){
        $return = $data;
        foreach($data as $key => $input){
            $temp = null;
            if ($key != 'text') {
                $input = is_array($input) ? FormInput::trimArray($input) : FormInput::checkInput($input);
            }
            $return[$key] = $input;

            //title
            if($key == 'title'){
                //required
                if(empty($input) || $input == null){
                    $return['error'][$key] = 'News Title cannot be empty';
                }else{
                    // Creating slug based off formatted title.
                    $temp = Formatting::removeAccents($input);
					$temp = FormatUrl($temp);
                    $return['slug'] = $temp;
                }

                //unique
                if($type != 'edit') {
                    $temp = $this->selectDataByTitle($input);
                    if (!empty($temp) || $temp != null) {
                        $return['error'][$key] = "This Title already exists";
                    }
                }else{
                    //if the edited title is different than their previous one, check its unique.
                    if($input != $return['stored_title']){
                        $temp = $this->selectDataByTitle($input);
                        if (!empty($temp) || $temp != null) {
                            $return['error'][$key] = "This Title already exists";
                        }
                    }
                }
            }

            //text
            if($key == 'text'){
                //Required
                if(empty($input) || $input == null){
                    $return['error'][$key] = 'Text cannot be empty';
                }
            }

            //contact_name
            if($key == 'contact_name'){
                //Required
                if(empty($input) || $input == null){
                    $return['error'][$key] = 'Media Contact Name cannot be empty';
                }
            }

            //date
            if($key == 'date'){
                //Required
                if(empty($input) || $input == null){
                    $return['error'][$key] = 'Date cannot be empty';
                }
            }
        }
        return $return;
    }

    /**
	 * FUNCTION: selectDataByID
	 * This function gets news information for backoffice
	 * @param int $id
	 */
	public function selectDataByID($id){
		$sql = "SELECT t1.id, t1.title, t1.slug, t1.text, t1.image, t1.date, t1.is_active
				FROM news t1
				WHERE t1.id = :id
                GROUP BY t1.id
                ";

		return $this->_db->select($sql, array(':id' => $id));
	}

    /**
	 * FUNCTION: getAllData
	 * This function returns the details for All news
	 * @param int $limit, $keywords
	 */
	public function getAllData($limit = false, $keywords = false){
        $optLimit = $limit != false ? " LIMIT $limit" : "";
        $optKeywords = $keywords != false ? " AND CONCAT(IF(isnull(t1.title),' ',CONCAT(LOWER(t1.title),' ')), IF(isnull(t1.slug),' ',CONCAT(LOWER(t1.slug),' '))) LIKE '%$keywords%'" : "";

		$sql = "SELECT t1.id, t1.title, t1.slug, t1.text, t1.image, t1.date, t1.is_active
				FROM news t1
				WHERE 1 = 1
				".$optKeywords."
				ORDER BY t1.id DESC
				".$optLimit;

		return $this->_db->select($sql);
	}

    /**
	 * FUNCTION: countAllData
	 * This function returns the count for All news
	 * @param int $keywords
	 */
	public function countAllData($keywords = false){
        $optKeywords = $keywords != false ? " AND CONCAT(IF(isnull(t1.title),' ',CONCAT(LOWER(t1.title),' ')), IF(isnull(t1.slug),' ',CONCAT(LOWER(t1.slug),' '))) LIKE '%$keywords%'" : "";

		$sql = "SELECT COUNT(t1.id) AS total
				FROM news t1
				WHERE 1 = 1
				".$optKeywords;
		return $this->_db->select($sql);
	}

    /**
	 * FUNCTION: createData
	 * This function adds a new news to the Database from backoffice
	 * @param mixed $data Array of news Data
	 */
	public function createData($data){
        $data = $this->validation($data, 'add');
        if(isset($data['error']) && $data['error'] != null){
            return $data;
        }else {
            $dbTable = 'news';
            $postData = array(
                'title' => $data['title'],
                'slug' => $data['slug'],
                'text' => $data['text'],
                'text_clean' => $data['text_clean'],
                'image' => $data['image'][0],
                'date' => $data['date'],
                'is_active' => $data['is_active']

            );

            $this->_db->insert($dbTable, $postData);

            // Gets Last Insert ID
            return $lastInsertID = $this->_db->lastInsertId('id');
        }
	}

    /**
	 * FUNCTION: updateData
	 * This function updates news details for backoffice
	 * @param mixed $data An array of data passed from the Controller
	 */
	public function updateData($data){
        $data = $this->validation($data, 'edit');
        if(isset($data['error']) && $data['error'] != null){
            return $data;
        }else {
            $dbTable = 'news';
            $postData = array(
                'title' => $data['title'],
                'slug' => $data['slug'],
                'text' => $data['text'],
                'text_clean' => $data['text_clean'],
                'image' => $data['image'][0],
                'date' => $data['date'],
                'is_active' => $data['is_active']
            );
            $where = "`id` = {$data['id']}";

            $this->_db->update($dbTable, $postData, $where);
            return true;
        }
	}

    /**
	 * FUNCTION: deleteData
	 * This function deletes an news
	 * @param Int $id of an news
	 */
    public function deleteData($id){
        $dbTable = 'news';
        $where = "`id` = $id";
        $this->_db->delete($dbTable, $where);
		return true;
    }

    /**
	 * FUNCTION: createNewsCategory
	 * This function adds a new news category
	 * @param int $news_id, int $category_id
	 */
    public function createNewsCategory($news_id, $category_id){
        $dbTable = 'news_categories';
        $postData = array(
            'news_id' => $news_id,
            'category_id' => $category_id
        );

        $this->_db->insert($dbTable, $postData);
    }

    /**
	 * FUNCTION: deleteNewsCategoriesById
	 * This function deletes all news categories by news_id
	 * @param int $news_id
	 */
    public function deleteNewsCategoriesById($news_id){
        $dbTable = 'news_categories';
        $where = "`news_id` = $news_id";
        $this->_db->delete($dbTable, $where);
		return true;
    }

    /**
	 * FUNCTION: selectDataByTitle
	 * This function gets date based on title
	 * @param int $title
	 */
	public function selectDataByTitle($title){
		$sql = "SELECT t1.id
				FROM news t1
				WHERE t1.title = :title";

		return $this->_db->select($sql, array(':title' => $title));
	}

}?>