<?php 
      require_once __DIR__.'/header.php'; 
      require_once __DIR__.'/includes/actions.php'; 

      $post_type   = $_GET['post_type'] ?? 'post';
      $post_status = $_GET['post_status'] ?? 'publish';
      $page        = $_GET['page'] ?? 1;
      $limit       = $_GET['limit'] ?? 10;       
      $offset      = ( $page - 1 ) * ( $limit == 'all' ? 500000 : $limit );
      $orderby     = $_GET['orderby'] ?? 'post_date';
      $order       = $_GET['order'] ?? 'DESC';
      $action      = $_GET['action'] ?? '';
      $search      = $_GET['search'] ?? '';

      // add date filtering option to the first position of predefined filtering array
      $date_filters = [
            'post_date' => [
                  'post_type'      => $post_type,
            ]
      ];
      
      $post_action->filtering = array_merge($date_filters,$post_action->filtering);
      //check if post date set as filter option
      $include = [];
      $options = $post_action->filtering;

      if ( isset ( $options['post_date'] ) ) {
            if ( isset ( $_GET['post_date'] ) && ! empty ( $_GET['post_date'] ) ) {
                  $include['post_month_year'] = $_GET['post_date'];
            }
            unset ( $options['post_date'] );
      }
      // check for other filter options
      $filters = array_intersect_key($_GET,$options);
      $filters = array_filter ( $filters,fn( $item ) => ! empty ($item));

      $default_post_args = [
            'post_type'    => $post_type,
            'post_status'  => $post_status,
            'offset'       => $offset,
            'numberposts'  => $limit == 'all' ? 500000 : $limit,
            'orderby'      => $orderby,
            'order'        => $order,
            'include'      => $include,
            'tax_query'    => $filters,
            'search'       => $search,
      ];

      $posts     = get_posts( $default_post_args );

      //action 
      if ( ! empty( $action ) > 0) {
            $edit_ids = $_GET['post'] ?? [];
            if ( count( $edit_ids ) > 0 ) {
                  if ( function_exists ( "action_$action" ) ) {
                        call_user_func ( "action_$action",$edit_ids );
                  }
            }
      }

      $_TOTAL_POSTS                       = count ( get_posts (
                                                      [
                                                            'post_type' => $post_type,
                                                            'search'    => $search,
                                                            'include'      => $include,
                                                            'tax_query'    => $filters,
                                                            'search'       => $search,
                                                      ]
                                                )); //total posts
      $pages                              = ceil( $_TOTAL_POSTS / $limit ); //total pages

      // actions 
      if ( isset ( $_GET['action'] ) && $_GET['action'] !== '' ) {
            if ( function_exists ($_GET['action']."_action_trigger") ) {
                  call_user_func ( $_GET['action']."_action_trigger" );
            }
      }
      
?>

<div class="top-filter-nav">
      <ul class="filter-nav">
            <li><a href="#" class="active-tab">Library</a></li>
            <li><a href="#">Add New</a></li>
      </ul>
</div>
<form method="GET" id="post-form">
      <!-- Set post type -->
      <input type="hidden" value="<?=$post_type;?>" name="post_type">
      <input type="hidden" value="<?=$page;?>" name="page">
      <!-- filter -->
      <div class="sort-search">
            <div class="left">
                  <div class="group"><?php $post_action->get_show_options($limit); ?></div>
                  <div class="group"><?php $post_action->get_sort_options($post_type,$orderby); ?></div>
                  <div class="group"><?php $post_action->get_order_options($order); ?></div>
            </div>
            <div class="right">
                  <div class="group">
                        <input type="text" id="search" name="search" value = "<?=$search;?>" placeholder="Search <?=$post_type; ?>">
                        <button type="submit" class="sm-btn">Search <?=$post_type; ?></button>
                  </div>
            </div>
      </div>

      <div class="filter-pagi">
            <div class="left">
                  <!-- Action -->
                  <div class="group">
                        <?php
                              if ( count ( $posts ) > 0 ) {
                                    $post_action->get_actions($post_status);
                              }
                        ?>
                  </div>

                  <div class="group">
                        <?php 
                              $post_action->get_filters($post_type,array_intersect_key($_GET,$post_action->filtering));
                        ?>
                  </div>

            </div>
            <div class="right">
                  <?php 
                        $post_action->get_pagination([
                              'post_type' => $post_type,
                              'page'      => $page,
                              'pages'     => $pages,
                              'posts'     => $_TOTAL_POSTS,
                              'limit'     => $limit,
                              'offset'    => $offset,
                        ]); 
                  ?>
                  <!-- total post items -->
                  <span class="itemcount"><?=$_TOTAL_POSTS;?>item</span>
            </div>
      </div>
      <!-- Post status show -->
      <div class="post_status_show">
            <?php 
                  $post_action->get_posts_status_lists($post_type);
            ?>
      </div>
      <!-- items wraper  -->
      <div class="items">

            <?php  foreach ( $posts as $_post ) :extract ( $_post ); ?>
                  <div class="item" title="<?=$post_title;?>">
                        <a href="" class="first-part">
                              <span href="" class="frist-letter"><?=substr( $post_title,0,1 ); ?></span>
                              <div class="extra">
                                    <h4><?=strlen ( $post_title ) > 100 ? substr ( $post_title,0,100 ).'...' : $post_title;?></h4>
                                    <span class="date">
                                          Published.<?=date("d M Y",strtotime ( $post_datetime )); ?>
                                    </span>
                              </div>
                        </a>
                        <div class="last-part">
                              <div class="capabilities">
                                    <ul>
                                          <li title="Select item for action">
                                                <input type="checkbox" name="<?=$post_type;?>[]" value="<?=$ID?>">
                                          </li>
                                          <li title="View">
                                                <a href="<?=get_site_url();?>/admin/post-new.php?<?=$post_type.'='.$ID?>&action=view" >
                                                      <i class="fa fa-eye" aria-hidden="true"></i>
                                                </a>
                                          </li>
                                          <li title="Trash">
                                                <a href="<?=get_site_url();?>/admin/post-new.php?<?=$post_type.'='.$ID?>&action=trash">
                                                      <i class="fa fa-trash" aria-hidden="true"></i>
                                                </a>
                                          </li>
                                          <li title="Delete">
                                                <a href="<?=get_site_url();?>/admin/post-new.php?<?=$post_type.'='.$ID?>&action=delete">
                                                      <i class="fas fa-broom"></i>
                                                </a>
                                          </li>
                                          <li  title="Edit">
                                                <a href="<?=get_site_url();?>/admin/post-new.php?<?=$post_type.'='.$ID?>&action=edit">
                                                      <i class="fa fa-edit" aria-hidden="true"></i>
                                                </a>
                                          </li>
                                    </ul>
                                    <a href="<?=get_site_url();?>/admin/profile.php?user=<?=$post_author;?>">
                                          <i class="fa fa-user" aria-hidden="true"></i>
                                    </a>
                              </div>
                              <div class="comment-view">
                                    <ul>
                                          <li title="Comment Count">
                                                <?=$comment_count;?> <i class="fa fa-comment" aria-hidden="true"></i>
                                          </li>
                                          <li title="View Count">
                                                <?=get_views_count($ID);?> <i class="fa fa-bar-chart" aria-hidden="true"></i>
                                          </li>
                                    </ul>
                              </div>
                        </div>
                  </div>
            <?php endforeach; ?>

      </div>
</form>
<?php  require_once __DIR__.'/footer.php'; ?>