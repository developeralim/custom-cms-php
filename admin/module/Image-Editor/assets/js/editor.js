/* =========================== sub nav open and close ============================== */
const subnav          = $('.sub-nav');
const subnavopen      = $('.open-sub-nav');
const nav             = $('.nav');
const editBtns        = $('.edit-btn'); 
const predefinedColor = ['#FFFFFF00','green','yellow','purple'];
const download        = $('.download');
const downloadImg     = $('.export-option button');
const predefinedImage = [
      {
            'name' : 'test',
            'url' : 'https://images.unsplash.com/photo-1553095066-5014bc7b7f2d?ixlib=rb-1.2.1&ixid=MnwxMjA3fDB8MHxzZWFyY2h8MXx8d2FsbCUyMGJhY2tncm91bmR8ZW58MHx8MHx8&w=1000&q=80',
      },
      {
            'name' : 'test1',
            'url' : 'https://png.pngtree.com/thumb_back/fh260/background/20200714/pngtree-modern-double-color-futuristic-neon-background-image_351866.jpg',
      },
      {
            'name' : 'test2',
            'url' : 'https://images.pexels.com/photos/586744/pexels-photo-586744.jpeg?auto=compress&cs=tinysrgb&dpr=1&w=500',
      },

];
var submenu           = $('.sub-menu');
var img               = document.createElement('img');
var editorProperties  = {
      loadImageWidth          : 0,
      loadImageHeight         :0,
      loadImageURL            : getURLParams('url'),
      loadImage               : null,
      canvas                  : $('#main-canvas'),
      canvasParaent           : $('.canvas'),
      canvasRect              : null,
      previewCanvasRect       : null,
      ctx                     : null,
      filter                  : {
                                    'blur'        : '0px',
                                    'brightness'  : '100%',
                                    'contrast'    : '100%',
                                    'grayscale'   : '0',
                                    'hue-rotate'  : '0deg',
                                    'invert'      : '0%',
                                    'opacity'     : '100',
                                    'saturate'    : '100%',
                                    'sepia'       : '0',
                                    'drop-shadow' : '0px 0px 10px #ffffff',
                              },
      shadow                  : {
                                    horizontal : '0',
                                    vertical   : '0',
                                    spread     : '10',
                                    color      : '#ffffff',
                              },
      flip                    : 1,
      flipDirection           : 'horizontal',
      rotate                  : 0,
      currentCanvasBlob       : null,
      currentCanvasBlobURL    : null,
      currentCanvasSize       : 0,
      removeBackground        : getLocalStorage('loadImageExtension') === 'png' ? true : false,
      setBackgroundType       : 'color',
      background              : '#FFFFFF00',
      compressImageUrl        : null,
      loadImageExtension      : getLocalStorage('loadImageExtension'),
      loadImageName           : getLocalStorage('loadImageName'),
      loadImageType           : getLocalStorage('loadImageType'),
      pngimageoptimized       : false,
      drawErrorMessage        : null,
      drawSuccessMessage      : null,
      defaultImageURL         : getURLParams('url'),
}


/* =========================================================== Window events ================================ */
window.addEventListener('resize', makeSubNavHide);
window.onload = async () => {
      makeSubNavHide();
      open_upload_tab_on_window_load();

      try {
            editorProperties = await setImageURL();
            editorProperties = await drawImageToCanvas();
      } catch (error) {
            new SetError(error.message);
      }
}
/* =========================================================== navbar and tab functionality ================================ */
//////For sub navbar open add close//////
subnavopen.onclick = () => nav.classList.toggle('sub-nav-open');
//////For open navar tab ////////
editBtns.forEach(btn => btn.listener('click',open_tab));

function open_tab(){
      let e,tab;
      /* set value */
      e = window.event;
      tab = e.target.dataset.action;
      /* change url state */
      change_URL_param({tab});

      /* change active class to current tab add active class to current tab */
      editBtns.forEach(b => b.classList.remove('active'));
      e.target.classList.add('active');

      if ( tab == 'Upload' )
      {
            $( '.upload-area' ).css( { display : 'block' } );
            $( '.canvas' ).css( { display      : 'none' } );
            $( '.sub-nav' ).css( { display     : 'none' } );
      }
      else{
            $( '.upload-area' ).css( { display : 'none' } );
            $( '.canvas' ).css( { display      : 'block' } );
            $( '.sub-nav' ).css( { display     : 'block' });
      }
      /* do all actioins with this hook */
      do_action('open_tab');
      return true;
}
function open_upload_tab_on_window_load ( ) 
{     
      $( `button[data-action="${
            ! getURLParams('url').match(/^(http|https):\/\//) ? 'Upload' : 'Filter'
      }"]` ).click();
      return true;
}

/* =========================================================== Configurations methods ================================ */
function base64tobuffer( base64 ) 
{
      let binaryString,binaryLength,bytesArray,index;
      /* change base64 string to binary */
      binaryString = window.atob(base64);
      binaryLength = binaryString.length;
      bytesArray   = new Uint8Array(binaryLength);
      index        = 0;

      while (index < binaryLength) {
            bytesArray[index] = binaryString.charCodeAt(index);
            index++;
      }
     
      return bytesArray.buffer;
}

function exportCanvas( type,quality,action='compress' )
{
      let blobURL,blobSize;
      return new Promise(resolve => {
            if(action == 'compress') {
                  type = (type == 'image/png') ? type : 'image/jpeg';
            }
            /* export canvas loaded image as blob url */
            editorProperties.canvas.toBlob( blob => {
                  blobURL  = URL.createObjectURL(blob);
                  blobSize = parseSize(blob.size);
                  /* resolve after seting blobsize and blob url */
                  resolve({blobURL,blobSize,blob});
            },type,quality);
      });
}

function change_URL_param ( param = {} )
{
      var url = new URL(window.location);
      for (const key in param) {
            if (Object.hasOwnProperty.call(param, key)) {
                  url.searchParams.set(key,param[key]);
            }
      }
      window.history.pushState({},'',url);
      return true;

}

function checkForAspectRatio( t,c )
{
      let wInput,hInput,lw,lh,value;

      lw     = editorProperties.loadImageWidth // load image width
      lh     = editorProperties.loadImageHeight // load image height
      wInput = $('input[data-input="width"]',t.parentElement)  // width input
      hInput = $('input[data-input="height"]',t.parentElement); // height input
      value  = t.value;

      /// if aspect ratio checkbox is not checked the return ///
      if( c.checked !== true ) return;
      if( value === '' ) {
            t.parentElement.parentElement.reset();
            return;
      };
      
      if( t.dataset.input === 'width' ){
            hInput.value  = Math.round( ( wInput.value * lh ) / lw );
      }else{
            wInput.value = Math.round( ( hInput.value * lw ) / lh );
      }

      ////// do all actions with this hook ////////
      do_action('resizeImage');

      return true;
}

