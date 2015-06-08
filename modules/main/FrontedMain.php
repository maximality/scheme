<?php
/**
 * класс отображения главной страницы сайта
 * @author riol
 *
 */

class FrontedMain extends View {
	public function index() {
                $building_id = $this->request->get('building', 'integer');
                $floor_id = $this->request->get('floor', 'integer');
                $buildings = $this->buildings->get_list_buildings(array('sort' => array('date_add', 'asc')));
                
                $this->tpl->add_var('buildings', $buildings);
                $this->tpl->add_var('building_id', $building_id);
                $this->tpl->add_var('floor_id', $floor_id);
                 $this->tpl->add_var('content_photos_dir', SITE_URL.URL_IMAGES.$this->buildings->setting('dir_images'));
		return $this->tpl->fetch('main');
	}
}