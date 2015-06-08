<?php

class Buildings extends Module {

	protected $module_name = "buildings";
	public $module_table = "buildings";
	private $module_nesting = false;
	
        private $module_settings = array(
			"dir_images" => "img/",
			"dir_files" => "img/",
			"scheme_sizes"=> array (
					"big"=> array(1200, 15000, false, true),// ширина, высота, crop, watermark
					"normal"=> array(350, 3500, false, false),
					"small"=> array(50, 50, true, false)
			),
                        "palennum_sizes"=> array (
					"big"=> array(1600, 15000, false, true),// ширина, высота, crop, watermark
					"normal"=> array(280, 3500, false, false),
					"small"=> array(50, 50, true, false)
			),
			"images_content_type" => "buildings",
			"files_content_type" => "buildings",
			"revisions_content_type" => "buildings"
	);
        
	public function add($buildings) {
		//чистим кеш
		$this->cache->clean("list_buildings");
		return $this->db->query("INSERT INTO ?_".$this->module_table." (?#) VALUES (?a)", array_keys($buildings), array_values($buildings));
	}

	/**
	 * обновляет элемент в базе
	 */
	public function update($id, $buildings) {
		/**
		 * при изменении имени файла перестраиваем пути у вложенных страниц
		 */
		if(isset($buildings['url'])) {
			$this->cache->delete("buildings_".$buildings["url"]);
		}

		/**
		 * при изменении даты стираем кеш все списков новостей
		 */
		if(isset($buildings['date_add'])) {
			$old_buildings = $this->get_buildings($id);
			if($buildings['date_add']!=$old_buildings['date_add']) $this->cache->clean("list_buildings");
		}

		//чистим кеш
		if(is_array($id)) {
			$cache_tags = array();
			foreach($id as $one_id) {
				$cache_tags[] = "buildingsid_".$one_id;
			}
			$this->cache->clean($cache_tags);
		}
		else {
			$this->cache->clean("buildingsid_".$id);
		}

		if($this->db->query("UPDATE ?_".$this->module_table." SET ?a WHERE id IN (?a)", $buildings, (array)$id))
			return $id;
		else
			return false;
	}

	/**
	 * удаляет элемент из базы
	 */
	public function delete($id) {
		$buildings = $this->get_buildings($id);

		$this->db->query("DELETE FROM ?_".$this->module_table." WHERE id=?", $id);
	}
	
	/**
	 * возвращает новость по id или url
	 * @param mixed $id
	 * @return array
	 */
	public function get_buildings($id) {
		if(is_int($id)) {
			$where_field = "id";
		}
		else return null;

		return $this->db->selectRow("SELECT * FROM ?_".$this->module_table." WHERE ".$where_field."=?", $id);
	}

	/**
	 * возвращает новости удовлетворяющие фильтрам
	 * @param array $filter
	 */
	public function get_list_buildings($filter=array()) {
		$sort_by = " ORDER BY n.date_add DESC";
		$limit = "";
		$where = "";
		if(isset($filter['sort']) and count($filter['sort'])==2) {
			if($filter['sort'][0]=='find_in_set' and isset($filter['in_ids']) and is_array($filter['in_ids']) and count($filter['in_ids'])>0) {

				$new_in_ids = array();
				//выбираем id из списка только те, которые попадают на страницу, чтобы не сортировать при запросе лишнее
				for($i=($filter['limit'][0]-1)*$filter['limit'][1]; $i<(($filter['limit'][0]-1)*$filter['limit'][1]+$filter['limit'][1]); $i++) {
					if(!isset($filter['in_ids'][$i])) break;
					$new_in_ids[] = $filter['in_ids'][$i];
				}
				$filter['in_ids'] = $new_in_ids;
				$sort_by = " ORDER BY FIND_IN_SET(n.id, '".implode(",", $new_in_ids)."')";
				$filter['limit'] = array(1, $filter['limit'][1]);
			}
			else {
				if(!in_array($filter['sort'][0], array("title", "date_add", "hosts"))) $filter['sort'][0] = "date_add";
				if(!in_array($filter['sort'][1], array("asc", "desc")) ) $filter['sort'][1] = "desc";
				$sort_by = " ORDER BY n.".$filter['sort'][0]." ".$filter['sort'][1];
			}
		}

		if(isset($filter['limit']) and count($filter['limit'])==2) {
			$filter['limit'] = array_map("intval", $filter['limit']);
			$limit = " LIMIT ".($filter['limit'][0]-1)*$filter['limit'][1].", ".$filter['limit'][1];
		}
		
        if(isset($filter['title']) and $filter['title']) {
			$where .= (empty($where) ? " WHERE " : " AND ")."n.title LIKE '%".$filter['title']."%'";
		}

		if(isset($filter['hosts']) and $filter['hosts']) {
			$where .= (empty($where) ? " WHERE " : " AND ")."n.hosts LIKE '%".$filter['hosts']."%'";
		}
		
		if(isset($filter['notid'])) {
			$where .= (empty($where) ? " WHERE " : " AND ")."n.id!=".intval($filter['notid']);
		}

		if(isset($filter['date_add']) and count($filter['date_add'])==2) {
			$where .= (empty($where) ? " WHERE " : " AND ").($filter['date_add'][0]>0 ? "n.date_add > {$filter['date_add'][0]}" : "true")." AND ".($filter['date_add'][1]>0 ? "n.date_add < {$filter['date_add'][1]}" : "true");
		}

		if(isset($filter['in_ids']) and is_array($filter['in_ids']) and count($filter['in_ids'])>0) {
			$where .= (empty($where) ? " WHERE " : " AND ")."n.id IN (".implode(",", $filter['in_ids']).")";
		}

		return $this->db->select("SELECT n.*
				FROM ?_".$this->module_table." n".$where
				.$sort_by.$limit);
	}

