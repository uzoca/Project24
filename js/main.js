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
            // console.log(data)
            if(data.indexOf('<script>') != -1){
                $("body").append(data)
            }else{
                data = JSON.parse(data)
               
                $("#profile-image")
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
           // console.log(data)
            func(data)
        }
    })
}
function logOut(){
     document.cookie = "session_id=; expires=Thu, 01 Jan 1970 00:00:00 UTC; path=/;";
    sendMinorData('logout',function(data){console.log(data); $("body").append(data)} )
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
 var acc_balance
 function getAccountDetails(){
  sendMinorData({'get_bank_details_org':getCookie('session_id')},function(data){
     // console.log(data)
      data =JSON.parse(data)
      acc_balance = data['acount_balance']
      $(".acc_balance, .balance").html("&#8358 "+data['acount_balance'])
      organisation_acount = data['acount_number'] 
     })
 }
function get_transactions()
{
     var total_expenses = 0, total_income = 0
     var tutionAmount = 0 , staff_expenses = 0 
     if(organisation_acount != '')
    sendMinorData({'get_user_transaction': organisation_acount},function(data){
     // console.log(data)
      data = JSON.parse(data)
     $("#defaultOrderingDataTable tbody").empty()
      var count = 1
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
        if(element['description'].indexOf('staff') !=-1){
            staff_expenses = staff_expenses + parseFloat(element['amount'])
        }
          if(element['description'].indexOf('fee') !=-1){
            tutionAmount= tutionAmount + parseFloat(element['amount'])
        }
        $("#defaultOrderingDataTable tbody").append( "<tr><td><div style='width:40px'>"+ count++ +"</div></td>"+
            "<td><div class='font-weight-bold' style='width:120px' >"+ element['object_fullname']+"</div></td>"+
            "<td><div class='font-weight-bold' style='width:60px' >"+ element['amount']+"</div></td>"+
            "<td><div style='width:100px'>"+element['flow_type']+"</div></td>"+
            "<td '><div style='width:140px'>"+element['description']+"</div></td> "+
            "<td><div style='width:80px'>"+ statusColumn +"</div></td>"+
            "<td><div style='width:80px'>"+element['date'] +"</div></td></tr>"
            )


     });
     $(".expenditure_h3").html("&#8358 "+total_expenses)
     $(".income_h3").html("&#8358 "+total_income)
     $(".income_pill").html('<i class="fa fa-arrow-up m-xl-1"></i>'+parseInt((total_income/acc_balance)*100)+'%')
     $(".expenditure_pill").html('<i class="fa fa-arrow-down m-xl-1"></i>'+parseInt((total_expenses/acc_balance)*100)+"%")
     var newIncome = total_income - total_expenses
     if(Math.sign(newIncome)){
       $(".acc_balance-pill").removeClass('badge-outline-danger').addClass('badge-outline-success').html('<i class="fa fa-arrow-up m-xl-1 ">'+parseInt((newIncome/acc_balance)*100)+"%")
     }else{
       $(".acc_balance-pill").removeClass('badge-outline-success').addClass('badge-outline-danger').html('<i class="fa fa-arrow-up m-xl-1 ">'+(newIncome/acc_balance)*100+"%")
     }
     var tutionAmountPercent = (parseFloat(tutionAmount)/ parseFloat(total_expenses))*100
     $(".tt").remove()
     $(".stat").after('<div class="col-12 mb-3 tt"><div class="d-flex text-secondary-light"><div class="bg-light rounded-sm text-center px-1"><i class="ri-exchange-cny-line" style="font-size:22px;"></i> </div><span class="pl-2 font-weight-bold">Tution fees</span> </div><div class="progress border-0 mt-2"><div class="progress-bar bg-secondary" role="progressbar" style="width: '+parseInt(tutionAmountPercent)+'%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div> </div><div class=" d-flex justify-content-between"><span style="font-size: 12px;font-weight: bold;color:black">'+tutionAmount +'</span><span style="font-size: 12px;font-weight: bold;color:black">'+parseInt(tutionAmountPercent) +'%</span></div></div>')
      var staff_expensesPercent = (parseFloat(staff_expenses)/ parseFloat(total_expenses))*100
     $(".st").remove()
     $('.tt').after('<div class="col-12 mb-3 st"><div class="d-flex text-secondary-light"><div class="bg-light rounded-sm text-center px-1"><i class="ri-exchange-cny-line" style="font-size:22px;"></i> </div><span class="pl-2 font-weight-bold">Staff Expenses</span> </div><div class="progress border-0 mt-2"><div class="progress-bar bg-secondary" role="progressbar" style="width: '+parseInt(staff_expensesPercent)+'%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div> </div><div class=" d-flex justify-content-between"><span style="font-size: 12px;font-weight: bold;color:black">'+ staff_expenses +'</span><span style="font-size: 12px;font-weight: bold;color:black">'+parseInt(staff_expensesPercent) +'%</span></div></div>')
     setTimeout(function(){
       if (!($.fn.DataTable.isDataTable("#defaultOrderingDataTable"))){
          let dataTable2 =  $("#defaultOrderingDataTable")
       $(dataTable2).DataTable({
        paging : false,
        ordering : false,
        info : false,
         dom: 'Bfrtip',
         buttons: [
        'copy','excel', 'csv', 'pdf'
        ],
       }); 
        //$(".dataTables_filter").parent().addClass("text-right mr-4")
       }
      
     },1000)
     })
}