/* =========================================================== getter and setter ================================ */
function getURLParams(p)
{
      var urlparams = new URLSearchParams(window.location.search);
      if( urlparams.has(p) ){
            return urlparams.get(p);
      }else{
            return false;
      }
}
function getLocalStorage(i)
{
      return localStorage.getItem(i);
}
function removeLocalStorage(i)
{
      return localStorage.removeItem(item);
}
function setLocalStorage(k,i)
{
      return localStorage.setItem(k,i);
}
function makeSubNavHide( )
{
      if( window.innerWidth <= 992 ){
            nav.classList.add('sub-nav-open');
      }else{
            nav.classList.remove('sub-nav-open');
      }
}
function setImageURL( )
{
      return new Promise((resolve,reject) => {
            img.src = editorProperties.loadImageURL;
            img.crossOrigin = 'anonymous';
            img.onload = () => {
                  editorProperties.loadImage = img;
                  /// Parse image to get image name and extension ///
                  let parseURL = editorProperties.defaultImageURL.split('/').pop().split('.');
                  
                  setLocalStorage('loadImageName',parseURL[0]);
                  setLocalStorage('loadImageType',`image/${parseURL[1] == 'jpg' ? 'jpeg' : parseURL[1]}`);
                  setLocalStorage('loadImageExtension',parseURL[1]);

                  resolve(editorProperties);
            }
            img.onerror = () => {
                  reject({ status: 404, imageURL: "Invalid", message: "Failed To Load Image From Given URL" });
            }
      });
}
function set_height_width_for_user_view( editorProperties )
{

      return new Promise( resolve => 
            {
                  $('.title .width').innerText  = editorProperties.loadImageWidth;
                  $('.title .height').innerText = editorProperties.loadImageHeight;

                  ////// do all actions with this hook ////////
                  do_action( 'setFilter' );
                  resolve( editorProperties );
            }      
      );

}
/* ======================================================== Render HTML as clicking over tab =================================== */
function render_edit_options_HTML()
{
      return new Promise((resolve,reject) => {
            /* check if the getting image url from URL is an image file or not */
            var image         = new Image();
            image.crossOrigin = 'anonymous';
            /* if image url loads correctly */
            image.onload = () => {
                  switch(getURLParams('tab')){
                        case 'Filter' :
                              renderFilterHTML();
                              break;
                        case 'Rotate' :
                              renderRotateHTML();
                              break;
                        case 'RemoveBG' :
                              renderRemoveBGHTML();
                              break;
                        case 'Flip' :
                              renderFlipHTML();
                              break;
                        case 'Resize' :
                              renderResizeHTML();
                              break;
                        case 'Crop' :
                              renderCropHTML();
                              break;
                        case 'Compress' :
                              renderCompressHTML();
                              break;
                  }  
                  //// Do resolve on successfully loaded image
                  resolve({
                        status   : 200,
                        imageURL : 'Valid',
                        message  : 'Successfylly Loaded Image From Given URL', 
                  });
            };
            image.onerror = () => new SetError('Invalid URL');
            /* set image URL */
            image.src = getURLParams('url');
      })
}
add_action('open_tab','render_edit_options_HTML',4);

/////////////////////////////////////////Render Image filtering HTML /////////////////////////////////////////////
function renderFilterHTML()
{      
      let f,s;
      f = editorProperties.filter;
      s = editorProperties.shadow;

      submenu.innerHTML = `
            <div class="filter-item">
                  <label for="grayscale">
                        Grayscale-<em>${parseInt(f.grayscale)}</em>%
                        <span>(apply gray effect on you image)</span>
                  </label>
                  <input type="range" min="0" max="100" value="${parseInt(f.grayscale)}" data-func="grayscale">
            </div>
            <div class="filter-item">
                  <label for="blur">
                        Blur effect-<em>${parseInt(f.blur)}</em>px
                        <span>(apply blur effect on you image)</span>
                  </label>
                  <input type="range" min="0" max="100" value="${parseInt(f.blur)}" data-func="blur">
            </div>
            <div class="filter-item">
                  <label for="contrast">
                        Contrast effect-<em>${parseInt(f.contrast)}</em>%
                        <span>(apply contrast effect on you image)</span>
                  </label>
                  <input type="range" min="0" max="200" value="${parseInt(f.contrast)}" data-func="contrast">
            </div>
            <div class="filter-item">
                  <label for="brightness">
                        Brightness effect-<em>${parseInt(f.brightness)}</em>%
                        <span>(apply brightness effect on you image)</span>
                  </label>
                  <input type="range" min="0" max="200" value="${parseInt(f.brightness)}" data-func="brightness">
            </div>
            <div class="filter-item">
                  <label for="opacity">
                        Opacity effect-<em>${parseInt(f.opacity)}</em>%
                        <span>(apply opacity effect on you image)</span>
                  </label>
                  <input type="range" min="0" max="100" value="${parseInt(f.opacity)}" data-func="opacity">
            </div>
            <div class="filter-item">
                  <label for="sepia">
                        Sepia effect-<em>${parseInt(f.sepia)}</em>%
                        <span>(a reddish-brown color associated particularly with monochrome photographs of the 19th and early 20th centuries)</span>
                  </label>
                  <input type="range" min="0" max="100" value="${parseInt(f.sepia)}" data-func="sepia">
            </div>
            <div class="filter-item">
                  <label for="invert">
                        Invert effect-<em>${parseInt(f.invert)}</em>%
                        <span>(apply negative effect on your image)</span>
                  </label>
                  <input type="range" min="0" max="100" value="${parseInt(f.invert)}" data-func="invert">
            </div>
            <div class="filter-item">
                  <label for="hue-rotate">
                        Hue Rotate effect-<em>${parseInt(f["hue-rotate"])}</em>deg
                        <span>(apply hue rotate effect on your image)</span>
                  </label>
                  <input type="range" min="0" max="300" value="${parseInt(f["hue-rotate"])}" data-func="hue-rotate">
            </div>
            <div class="filter-item">
                  <label for="saturate">
                        Saturation effect-<em>${parseInt(f.saturate)}</em>%
                        <span>(apply hue saturate effect on your image)</span>
                  </label>
                  <input type="range" min="0" max="100" value="${parseInt(f.saturate)}" data-func="saturate">
            </div>
            <div class="filter-item">
                  <label for="shadow">
                        Shadow effect
                        <span>(make a shadow of your image)</span>
                  </label>
                  <div class="shadow-elements">
                        <input type="number" value="${parseInt(s.horizontal)}" title="Horizontal" placeholder="Horizontal" data-direction="horizontal">
                        <input type="number" value="${parseInt(s.vertical)}" title="Vertical" placeholder="Vertical" data-direction = "vertical">
                        <input type="number" value="${parseInt(s.spread)}" title="Spread" placeholder="Spread" data-direction = "spread">
                        <input type="color"  value="${s.color}" title="Color" data-direction = "color">
                        <input type="text" value="${s.color}" title="Custom Color" data-direction = "color" placeholder="Custom color e.g #fffff">
                  </div>
            </div>
      `;
      ///// Set Filter Event /////
      $('.sub-menu input[type="range"]',submenu).listener('input',setFilter);
      ///// Set Shadow Event /////
      $('.shadow-elements input',submenu).listener('input',setShadow);

}

