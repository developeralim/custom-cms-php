<?php require_once __DIR__.'/header.php'; ?>
<div class="top-filter-nav">
      <ul class="filter-nav">
            <li><a href="#" class="active-tab">Library</a></li>
            <li><a href="#">Add New</a></li>
      </ul>
</div>
<form action="">
      <!-- filter -->
      <div class="sort-search">
            <div class="left">
                  <div class="group">
                        <label for="show">Show :</label>
                        <select name="" id="show">
                              <option value="">10</option>
                              <option value="">20</option>
                              <option value="">30</option>
                              <option value="">100</option>
                              <option value="">All</option>
                        </select>
                  </div>
                  <div class="group">
                        <label for="sortby">Sort-by :</label>
                        <select name="" id="sortby">
                              <option value="">Sort By</option>
                              <option value="">Name</option>
                              <option value="">Slug</option>
                              <option value="">Date</option>
                        </select>
                  </div>
                  <div class="group">
                        <label for="sortorder">Sort-order :</label>
                        <select name="sortorder" id="">
                              <option value="">Sort Order</option>
                              <option value="">DESC</option>
                              <option value="">ASC</option>
                        </select>
                  </div>
            </div>
            <div class="right">
                  <div class="group">
                        <label for="search">Search : </label>
                        <input type="text" id="search" placeholder="Type to search...">
                  </div>
            </div>
      </div>
      <div class="filter-pagi">
            <div class="left">
                  <div class="group">
                        <select name="" id="">
                              <option value="">Bulk Action</option>
                              <option value="">Option two</option>
                              <option value="">Option three</option>
                        </select>
                        <button type="submit" class="sm-btn">Action</button>
                  </div>
                  <div class="group">
                        <select name="" id="">
                              <option value="">Option one</option>
                              <option value="">Option two</option>
                              <option value="">Option three</option>
                        </select>
                        <select name="" id="">
                              <option value="">Option four</option>
                              <option value="">Option five</option>
                              <option value="">Option six</option>
                        </select>
                        <button type="submit" class="sm-btn">Filter</button>
                  </div>
            </div>
            <div class="right">
                  <ul class="pagi">
                        <li>
                              <a href="">
                                    <i class="fa fa-angle-double-left" aria-hidden="true"></i>
                              </a>
                        </li>
                        <li>
                              <a href="">
                                    <i class="fa fa-angle-left" aria-hidden="true"></i>
                              </a>
                        </li>
                        <li>
                              <a href="">5</a>
                        </li>
                        <li>
                              <a href="">
                                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                              </a>
                        </li>
                        <li>
                              <a href="">
                                    <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                              </a>
                        </li>
                  </ul>
                  <span class="itemcount">10 item</span>
            </div>
      </div>
      <!-- items wraper  -->
      <div class="items">
            <div class="item">
                  <a href="" class="first-part">
                        <span href="" class="frist-letter">H</span>
                        <div class="extra">
                              <h4>Hello world</h4>
                              <span class="date">
                                    Published.20 Jan 2022
                              </span>
                        </div>
                  </a>
                  <div class="last-part">
                        <div class="capabilities">
                              <ul>
                                    <li title="Select item for action">
                                          <input type="checkbox" name="" id="">
                                    </li>
                                    <li title="Select"><a href="">
                                                <i class="fas fa-share"></i>
                                          </a></li>
                                    <li title="View"><a href="">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                          </a></li>
                                    <li title="Trash"><a href="">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                          </a></li>
                                    <li title="Delete"><a href="">
                                                <i class="fas fa-broom"></i>
                                          </a></li>
                                    <li title="Edit"><a href="">
                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                          </a></li>
                              </ul>
                              <a href="">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                              </a>
                        </div>
                        <div class="comment-view">
                              <ul>
                                    <li title="Comment Count">
                                          1 <i class="fa fa-comment" aria-hidden="true"></i>
                                    </li>
                                    <li title="View Count">
                                          10 <i class="fa fa-bar-chart" aria-hidden="true"></i>
                                    </li>
                              </ul>
                        </div>
                  </div>
            </div>
            <div class="item">
                  <a href="" class="first-part">
                        <span href="" class="frist-letter">H</span>
                        <div class="extra">
                              <h4>Hello world</h4>
                              <span class="date">
                                    Published.20 Jan 2022
                              </span>
                        </div>
                  </a>
                  <div class="last-part">
                        <div class="capabilities">
                              <ul>
                                    <li title="Select item for action">
                                          <input type="checkbox" name="" id="">
                                    </li>
                                    <li title="Select"><a href="">
                                                <i class="fas fa-share"></i>
                                          </a></li>
                                    <li title="View"><a href="">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                          </a></li>
                                    <li title="Trash"><a href="">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                          </a></li>
                                    <li title="Delete"><a href="">
                                                <i class="fas fa-broom"></i>
                                          </a></li>
                                    <li title="Edit"><a href="">
                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                          </a></li>
                              </ul>
                              <a href="">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                              </a>
                        </div>
                        <div class="comment-view">
                              <ul>
                                    <li title="Comment Count">
                                          1 <i class="fa fa-comment" aria-hidden="true"></i>
                                    </li>
                                    <li title="View Count">
                                          10 <i class="fa fa-bar-chart" aria-hidden="true"></i>
                                    </li>
                              </ul>
                        </div>
                  </div>
            </div>
            <div class="item">
                  <a href="" class="first-part">
                        <span href="" class="frist-letter">H</span>
                        <div class="extra">
                              <h4>Hello world</h4>
                              <span class="date">
                                    Published.20 Jan 2022
                              </span>
                        </div>
                  </a>
                  <div class="last-part">
                        <div class="capabilities">
                              <ul>
                                    <li title="Select item for action">
                                          <input type="checkbox" name="" id="">
                                    </li>
                                    <li title="Select"><a href="">
                                                <i class="fas fa-share"></i>
                                          </a></li>
                                    <li title="View"><a href="">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                          </a></li>
                                    <li title="Trash"><a href="">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                          </a></li>
                                    <li title="Delete"><a href="">
                                                <i class="fas fa-broom"></i>
                                          </a></li>
                                    <li title="Edit"><a href="">
                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                          </a></li>
                              </ul>
                              <a href="">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                              </a>
                        </div>
                        <div class="comment-view">
                              <ul>
                                    <li title="Comment Count">
                                          1 <i class="fa fa-comment" aria-hidden="true"></i>
                                    </li>
                                    <li title="View Count">
                                          10 <i class="fa fa-bar-chart" aria-hidden="true"></i>
                                    </li>
                              </ul>
                        </div>
                  </div>
            </div>
            <div class="item">
                  <a href="" class="first-part">
                        <span href="" class="frist-letter">H</span>
                        <div class="extra">
                              <h4>Hello world</h4>
                              <span class="date">
                                    Published.20 Jan 2022
                              </span>
                        </div>
                  </a>
                  <div class="last-part">
                        <div class="capabilities">
                              <ul>
                                    <li title="Select item for action">
                                          <input type="checkbox" name="" id="">
                                    </li>
                                    <li title="Select"><a href="">
                                                <i class="fas fa-share"></i>
                                          </a></li>
                                    <li title="View"><a href="">
                                                <i class="fa fa-eye" aria-hidden="true"></i>
                                          </a></li>
                                    <li title="Trash"><a href="">
                                                <i class="fa fa-trash" aria-hidden="true"></i>
                                          </a></li>
                                    <li title="Delete"><a href="">
                                                <i class="fas fa-broom"></i>
                                          </a></li>
                                    <li title="Edit"><a href="">
                                                <i class="fa fa-edit" aria-hidden="true"></i>
                                          </a></li>
                              </ul>
                              <a href="">
                                    <i class="fa fa-user" aria-hidden="true"></i>
                              </a>
                        </div>
                        <div class="comment-view">
                              <ul>
                                    <li title="Comment Count">
                                          1 <i class="fa fa-comment" aria-hidden="true"></i>
                                    </li>
                                    <li title="View Count">
                                          10 <i class="fa fa-bar-chart" aria-hidden="true"></i>
                                    </li>
                              </ul>
                        </div>
                  </div>
            </div>
      </div>
      <!-- filter -->
      <div class="filter-pagi">
            <div class="left">
                  <div class="group">
                        <select name="" id="">
                              <option value="">Bulk Action</option>
                              <option value="">Option two</option>
                              <option value="">Option three</option>
                        </select>
                        <button type="submit" class="sm-btn">Action</button>
                  </div>
                  <div class="group">
                        <select name="" id="">
                              <option value="">Option one</option>
                              <option value="">Option two</option>
                              <option value="">Option three</option>
                        </select>
                        <select name="" id="">
                              <option value="">Option four</option>
                              <option value="">Option five</option>
                              <option value="">Option six</option>
                        </select>
                        <button type="submit" class="sm-btn">Filter</button>
                  </div>
            </div>
            <div class="right">
                  <ul class="pagi">
                        <li>
                              <a href="">
                                    <i class="fa fa-angle-double-left" aria-hidden="true"></i>
                              </a>
                        </li>
                        <li>
                              <a href="">
                                    <i class="fa fa-angle-left" aria-hidden="true"></i>
                              </a>
                        </li>
                        <li>
                              <a href="">5</a>
                        </li>
                        <li>
                              <a href="">
                                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                              </a>
                        </li>
                        <li>
                              <a href="">
                                    <i class="fa fa-angle-double-right" aria-hidden="true"></i>
                              </a>
                        </li>
                  </ul>
                  <span class="itemcount">10 item</span>
            </div>
      </div>
</form>
<?php  require_once __DIR__.'/footer.php'; ?>