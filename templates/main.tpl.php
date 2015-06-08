<a href="<?php echo SITE_URL; ?>admin/" class="to-admin">Войти в админ панель</a>
<nav>
    <?php 
    $selected_building = array();
    if($buildings)
        foreach($buildings as $id => $val) { ?>
            <a href="<?php echo SITE_URL; ?>?building=<?php echo $id; ?>" <?php if($id == $building_id) { ?>class="active"<?php } ?>><?php echo $val['title']; ?></a>
        <?php 
        if($id == $building_id)
            $selected_building = $val;
        } 
        
        $selected_floor = array();
        $floors = array();
        if(isset($selected_building['floors']) and @$floors = unserialize($selected_building['floors'])){
            $i = 0;
            foreach($floors as $floor){
                if($floor_id == $i){
                    $selected_floor = $floor;
                    break;
                }
                $i++;
            }
        }
        
        $panorama_img = $panorama_title = $panorama_desc = "";
        if($selected_floor and $points = $selected_floor['points']){
            $panorama_desc = $points[0]['description'];
            $panorama_title = $points[0]['title'];
            $panorama_show_as_img = $points[0]['point_show_as_img'];
            $panorama_img = $this->buildings->get_picture($points[0]['palennum_tour_img']);
            if(!$panorama_img)
                $panorama_title .= '(Нет изображения)';
        }
        
        ?>     
</nav>
<div class="content">
    <div class="panellum-block">
        <?php if(!$panorama_show_as_img) { ?>
        <iframe  class="js-pannellum" title="pannellum panorama viewer" width="460" height="380" 
                webkitAllowFullScreen mozallowfullscreen allowFullScreen style="border-style:none;" 
                accesskey="" 
                src="<?php echo $dir_js; ?>panellum/pannellum.htm?panorama=<?php echo $content_photos_dir.'original/'.$panorama_img.'&title='.$panorama_title; ?>&autoLoad=true">
        </iframe>
        <?php } else { ?>
        <a href="<?php echo $content_photos_dir.'big/'.$panorama_img; ?>" class=" fb-gallery"><img src="<?php echo $content_photos_dir.'big/'.$panorama_img; ?>" class="js-pannellum" alt="<?php echo $panorama_title; ?>" width="460"/></a>
        <?php } ?>
    </div> 
    <div class="scheme-block">
        <?php if($selected_floor) { ?>
        <h2><?php echo $selected_floor['title']; ?></h2>
        <?php if($selected_floor['floor_scheme_img']) { ?>
        <div class="img-block">
            <img class="image-scheme" src="<?php echo $content_photos_dir.'big/'.$this->buildings->get_picture($selected_floor['floor_scheme_img']); ?>" style="width: 100%;" alt="<?php echo $selected_floor['title']; ?>"/>
            <?php if($selected_floor['points']) { 
                foreach($selected_floor['points'] as $point) { ?>
                <div class="area" data-title="<?php echo $point['title'].(!$point['palennum_tour_img']?' (нет изображения)':''); ?>"  data-area="<?php echo $point['area']; ?>" 
                     data-panorama="<?php echo $content_photos_dir.'original/'.$this->buildings->get_picture($point['palennum_tour_img']); ?>" data-panorama-light="<?php echo $content_photos_dir.'big/'.$this->buildings->get_picture($point['palennum_tour_img']); ?>" data-show-as-img="<?php echo $point['point_show_as_img']; ?>">
                    <span><?php echo $point['title']?$point['title']:'Точка без названия'; ?></span>
                    <div style="display: none;" class="js-descr"><?php echo ($point['description']); ?></div>
                </div>
                <?php } 
                
                } ?>
        </div>  
       <?php } else { ?>
            <p>Нет схемы у выбранного этажа</p>
       <?php } ?>
       
        <?php } else { ?>
        <p>Выберите этаж</p>
        <?php } ?>
    </div>
    <div class="floor-nav-block">
        <?php if(count($floors) > 1) { ?>
        <a href="<?php echo SITE_URL; ?>?building=<?php echo $building_id; ?>&floor=<?php echo (count($floors) != $floor_id + 1)?$floor_id + 1:0; ?>">
            <img src="<?php echo $dir_images; ?>uparrow.png" width="40" alt="Следующий этаж"/>
        </a>
        <a href="<?php echo SITE_URL; ?>?building=<?php echo $building_id; ?>&floor=<?php echo ($floor_id==0?count($floors) - 1:$floor_id-1); ?>">
            <img src="<?php echo $dir_images; ?>downarrow.png" width="40" alt="Предыдущий этаж"/>
        </a>
        <?php } ?>
    </div>
    
    <div class="point-description-block">
        <label>Описание точки:</label>
        <p class="js-point-info">
            <?php echo $panorama_desc; ?>
        </p>
    </div>
</div>
