<!doctype html>
<html lang="en">
      <head>
            <title>Title</title>
            <!-- Required meta tags -->
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

            <!-- Bootstrap CSS v5.0.2 -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css"  integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

            <!-- FontAwesome 5.15.3 CSS -->
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" integrity="sha512-iBBXm8fW90+nuLcSKlbmrPcLa0OT92xO1BIsZ+ywDWZCvqsWgccV3gFoRBv0z+8dLJgyAHIhR35VZc2oM/gI1w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

            <!-- Custom styles -->
            <link rel="stylesheet" href="assets/css/editor.min.css" crossorigin="anonymous">
            <link rel="stylesheet" href="assets/css/style.css" crossorigin="anonymous">
            <link rel="stylesheet" href="assets/css/responsve.css" crossorigin="anonymous">
      </head>
      <body>
            <!-- Header Area Start-->
            <header id="header" class="d-flex justify-content-between align-items-center">
                  <div class="header-logo">
                        <a href="editor.html">
                              <img src="assets/images/logo white.png" alt="Image Editor">
                        </a>
                  </div>
                  <div class="header-right">
                        <ul class="menu d-flex justify-content-between align-items-center">
                              <li>
                                    <button class="undo">
                                          <img src="assets/images/undo.png" alt="">
                                    </button>
                              </li>
                              <li>
                                    <button class="redo">
                                          <img src="assets/images/redo.png" alt="">
                                    </button>
                              </li>
                              <li>
                                    <button class="download save">Save</button>
                                    <button class="download">Download</button>
                                    <div class="export-option">
                                          <span>File Type</span>
                                          <select name="" id="">
                                                <option value="default" selected>Export as default file type</option>
                                                <option value="png">PNG (High quality image)</option>
                                                <option value="jpeg">JPG (Small file size image)</option>
                                          </select>
                                          <button class="download-img">Download</button>
                                    </div>
                              </li>
                        </ul>
                  </div>
            </header>
            <!-- Header Area End-->

            <!-- Main Area Start -->
            <main id="main">
                  <!-- Navigation start -->
                  <nav id="navigation">
                        <ul class="nav">
                              <li>
                                    <button class="edit-btn active" data-action="Filter">
                                          <label>Filter</label>
                                          <img src="assets/images/filter.png" alt="Filter Image">
                                    </button>
                              </li>
                              <li>
                                    <button class="edit-btn" data-action="RemoveBG">
                                          <label>Remove Background</label>
                                          <img src="assets/images/transparency.png" alt="Filter Image">
                                    </button>
                              </li>
                              <li>
                                    <button class="edit-btn" data-action="Rotate">
                                          <label>Rotate</label>
                                          <img src="assets/images/rotate.png" alt="Filter Image">
                                    </button>
                              </li>
                              <li>
                                    <button class="edit-btn" data-action="Flip">
                                          <label>Flip</label>
                                          <img src="assets/images/flip.png" alt="Filter Image">
                                    </button>
                              </li>
                              <li>
                                    <button class="edit-btn" data-action="Resize">
                                          <label>Resize</label>
                                          <img src="assets/images/resize.png" alt="Filter Image">
                                    </button>
                              </li>
                              <li>
                                    <button class="edit-btn" data-action="Crop">
                                          <label>Crop</label>
                                          <img src="assets/images/crop.png" alt="Crop Image">
                                    </button>
                              </li>
                              <li>
                                    <button class="edit-btn" data-action="Compress">
                                          <label>Compress</label>
                                          <img src="assets/images/folder.png" alt="Filter Image">
                                    </button>
                              </li>
                              <li>
                                    <button class="edit-btn" data-action="Upload">
                                          <label>Upload</label>
                                          <img src="assets/images/upload.png" alt="Filter Image">
                                    </button>
                              </li>
                        </ul>
                        <!-- Sub navigation -->
                        <div class="sub-nav">    
                              <button class="open-sub-nav">
                                    <i class="fa fa-caret-left" aria-hidden="true"></i>
                                    <i class="fa fa-caret-right" aria-hidden="true"></i>
                              </button>
                              <div class="sub-menu"></div>
                        </div>
                  </nav>
                  <!-- Navigation end -->

                  <!-- Canvas Start -->
                  <aside id="aside">
                        <div class="canvas">
                              <!-- main canvas -->
                              <canvas id="main-canvas"></canvas>
                        </div>
                        <!-- upload area -->
                        <div class="upload-area uploads" dir="<?=realpath('./assets/uploads');?>" type="image"></div>
                  </aside>
                  <!-- Canvas End -->
            </main>
            <!-- Main Area End -->

            <!-- Bootstrap JavaScript Libraries -->
            <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
            <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

            <!-- Custom javascript -->
            <script src="assets/js/editor.min.js"></script>
            <script src="assets/js/editor.js"></script>
      </body>
</html>