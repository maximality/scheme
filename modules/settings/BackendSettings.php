<?php
/**
 * класс отображения настроек в административной части сайта
 * @author riol
 *
 */

class BackendSettings extends View {
	public function index() {
		$this->admins->check_access_module('settings', 2);

		if($this->request->method('post') && !empty($_POST)) {
			$settings = array();
		
			$settings['limit_num'] = $this->request->post('limit_num', 'integer');
			$settings['limit_admin_num'] = $this->request->post('limit_admin_num', 'integer');
			$settings['admin_num_links'] = $this->request->post('admin_num_links', 'integer');
			$settings['num_links'] = $this->request->post('num_links', 'integer');



			//при изменении количества записей на странице очищаем кеш у списковых данных
			if($settings['limit_num']!=$this->settings->limit_num) {
				$this->cache->clean(array("list_news", "list_articles"));
			}

			$this->settings->update_settings($settings);

			/**
			 * если загрузка аяксом возвращаем только 1 в ответе, чтобы обновилась только кнопка сохранения
			 */
			if($this->request->isAJAX()) return 1;
		}

		return $this->tpl->fetch('settings');
	}
}