function redirect(data){
      const parseData = Object.fromEntries(data[0]);
      if(parseData.target.id == 'media-upload'){
            window.location.href = `http://localhost/cms/admin/media.php`;
      }
}
add_action('completeHandler','redirect',36,15);

/* navbar collapse */
const navCloseBtn = document.querySelector(".nav-close");
const navMenu     = document.querySelector(".navigation");
navCloseBtn.onclick = function(){
      this.classList.toggle("do-action");
      if(navMenu.classList.contains("expand")){
            navMenu.classList.remove("expand");
      }else{
            navMenu.classList.add("expand");
      }
}
/* add caret icon to link item */
const dropdown = document.querySelectorAll(".dropdown");
dropdown.forEach(dropitem => {
      var sibling = dropitem.previousElementSibling;
      let i = document.createElement("i");
      i.className = 'fa fa-caret-right';
      i.setAttribute("aria-hidden","true");
      sibling.appendChild(i);

      sibling.addEventListener('click',function(e){
            e.preventDefault();
            var rect = dropitem.getBoundingClientRect();
            if(rect.height == 0){
                  dropitem.style.height = `${dropitem.querySelector(".dropdown-nav").getBoundingClientRect().height}px`;
            }else{
                  dropitem.style.height = '0px';
            }
            sibling.classList.toggle("active-sibling");
      });
});

/* ================================================== custom permalink struct ============================== */
const tags         = $('.tags .tag');
const customStruct = $('#custom_struct');

tags.forEach(tag => {
      tag.listener('click',e => {
            let value      = e.target.dataset.value;
            let existValue = customStruct.value; 

            e.target.classList.toggle('active');
            if ( e.target.classList.contains ( 'active' ) ) {
                  if ( existValue.search ( value ) !== -1 ) {
                        customStruct.value = existValue.replace(value,value);
                  } else {
                        customStruct.value = `${existValue}${value}/`;
                  }
            } else {
                  if ( existValue.search ( value + '/' ) !== -1 ) {
                        customStruct.value = existValue.replace(value + '/','');
                  }
            }
      });
});
//close flash message
function closeFlashMsg ( btn ) {
      btn.parentElement.classList.add('closing');
      setTimeout(() => {
            btn.parentElement.parentElement.removeChild(btn.parentElement);
      }, 500);
};

// add onchange event to submit the form
$('.c-action').forEach ( item => {
      item.listener ( 'change',e => {
            $('#post-form').submit();
      });
});