	/**
	 * возвращает количество новостей удовлетворяющих фильтрам
	 * @param array $filter
	 */
	public function get_count_buildings($filter=array()) {
		$where = "";
		
		if(isset($filter['title']) and $filter['title']) {
			$where .= (empty($where) ? " WHERE " : " AND ")."n.title LIKE '%".$filter['title']."%'";
		}

		if(isset($filter['hosts']) and $filter['hosts']) {
			$where .= (empty($where) ? " WHERE " : " AND ")."n.hosts LIKE '%".$filter['hosts']."%'";
		}
		
		if(isset($filter['date_add']) and count($filter['date_add'])==2) {
			$where .= (empty($where) ? " WHERE " : " AND ")."n.date_add".($filter['date_add'][0]==0 ? "<" : ">")."=".intval($filter['date_add'][1]);
		}
		
		if(isset($filter['notid'])) {
			$where .= (empty($where) ? " WHERE " : " AND ")."n.id!=".intval($filter['notid']);
		}

		if(isset($filter['in_ids']) and is_array($filter['in_ids']) and count($filter['in_ids'])>0) {
			$where .= (empty($where) ? " WHERE " : " AND ")."n.id IN (".implode(",", $filter['in_ids']).")";
		}


		return $this->db->selectCell("SELECT count(n.id)
				FROM ?_".$this->module_table." n".$where);
	}
	
	public function is_nesting() {
		return $this->module_nesting;
	}
        
         /**
	 * возвращает настройку модуля
	 * @param string $id
	 * @return Ambigous <NULL, multitype:string >
	 */
	public function setting($id) {
		return (isset($this->module_settings[$id]) ? $this->module_settings[$id] : null);
	}
       
        public function add_image($building_id, $image, $image_sizes) {
		if(!$this->image->create_image(ROOT_DIR_IMAGES.$this->setting("dir_images")."original/".$image, ROOT_DIR_IMAGES.$this->setting("dir_images")."big/".$image, $image_sizes["big"])) return false;
		if(!$this->image->create_image(ROOT_DIR_IMAGES.$this->setting("dir_images")."original/".$image, ROOT_DIR_IMAGES.$this->setting("dir_images")."normal/".$image, $image_sizes["normal"])) return false;
		if(!$this->image->create_image(ROOT_DIR_IMAGES.$this->setting("dir_images")."original/".$image, ROOT_DIR_IMAGES.$this->setting("dir_images")."small/".$image, $image_sizes["small"])) return false;
		return $this->db->query("INSERT INTO ?_attach_fotos (for_id, picture, content_type) VALUES (?, ?, ?)", $building_id, $image, $this->setting("images_content_type"));
	}
        
        public function delete_image($id) {
		$picture = $this->db->selectCell("SELECT picture FROM ?_attach_fotos WHERE id=? AND content_type=?", $id, $this->setting("images_content_type"));
		if($picture) {
			//проверяем, не используется ли это изображение где-то еще
			$count = $this->db->selectCell("SELECT count(*) FROM ?_attach_fotos WHERE picture=?", $picture);
			if($count==1) {
				@unlink(ROOT_DIR_IMAGES.$this->setting("dir_images")."original/".$picture);
				@unlink(ROOT_DIR_IMAGES.$this->setting("dir_images")."big/".$picture);
				@unlink(ROOT_DIR_IMAGES.$this->setting("dir_images")."normal/".$picture);
				@unlink(ROOT_DIR_IMAGES.$this->setting("dir_images")."small/".$picture);
			}
		}
		return $this->db->query("DELETE FROM ?_attach_fotos WHERE id=? AND content_type=?", $id, $this->setting("images_content_type"));
	}
        
        public function get_picture($id){
            return $this->db->selectCell("SELECT picture FROM ?_attach_fotos WHERE id=? AND content_type=?", $id, $this->setting("images_content_type"));
        }
        
        public function delete_palennum_tour_image($building_id, $afloor_id, $apoint_id){
            $building = $this->get_buildings($building_id);
            if(isset($building['floors']) and @$building['floors'] = unserialize($building['floors'])){
                $new_floors = array();
                foreach($building['floors'] as $floor_id => $floor){
                    if($floor_id == $afloor_id){
                        if($floor['points']){
                            $new_points = array();
                            foreach($floor['points'] as $point_id => $point)
                            {
                                if($point_id == $apoint_id){
                                    $this->delete_image($point['palennum_tour_img']);
                                    $point['palennum_tour_img'] = 0;
                                }
                                $new_points[] = $point;
                            }
                            $floor['points'] = $new_points;
                        }
                    }
                    $new_floors[] = $floor;
                }
                $building['floors'] = serialize($new_floors);
                $this->update($building['id'], $building);
            }
            return 1;
        }
        
        public function delete_floor_scheme($building_id, $afloor_id){
            $building = $this->get_buildings($building_id);
            if(isset($building['floors']) and @$building['floors'] = unserialize($building['floors'])){
                $new_floors = array();
                foreach($building['floors'] as $floor_id => $floor){
                    if($floor_id == $afloor_id){
                        $this->delete_image($floor['floor_scheme_img']);
                        $floor['floor_scheme_img'] = 0;
                    }
                    $new_floors[] = $floor;
                }
                $building['floors'] = serialize($new_floors);
                $this->update($building['id'], $building);
            }
            return 1;
        }
}