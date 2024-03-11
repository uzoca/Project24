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

  function getCookie(cname) {
  let name = cname + "=";
  let decodedCookie = decodeURIComponent(document.cookie);
  let ca = decodedCookie.split(';');
  for(let i = 0; i <ca.length; i++) {
    let c = ca[i];
    while (c.charAt(0) == ' ') {
      c = c.substring(1);
    }
    if (c.indexOf(name) == 0) {
      return c.substring(name.length, c.length);
    }
  }
  return "";
}
function checkCookie() {
  let username = getCookie("username");
  if (username != "") {
   alert("Welcome again " + username);
  } else {
    username = prompt("Please enter your name:", "");
    if (username != "" && username != null) {
      setCookie("username", username, 365);
    }
  }
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


function sendMinorData(mdata,func,beforeSend){
    $.ajax({
        method :'post',
        url: 'backend/backend.php',
        data : mdata,
        success : function(data){
            func(data)
        }
    })
}
function logOut(){
     document.cookie = "session_id=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    sendMinorData('logout',function(data){$("body").append(data)} )
}

function getTransaction(n,func){
 sendMinorData({'all_transactions':n},func)
}

getUser()
$("#logout-btn,.logout").click(function(){
    logOut()
})
 var sidebarWidth =  $(".side-bar").css("width")
  if($(document).width()<= 1200)
     sidebarWidth = '13rem'
  var mainBodyWidth =   $(".main_body_div").css("width")
  var mainBodyMarginLeft =  $(".main_body_div").css("margin-left")
$(".menu_toggle").click(function(){
  console.log(sidebarWidth)
  console.log(sidebarWidth)
  var styleString = "width:"+sidebarWidth+" !important; visibility: visible !important"
  if( $(".side-bar").css("width") == "0px"){
    $(".side-bar").removeAttr("style")
    $(".side-bar").attr("style",styleString)
    $(".main_body_div").css("width","80%").css("margin-left",mainBodyMarginLeft)
  }else{
    $(".side-bar").removeAttr("style")
    $(".side-bar").attr("style","width:0rem !important")
    $(".main_body_div").attr("style","position: relative; margin-left:2rem !important; width: 96% !important ;padding-top:3.5%;transition: all ease 0.5s;")
  }

})

$(".close-btn").click(function(){
   $(".side-bar").removeAttr("style")
    $(".side-bar").attr("style","width:0rem !important")
    $(".main_body_div").css("width","96%").css("margin-left","2rem")
})

var organisation_acount = ''
 //  Get bank details =======
 function getAccountDetails(){
  sendMinorData({'get_bank_details_org':getCookie('session_id')},function(data){
     // console.log(data)
      data =JSON.parse(data)
      $(".acc_balance").html("&#8358 "+data['acount_balance'])
      organisation_acount = data['acount_number'] 
     })
 }
function get_transactions()
{
     var total_expenses = 0, total_income = 0
     if(organisation_acount != '')
    sendMinorData({'get_user_transaction': organisation_acount},function(data){
     // console.log(data)
      data = JSON.parse(data)
     $("#defaultOrderingDataTable tbody").empty()
       
     data.forEach( 
       element => {
        var statusColumn 
        if(element['type'] == 'debit'){
          total_expenses = total_expenses + parseFloat(element['amount'])
          statusColumn = 
        '<span class= "badge badge-outline-danger badge-pill m-1 border-0 px-3 pt-1 pb-2" style="background-color:#f3dbdb;"></i>Debit</span>'
        }else{
          total_income = total_income + parseFloat(element['amount'])
           statusColumn = '<span class="badge badge-outline-success badge-pill m-1 border-0 px-3 pt-1 pb-2" style="background-color:#c1f1c3 ;"></i>Credit</span>'
        }    

        $("#defaultOrderingDataTable tbody").append( "<tr><td><div style='width:40px'>"+element['id'] +"</div></td>"+
            "<td><div  style='width:80px' >"+ element['object_fullname']+"</div></td>"+
            "<td><div  style='width:60px' >"+ element['amount']+"</div></td>"+
            "<td><div style='width:100px'>"+element['flow_type']+"</div></td>"+
            "<td '><div style='width:140px'>"+element['description']+"</div></td> "+
            "<td><div style='width:80px'>"+ statusColumn +"</div></td>"+
            "<td><div style='width:80px'>"+element['date'] +"</div></td></tr>"
            )
     });
     $(".expenditure_h3").html("&#8358 "+total_expenses)
     $(".income_h3").html("&#8358 "+total_income)
     })
}