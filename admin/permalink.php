<?php 

      require_once __DIR__.'/header.php';
      $error          = '';
      $available_tags = [
            'post_name'     => 'post-name',
            'post_author'   => 'post-author',
            'ID'            => 'post-id',
            'post_year'     => 'post-year',
            'post_month'    => 'post-month',
            'post_date'     => 'post-date',
            'post_minute'   => 'post-minute',
            'post_second'   => 'post-second',
            'post_day'      => 'post-day',
            'post_tag'      => 'post-tag',
            'post_category' => 'post-category',
      ];

      if ( isset ( $_REQUEST['update-permalink'] ) ) {
            extract ( $_POST );

            try {
                  if ( $permalink == 'custom') {
                        if ( ! empty ( $custom_struct ) ) {
                              $permalink_custom_struct = serialize ( array_filter ( explode('/',$custom_struct),fn($item) => ! empty($item) ));

                              if ( has_option( 'permalink_custom_struct' ) ) {
                                    update_option ( 'permalink_custom_struct',$permalink_custom_struct );
                              } else {
                                    add_option ( 'permalink_custom_struct',$permalink_custom_struct );
                              }
                        } else {
                              throw new Exception('Permalink structure is empty'); 
                        }
                  } else {
                        if ( has_option('permalink_custom_struct')) {
                              $id = get_option ( 'permalink_custom_struct','option_id' );
                              delete_option('permalink_custom_struct', (int) $id);
                        }
                  }

                  update_option('permalink_type',$permalink);
                  register_flash_message('Permalink Updated');
                  
            } catch (Exception $e) {
                  register_flash_message($e->getMessage(),'error');
            }
      }
      $expt             = get_option('permalink_type'); //exsited permalink type
      $ex_custom_struct = '';
      $tags             = '';

      if ( has_option ( 'permalink_custom_struct' ) ) {
            $ex_custom_struct = unserialize ( get_option('permalink_custom_struct') );
            
            foreach ($available_tags as $key => $value) 
            {
                  $tags .= sprintf('<li>
                              <button type="button" class="tag %s" data-value="%s">%s</button>
                        </li>'
                  ,in_array ($key,$ex_custom_struct)? 'active' : '',$key,$value);
            }

            $ex_custom_struct = implode ('/',$ex_custom_struct).'/';

      } else {

            foreach ($available_tags as $key => $value) {
                  $tags .= sprintf('<li>
                              <button type="button" class="tag" data-value="%s">%s</button>
                        </li>'
                  ,$key,$value);
            }
      }
      get_flash_message();
?>    

<h3 class="section-title mb-2">Permalink Setting</h3>

<form method="POST" class="permalink">
      <h5 class="mb-2 section-subtitle">Common Setting</h5>
      <!-- permalink example -->
      <?php 

            $examples = [
                  'Plain'            => [
                        'label'      => 'plain',
                        'ex'         => 'http://localhost/cms/?post-type=123',
                  ],
                  'Day and name'     => [
                        'label'      => 'day-name',
                        'ex'         => 'http://localhost/cms/2022/03/09/sample-post/',
                  ],
                  'Month And Name'   => [
                        'label'      => 'month-name',
                        'ex'         => 'http://localhost/cms/2022/03/sample-post/',
                  ],
                  'Numeric'          => [
                        'label'      => 'numeric',
                        'ex'         => 'http://localhost/cms/post-type/123',
                  ],
                  'Post Name'        => [
                        'label'      => 'post-name',
                        'ex'         => 'http://localhost/cms/sample-post/',
                  ],
                  'Custom Structure' => [
                        'label'      => 'custom',
                        'ex'         => 'http://localhost/cms/',
                  ],
            ]
      
      ?>
      <table>
            <?php 
                  foreach ($examples as $key => $tr) {
                        extract ( $tr );

                        $textarea       = '';
                        $select_tag     = '';

                        if ( $label == 'custom' ) {
                              // text area for custom permalink structure
                              $textarea  = sprintf ('
                                    <textarea name="custom_struct" id="custom_struct" cols="1" rows="1">%s</textarea>
                              ',$ex_custom_struct);

                              // example tags to chose tag
                              $select_tag = sprintf ('
                                    <br/>
                                    <span>Available tags:</span>
                                    <br/>
                                    <ul class="tags">%s</ul>
                              ',$tags);
                        }

                        //if checkbox should be checked
                        $checked = ($expt === $label) ? 'checked' : '';

                        printf (
                              '<tr>
                                    <td>
                                          <label for="%s">
                                                <input type="radio" %s name="permalink" id="%s" value="%s"> %s
                                          </label>
                                    </td>
                                    <td>
                                          <span class="ex">%s%s</span>
                                          %s
                                    </td>
                              </tr>',
                              $label,
                              $checked,
                              $label,
                              $label,
                              $key,
                              $ex,
                              $textarea,
                              $select_tag
                        );
                  }
            ?>
      </table>
      <!-- optional setting -->
      <h5 class="mb-2 section-subtitle">Optional Setting</h5>
      <table>
            <?php 
                  $optionals = [
                        'Category Base' => [
                              'label' => 'category-base',
                              'type'  => 'text',
                        ],
                        'Tag Base' => [
                              'label' => 'tag-base',
                              'type'  => 'text',
                        ]
                  ];
                  foreach ($optionals as $key => $tr) {

                        extract ( $tr );

                        printf (
                              '<tr>
                                    <td>
                                          <label for="%s">%s</label>
                                    </td>
                                    <td>
                                          <input type="%s" name="%s" id="%s">
                                    </td>
                              </tr>',
                              $label,
                              $key,
                              $type,
                              $label,
                              $label
                        );
                  }
            ?>
      </table>
      <div class="submit-button">
            <button type="submit" class="btn btn-primary btn-sm" name="update-permalink">Update</button>
      </div>
</form>
<?php require_once __DIR__.'/footer.php' ?>;