// password obscure toggle
$("body").on('click','.obscure',function(e){
  console.log($(this))
 $(this).prev("input[type='password'],input[name='pass'],input[name='c-pass'],input[name='password']").first().attr('type','text')
  var mwid = parseInt( $(this).css('left'))
 $(this).replaceWith('<div class="unObscure" style="position:relative;left:'+mwid +'px;margin-top:-27px;width:10px" ><i class="ri-eye-off-fill"></i></div>')
})

$("body").on('click','.unObscure',function(e){
 console.log($(this))
 $(this).prev("input[name='c-pass'],input[name='password']").first().attr('type','password')
  var mwid = parseInt( $(this).css('left'))
 $(this).replaceWith('<div class="obscure" style="position:relative;left:'+mwid +'px;margin-top:-27px;width:10px" ><i class="ri-eye-fill"></i></div>')
})

$("input[name='c-pass'],input[type='password'], input[name='password']").each((idx,ele)=>{
 var mwid = parseInt($(ele).width())-10
$(ele).after('<div class="obscure" style="position:relative;left:'+mwid +'px;margin-top:-27px;width:10px" ><i class="ri-eye-fill" ></i></div>')
})
// form validation 


function get_transactions_decending_order(){
  
  sendMinorData({'transaction_decending': organisation_acount},function(data){
   data = JSON.parse(data) 
  // console.log(data)
   var notificatio_count = 0
   if(data['credit'].length<=0){
     $(".credit_div").append('<div class="btn bg-light d-flex justify-content-between mx-2 my-1"><div class="col-7  rounded-sm text-left" style=" white-space: nowrap; overflow: hidden !important;text-overflow: ellipsis !important;">No Credit yet</div></div>')
     
   }
    if(data['debit'].length <= 0){
     $(".debit_div").append('<div class="btn bg-light d-flex justify-content-between mx-2 my-1"><div class="col-7  rounded-sm text-left" style=" white-space: nowrap; overflow: hidden !important;text-overflow: ellipsis !important;">No Credit yet</div></div>')
     
   }
   data['credit'].forEach(function(ele){
   
    $(".credit_div").append('<div class="btn bg-light d-flex justify-content-between mx-2 my-1"><i class="ri-arrow-left-right-line"></i><div class="col-7  rounded-sm text-left" style=" white-space: nowrap; overflow: hidden !important;text-overflow: ellipsis !important;">'+ ele["description"]+'</div><i class="ri-arrow-right-line"></i></div>')
    
    if(ele["checked"] == "0"){
     notificatio_count++
     $(".notification_toggle").append('<a href="#" class="dropdown-item text-secondary-light p-0"><div class="d-flex flex-row border-bottom"><div class="notification-icon bg-secondary-c pt-3 px-3"><i class="fas fa-clipboard-list text-success fa-lg"></i></div><div class="flex-grow-1 px-3 py-3"><p class="m-0">Credit alert <span class="badge badge-pill badge-success">New</span></p> <small>from : '+ele['subject'] +'</small></div> </div> </a>')
    }else{
      $(".notification_toggle").append('<a href="#" class="dropdown-item text-secondary-light p-0"><div class="d-flex flex-row border-bottom"><div class="notification-icon bg-secondary-c pt-3 px-3"><i class="fas fa-clipboard-list text-secondary fa-lg"></i></div><div class="flex-grow-1 px-3 py-3"><p class="m-0">Credit alert </p> <small>from : '+ele['subject'] +'</small></div> </div> </a>')
    }
   })
   data['debit'].forEach(function(ele){
   
   $(".debit_div").append('<div class="btn bg-light d-flex justify-content-between mx-2 my-1"><i class="ri-bank-card-fill"></i><div class="col-7  rounded-sm text-left" style=" white-space: nowrap; overflow: hidden !important;text-overflow: ellipsis !important;">'+ ele["description"]+' </div><i class="ri-arrow-right-line"></i> </div>')

   if(ele["checked"] == "0"){
     notificatio_count++
     $(".notification_toggle").append('<a href="#" class="dropdown-item text-secondary-light p-0"><div class="d-flex flex-row border-bottom"><div class="notification-icon bg-secondary-c pt-3 px-3"><i class="fas fa-clipboard-list text-danger fa-lg"></i></div><div class="flex-grow-1 px-3 py-3"><p class="m-0">Debit alert <span class="badge badge-pill badge-danger">New</span></p> <small>from : '+ele['subject'] +'</small></div> </div> </a>')
    }else{
       $(".notification_toggle").append('<a href="#" class="dropdown-item text-secondary-light p-0"><div class="d-flex flex-row border-bottom"><div class="notification-icon bg-secondary-c pt-3 px-3"><i class="fas fa-clipboard-list text-secondary fa-lg"></i></div><div class="flex-grow-1 px-3 py-3"><p class="m-0">Debit alert </p> <small>from : '+ele['subject'] +'</small></div> </div> </a>')
    }
  })
    $(".notificatio_count").text(notificatio_count)
    updateCheckedData()
  })

}

    getAccountDetails() 
     setTimeout(function(){get_transactions();get_transactions_decending_order()},1000) 
     

    var tranTicker=  setInterval(() => {
    get_transactions()
    getAccountDetails()
    }, 2000);
   

    $("#logout-btn,.logout,.logout-btn").click(function(){
        logOut()
    })

  function updateCheckedData(){
  sendMinorData('update_transaction_check',function(data){
   //console.log(data)
  })
  
  }
 function ValidateEmail(mail) 
{
console.log(mail) 
 if (/^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/.test(mail))
 
  {
    return true
  }
  return false
}
