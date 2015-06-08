
<script type="text/javascript" src="<?php echo $dir_js; ?>floor.js">

</script>  
<div class="bt-set right">
					<span class="btn standart-size">
						<a href="#" class="button js-add-floor">
							<span><img class="bicon plus-s" src="<?php echo $dir_images;?>icon.png" alt="icon"/> Добавить этаж</span>
						</a>
					</span>
				</div>

		<h1><img class="catalog-icon" src="<?php echo $dir_images;?>icon.png" alt="icon"/> <?php if(isset($buildings['id']) and $buildings['id']>0) { ?>Редактировать<?php } else { ?>Добавить<?php } ?> здание</h1>

                <form action="<?php echo DIR_ADMIN; ?>?module=<?php echo $module;?>&action=edit" method="post" enctype="multipart/form-data">
                <?php if(isset($buildings['id'])) { ?><input type="hidden" name="id" value="<?php echo $buildings['id'];?>"><?php } ?>
				
				<div class="tabs">
					<ul class="bookmarks">
						<li <?php if($tab_active=="main")  { ?>class="active"<?php } ?>><a href="#" data-name="main">Основное</a></li>
                                                <?php 
                                                $new_floor_id = 0;
                                               
                                                if(isset($buildings['floors']) and $buildings['floors']) {
                                                    
                                                    foreach($buildings['floors'] as $floor_id=>$floor){
                                                        ?>
                                                            <li <?php if($tab_active=="floor_".$floor_id)  { ?>class="active"<?php } ?>><a href="#" data-name="<?php echo "floor_".$floor_id; ?>"><?php echo $floor['title']?$floor['title']:'Этаж без названия'; ?></a></li>
                                                        <?php
                                                        $new_floor_id = $floor_id;
                                                    }
                                                }
                                                ?>
                                                <li style="display:none;"><a href="#" data-name="floor_0">Новый этаж</a></li>
					</ul>
                                        <div class="js-floor-count"><?php echo $new_floor_id; ?></div>

					<div class="tab-content">
											
							<ul class="form-lines wide left">
								<li>
									<label for="page-caption">Название</label>
									<div class="input text <?php if(isset($errors['title'])) echo "fail";?>">
										<input type="text" id="page-caption" name="title" <?php if(!isset($buildings['id']) or $buildings['id']<1) { ?>class="title_for_slug"<?php } ?> value="<?php if(isset($buildings['title'])) echo $buildings['title'];?>"/>
	                                    <?php if(isset($errors['title'])) { ;?><p class="error">это поле обязательно для заполнения</p><?php } ?>
									</div>
								</li>
                             
								
							</ul>
							
							<ul class="form-lines narrow right">
                                <li>
                                    <label>Дата добавления</label>
                                    <div class="input date">
                                        <input type="text" name="date" value="<?php if(isset($buildings['date_add'])) echo date('d.m.Y', $buildings['date_add']);?>" />
                                    </div>
                                </li>
							</ul>
							
							<div class="clear"></div>
					</div><!-- .tab-content end -->	
                                        <?php
                                        $buildings['floors'][] = array();
                                        foreach($buildings['floors'] as $floor_id => $floor){ 
                                            if(!$floor)
                                                $floor_id = 0;
                                            $active_floor = $tab_active == "floor_".$floor_id?true:false;
                                        ?>
                                        <div class="tab-content js-floor-content" <?php echo  (!$active_floor?'style="display:none;"':''); ?>>
                                            <input type="hidden" class="js-floor-id" value="<?php echo $floor_id; ?>"/>
                                            <ul class="form-lines wide">
						<li>
                                                        <label>Заголовок этажа</label><br/>
                                                        <div class="input text" style="display: inline-block; width: 300px; float: none;">
                                                                <input type="text" name="floor_title[<?php echo $floor_id; ?>]" class="js-floor-title" value="<?php echo (isset($floor['title'])?$floor['title']:''); ?>"/>
                                                        </div>
                                                        <div style="display: inline-block; float:none;">
                                                                <span class="btn standart-size red">
                                                                        <button class="js-delete-floor" >
                                                                                <span><img class="bicon cross-w" src="<?php echo $dir_images;?>icon.png" alt="icon"/> Удалить этаж</span>
                                                                        </button>
                                                                </span>
                                                        </div>
                                                </li>
                                            </ul>
                                            <div class="floor-points">
                                                <div class="product-table phones js-points">
                                                    <table>
                                                      <tbody>
                                                        <?php
                                                        $floor['points'][] = array();
                                                        foreach($floor['points'] as $point_id => $point) { ?>
                                                        <tr style="<?php if(!$point) echo 'display: none;'; ?> <?php if(isset($floor['selected_point']) and $floor['selected_point'] == $point_id) echo 'background-color: #F0F0F2'; ?>">
                                                            <td class="phone">
                                                                <div class="input text always_visible">
                                                                    <input type="text" placeholder="Название комнаты" value="<?php echo (isset($point['title'])?$point['title']:''); ?>" class="js-floor-point" name="floor_points[<?php echo $floor_id; ?>][]" autocomplete="off">
                                                                </div>
                                                            </td>
                                                            <td style="width: 120px;"> 
                                                              <input type="radio" name="selected_point[<?php echo $floor_id; ?>]" class="js-selected-point" value="<?php echo $point_id; ?>" <?php if(isset($floor['selected_point']) and $floor['selected_point'] == $point_id) echo 'checked'; ?>/>
                                                              <a href="#" class="delete-inline js-delete-point" title="Удалить"><img src="<?php echo $dir_images;?>icon.png" class="eicon del-s" alt="icon"/></a>
                                                            </td>
                                                        </tr>
                                                        <?php } ?>
                                                      </tbody>
                                                    </table>
                                                </div>
                                                <div class="bt-set" style="margin-top: 20px;">
                                                        <span class="btn standart-size">
                                                                <a href="#" class="button js-add-point">
                                                                        <span><img class="bicon plus-s" src="<?php echo $dir_images;?>icon.png" alt="icon"/> Добавить точку</span>
                                                                </a>
                                                        </span>
                                                </div>
                                            </div>
                                            <div class="floor-point-info">
                                                  <?php
                                                  foreach($floor['points'] as $point_id => $point) { 
                                                      
                                                  ?>
                                                  <div class="js-point-info-item" <?php if((isset($floor['selected_point']) and $floor['selected_point'] != $point_id) or !$point) echo 'style="display: none;"'; ?>>
                                                      
                                                      <input type="hidden" name="floor_point_area[<?php echo $floor_id; ?>][]" value="<?php echo isset($point['area'])?$point['area']:''; ?>" class="js-point-area-val">
                                                      <?php if((isset($floor['selected_point']) and $floor['selected_point'] == $point_id) and isset($point['area']) and $point['area']) { ?>
                                                      <script type="text/javascript">
                                                               $(document).ready(function(){
                                                                    redraw_sel_area($('input.js-floor-id[value=' + <?php echo $floor_id; ?> + ']').closest('.js-floor-content').find('img.scheme-image'),
                                                                            $('input.js-floor-id[value=' + <?php echo $floor_id; ?> + ']').closest('.js-floor-content').find('.floor-point-info').find('.js-point-info-item').eq(<?php echo $point_id; ?>).find('input.js-point-area-val').val()
                                                                        );
                                                                });
                                                      </script>
                                                      <?php } ?>
                                                      <h2 class="point-info-title"><?php echo (isset($point['title'])?$point['title']:''); ?></h2>
                                                      <div class="point-info-img">
                                                            <label>Изображение или виртуальный тур</label>
                                                            <label><input type="checkbox" name="point_show_as_img[<?php echo $floor_id; ?>][<?php echo $point_id; ?>]"  value="1" <?php echo (isset($point['point_show_as_img']) and $point['point_show_as_img'])?'checked':''; ?>/>Показать только как изображение</label>
                                                            <?php if(isset($point['palennum_tour_img']) and $point['palennum_tour_img']!="") { ?>
                                                            <div class="one_image">
                                                                    <input type="hidden" name="palennum_tour_img[<?php echo $floor_id; ?>][<?php echo $point_id; ?>]" class="js-palennum-tour-img" value="<?php if(isset($point['palennum_tour_img'])) echo $point['palennum_tour_img'];?>"/>
                                                                    <img src="<?php echo $content_photos_dir."normal/".$this->buildings->get_picture($point['palennum_tour_img']);?>" alt="" style="width: 100%;"><br>
                                                                    <a href="<?php echo DIR_ADMIN; ?>ajax_delete_image.php?module=<?php echo $module;?>&id=<?php echo $buildings['id']; ?>&action=delete_palennum_tour_image&id2=<?php echo $floor_id; ?>&id3=<?php echo $point_id; ?>" class="delete-confirm delete-one-image" data-module="<?php echo $module;?>" data-text="Вы действительно хотите удалить это изображение?" title="Удалить">Удалить изображение</a>
                                                            </div>
                                                            <?php } ?>
                                                            <div class="input input_smart_file">
                                                                    <span class="btn standart-size">
                                                                        <span class="button">
                                                                            <span><img class="bicon plus-s" src="<?php echo $dir_images;?>icon.png" alt="icon"/> Выбрать файл</span>
                                                                        </span>
                                                                    </span>
                                                                <span class="file_name"></span>
                                                                <input type="file" accept="image/jpeg,image/png,image/gif" name="palennum_tour_file[<?php echo $floor_id; ?>][<?php echo $point_id; ?>]">
                                                            </div>
                                                            <?php if(isset($errors['floors'][$floor_id]['points'][$point_id]['photo']) and $errors['floors'][$floor_id]['points'][$point_id]['photo']=='error_type') { ;?><p class="error">неверный тип файла</p><?php } ?>
                                                            <?php if(isset($errors['floors'][$floor_id]['points'][$point_id]['photo']) and $errors['floors'][$floor_id]['points'][$point_id]['photo']=='error_size') { ;?><p class="error">слишком большой файл</p><?php } ?>
                                                            <?php if(isset($errors['floors'][$floor_id]['points'][$point_id]['photo']) and $errors['floors'][$floor_id]['points'][$point_id]['photo']=='error_upload') { ;?><p class="error">папка загрузки недоступна для записи или недостаточно места</p><?php } ?>
                                                            <?php if(isset($errors['floors'][$floor_id]['points'][$point_id]['photo']) and $errors['floors'][$floor_id]['points'][$point_id]['photo']=='error_internal') { ;?><p class="error">внутренняя ошибка сервера</p><?php } ?>
                                                      </div>
                                                      <div class="bt-set">
                                                            <span class="btn standart-size">
                                                                    <a href="#" class="button js-change-point-area">
                                                                            <span>Изменить область</span>
                                                                    </a>
                                                            </span>
                                                      </div>
                                                      или <a href="#" class="js-clear-area">очистить</a>
                                                      <div class="point-info-description">
                                                          <label>Описание точки</label>
                                                          <div class="input textarea" style="width: 100%;">
                                                              <textarea name="point_description[<?php echo $floor_id; ?>][]" rows="15"><?php echo isset($point['description'])?$point['description']:''; ?></textarea>
                                                          </div>
                                                      </div>
                                                  </div>
                                                  <?php } ?>
                                            </div>
                                            <div class="floor-scheme">
                                                    <div class="floor-scheme-img">
                                                            <label>Схема этажа</label>
                                                            <?php if(isset($floor['floor_scheme_img']) and $floor['floor_scheme_img']!="") { ?>
                                                            <div class="one_image">
                                                                    <?php
                                                                        $picture = $this->buildings->get_picture($floor['floor_scheme_img']);
                                                                        $sizes = getimagesize($content_photos_dir."big/".$picture);
                                                                        $sizes2 = getimagesize($content_photos_dir."normal/".$picture);
                                                                    ?>
                                                                    <input type="hidden" name="floor_scheme_img[<?php echo $floor_id; ?>]" value="<?php if(isset($floor['floor_scheme_img'])) echo $floor['floor_scheme_img'];?>"/>
                                                                    <img src="<?php echo $content_photos_dir."normal/".$picture;?>" alt="" class="scheme-image" data-width="<?php echo $sizes[0]; ?>" 
                                                                         data-height="<?php echo $sizes[1]; ?>" data-name="<?php echo $content_photos_dir."big/".$picture; ?>"
                                                                         data-width2="<?php echo $sizes2[0]; ?>" data-height2="<?php echo $sizes2[1]; ?>"
                                                                         ><br>
                                                                    <a href="<?php echo DIR_ADMIN; ?>ajax_delete_image.php?module=<?php echo $module;?>&id=<?php echo $buildings['id']; ?>&id2=<?php echo $floor_id; ?>&action=delete_floor_scheme" class="delete-confirm delete-one-image" data-module="<?php echo $module;?>" data-text="Вы действительно хотите удалить это изображение?" title="Удалить">Удалить изображение</a>
                                                            </div>
                                                            <?php } ?>
                                                            <div class="input input_smart_file">
                                                                    <span class="btn standart-size">
                                                                        <span class="button">
                                                                            <span><img class="bicon plus-s" src="<?php echo $dir_images;?>icon.png" alt="icon"/> Выбрать файл</span>
                                                                        </span>
                                                                    </span>
                                                                <span class="file_name"></span>
                                                                <input type="file" accept="image/jpeg,image/png,image/gif" name="floor_scheme_file[<?php echo $floor_id; ?>]">
                                                            </div>
                                                            <?php if(isset($errors['floors'][$floor_id]['photo']) and $errors['floors'][$floor_id]['photo']=='error_type') { ;?><p class="error">неверный тип файла</p><?php } ?>
                                                            <?php if(isset($errors['floors'][$floor_id]['photo']) and $errors['floors'][$floor_id]['photo']=='error_size') { ;?><p class="error">слишком большой файл</p><?php } ?>
                                                            <?php if(isset($errors['floors'][$floor_id]['photo']) and $errors['floors'][$floor_id]['photo']=='error_upload') { ;?><p class="error">папка загрузки недоступна для записи или недостаточно места</p><?php } ?>
                                                            <?php if(isset($errors['floors'][$floor_id]['photo']) and $errors['floors'][$floor_id]['photo']=='error_internal') { ;?><p class="error">внутренняя ошибка сервера</p><?php } ?>
                                                            <div class="floor-scheme-img-sel-area">
                                                                
                                                            </div>
                                                           
                                                    </div> 
                                            </div>
                                        </div>
                                        <?php } ?>
				</div><!-- .tabs end -->
				
				<div class="bt-set clip">
                	<div class="left">
						<span class="btn standart-size blue hide-icon">
                        	<button class="ajax_submit js-auto-saving" data-success-name="Cохранено">
                                <span><img class="bicon check-w" src="<?php echo $dir_images;?>icon.png" alt="icon"/> <i>Сохранить</i></span>
                            </button>
						</span>
                        <span class="btn standart-size blue hide-icon">
							<button class="submit_and_exit">
								<span>Сохранить и выйти</span>
							</button>
						</span>
                   </div>
					<?php if(isset($buildings['id']) and $buildings['id']>0) { ?>
                   <div class="right">
						<span class="btn standart-size red">
							<button class="delete-confirm" data-module="<?php echo $module;?>" data-text="Вы действительно хотите удалить это здание?" data-url="<?php echo DIR_ADMIN; ?>?module=<?php echo $module;?>&action=delete&id=<?php echo $buildings['id']; ?>">
								<span><img class="bicon cross-w" src="<?php echo $dir_images;?>icon.png" alt="icon"/> Удалить здание</span>
							</button>
						</span>
					</div>
					<?php } ?>
				</div>
                                <input type="hidden" name="tab_active" value="<?php echo $tab_active;?>">
				</form>

