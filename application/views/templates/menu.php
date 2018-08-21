<ul class="nav justify-content-center">
     
    <?php foreach($menu_items as $mi): ?>
        <li class="nav-item">
            <a class="nav-link " href="<?php echo site_url($mi['page_path']) ?>"><?php echo ucfirst($mi['page_name']) ?></a>
        </li>
    <?php endforeach; ?>
        
</ul>