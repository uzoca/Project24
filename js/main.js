// Script for adding ripple efect to the button element
let buttonWithRippleClass = document.querySelectorAll(".btn-ripple");
buttonWithRippleClass.forEach(btn => {
    btn.addEventListener("mousedown", function(e) {
        // let x = e.clientX - e.target.offsetLeft;
        // let y = e.clientY - e.target.offsetTop;
        
        let rippleElement = document.createElement("span");
        // rippleElement.style.left = x + "px";
        // rippleElement.style.top = y + "px";

        this.appendChild(rippleElement);

        setTimeout(() => {
            rippleElement.remove();
        },500)
    })
})
function setCookie(cname, cvalue, exdays) {
    const d = new Date();
    d.setTime(d.getTime() + (exdays*24*60*60*1000));
    let expires = "expires="+ d.toUTCString();
    document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
  }
function getUser(){
    $.ajax({
        method :'Post',
        url: 'backend/backend.php',
        data : 'check-user',
        success : function(data){
            if(data.indexOf('<script>') != -1){
                $("body").append(data)
            }else{
                data = JSON.parse(data)
                $(".profile-avatar")
                .attr('src',data['userimg']).css("width","50px").css("margin","0px 20px 0px 20px")
                $("#user-name").text(data['firstname']+' '+data['lastname'])
            }

        }
    })
}

function sendMinorData(mdata,func){
    $.ajax({
        method :'Post',
        url: 'backend/backend.php',
        data : mdata,
        success : function(data){
            console.log(data)
            func(data)
        }
    })
}
function logOut(){
    sendMinorData('logout',function(data){$("body").append(data)} )
}