/////////////////////////////////////////Render Image Rotating HTML /////////////////////////////////////////////
function renderRotateHTML()
{
      submenu.innerHTML = `
            <h3 class="title">Rotate</h3>
            <div class="rotate-btns">
                  <button class="rotate-left rotate-btn" data-rotate="left">
                        <img src="assets/images/rotating-arrow-to-the-left.png" alt="">
                  </button>
                  <button class="rotate-right rotate-btn" data-rotate="right">
                        <img src="assets/images/rotating-arrow-to-the-right.png" alt="">
                  </button>
            </div>
            <div class="rotate-input d-flex mt-3">
                  <input type="range" id="rotate-bar" value="${editorProperties.rotate}" max="360" min="0" step="90" data-rotate="input">
                  <input type="text" id="rotate-degree" value="${editorProperties.rotate} Deg" readonly>
            </div>
      `;
      /// Add rotate action listener ///
      $('.rotate-btn',submenu).listener('click',setRotation);
      $('#rotate-bar',submenu).listener('input',setRotation);
}

/////////////////////////////////////////Render Image Flipping HTML /////////////////////////////////////////////
function renderFlipHTML()
{
      submenu.innerHTML = `
            <h3 class="title">Flip</h3>
            <div class="rotate-btns d-flex align-items-center">
                  <button class="rotate-left rotate-btn flip-btn" data-flipDirection="horizontal">
                        <img src="assets/images/horizontal.png" alt="">
                  </button>
                  <button class="rotate-right rotate-btn flip-btn" data-flipDirection="vertical">
                        <img src="assets/images/vertical.png" alt="">
                  </button>
                  <input type="text" id="flip-hint" class="w-100 mt-2 text-center" value="${editorProperties.flipDirection.toUpperCase()}-${editorProperties.flip == -1 ? 'INVERT' : 'REVERT'}" readonly/>
            </div>
      `;
      /// Set flip action event ///
      $('.flip-btn',submenu).listener('click',setFlip);
}

/////////////////////////////////////////Render Image Resizing HTML /////////////////////////////////////////////
function renderResizeHTML( )
{
      submenu.innerHTML = `
            <h3 class="title align-left mb-2">Orginal Image Dimension <span class="width">${editorProperties.loadImageWidth}px</span> By <span class="height">${editorProperties.loadImageHeight}px</span></h3>
            <hr>
            <h5 class="sub-title align-left mb-2">Resize Dimesion</h5>
            <form id="resize-image">
                  <div class="scale-input mt-2 d-flex justify-content-between">
                        <input type="text" id="resize-width" class="w-45" placeholder="Width" required="" data-input="width">
                        <span>x</span>
                        <input type="text" id="resize-height" class="w-45" placeholder="Height" required="" data-input="height">
                  </div>
                  <label for="apr" class="mt-2">
                        <input type="checkbox" id="apr"> Keep Aspect Ratio
                  </label>
                  <button type="submit" class="btn btn-primary btn-sm mt-3 w-100" id="resize-btn">Scale or resize</button>
            </form>
      `;

      /// Set Resize event action ///
      $('#resize-image').listener('submit',e => resize_image('resize'));
      $('.scale-input input').listener('input',e => checkForAspectRatio(e.target,$('#apr',submenu)));
}

/////////////////////////////////////////Render Image Resizing HTML /////////////////////////////////////////////
function renderCropHTML( )
{

      submenu.innerHTML = `
            <h3 class="title align-left mb-2">Orginal Image Dimension <span class="width">${editorProperties.loadImageWidth}px</span> By <span class="height">${editorProperties.loadImageHeight}px</span></h3>
            <hr>
            <h5 class="sub-title align-left mb-2">Crop Dimesion</h5>
            <form id="resize-image">
                  <div class="scale-input mt-2 d-flex justify-content-between">
                        <input type="text" id="from-left" class="w-45" placeholder="Left" required="" data-input="left">
                        <span>x</span>
                        <input type="text" id="from-top" class="w-45" placeholder="Top" required="" data-input="right">
                  </div>
                  <div class="scale-input mt-2 d-flex justify-content-between">
                        <input type="text" id="resize-width" class="w-45" placeholder="Width" required="" data-input="width">
                        <span>x</span>
                        <input type="text" id="resize-height" class="w-45" placeholder="Height" required="" data-input="height">
                  </div>
                  <label for="acpr" class="mt-2">
                        <input type="checkbox" id="acpr"> Keep Aspect Ratio
                  </label>
                  <button type="submit" class="btn btn-primary btn-sm mt-3 w-100" id="resize-btn">Crop</button>
            </form>
      `;

      $('#resize-image').listener('submit',e => resize_image('crop'));
      $('.scale-input input').listener('input',e => checkForAspectRatio(e.target,$('#acpr',submenu)));
}

