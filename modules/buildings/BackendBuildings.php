<?php

class BackendBuildings extends View {
	public function index() {
		$this->admins->check_access_module('buildings');

		$sort_by = $this->request->get("sort_by", "string");
		$sort_dir = $this->request->get("sort_dir", "string");

		if(!$sort_by or !in_array($sort_by, array("title", "date_add", "hosts")) ) $sort_by = "date_add";
		if(!$sort_dir or !in_array($sort_dir, array("asc", "desc")) ) $sort_dir = "desc";

		$filter_query = "&title=".$this->request->get('title', 'string')."&hosts=".$this->request->get('hosts', 'string');
		$paging_added_query = "&action=index&sort_by=".$sort_by."&sort_dir=".$sort_dir.$filter_query;
		$link_added_query = "&sort_by=".$sort_by."&sort_dir=".$sort_dir;

		// Постраничная навигация
		$limit = ($tmpVar = intval($this->settings->limit_admin_num)) ? $tmpVar : 10;
		// Текущая страница в постраничном выводе
		$p = $this->request->get('p', 'integer');
		// Если не задана, то равна 1
		$p = max(1, $p);
		$link_added_query .= "&p=".$p;

		$filter = array("sort"=> array($sort_by, $sort_dir));

		$filter["limit"] = array($p, $limit);
		$filter['title'] = $title = $this->request->get('title', 'string');
		$date_add = $this->request->get('date_add', 'array');
		$date_add[0] = (isset($date_add[0]))?strtotime($date_add[0]):"";
		$date_add[1] = (isset($date_add[1]))?strtotime($date_add[1]):"";
		if($date_add[0] or $date_add[1])
			$filter['date_add'] = $date_add;
		$filter['hosts'] = $hosts = $this->request->get('hosts', 'string');

		// Вычисляем количество страниц
		$buildings_count = intval($this->buildings->get_count_buildings($filter));
		$total_pages_num = ceil($buildings_count/$limit);
		
		$buildings_full_link = $this->pages->get_full_link_module("buildings");

		$list_buildings = $this->buildings->get_list_buildings( $filter );
		
		$this->tpl->add_var('title', $title);
		$this->tpl->add_var('hosts', $hosts);
		$this->tpl->add_var('date_add', $date_add);
		$this->tpl->add_var('list_buildings', $list_buildings);
		$this->tpl->add_var('sort_by', $sort_by);
		$this->tpl->add_var('sort_dir', $sort_dir);
		$this->tpl->add_var('buildings_count', $buildings_count);
		$this->tpl->add_var('total_pages_num', $total_pages_num);
		$this->tpl->add_var('p', $p);
		$this->tpl->add_var('paging_added_query', $paging_added_query);
		$this->tpl->add_var('link_added_query', $link_added_query);
		$this->tpl->add_var('buildings_full_link', $buildings_full_link);
		$this->tpl->add_var('filter_query', $filter_query);
		return $this->tpl->fetch('buildings');
	}
	
	
	public function edit() {
		$this->admins->check_access_module('buildings', 1);

		//возможность не перезагружать форму при запросах аяксом, но если это необходимо, например, загружена картинка - обновляем форму
		$may_noupdate_form = true;

		$method = $this->request->method();
		$buildings_id = $this->request->$method("id", "integer");
		$tab_active = $this->request->$method("tab_active", "string");
		if(!$tab_active) $tab_active = "main";
		
		/**
		 * ошибки при заполнении формы
		 */
		$errors = array();
		
		$buildings = array("id"=>$buildings_id, "date_add"=>time());

		if($this->request->method('post') && !empty($_POST)) {
			$buildings['title'] = $this->request->post('title', 'string');
			/*$buildings['brief_description'] = $this->request->post('brief_description');
                        */
			$date = strtotime($this->request->post('date', 'string'));
			$h = $this->request->post('h', 'integer');
			$m = $this->request->post('m', 'integer');
                        
			if(!$date) $date=time();
			$buildings['date_add'] = $h * 3600 + $m * 60 + $date;
			
                        
			$after_exit = $this->request->post('after_exit', "boolean");
                        $num_floors = $num_points = 0;
                        
			if(count($errors)==0) {
                                $floor_titles = $this->request->post('floor_title', 'array');
                                $point_titles =  $this->request->post('floor_points', 'array');
                                $selected_points = $this->request->post('selected_point', 'array');
                                $point_imgs = $this->request->post('palennum_tour_img', 'array');
                                $point_descriptions = $this->request->post('point_description', 'array');
                                $floor_scheme_imgs = $this->request->post('floor_scheme_img', 'array');
                                $point_areas = $this->request->post('floor_point_area', 'array');
                                $point_show_as_img = $this->request->post('point_show_as_img', 'array');
                                
                                $floors = array();
                                foreach($floor_titles as $floor_id => $floor_title){
                                    if($floor_id){
                                        $num_floors++;
                                        $floor['title'] = $this->request->get_str($floor_title, 'string');
                                        $floor['floor_scheme_img'] = $this->request->get_str(isset($floor_scheme_imgs[$floor_id])?$floor_scheme_imgs[$floor_id]:0, 'integer');
                                        
                                        //floor scheme
                                        if($picture = $this->request->files('floor_scheme_file'))
					{
                                                $picture['name'] = isset($picture['name'][$floor_id])?$picture['name'][$floor_id]:'';
                                                $picture['type'] = isset($picture['type'][$floor_id])?$picture['type'][$floor_id]:'';
                                                $picture['size'] = isset($picture['size'][$floor_id])?$picture['size'][$floor_id]:'';
                                                $picture['tmp_name'] = isset($picture['tmp_name'][$floor_id])?$picture['tmp_name'][$floor_id]:'';
                                                $picture['error'] = isset($picture['error'][$floor_id])?$picture['error'][$floor_id]:'';
                                                
						if(isset($picture['error']) and $picture['error']!=0 and $picture['name']) {
							$errors['floors'][$floor_id]['photo'] = 'error_size';
						}
						else if($picture['name']){
							if($image_name = $this->image->upload_image($picture, $picture['name'], $this->buildings->setting("dir_images")))
							{
								$image_id = $this->buildings->add_image($buildings_id, $image_name, $this->buildings->setting('scheme_sizes'));
								
                                                                if(!$image_id) {
									$errors['floors'][$floor_id]['photo'] = 'error_internal';
								}
                                                                else{
                                                                        if($floor['floor_scheme_img'])
                                                                            $this->buildings->delete_image($floor['floor_scheme_img']);
                                                                        
									$floor['floor_scheme_img'] = $image_id;
                                                                        $may_noupdate_form = false;
								}
							}
							else
							{
								if($image_name===false) $errors['floors'][$floor_id]['photo'] = 'error_type';
								else $errors['floors'][$floor_id]['photo'] = 'error_upload';
							}
                                                        $tab_active = 'floor_'.$floor_id;
						}
					}
                                        
                                        $floor['points'] = array();
                                        $floor['selected_point'] = $this->request->get_str(isset($selected_points[$floor_id])?$selected_points[$floor_id]:0, 'integer');
                                        if(isset($point_titles[$floor_id]) and count($point_titles[$floor_id]) > 1){
                                            array_pop($point_titles[$floor_id]);
                                            foreach($point_titles[$floor_id] as $point_id => $title){
                                                $num_points++;
                                                $point['title'] = $title;
                                                $point['description'] = ($point_descriptions[$floor_id][$point_id]);
                                                @$point['point_show_as_img'] = $this->request->get_str($point_show_as_img[$floor_id][$point_id], 'integer');
                                                $point['area'] = $this->request->get_str($point_areas[$floor_id][$point_id], 'string');
                                                $point['palennum_tour_img'] = $this->request->get_str(isset($point_imgs[$floor_id][$point_id])?$point_imgs[$floor_id][$point_id]:0, 'integer');
                                                //point img
                                                if($picture = $this->request->files('palennum_tour_file'))
                                                {
                                                        
                                                        $picture['name'] = isset($picture['name'][$floor_id][$point_id])?$picture['name'][$floor_id][$point_id]:'';
                                                        $picture['type'] = isset($picture['type'][$floor_id][$point_id])?$picture['type'][$floor_id][$point_id]:'';
                                                        $picture['size'] = isset($picture['size'][$floor_id][$point_id])?$picture['size'][$floor_id][$point_id]:'';
                                                        $picture['tmp_name'] = isset($picture['tmp_name'][$floor_id][$point_id])?$picture['tmp_name'][$floor_id][$point_id]:'';
                                                        $picture['error'] = isset($picture['error'][$floor_id][$point_id])?$picture['error'][$floor_id][$point_id]:'';
                                                        
                                                        if(isset($picture['error']) and $picture['error']!=0 and $picture['name']) {
                                                                $errors['floors'][$floor_id]['photo'] = 'error_size';
                                                        }
                                                        else if($picture['name']){
                                                                if($image_name = $this->image->upload_image($picture, $picture['name'], $this->buildings->setting("dir_images")))
                                                                {
                                                                        $image_id = $this->buildings->add_image($buildings_id, $image_name, $this->buildings->setting('palennum_sizes'));

                                                                        if(!$image_id) {
                                                                                $errors['floors'][$floor_id]['photo'] = 'error_internal';
                                                                        }
                                                                        else{
                                                                                if($point['palennum_tour_img'])
                                                                                    $this->buildings->delete_image($point['palennum_tour_img']);
                                                                                $tab_active = 'floor_'.$floor_id;    
                                                                                $point['palennum_tour_img'] = $image_id;
                                                                               
                                                                                $may_noupdate_form = false;
                                                                        }
                                                                }
                                                                else
                                                                {
                                                                        if($image_name===false) $errors['floors'][$floor_id]['photo'] = 'error_type';
                                                                        else $errors['floors'][$floor_id]['photo'] = 'error_upload';
                                                                }
                                                                $tab_active = 'floor_'.$floor_id;
                                                        }
                                                }
                                                
                                                $floor['points'][] = $point;
                                            }
                                        }
                                        
                                        $floors[$floor_id] = $floor;
                                    }
                                }
                                
                               $buildings['floors'] = serialize($floors);
                               
                               if(empty($buildings['title'])) {
                                    $errors['title'] = 'no_title';
                                    $tab_active = "main";
                               }
                               
                               if(!$errors){
                                    $buildings['num_floors'] = $num_floors;
                                    $buildings['num_points'] = $num_points;
                                    if($buildings_id) {
                                            $this->buildings->update($buildings_id, $buildings);
                                    }
                                    else {
                                            $buildings_id = (int)$this->buildings->add($buildings);
                                            $buildings['id'] = $buildings_id;
                                            $may_noupdate_form = false;
                                    }

                                    /**
                                     * если было нажата кнопка Сохранить и выйти, перекидываем на список страниц
                                     */
                                    if($after_exit and count($errors)==0) {
                                            header("Location: ".DIR_ADMIN."?module=buildings");
                                            exit();
                                    }
                                    /**
                                     * если загрузка аяксом возвращаем только 1 в ответе, чтобы обновилась только кнопка сохранения
                                     */
                                    elseif($this->request->isAJAX() and count($errors)==0 and $buildings['id'] and $may_noupdate_form) return 1;
                               }
                               else{
                                   $may_noupdate_form = false;
                               } 
                                           
			}
		}
                else if($buildings_id) {
			$buildings = $this->buildings->get_buildings($buildings_id);
			
			if(count($buildings)==0) {
				header("Location: ".DIR_ADMIN."?module=buildings");
				exit();
			}
		}
                
                @$buildings['floors'] = unserialize($buildings['floors']);
                
                $this->tpl->add_var('content_photos_dir', SITE_URL.URL_IMAGES.$this->buildings->setting('dir_images'));
		$this->tpl->add_var('errors', $errors);
		$this->tpl->add_var('buildings', $buildings);
		$this->tpl->add_var('tab_active', $tab_active);
		return $this->tpl->fetch('buildings_add');
	}

	/**
	 * синоним для edit
	 */
	public function add() {
		return $this->edit();
	}
	
	public function delete() {
		$this->admins->check_access_module('buildings', 2);

		$id = $this->request->get("id", "integer");
		if($id>0) $this->buildings->delete($id);
		return $this->index();
	}
}