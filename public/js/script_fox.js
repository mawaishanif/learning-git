 
document.addEventListener('click' , function(e){
    if(e.target && e.target.classList.contains('bi-pencil-square')){
        console.log('icon clicked')
        console.log(e.classList)
        document.querySelector('#createbtn').click();
    }
});

var iconElement = document.getElementsByClassName('copy_icon');
document.addEventListener('click' , function(e){
    if(e.target && e.target.classList.contains('copy_icon')){
        var innerElemnt = e.target.parentElement.parentElement.querySelector('.text_to_copy');
        navigator.clipboard.writeText(innerElemnt.innerText); 
        e.target.innerText = "Copied";
        setTimeout(()=> {
            e.target.innerText = "Copy"
        }, 2000)
        console.log('Text Copied')
    }
});


function master(){
    $("#collectionRow").css('display','none');
      document.getElementById('pixel_search_box').innerHTML = ""
      console.log("master call");
}

function collection(){
    $("#collectionRow").css('display','block');
    $("#pixel_search_box").hide();

}

function niche(){
    $("#collectionRow").css('display','none');
       document.getElementById('pixel_search_box').innerHTML = '<div class="niche_input_pixel"><span class="px_text_search_box">fox_</span><input type="text"  class="search_box_pixel search_box_modal" placeholder="Type your tag name" id ="fieldvalue3"></div>';
        $("#pixel_search_box").show();
        console.log("niche call");
}






function ios_checkBox(){
    var input_ios = document.querySelector('#ios_checkbox');
    if(input_ios.checked == true){
        console.log('1st Ios condition matched ')
        document.querySelector('.alert_modal_content').innerHTML = '<p class="server_text">Enable Server Side API to track all customer behaviour events bypassing the browser\'s limitation, or ad-blockers.</p><span>Fill Facebook Access Token</span><input id="aceess_token" type="text" class="search_box_pixel search_box_modal modal_input_alert"> <span>Test Event Code</span> <input type="text" id="test_token"  class="search_box_pixel search_box_modal modal_input_alert"> <p class="text-muted"> Use This if you need to test the server-side event. <strong>Remove it after testing.</strong> </p>'
    }
    else if(input_ios.checked == false){
        console.log('2nd IOS condition matched')
        document.querySelector('.alert_modal_content').innerHTML =''
    }


}