/////////////////////////////////////////Render Image Remove Background HTML /////////////////////////////////////
function renderRemoveBGHTML()
{
      let bg_remove_status = editorProperties.removeBackground;

      submenu.innerHTML = `
            <button class="btn btn-primary btn-sm w-100" id="rm-bg-btn" ${bg_remove_status == true ? 'disabled' : ''}>
                  ${bg_remove_status == true ? 'Background Removed' : 'Click to remove background'}
            </button>
      `;
      if(! bg_remove_status) {
            $('#rm-bg-btn',submenu).listener('click',alertForBackground);
      }
}
function alertForBackground( ) {
      const bgSet = document.createElement('div');
      bgSet.classList.add('bg-select');
      document.body.appendChild(bgSet);

      bgSet.innerHTML = `
            <div class="bg-set">
                  <p class="hint-c">Do you want to set background image or color after removing your image background ?</p>
                  <div class="bg-set mb-2">
                        ${renderBGSetHTML()}
                  </div>
                  <div class="btns">
                        <button class="no s-btn" onclick="closeBackgroundAlert(this)">No</button>
                        <button class="okey s-btn" onclick="removeBG()">Ok</button>
                  </div>
            </div>
      `;
}
function closeBackgroundAlert( btn ) {
      document.body.removeChild(btn.parentElement.parentElement.parentElement);
}
/////////////////////////////////////////Render Background set HTML //////////////////////////////////////////////
function renderBGSetHTML(){

      var bgsethtml = (`
            <!-- Tab Buttons -->
            <div class="tab-btns">
                  <button class="tab-btn active-tab-btn" onclick="bg_select_tab(this)" data-id="color">Color</button>
                  <button class="tab-btn" data-id="image" onclick="bg_select_tab(this)">Image</button>
            </div>

            <!-- Tab Items -->
            <div class="tab-items">
                  <div class="tab-item active-tab-item" id="color">
                        <ul class="defined-color">
                              <li>
                                    <input type="color" onchange="setBackground(this)" data-bg-type="color" />
                              </li>
                              ${createDefinedColorOption()}
                        </ul>
                  </div>
                  <div class="tab-item" id="image">
                        <ul class="defined-color defined-image">
                              <li>
                                    <div class="custom-image">
                                          <img src="./assets/images/cloud-computing.png"/>
                                          <input type="file" onchange="setBackground(this)" data-bg-type="image"/>
                                    </div>
                              </li>
                              ${createDefinedImageOption()}
                        </ul>
                  </div>
            </div>
      `);

      return bgsethtml;
}

////////////////////////////////Create Defined Background Image Options for Setting Up background /////////////////
function createDefinedColorOption(){
      var colorOption = predefinedColor.map((color,index) => {
            let selected = '';
            if(index == 0) selected = 'selected';
            return (`
                  <li style="background:${color};">
                        <button data-bg="${color}" class="${selected}" onclick="setBackground(this)" data-bg-type="color" style="background:url('${editorProperties.removeBackground === true ? editorProperties.loadImageURL : '' }');"></button>
                  </li>
            `);
      }).join('');
      return colorOption;
}

////////////////////////////////Create Defined Background Color Options for Setting Up background /////////////////
function createDefinedImageOption(){
      var imageOption = predefinedImage.map(image => {
            return (`
                  <li style="background:url('${image.url}');">
                        <button data-bg="${image.url}" data-bg-type="image" onclick="setBackground(this)" style="background:url('${editorProperties.removeBackground === true ? editorProperties.loadImageURL : ''}');"></button>
                  </li>
            `);
      }).join('');
      return imageOption;
}

///////////////////////////////////////////Render Compress Image HTML /////////////////////////////////////////////
function renderCompressHTML(){

      let input,button,type,size,url;

      type = editorProperties.loadImageType;
      size = editorProperties.currentCanvasSize;
      url  = editorProperties.compressImageUrl;

      if( type == 'image/png' ){

            input = '';
            button = `<button class="btn btn-sm btn-primary w-100" id="compressImage" type="optimized-png">Optimize</button>`;

      }else if( type == 'image/jpeg' ){

            input = `<input type="range" id="decrease-bar" value="1" max="0.7" min="0" step="0.05">`;
            button = `<button class="btn btn-sm btn-primary w-100" id="compressImage" type="download">Download Optimized Image</button>`;

      }else{

            input = `<input type="number" id="quality" oninput="enableOptimizeing(this)" placeholder="Quality by default 80" max="100" min="0">`;
            button = `<button class="btn btn-sm btn-primary w-100" id="compressImage" type="optimized-other">Optimize</button>`;

      }
      /* get current drawing image size */
      submenu.innerHTML = `
            
            <h3 class="currnet-size">Current Image Size ${size}</h3>
            <div class="decrease-size-bar">
                  <div class="size-preview">
                        <span class="current">${size}</span>
                        <span>~</span>
                        <span class="decrease">${size}</span>
                  </div>
                  ${input}
                  <div class="preview-compressed-image">
                        <img src="${url}" alt="">
                  </div>
                  ${button}
            </div>

      `;
      //// compress image event actins ///
      $('input[id="decrease-bar"]',submenu).listener('input',setCompress);
      $('#compressImage',submenu).listener('click',compressImage);
}

/* ======================================================== actions with canvas element =================================== */
async function drawImageToCanvas()
{
      let e;

      e = editorProperties;

      return new Promise(async resolve => {

            e.ctx             = e.canvas.getContext('2d');    
            e.canvasRect      = e.canvas.getBoundingClientRect();
            e.loadImageWidth  = e.loadImage.width;
            e.loadImageHeight = e.loadImage.height;
            e.canvas.width    = e.loadImageWidth;
            e.canvas.height   = e.loadImageHeight;    

            /// canvas user view dimension css ///
            editorProperties.canvas.css({
                  width  : `${e.loadImageWidth}px`,
                  height : `${e.loadImageHeight}px`,
            });
            /// canvas parent element css ///
            editorProperties.canvasParaent.css({
                  width  : `${e.loadImageWidth}px`,
                  height : `${e.loadImageHeight}px`,
            });
            /// Do all actions with this hook ///
            do_action('drawImageToCanvas');
            /// draw image on editor by img which is created by given image
            editorProperties.ctx.drawImage(editorProperties.loadImage,0,0);

            /// Export current canvas keep this values ///
            let expc = await exportCanvas(e.loadImageType,0.7);

            e.currentCanvasBlobURL = expc.blobURL;
            e.compressImageUrl     = expc.blobURL;
            e.currentCanvasSize    = expc.blobSize;
            e.currentCanvasBlob    = expc.blob;

            resolve(e);
      });
}

///////////////////////////////////////////////////////Image Filteing functionality//////////////////////////////////////////////////
async function setFilter ( )
{
      var e,suffix,func,value,changePreview;

      e                             = window.event;
      func                          = e.target.dataset.func;
      value                         = e.target.value;
      changePreview                 = $('em',e.target.previousElementSibling);
      suffix                        = func == 'blur' ? 'px' : func == 'hue-rotate' ? 'deg' : '%';
      editorProperties.filter[func] = `${e.target.value}${suffix}`;
      changePreview.innerText       = value;

      try {
            ///// save apply changes in editorproperties object /////
            editorProperties              = await setImageURL();
            editorProperties              = await drawImageToCanvas();
      } catch (error) {
            new SetError(error);
      }

      ////// do all actions with this hook ////////
      do_action('setFilter');

      return true;
}

