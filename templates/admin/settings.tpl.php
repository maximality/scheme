
				<h1><img class="tools-icon" src="<?php echo $dir_images;?>icon.png" alt="icon"/> Настройки</h1>

                <form action="<?php echo DIR_ADMIN; ?>?module=settings&action=edit" method="post" enctype="multipart/form-data">
                <?php if(isset($page_t['id'])) { ?><input type="hidden" name="id" value="<?php echo $page_t['id'];?>"><?php } ?>

				<div class="tabs">
					<ul class="bookmarks">
						<li ><a href="#" data-name="system">Системные</a></li>
					</ul>

					<div class="tab-content ">
						<ul class="form-lines wide">
							<li>
									<label for="limit_num">Количество записей на странице</label>
									<div class="input text ">
										<input type="text" id="limit_num" name="limit_num" value="<?php echo $site->settings->limit_num;?>"/>
									</div>
							</li>
                            <li>
									<label for="num_links">Количество ссылок в пагинаторе</label>
									<div class="input text ">
										<input type="text" id="num_links" name="num_links" value="<?php echo $site->settings->num_links;?>"/>
									</div>
							</li>
                            <li>
									<label for="limit_admin_num">Количество записей на странице в админке</label>
									<div class="input text ">
										<input type="text" id="limit_admin_num" name="limit_admin_num" value="<?php echo $site->settings->limit_admin_num;?>"/>
									</div>
							</li>
                            <li>
									<label for="admin_num_links">Количество ссылок в пагинаторе в админке</label>
									<div class="input text ">
										<input type="text" id="admin_num_links" name="admin_num_links" value="<?php echo $site->settings->admin_num_links;?>"/>
									</div>
							</li>
						</ul>
					</div>

				</div>

				<div class="bt-set clip">
                	<div class="left">
						<span class="btn standart-size blue hide-icon">
                        	<button class="ajax_submit" data-success-name="Cохранено">
                                <span><img class="bicon check-w" src="<?php echo $dir_images;?>icon.png" alt="icon"/> <i>Сохранить</i></span>
                            </button>
						</span>
                   </div>
				</div>
				</form>