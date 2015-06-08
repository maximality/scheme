				<?php if($this->admins->get_level_access($module)) { ?>
                <div class="bt-set right">
					<span class="btn standart-size">
						<a href="<?php echo DIR_ADMIN; ?>?module=<?php echo $module;?>&action=add" class="button ajax_link" data-module="<?php echo $module;?>">
							<span><img class="bicon plus-s" src="<?php echo $dir_images;?>icon.png" alt="icon"/> Добавить здание</span>
						</a>
					</span>
				</div>
                <?php } ?>	
				
				<h1><img class="catalog-icon" src="<?php echo $dir_images;?>icon.png" alt="icon"/> Здания</h1>
         
				<form action="<?php echo DIR_ADMIN; ?>?module=<?php echo $module;?>&action=index" method="get">
                <div class="section_filtres">
                	<div class="input text">
							<input type="text" name="title" value="<?php if(isset($title) and $title) echo $title;?>" placeholder="Название"/>
					</div>
                    <!--
					<div class="input text">
							<input type="text" name="hosts" value="<?php if(isset($hosts)) echo $hosts;?>" placeholder="Адрес сайта"/>
					</div>  
				</div>
				<div class="section_filtres">
					<label>Дата добавления</label><br>
					<div class="input date for_price">
                        <input type="text" name="date_add[]" value="<?php if(isset($date_add[0])and $date_add[0]>0) echo date('d.m.Y', $date_add[0]);?>" />
                    </div>
                    <span class="input_sub_str" > по </span>
					<div class="input date for_price">
                        <input type="text" name="date_add[]" value="<?php if(isset($date_add[1]) and $date_add[1]>0) echo date('d.m.Y', $date_add[1]);?>" />
                    </div>-->
					
                    <span class="btn standart-size hide-icon">
                        	<button class="ajax_submit" >
                                    <span>Найти</span>
                                </button>
					</span>
                </div>
                </form>
					
				<?php
					if(count($list_buildings)>0) {
				?>
   				<div class="product-table">
					<table>
						<thead>
							<tr>
								<th style="width:400px" class="header <?php if($sort_by=="title") { echo ($sort_dir=="asc" ? "headerSortUp" : "headerSortDown");} ?>"><a href="<?php echo DIR_ADMIN;?>?module=<?php echo $module;?>&sort_by=title&sort_dir=<?php echo ( ($sort_by=="title" and $sort_dir=="asc") ? "desc" : "asc"); ?><?php echo $filter_query; ?>" class="ajax_link" data-module="<?php echo $module;?>">Название <img src="<?php echo $dir_images;?>icon.png" alt="icon"/></a></th>
                                                                <th>Этажей</th>
                                                                <th>Точек</th>
                                                                <th class="header <?php if($sort_by=="date_add") { echo ($sort_dir=="asc" ? "headerSortUp" : "headerSortDown");} ?>"><a href="<?php echo DIR_ADMIN;?>?module=<?php echo $module;?>&sort_by=date_add&sort_dir=<?php echo ( ($sort_by=="date_add" and $sort_dir=="desc") ? "asc" : "desc"); ?><?php echo $filter_query; ?>" class="ajax_link" data-module="<?php echo $module;?>">Дата добавления <img src="<?php echo $dir_images;?>icon.png" alt="icon"/></a></th>
								<th>&nbsp;</th>
							</tr>
						</thead>
						<tbody>
                        	<?php foreach($list_buildings as $buildings) { 
								
							?>
							<tr>
								<td>
									<a href="<?php echo DIR_ADMIN;?>?module=<?php echo $module;?>&action=edit&id=<?php echo $buildings['id'];?>" class="ajax_link" data-module="<?php echo $module;?>"><?php echo $buildings['title'];?></a>
                                </td>
                                <td>
                                    <?php echo $buildings['num_floors']; ?>
                                </td>
                                <td>
                                    <?php echo $buildings['num_points']; ?>
                                </td>
                                <td>
                                	<?php echo date('d.m.Y', $buildings['date_add']);?>
                                </td>
								<td class="nowrap">
 									<a href="<?php echo DIR_ADMIN;?>?module=<?php echo $module;?>&action=edit&id=<?php echo $buildings['id'];?>" class="ajax_link" data-module="<?php echo $module;?>" title="Редактировать"><img src="<?php echo $dir_images;?>icon.png" class="eicon edit-s" alt="icon"/></a>
                                                                       <a href="<?php echo DIR_ADMIN; ?>?module=<?php echo $module; ?>&action=delete&id=<?php echo $buildings['id']; ?>" class="delete-confirm" data-module="admins" data-text="Вы действительно хотите удалить это здание?" title="Удалить"><img src="<?php echo $dir_images;?>icon.png" class="eicon del-s" alt="icon"/></a>
                                 
                                                                </td>
							</tr>
                            <?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<th style="width:400px" class="header <?php if($sort_by=="title") { echo ($sort_dir=="asc" ? "headerSortUp" : "headerSortDown");} ?>"><a href="<?php echo DIR_ADMIN;?>?module=<?php echo $module;?>&sort_by=title&sort_dir=<?php echo ( ($sort_by=="title" and $sort_dir=="asc") ? "desc" : "asc"); ?><?php echo $filter_query; ?>" class="ajax_link" data-module="<?php echo $module;?>">Название <img src="<?php echo $dir_images;?>icon.png" alt="icon"/></a></th>
								<th>Этажей</th>
                                                                <th>Точек</th>
                                                                <th class="header <?php if($sort_by=="date_add") { echo ($sort_dir=="asc" ? "headerSortUp" : "headerSortDown");} ?>"><a href="<?php echo DIR_ADMIN;?>?module=<?php echo $module;?>&sort_by=date_add&sort_dir=<?php echo ( ($sort_by=="date_add" and $sort_dir=="desc") ? "asc" : "desc"); ?><?php echo $filter_query; ?>" class="ajax_link" data-module="<?php echo $module;?>">Дата добавления <img src="<?php echo $dir_images;?>icon.png" alt="icon"/></a></th>
								<th>&nbsp;</th>
							</tr>
						</tfoot>
					</table>
				</div>
                	
                <?php $this->tpl->display('paging'); ?>
                	
				
				<?php } else {?>
				<h3>По заданными критериям ничего не найдено</h3>
                <?php } ?>