function doFilter ( )
{
      var s,f,filtered = '';

      f                = editorProperties.filter;
      s                = editorProperties.shadow;
      f["drop-shadow"] = `${s.horizontal}px ${s.vertical}px ${s.spread}px ${s.color}`;

      for (const key in f) {
            filtered  += Object.hasOwnProperty.call(f, key) ? `${key}(${f[key]}) ` : '';
      }

      ///// apply filter to canvas when input is given /////
      editorProperties.ctx.filter = filtered;

      ////// do all actions with this hook ////////
      do_action('doFilter');

      return true;
}

add_action('drawImageToCanvas','doFilter',8);


async function setShadow()
{
      var e,s,direction,value;

      e                 = window.event; // Window event
      s                 = editorProperties.shadow; // s stands for the shadow property of editorproperties
      value             = e.target.value; // target value
      direction         = e.target.dataset.direction; // Shadow direction possible value horizontal or vertical
      s[direction]      = value;

      try {
            ///// save apply changes in editorproperties object /////
            editorProperties  = await setImageURL();
            editorProperties  = await drawImageToCanvas();
      } catch (error) {
            new SetError(error);
      }

      ////// do all actions with this hook ////////
      do_action('setShadow');

      return true;

}

//////////////////////////////////////////////////////Image Background Remove functionality///////////////////////////////////////////
function removeBG ( )
{
      var e,blob,btn;

      e    = window.event;
      btn  = $('#rm-bg-btn');

      //// make remove background button disalbed and changed its text////
      btn.innerText = 'Removing BG...';
      btn.setAttribute('disabled',true);

      //close popup alert
      closeBackgroundAlert(e.target);
      //// set preloader when click to remove background color/////
      new SetLoader(
            $('.canvas'),
            false
      );
      //// send ajax request to server for removing background of image ////
      fetch( './functions.php',{
            method : 'POST',
            headers : {
                  'Content-Type': 'application/x-www-form-urlencoded',
            },
            body : `removebg=${true}&image_url=${getURLParams('url')}&backgroundType=${editorProperties.setBackgroundType}&background=${editorProperties.background}`,
            
      })
      .then( data => data.json( ) )
      .then( async data => {
            let Errcss,progressCss,barCss;
            if( data.status == 'success' && data.code == 200){
                  blob = new Blob( [ base64tobuffer( data.image ) ],{ type : 'image/png' } );

                  ////change some properties when background removed successfull////
                  editorProperties.loadImageURL       = URL.createObjectURL( blob );
                  editorProperties.loadImageExtension = data.format;
                  editorProperties.loadImageType      = data.format == 'jpg' ? 'image/jpeg' : 'image/png';
                  editorProperties.removeBackground   = true;

                  try {
                        //// draw image on canvas once again after completing removing background ////
                        editorProperties = await setImageURL( );
                        editorProperties = await drawImageToCanvas( );
                  } catch (error) {
                        new SetError(error);
                  }

                  //// change remove background button text render bg set HTML /////
                  btn.innerText = "Background Removed";
                  $( '.bg-set' ).innerHTML = renderBGSetHTML( );

                  Errcss = {
                        background : '#12ce12',
                  };
                  barCss = {
                        background: '#d20000',
                  }
            }else{
                  //// make remove background button disalbed and changed its text////
                  btn.removeAttribute('disabled');
                  btn.innerText = "Remove Background";
            }
            new SetError(
                  data.message,
                  document.body,
                  Errcss,
                  progressCss,
                  barCss,
            );
            new SetLoader(
                  $('.canvas')
                  ,true
            );
      });
}

async function setBackground(target){

      $('.defined-color li button').forEach(l => l.classList.remove('selected'));
      $('.defined-color li input').forEach(l => l.classList.remove('selected'));
      target.classList.add('selected');

      editorProperties.background = await new Promise( resolve => {
            var bg,bgType,value,file,fileReader,buffer,blob,b64,url,formData;

            bgType     = target.dataset.bgType;
            bg         = target.dataset.bg;
            value      = target.value;

            //// set background type ////
            editorProperties.setBackgroundType = bgType;

            if( typeof bg == 'undefined' ) { // for uploading image as custom background

                  if( target.type == 'color' ) { // if custom color

                        resolve(value);

                  }else { //else for custom image
                        $('.okey').setAttribute('disabled','true');
                        fileReader  = new FileReader();
                        file        = target.files[0];

                        ////load file to file reader api////
                        fileReader.onload = function ( )
                        {
                              b64                        = this.result.split(',')[1];
                              buffer                     = base64tobuffer(b64);
                              blob                       = new Blob([buffer],{type : 'image/png'});
                              url                        = URL.createObjectURL(blob);
                              $('.custom-image img').src = url;
                              formData                   = new FormData(); 

                              formData.append('source',blob);

                              fetch(ajax_endpoint,{
                                    method : 'POST',
                                    body : formData,
                              })
                              .then(res => res.text())
                              .then(data => {
                                    $('.okey').removeAttribute('disabled');
                                    //// make resolve when custom image uploaded successfully/////
                                    resolve(data);
                              });
                              
                        }
                        
                        fileReader.readAsDataURL(file);
                  }

            }else{
                  resolve(bg);
            }
      });

      ////// do all actions with this hook ////////
      do_action('setBackground');

      return true;

}

/* image and color tab */
function bg_select_tab ( t )
{
      //// remove active-tab-btn and active-tab-item classes from all tab item////
      $('.tab-btn').forEach(b => b.classList.remove('active-tab-btn'));
      $(".tab-item").forEach(i => i.classList.remove('active-tab-item'));

      ///// add active-tab-btn and active-tab-item classes to target tab item////
      t.classList.add('active-tab-btn');
      $(`#${t.dataset.id}`).classList.add('active-tab-item');

      ////// do all actions with this hook ////////
      do_action('bg_select_tab');

      return true;
}
///////////////////////////////////////////////////////Image Rotating functionality//////////////////////////////////////////////////
async function setRotation ( )
{
      var r,e,rotate;

      r                 = editorProperties.rotate; //r stands for rotate property of editorproperties
      e                 = window.event; // window event
      rotate            = parseInt(e.target.value)
      rotateDirection   = e.target.dataset.rotate //rotate value
      

      switch (rotateDirection) {
            case 'left':
                  rotate = ( r <= 0 ) ? 360 : ( r - 90 );
            break;
            case 'right':
                  rotate = ( r >= 360 ) ? 90 : ( r + 90 );
            break;
      }

      ///// set how angle will rotate /////
      editorProperties.rotate = rotate;

      ///// set rotate angle for user view in DOM /////
      $('#rotate-bar').value    = editorProperties.rotate;
      $('#rotate-degree').value = editorProperties.rotate + 'Deg';

      try {
            ///// save apply changes in editorproperties object /////
            editorProperties  = await setImageURL();
            editorProperties  = await drawImageToCanvas();
      } catch (error) {
            new SetError(error);
      }

      ////// do all actions with this hook ////////
      do_action('setRotation');

      return true;
}

