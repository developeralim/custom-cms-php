<?php
      require_once __DIR__.'/header.php';
?>

      <div class="top-filter-nav">
            <ul class="filter-nav">
                  <li><a href="#" class="active-tab">Library</a></li>
                  <li><a href="#">Add New</a></li>
            </ul>
      </div>
      <div class="uploads" id="media-upload" multiple="multiple" dir="<?=realpath('./media'); ?>" type="image"></div>
                       
<?php
      require_once __DIR__.'/footer.php';
?>