function applyRotation ( )
{
      let lw,lh,cw,ch,tw,th,r,css;

      lw = editorProperties.loadImageWidth; //load image width 
      lh = editorProperties.loadImageHeight; // load image height
      tl = 0; // translate from left
      tt = 0; // translate from top
      r  = editorProperties.rotate // r stands for rotate
      cw = lw; 
      ch = lh; 

      switch ( r ) {
            case 90:
            case 270:
                  cw = lh;
                  ch = lw;
                  tw = ( r == 270 ) ? 0 : cw;
                  th = ( r == 270 ) ? ch : 0;
            break;
            case 180:
                  tw = lw;
                  th = lh;
            break;
      }

      //// set canvas original width and height as rotation degree ////
      editorProperties.canvas.width  = cw;
      editorProperties.canvas.height = ch;

      ///// css for canvas parent element and canvas user view width and height /////
      css = {
            width  : `${cw}px`,
            height : `${ch}px`,
      }

      ///// apply css ////
      editorProperties.canvas.css(css);
      editorProperties.canvasParaent.css(css);

      //// translate and rotate canvas ////
      editorProperties.ctx.translate(tw,th);
      editorProperties.ctx.rotate( r * Math.PI / 180);

      ////// do all actions with this hook ////////
      do_action('setRotation');

      return true;
}

add_action('drawImageToCanvas','applyRotation',5);


///////////////////////////////////////////////////////Image flipping functionality//////////////////////////////////////////////////
async function setFlip ( )
{
      var e                          = window.event;
      editorProperties.flipDirection = e.target.dataset.flipdirection;
      editorProperties.flip          = Math.sign(editorProperties.flip) === 1 ? -1 : 1;
      $('#flip-hint').value          = `${editorProperties.flipDirection.toUpperCase()}-${editorProperties.flip === -1 ? 'INVRET' : 'REVERT'}`;

      try {
            ///// save apply changes in editorproperties object /////
            editorProperties  = await setImageURL();
            editorProperties  = await drawImageToCanvas();
      } catch (error) {
            new SetError(error);
      }

      ////// do all actions with this hook ////////
      do_action('setFlip');

      return true;
}
function applyFlip ( )
{
      let tw = 0,th = 0,sx = 1,sy = 1;

      if(editorProperties.flipDirection == 'vertical'){
            if(editorProperties.flip == -1){
                  th = editorProperties.loadImageHeight;
                  sy = -1;
            }
      }else{
            if(editorProperties.flip == -1){
                  tw = editorProperties.loadImageWidth;
                  sx = -1;
            }
      }
      editorProperties.ctx.translate(tw,th);
      editorProperties.ctx.scale(sx,sy);

      ////// do all actions with this hook ////////
      do_action('applyFlip');

      return true;
}

add_action('drawImageToCanvas','applyFlip',9);

/////////////////////////////////Image resizeing and cropping functionality/////////////////////////////

async function resize_image( type='resize' ) 
{
      var e,width,height,left,top,tmpCanvas,tmpContext,image,url,cropper;
      e = window.event;

      //prevent default behaviour when submitting the form//
      e.preventDefault();
      //set a preloader
      new SetLoader(
            document.body,
            false
      );
      url = await new Promise( resolve => 
      {

            width             = parseInt($('#resize-width').value);
            height            = parseInt($('#resize-height').value);
            tmpCanvas         = document.createElement("canvas");
            tmpContext        = tmpCanvas.getContext('2d');
            image             = new Image();
            image.crossOrigin = 'anonymous';

            if( type == 'crop' ) {
                  left              = parseInt($('#from-left').value);
                  top               = parseInt($('#from-top').value);
                  image.src         = editorProperties.currentCanvasBlobURL;
            }else {
                  image.src         = editorProperties.loadImageURL;
            }

            //// load image////
            image.onload = () => 
            {
                  tmpCanvas.width  = width;
                  tmpCanvas.height = height;

                  ///draw image on tmp canvas ///
                  if ( type == 'crop' ) {
                        tmpContext.drawImage(image,left,top,width,height,0,0,width,height);
                  }else {
                        tmpContext.drawImage(image,0,0,width,height);
                  }

                  tmpCanvas.toBlob(blob => resolve(URL.createObjectURL(blob)));
            }

      });

      editorProperties.loadImageURL = url;
      
      if ( type == 'crop' ) {
            editorProperties.flip         = 1;
            editorProperties.rotate       = 0;
            editorProperties.flipDirection = 'horizontal';
      }

      try {
            ////draw image to canvas again after cropping////
            editorProperties = await setImageURL();
            editorProperties = await drawImageToCanvas();
            editorProperties = await set_height_width_for_user_view(editorProperties);

            //set a preloader
            new SetLoader(
                  document.body,
                  true
            );
      } catch (error) {
            new SetError(error);
      }

      e.target.reset();

      ////// do all actions with this hook ////////
      do_action('resize_image');
      
      let msg = 'Image Resized',progress = {background : 'purple'},barCss = {background : 'yellow'};

      if( (cropper = $( '.cropper' )).length !== 0 && type === 'crop'){
            cropper.parentElement.removeChild(cropper);
            editorProperties.canvasParaent.classList.remove('cropping');

            msg          = "Image Cropped";
            barCss       = {background : 'teal'}
            progress     = {background : '#ffbd4a'}
      }

      //set a success message after resizeing image
      new SetError(
            msg,
            document.body,
            progress,
            barCss,
      );

      return true;
}

function initDrawingCropper ( )
{
      var cropper,css,cp = editorProperties.canvasParaent; // canvas parent element

      if( getURLParams( 'tab' ) == 'Crop' ){
            //add draw-canvas class when crop tab is opened//
            cp.classList.add("draw-canvas");

            css = {cursor : 'crosshair'}

            initializeDraw(cp);

      }else{

            cp.classList.remove("draw-canvas");
            cp.classList.remove("cropping");

            css = {cursor : 'default'};

            if( ( cropper = $('.cropper') ).length !== 0 ) cp.removeChild(cropper);
            
            //remove drawing event from canvas parent element when it is another tab except crop//
            cp.removeEventListener('mousedown',mouseDown);
      }
      cp.css(css);

      ////// do all actions with this hook ////////
      do_action('initDrawingCropper');

      return true;
}

add_action('open_tab','initDrawingCropper',10);

function resizeCropper( data )
{
      var parseData,cropperStyle,cropper;

      parseData      = Object.fromEntries(data[0]);
      cropper        = parseData.targetResizeElement;
      cropperStyle   = window.getComputedStyle(cropper);

      // set height,width,left and top value for user view in DOM //
      $('#resize-image #resize-width').value  = `${cropperStyle.width}`;
      $('#resize-image #resize-height').value = `${cropperStyle.height}`;
      $('#resize-image #from-left').value     = `${cropperStyle.left}`;
      $('#resize-image #from-top').value      = `${cropperStyle.top}`;

      // apply css on cropper //
      cropper.css({backgroundPosition : `-${cropperStyle.left} -${cropperStyle.top}`});

      ////// do all actions with this hook ////////
      do_action('resizeCropper');

      return true;
}

add_action('resize','resizeCropper',11,15);

function moveCropper( data )
{
      var cropper,parseData;

      parseData = Object.fromEntries(data[0]);
      cropper   = parseData.targetElement;

      // set left and top value for user view in DOM //
      $('#resize-image #from-left').value = `${parseData.left}px`;
      $('#resize-image #from-top').value  = `${parseData.top}px`;
      
      // apply css on cropper //
      cropper.css({backgroundPosition : `-${Math.floor(parseData.left)}px -${parseData.top}px`});

      ////// do all actions with this hook ////////
      do_action('moveCropper');

      return true;
}

add_action('move','moveCropper',12,16);

/////////////////////////////////// remove disable atribute when type on quality input //////////////////////////////

function enableOptimizeing( ) 
{
      let download;
      $('#compressImage').removeAttribute('disabled');
      if(( download = $('.opt-download-img') ).length !== 0 ) {
            download.parentElement.removeChild(download);
      }
      do_action('enableOptimizeing');
      return true;
}

/////////////////////////////////Image Compress functionality/////////////////////////////

async function setCompress ( )
{
      var e,quality,r,previewWraper,sizeWraper;

      e                 = window.event;
      quality           = parseFloat(e.target.value);
      r                 = await exportCanvas(editorProperties.loadImageType,quality);
      previewWraper     = e.target.nextElementSibling;
      sizeWraper        = e.target.previousElementSibling;

      //set compress image as preview and compress size for user view//
      $('img',previewWraper).src          = r.blobURL;
      $('.decrease',sizeWraper).innerText = r.blobSize;

      editorProperties.compressImageUrl   = r.blobURL;
      editorProperties.compressBlob       = r.blobSize;

      ////// do all actions with this hook ////////
      do_action('setCompress');

      return true;
}

async function compressImage ( )
{
      var e,a,t,r,formData,qlty,imageName,imageExt,type;

      e            = window.event,type;
      a            = document.createElement('a');
      t            = e.target // target element
      formData     = new FormData();
      qlty         = $('#quality').value;
      imageName    = editorProperties.loadImageName;
      imageExt     = editorProperties.loadImageExtension;
      a.download   = `${imageName}.${imageExt}`;

      if( ( type = t.getAttribute('type') ) === 'download' ) {
            //change download button url 
            a.href            = editorProperties.compressImageUrl;
            t.removeAttribute("disabled");
            a.click();

      }else {
            
            // make target button text change and add disabled attribute also change download button download image name//
            t.innerText  = 'Optimizing...'; 
            t.setAttribute('disabled','true');

            ///set preloader
            new SetLoader($('.preview-compressed-image'),false);

            if( type.split('-')[1] === 'png' ){
                  r = editorProperties.currentCanvasBlob;
            }else {
                  qlty = qlty === '' ? 0.7 : parseInt(qlty)/100 - 0.1;
                  qlty = Math.sign(qlty) == -1 ? 0 : qlty;
                  r = (await exportCanvas('image/jpeg',qlty)).blob;
            }

            // append optimizing image data to formData object
            formData.append('files',r);
            formData.append('file_name',editorProperties.loadImageName);
            formData.append('file_ext',editorProperties.loadImageExtension);
            formData.append('mime_type',editorProperties.loadImageType);

            // send image to server for compressing//
            fetch( ajax_endpoint, {
                  method : 'POST',
                  body : formData,
            } )
            .then( res => res.json( ))
            .then( data => 
                  {
                        let Errcss,barCss,progressCss;
                        // set some error on failed
                        if( data.code == 200 ){
                              Errcss = {
                                    background : '#12ce12',
                              };
                              barCss = {
                                    background: '#d20000',
                              }
                        }
                        new SetError(
                              data.message,
                              document.body,
                              Errcss,
                              progressCss,
                              barCss,
                        );
                        ///set preloader
                        new SetLoader($('.preview-compressed-image'),false);
                        //set download option of optimized image
                        $('.decrease').innerText                  = data.dest_size;
                        $('.preview-compressed-image').innerHTML  = `<img src="${data.dest_url}"/>`;
                        e.target.innerText                        = 'Optimized';

                        setTimeout( ( ) => {
                              a.href = data.dest_url;
                              a.innerText    = 'Download Optimized Image';
                              a.className    = 'btn btn-sm btn-primary w-100 mb-2 opt-download-img';
                              t.innerText    = 'Optimized';
                              e.target.parentElement.insertBefore(a,t);
                        }, 100 );
                  }
            );
      }
      
}

/////////////////////////////////Image Uploading functionality/////////////////////////////

function change_URL_on_upload_image( data )
{     
      const parseData = Object.fromEntries(data[0]);

      ////change url by upload image url////
      change_URL_param({'tab' : 'Filter','url' : parseData.response.upload_url});

      // apply css on image upload area to make it display none
      $( '.img-progress-bar' ).css( { display : 'none' } );
      $( '.upload-area' ).css( { display : 'none' } );
      $( '.canvas' ).css( { display : 'block' } );
      $( '.sub-nav' ).css( {display : 'block' } );

      //make the upload form reset //
      $('.image_upload').reset();
      $('button[data-action="Filter"]').click();

      ////// do all actions with this hook ////////
      do_action('change_URL_on_upload_image');

      return true;
}

add_action('completeHandler','change_URL_on_upload_image',14,15);

async function set_editor_properties_as_default (data)
{
      const parseData = Object.fromEntries ( data[0] );
      /* set image data to localstorage */
      setLocalStorage('loadImageExtension',parseData.response.fileinfo.extension);
      setLocalStorage('loadImageName',parseData.response.fileinfo.filename);

      editorProperties.loadImageWidth           = 0;
      editorProperties.loadImageHeight          = 0;
      editorProperties.loadImageURL             = parseData.response.upload_url;
      editorProperties.loadImage                = null;
      editorProperties.canvas                   = $('#main-canvas');
      editorProperties.canvasParaent            = $('.canvas');
      editorProperties.canvasRect               = null;
      editorProperties.previewCanvasRect        = null;
      editorProperties.ctx                      = null;
      editorProperties.filter                   = {
                                                      'blur'        : '0px',
                                                      'brightness'  : '1',
                                                      'contrast'    : '1',
                                                      'grayscale'   : '0',
                                                      'hue-rotate'  : '0deg',
                                                      'invert'      : '0%',
                                                      'opacity'     : '1',
                                                      'saturate'    : '100%',
                                                      'sepia'       : '0',
                                                      'drop-shadow' : '0px 0px 10px #ffffff',
                                                },
      editorProperties.shadow                   = {
                                                      horizontal : '0',
                                                      vertical   : '0',
                                                      spread     : '10',
                                                      color      : '#ffffff',
                                                },
      editorProperties.flip                     = 1;
      editorProperties.flipDirection            = 'horizontal';
      editorProperties.rotate                   = 0;
      editorProperties.currentCanvasBlob        = null;
      editorProperties.currentCanvasBlobURL     = null;
      editorProperties.currentCanvasSize        = 0;
      editorProperties.removeBackground         = parseData.response.fileinfo.extension == 'png' ? true : false;
      editorProperties.setBackgroundType        = null;
      editorProperties.background               = null;
      editorProperties.compressImageUrl         = null;
      editorProperties.loadImageExtension       = parseData.response.fileinfo.extension;
      editorProperties.loadImageName            = parseData.response.fileinfo.filename;
      editorProperties.loadImageType            = getLocalStorage('loadImageType');
      editorProperties.pngimageoptimized        = false;
      editorProperties.defaultImageURL          = parseData.response.upload_url;
      /* set url when upload an image */
      editorProperties                          = await setImageURL();
      editorProperties                          = await drawImageToCanvas();
      drawErrorMessage                          = null;
      drawSuccessMessage                        = null;

      renderFilterHTML();

      ////// do all actions with this hook ////////
      do_action('set_editor_properties_as_default');

      return true;
}

add_action('completeHandler','set_editor_properties_as_default',15,15);

function setLoadImageMimeOnLocalstorage(data)
{
      const parseData                = Object.fromEntries(data[0]);
      editorProperties.loadImageType = parseData.files[0].type;
      /* set type on localstorage */
      setLocalStorage('loadImageType',parseData.files[0].type);

      ////// do all actions with this hook ////////
      do_action('setLoadImageMimeOnLocalstorage');

      return true;
}

add_action('uploadImage','setLoadImageMimeOnLocalstorage',13,15);

/* ==================================================== Set error =============================================== */
class SetError{
      constructor(
            message = 'No Text Given',
            parent = document.body,
            error = {background: '#d20000'},
            progress = {},
            bar = {background: '#12ce12'},
            msg = {},
            closeErr = {},
      ){
            this.parent         = parent;
            this.message        = message;
            this.errorCss       = error;
            this.progressCss    = progress;
            this.barCss         = bar;
            this.msgCss         = msg;
            this.closeCss       = closeErr;
            this.cw             = 0;
            this.error          = this.createError();
            this.errorProgress  = $('.progress-error',this.error);
            this.errorMsg       = $('.error-msg',this.error);
            this.closeErr       = $('.close-error',this.error);
            this.errProgressBar = $('.progress-error span',this.error);
            this.initProgress   = setInterval(() => this.progress(), 40);

            this.closeError();     
            this.error.css(this.errorCss);
            this.errorProgress.css(this.progressCss);
            this.errProgressBar.css(this.barCss);
            this.closeErr.css(this.closeCss);
            this.errorMsg.css(this.msgCss);
      }

      progress(){
            this.cw += 1;
            this.errProgressBar.css({width : `${this.cw}%`});
            // add initial mode when error object is initialized//
            if(! this.error.classList.contains('i-mode')){
                  this.error.classList.add('i-mode');
            }
            //clear interable after reaching target width//
            if(this.cw >= 100) {
                  clearInterval(this.initProgress);
                  this.closeErr.click();
            }
            return true;
      }
      closeError(){
            this.closeErr.listener('click',e => {
                  clearInterval(this.initProgress);
                  var p = e.target.parentElement;
                  p.classList.add('v-mode');
                  setTimeout(() => {
                        p.parentElement.removeChild(p);
                  }, 500);
            });
            return true;
      }
      createError(){
            const error = document.createElement('div');
            error.classList.add('error');
            
            error.innerHTML = `
                  <div class="error-msg">${this.message}</div>
                  <button class="close-error">&times;</button>
                  <div class="progress-error">
                        <span></span>
                  </div>
            `;
            
            //append error to DOM
            this.parent.appendChild(error);

            return error;
      }
}
/* ==================================================== Set loader =============================================== */
class SetLoader {
      constructor( toSet = document.body ,remove = false) {
            this.toSet  = toSet;
            this.remove = remove;
            this.css    = css;

            if ( this.remove === false ) {
                  this.setPreloader();
            }else {
                  this.removePreloder();
            }
      }

      removePreloder(){
            const preloader = $('.preloader');
            if ( preloader.length !== 0 ) {
                  preloader.parentElement.removeChild(preloader);
            }
            return true;
      }
      
      createPreloader( ) {
            const preloader = document.createElement('div');
            preloader.classList.add('preloader');
            preloader.css(this.css);
            preloader.innerHTML = `
                  <div class="loader">
                        <img src="assets/images/ZZ5H.gif" alt="">
                  </div>
            `;
            return preloader;
      }

      setPreloader ( ) {
            if(this.toSet.parentElement) {
                  let position;
                  if( ( position = window.getComputedStyle( this.toSet ).position ) === 'static' ) {
                        this.toSet.css({position : 'relative'});
                  }else {
                        this.toSet.css({position : position});
                  }
            }

            if($( '.preloader' ).length === 0) {
                  this.toSet.appendChild(this.createPreloader());
                  return true;
            }
      } 
}

/* ==================================================== download image =============================================== */
download.onclick = () => $('.export-option').classList.toggle('open-option');
downloadImg.onclick = async (e) => {

      const type = $('select',e.target.parentElement).value;
      const a    = document.createElement('a');
      let mime   = '';

      switch(type) {
            case 'default' :
                  mime        = editorProperties.loadImageType;
                  a.download  = `${editorProperties.loadImageName}.${editorProperties.loadImageExtension}`;
                  console.log(editorProperties.loadImageExtension);
                  break;
            case 'png' :
                  mime        = 'image/png';
                  a.download  = `${editorProperties.loadImageName}.png`;
                  break;
            case 'jpeg' :
                  mime        = 'image/jpeg';
                  a.download  = `${editorProperties.loadImageName}.jpg`;
                  break;
      }
      const exportData  = await exportCanvas(mime,0.7);
      a.href            = exportData.blobURL;
      a.click();
      download.click();
}