/*-----GLOBAL-VARIABLES-----*/
/*Profile Nav Select*/
var previousProfileSelection = 'profilePersonal';
/*Booking Variables*/
var bookingid = "";
var bookingDates = new Array();
var datesWeekend = new Array();
var holidayDates = new Array();
var checkIn;
var checkOut;
var splitBooking = false;
var splitCheckIn = new Array();
var splitCheckOut = new Array();
var meetGreetDates = new Array();
var kennelsSelected = new Array();
var kennelsUsed = new Array();
var holidays = [];
var numberOfDogs = 1;
var clientId;
var dogsId = new Array();
var tax;
var subTotal;
var additionalId = new Array();
var additionalNames = new Array();
var additionalPrices = new Array();
var additionalQuantity = new Array();
var additionalType = new Array();
var additionalDesc = new Array();
var loadAdditionalDesc = new Array();
var loadAdditionalPrice = new Array();
var loadAdditionalType = new Array();
var total;
var deposit = 0;
var additionOnLoad = false;
var updateAdditionalId = [""];
var typeOfBooking = "";
var paymentType = "";
var bookingDate = "";
var paymentNote = "";
var clientName;
/*Split Calendar Variables*/
var selectedSplitDates = new Array();
var splitDatesForSaving = new Array();

/*-----GLOBAL-FUNCTIONS-----*/
function goBack() {
    window.history.back();
}
function navSelected(url) {
    var pages = ['index', 'customers', 'calendar', 'booking', 'settings'];
    var subPages = ['arrivals', 'departures', 'appointments', 'allBookings', 'outstanding'];
    var i = 0;
    for (i; i <= pages.length - 1; i++) {
        var find = url.indexOf(pages[i]);
            if (find != -1) {
                var newMenu = pages[i];
                $("#mainNav > ." + newMenu).addClass("selectedNav");
                $("#mobileNav > ul > ." + newMenu).addClass("active");
                i = pages.length;
            }else{
                for (j = 0; j <= subPages.length - 1; j++) {
                  find = url.indexOf(subPages[j]);
                  if (find != -1) {
                    $("#mainNav > .reports").addClass("selectedNav");
                    $("#mobileNav > ul > .reports").addClass("active");
                  }
                }
            }
        }
}
/*Index Page Functions*/
function getMonth(monthNumber) {
  var monthName = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
  return monthName[monthNumber - 1];
}
function clickDate(date, direction, container) {
  var position = $('#' + container + 'Container').position();
  var width = $('#' + container + 'Container').width();
  var height = $('#' + container + 'Container').height();
  $( "#" + container + "Loading" ).css({
    'left':position.left,
    'top': position.top,
    'height': height + "px",
    'width': width + "px",
  });
  $( "#" + container + "Loading > i" ).css('margin-top', (height / 2) + 'px');
  $( "#" + container + "Loading" ).show();
  date = date.split(' ');

  var day = date[1];
  var month = date[0];
  var year = date[2];

  $.post("clickDates.php", {
      day1: day,
      month1: month,
      year1: year,
      direction1: direction,
  }, function(data) {
      data = data.split('-');
      day = data[1];
      month = data[0];
      year = data[2];
      $('#' + container + 'Date').val(day + " " + month + " " + year);
      month = getMonth(month);
      $('#' + container + 'Time').html(day + " " + month + " " + year);

      loadContainerBookings($('#' + container + 'Date').val(), container);
  });

}
function loadContainerBookings(date, container) {
  date = date.split(' ');
  var day = date[0];
  var month = date[1];
  var year = date[2];
  var limit = $("#limitNumber").val();
  $.post("load" + container + "s.php", {
      day1: day,
      month1: month,
      year1: year,
      limit: limit,
  }, function(data) {
      if(data == 'none') {
        $('#' + container + 'sLoaded').html('You have no ' + container + 's for today. <a href="booking">Click here to make a booking.</a>');
      }else{
        data = data.replace('[', '');
        data = data.replace(']', '');
        data = data.split(',');
        if (container == "appointment") {
          $("#" + container + "sLoaded").html("<table id='" + container + "Table' class='departmentTable' width='100%'><tr id='" + container + "Header' class='departmentHeader'><thead><td width='60%'>Customer</td><td>Type</td><td>Status</td></tr><thead>");
        }else{
          $("#" + container + "sLoaded").html("<table id='" + container + "Table' class='departmentTable' width='100%'><tr id='" + container + "Header' class='departmentHeader'><thead><td width='60%'>Customer</td><td>Kennel</td><td>Status</td></tr></thead>");
        }
        for(var i = 1; i < data.length; i++){
          var postData;
          data[i] = data[i].replace('"', '');
          data[i] = data[i].replace('"', '');
          var bookingInfo = data[i].split('&');
          var dogs = bookingInfo[2].split('*');
          postData = '<tr><td width="60%" onclick="location.href=\'https://friendsfur-ever.ca/schedule/booking?bookingid=' + bookingInfo[3] + '\';">' + bookingInfo[0];
          for(var j = 0; j <= dogs.length -2; j++) {
            var dogsInfo = dogs[j].split('^');
            postData = postData + "<button onclick='location.href=\"kennelsheet.php?dogid=" + dogsInfo[1] + ";\"' class='profileButton3'>" + dogsInfo[0] + "</button>";
          }
          $("#" + container + "Table").append(postData + '</td><td>' + bookingInfo[1] + '</td><td>??</td></tr></table>');
        }
      }
      $( "#" + container + "Loading" ).hide();
  });
}
/*Profile Nav Select*/
function profileSelect(id) {

    /*remove previous selection and hide content*/
    $(".profileMenu").removeClass('profileMenuSelected');
    $("#" + previousProfileSelection + "Section").hide();
    /*Add class to new menu selection and show content*/
    $("#" + id).addClass('profileMenuSelected');
    $("#" + id + "Section").show();
    /*Store new selections id*/
    previousProfileSelection = id;
}
/*Save New Client*/
function newClient() {
    $('#customerMessage').html("");
    $("span").remove('#redError');
    $('input').removeClass('error');
    $('select').removeClass('error');
    $('textarea').removeClass('error');
    var first = $('#customerFirst').val();
    var last = $('#customerLast').val();
    var email = $('#customerEmail').val();
    var phone = $('#customerPhone').val();
    var mobile = $('#customerMobile').val();
    var work = $('#customerWork').val();
    var street = $('#customerStreet').val();
    var street2 = $('#customerStreet2').val();
    var city = $('#customerCity').val();
    var province = $('#customerProvince').val();
    var postal = $('#customerPostal').val();
    var ename = $('#customerEname').val();
    var ephone = $('#customerEphone').val();
    var notes = $('#customerNotes').val();
    $.post("customerSave.php", {
        first1: first,
        last1: last,
        email1: email,
        phone1: phone,
        mobile1: mobile,
        work1: work,
        street1: street,
        street21: street2,
        city1: city,
        province1: province,
        postal1: postal,
        ename1: ename,
        ephone1: ephone,
        notes1: notes,
    }, function(data) {
        if(data === 'Saved') {
            $('#customerMessage').html("We saved your new client but could not redirect your page. Please go to the customers page and select the client.");
            $("span").remove('#redError');
            $('input').removeClass('error');
            $('select').removeClass('error');
            $('textarea').removeClass('error');
        }else if(data === "Fail"){
            $('#customerMessage').html("Something went wrong. Please try again.");
        }else if(data === "customerFirst") {
            $('#' + data).addClass('error');
            $('body').animate({ scrollTop: $("#" + data).offset() }, 'slow');
            $('#' + data).before("<span id='redError'>This field is required.</span>");
            $('body').animate({ scrollTop: $("#redError").offset().top }, 'slow');
        }else if(data === "customerLast") {
            $('#' + data).addClass('error');
            $('#' + data).before("<span id='redError'>This field is required.</span>");
            $('body').animate({ scrollTop: $("#redError").offset().top }, 'slow');
        }else if(data === "customerPhone") {
            $('#' + data).addClass('error');
            $('#' + data).before("<span id='redError'>Please remove any spaces or dashes.</span>");
            $('body').animate({ scrollTop: $("#redError").offset().top }, 'slow');
        }else if(data === "customerEphone") {
            $('#' + data).addClass('error');
            $('#' + data).before("<span id='redError'>Please remove any spaces or dashes.</span>");
            $('body').animate({ scrollTop: $("#redError").offset().top }, 'slow');
        }else if(data === "customerMobile") {
            $('#' + data).addClass('error');
            $('#' + data).before("<span id='redError'>Please remove any spaces or dashes.</span>");
            $('body').animate({ scrollTop: $("#redError").offset().top }, 'slow');
        }else if(data === "customerWork") {
            $('#' + data).addClass('error');
            $('#' + data).before("<span id='redError'>Please remove any spaces or dashes.</span>");
            $('body').animate({ scrollTop: $("#redError").offset().top }, 'slow');
        }else if(data === "customerEmail") {
            $('#' + data).addClass('error');
            $('#' + data).before("<span id='redError'>Not a valid email.</span>");
            $('body').animate({ scrollTop: $("#redError").offset().top }, 'slow');
        }else{
            location.href='customer.php?id=' + data;
        }
    });
}
/*Load Client*/
function loadCustomer(id) {
    $.post("loadCustomer.php", {
        id1: id,
    }, function(data) {
        if(data) {
            $("#customerForm").html(data);
        }else{
            $("#customerForm").html('Something went wrong.');
        }
    });
}
/*Update Client*/
function updateCustomer(id) {
    $('#customerMessage').html("");
    $("span").remove('#redError');
    $('input').removeClass('error');
    $('select').removeClass('error');
    $('textarea').removeClass('error');
    var first = $('#customerFirst').val();
    var last = $('#customerLast').val();
    var email = $('#customerEmail').val();
    var phone = $('#customerPhone').val();
    var mobile = $('#customerMobile').val();
    var work = $('#customerWork').val();
    var street = $('#customerStreet').val();
    var street2 = $('#customerStreet2').val();
    var city = $('#customerCity').val();
    var province = $('#customerProvince').val();
    var postal = $('#customerPostal').val();
    var ename = $('#customerEname').val();
    var ephone = $('#customerEphone').val();
    var notes = $('#customerNotes').val();
    $.post("customerUpdate.php", {
        id1: id,
        first1: first,
        last1: last,
        email1: email,
        phone1: phone,
        mobile1: mobile,
        work1: work,
        street1: street,
        street21: street2,
        city1: city,
        province1: province,
        postal1: postal,
        ename1: ename,
        ephone1: ephone,
        notes1: notes,
    }, function(data) {
        if(data === 'Success') {
            $('#customerMessage').html("");
            $("span").remove('#redError');
            $('input').removeClass('error');
            $('select').removeClass('error');
            $('textarea').removeClass('error');
            alert("Success");
            loadCustomer(id);
        }else if(data === "Fail"){
            $('#customerMessage').html("Something went wrong. Please try again.");
        }else if(data === "customerFirst") {
            $('#' + data).addClass('error');
            $('body').animate({ scrollTop: $("#" + data).offset() }, 'slow');
            $('#' + data).before("<span id='redError'>This field is required.</span>");
            $('body').animate({ scrollTop: $("#redError").offset().top }, 'slow');
        }else if(data === "customerLast") {
            $('#' + data).addClass('error');
            $('#' + data).before("<span id='redError'>This field is required.</span>");
            $('body').animate({ scrollTop: $("#redError").offset().top }, 'slow');
        }else if(data === "customerPhone") {
            $('#' + data).addClass('error');
            $('#' + data).before("<span id='redError'>Please remove any spaces or dashes.</span>");
            $('body').animate({ scrollTop: $("#redError").offset().top }, 'slow');
        }else if(data === "customerEphone") {
            $('#' + data).addClass('error');
            $('#' + data).before("<span id='redError'>Please remove any spaces or dashes.</span>");
            $('body').animate({ scrollTop: $("#redError").offset().top }, 'slow');
        }else if(data === "customerMobile") {
            $('#' + data).addClass('error');
            $('#' + data).before("<span id='redError'>Please remove any spaces or dashes.</span>");
            $('body').animate({ scrollTop: $("#redError").offset().top }, 'slow');
        }else if(data === "customerWork") {
            $('#' + data).addClass('error');
            $('#' + data).before("<span id='redError'>Please remove any spaces or dashes.</span>");
            $('body').animate({ scrollTop: $("#redError").offset().top }, 'slow');
        }else if(data === "customerEmail") {
            $('#' + data).addClass('error');
            $('#' + data).before("<span id='redError'>Not a valid email.</span>");
            $('body').animate({ scrollTop: $("#redError").offset().top }, 'slow');
        }
    });

}
/*Delete Client and Dogs*/
function deleteClient(id) {
    $.post("deleteCustomer.php", {
        id: id,
    }, function(data) {
        if(data === "Success") {
            location.href='customers.php';
        }else{
            alert('Something went wrong.');
        }
    });

}
/*toggle add a dog form*/
function addDogForm() {

    $('#addDog').toggle();

}
/*toggle editing dogs*/
function showEditDog() {
    $(".editDog").hide();
}
/*Load Dogs*/
function loadDogs(id) {
    if ($("#loadDog").html() === "This client has no dogs.") {
        addDogForm();
    }

    $.post("loadDogs.php", {
        id1: id,
    }, function(data) {

        if(data) {
            $("#loadDog").html(data);
        }else{
            $("#loadDog").html('Something went wrong.');
        }
    });
}
/*Save a new dog*/
function saveDog(id, number) {
  $('#saveDogButton').html('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
    /*Clear Previous Errors*/
    $('#addDogMessage').html("");
    $("span").remove('#redError');
    $('input').removeClass('error');
    $('select').removeClass('error');
    $('textarea').removeClass('error');
    /*Get values from form*/
    var name = $('#addName').val();
    var breed = $('#addBreed').val();
    var color = $('#addColor').val();
    var age = $('#addAge').val();
    var gender = $('#addGender').val();
    var fixed = $('#addFixed').val();
    var weight = $('#addWeight').val();
    var vdate = $('#addVdate').val();
    var vphone = $('#addVphone').val();
    var vname = $('#addVname').val();
    var brand = $('#addBrand').val();
    var often = $('#addOften').val();
    var amount = $('#addAmount').val();
    var message = $('#addMessage').val();

    $.post("dogSave.php", {
        id1: id,
        name1: name,
        breed1: breed,
        color1: color,
        gender1: gender,
        age1: age,
        fixed1: fixed,
        weight1: weight,
        brand1: brand,
        amount1: amount,
        often1: often,
        vdate1: vdate,
        vname1: vname,
        vphone1: vphone,
        message1: message,
    }, function(data) {
        if(data === 'Success') {
            $('#addName').val('');
            $('#addBreed').val('');
            $('#addColor').val('');
            $('#addAge').val('');
            $('#addGender').val('');
            $('#addFixed').val('');
            $('#addWeight').val('');
            $('#addVdate').val('');
            $('#addVphone').val('');
            $('#addVname').val('');
            $('#addBrand').val('');
            $('#addOften').val('');
            $('#addAmount').val('');
            $('#addMessage').val('');
            $('#addDogMessage').html("");
            $("span").remove('#redError');
            $('input').removeClass('error');
            $('select').removeClass('error');
            $('textarea').removeClass('error');
            $("#addDog").hide();
        }else if(data === "Fail"){
            $('#addDogMessage').html("Something went wrong. Please try again.");
        }else if(data === "addVdate") {
            $('#' + data).addClass('error');
            $('body').animate({ scrollTop: $("#" + data).offset() }, 'slow');
            $('#' + data).before("<span id='redError'>Already Expired.</span>");
            $('body').animate({ scrollTop: $("#redError").offset().top }, 'slow');
        }else if(data === "addVphone") {
            $('#' + data).addClass('error');
            $('#' + data).before("<span id='redError'>Please enter a phone number.</span>");
            $('body').animate({ scrollTop: $("#redError").offset().top }, 'slow');
        }else if(data === "addFixed") {
            $('#' + data).addClass('error');
            $('#' + data).before("<span id='redError'>This is a requirement after 8 months.</span>");
            $('body').animate({ scrollTop: $("#redError").offset().top }, 'slow');
        }else if(data === "addName") {
            $('#' + data).addClass('error');
            $('#' + data).before("<span id='redError'>This field is required.</span>");
            $('body').animate({ scrollTop: $("#redError").offset().top }, 'slow');
        }
        $('#saveDogButton').html('Save Dog');
        var url = window.location.href;
        if (url.indexOf("booking") !== -1) {
          liveSearchLoadDogs(id);
          /*Change Prices based on number of dogs*/
            subTotal = calculatePrice(bookingDates.length, numberOfDogs);
            tax = addTax(subTotal);
            addTotal(subTotal, tax);
        }else{
          loadDogs(id);
        }
    });

}
/*update a dogs profile*/
function updateDog(id, number) {
    /*Clear Previous Errors*/
    $('#addDogMessage' + number).html("");
    $("span").remove('#redError');
    $('input').removeClass('error');
    $('select').removeClass('error');
    $('textarea').removeClass('error');
    /*Get values from form*/
    var dogid = $('#dogId' + number).val();
    var name = $('#addName' + number).val();
    var breed = $('#addBreed' + number).val();
    var color = $('#addColor' + number).val();
    var age = $('#addAge' + number).val();
    var gender = $('#addGender' + number).val();
    var fixed = $('#addFixed' + number).val();
    var weight = $('#addWeight' + number).val();
    var vdate = $('#addVdate' + number).val();
    var vphone = $('#addVphone' + number).val();
    var vname = $('#addVname' + number).val();
    var brand = $('#addBrand' + number).val();
    var often = $('#addOften' + number).val();
    var amount = $('#addAmount' + number).val();
    var message = $('#addMessage' + number).val();

    $.post("dogUpdate.php", {
        dogid1: dogid,
        name1: name,
        breed1: breed,
        color1: color,
        gender1: gender,
        age1: age,
        fixed1: fixed,
        weight1: weight,
        brand1: brand,
        amount1: amount,
        often1: often,
        vdate1: vdate,
        vname1: vname,
        vphone1: vphone,
        message1: message,
    }, function(data) {
        if(data === 'Success') {
            $("span").remove('#redError');
            $('input').removeClass('error');
            $('select').removeClass('error');
            $('textarea').removeClass('error');
            alert("Success");
            //bookingloadDogs(id);
            $(".editDog").hide();
        }else if(data === "Fail"){
            $('#addDogMessage' + number).html("Something went wrong. Please try again.");
        }else if(data === "addVdate") {
            $('#' + data).addClass('error');
            $('body').animate({ scrollTop: $("#" + data + number).offset() }, 'slow');
            $('#' + data + number).before("<span id='redError'>Already Expired.</span>");
            $('body').animate({ scrollTop: $("#redError").offset().top }, 'slow');
        }else if(data === "addVphone") {
            $('#' + data + number).addClass('error');
            $('#' + data + number).before("<span id='redError'>Please enter a phone number.</span>");
            $('body').animate({ scrollTop: $("#redError").offset().top }, 'slow');
        }else if(data === "addFixed") {
            $('#' + data + number).addClass('error');
            $('#' + data + number).before("<span id='redError'>This is a requirement after 8 months.</span>");
            $('body').animate({ scrollTop: $("#redError").offset().top }, 'slow');
        }else if(data === "addName") {
            $('#' + data + number).addClass('error');
            $('#' + data + number).before("<span id='redError'>This field is required.</span>");
            $('body').animate({ scrollTop: $("#redError").offset().top }, 'slow');
        }
    });

}
/*Delete a dog*/
function deleteDog(dogid, id) {
    $.post("deleteDog.php", {
        dogid1: dogid,
    }, function(data) {
        if(data === "Success") {
            alert("success");
            loadDogs(id);
        }else{
            alert('Something went wrong.');
        }
    });
}
/*edit a dog*/
function editDog(dogid) {
    $(".editDog").hide();
    $('.edit' + dogid).show();
}
function loadEditDog(dogid) {
    /*Change menu to pet menu*/
   $('.profileMenu').removeClass('profileMenuSelected');
   $('#profilePet').addClass('profileMenuSelected');
    previousProfileSelection = "profilePet";
    /*Display pet section*/
    $(".profileSection").hide();
    $('#profilePetSection').show();
    /*show specific dog*/
    $('.edit' + dogid).show();
    $('body').animate({ scrollTop: $(".edit" + dogid).offset().top }, 'slow');
}
/*load and edit Kennls*/
function editKennels(number) {
    $("#editKennelMessage").html('');
    if (number === 0) {
        $("#editKennelMessage").html('You must have at least one kennel to edit.');
    }else{
        $('#kennelEditBox').toggle();
    }
}
//-----BOOKING-PAGE-FUNCTIONS-----//
/*All Containers*/
/*Reset Defaults*/
function reset() {

}
//load next container//
function switchAccordian(id) {
  id = id.replace("AccordianButton", "");
  if ($('#' + id + "Panel").hasClass('show')) {
    $('#' + id + "Panel").removeClass('show');
    $("#" + id + "AccordianButton > i").removeClass('fa-rotate-180');
  }else{
    $('#' + id + "Panel").addClass('show');
    $("#" + id + "AccordianButton > i").addClass('fa-rotate-180');
  }
}
/*Format date for database*/
function formatDate(date) {
  date = date.split("/");
  newDateFormat = date[2] + '-' + date[0] + '-' + date[1];
  return newDateFormat;
}
/*Format date for database*/
function formatDateReverse(date) {
  date = date.split("-");
  newDateFormat = date[1] + '/' + date[2] + '/' + date[0];
  return newDateFormat;
}
/*Select current container in summary and scroll to current container*/
function whichContainer(nextContainer) {
  /*create array of all containers*/
  var idArray = ["bookingDate", "bookingCustomer", "bookingPet", "bookingCost"];
  //retireve current container id//
  var id = $("#whichContainer").val();

  //Get indexs of current container and next container and compare//
  var index1 = idArray.indexOf(id);
  var index2 = idArray.indexOf(nextContainer);
  if(index2 > index1) {
    $("#whichContainer").val(nextContainer);
    id = nextContainer;
  }
  //remove last selected button in summary//
  $("#bookingSummary button").removeClass('selected');
  //add selection to current container in summary tab//
  $("#" + id + "SummaryButton").addClass('selected');
  $("#" + id + "Panel").addClass('show');
  $("#" + id + "AccordianButton").addClass('fa-rotate-180');
  $('html, body').animate({
    scrollTop: $("#" + id).offset().top - 40
  }, 1000);
}
/*Create Booking Container*/
//calculate easter//
function calculateEaster(year)
{
    var y = year
    var a, b, c, d, e, f, g, h, i, j, k, m, n, p;
    var temp;
    var mon;

    temp = y / 19;
    a = Math.floor( ( temp - Math.floor( temp ) ) * 19 + 0.001 );

    temp = y / 100;
    b = Math.floor( temp );
    c = Math.floor( ( temp - Math.floor( temp ) ) * 100 + 0.001 );

    temp = b / 4;
    d = Math.floor( temp );
    e = Math.floor( ( temp - Math.floor( temp ) ) *4 + 0.001 );
    f = Math.floor( ( ( b + 8 ) / 25 ) + 0.001 );
    g = Math.floor( ( b - f + 1 ) / 3 )

    temp = ( 19 * a + b - d - g + 15 ) / 30;
    h = Math.floor( ( temp - Math.floor( temp ) ) * 30 + 0.001 );

    temp = c / 4;
    i = Math.floor( temp );
    j = Math.floor( ( temp - i ) * 4 + 0.001 );

    temp = ( 32 + 2 * e + 2 * i - h - j ) / 7;
    k = Math.floor( ( temp - Math.floor( temp ) ) * 7 + 0.001 );
    m = Math.floor( ( a + 11 * h + 22 * k ) / 451 );

    temp = ( h + k - 7 * m + 114 ) / 31;
    n = Math.floor( temp );
    p = Math.floor( ( temp - n ) * 31 + 0.001 );

    mon = "-04-";
    if( n == 3 )
        mon = "-03-";

        easter = year + mon + ( ++p + 1);
     return easter;
}
/*Calculate Holidays*/
function holidaysCalculate() {
  var date = new Date();
  var year = date.getFullYear();
  //add New Years//
  holidays.push(year + "-01-01");
  //add family day//
  var thirdMonday;
  for(var i = 1; i <= 7; i++) {
    var date2 = new Date(year, 01, i);
    var day = date2.getDay();
    if(day == 1) {
      thirdMonday = year + "-02-" + (i + 14);
      i = 7;
    }
  }
  holidays.push(thirdMonday);
  /*good friday and easter*/
  var easterMonday = calculateEaster(year);
  var date3 = new Date(easterMonday);
  date3.setDate(date3.getDate() - 2);
  var dd = date3.getDate();
  var mm = date3.getMonth() + 1;
  var goodFriday = year + '-0'+ mm + '-'+ dd;
  holidays.push(goodFriday);
  holidays.push(easterMonday);
  //add victoria day//
  var victoriaDay;
  for (var i = 25; i >= 18; i--) {
    var date3 = new Date(year, 04, i);
    var day = date3.getDay();
    if (day == 1) {
      victoriaDay = year + "-05-" + i;
      i = 18;
    }
  }
  holidays.push(victoriaDay);
  //add canada day//
  holidays.push(year + "-07-01");
  //add labour day//
  var labourDay;
  for(var i = 1; i <= 7; i++) {
    var date2 = new Date(year, 08, i);
    var day = date2.getDay();
    if(day == 1) {
      labourDay = year + "-09-0" + i;
      i = 7;
    }
  }
  holidays.push(labourDay);
  //add thanksgiving//
  var thanksgiving;
  for(var i = 1; i <= 7; i++) {
    var date2 = new Date(year, 09, i);
    var day = date2.getDay();
    if(day == 1) {
      thanksgiving = year + "-10-" + (i + 7);
      i = 7;
    }
  }
  holidays.push(thanksgiving);
  //add christmas//
  holidays.push(year + "-12-25");
  //add boxing day//
  holidays.push(year + "-12-26");
}
/*Add selected date to day care booking, booking page*/
function addDayCareDate() {
  $("#dayCareDateError").html('');
  $('div').removeClass('redHighlight');
  var date = $("#dayCareDateSelect").val();
  date2 = date.split("/");
  if (!$("#" + parseInt(date2[0], 10) + date2[1] + date2[2]).length) {
    storeDayCareDates(date);
    if ($('#editSwitch').val() == 'Off') {
      $("#dayCareSelected").append("<div id=" + parseInt(date2[0], 10) + date2[1] + date2[2] + " class='dayCareDateBox'><span class='dayCareDate'>" + date + "</span><div class='dayCareDateBoxTools'><a onclick='editDayCareDate(" + parseInt(date2[0], 10) + date2[1] + date2[2] + ");'>edit</a> | "+
      "<a onclick='deleteDayCareDate(" + parseInt(date2[0], 10) + date2[1] + date2[2] + ");'>delete</a></div></div>");
    }else{
      var id = $('#editId').val();
      var dateArrayRemove = $("#" + id + "> .dayCareDate").html();
      dateArrayRemove = dateArrayRemove.split("/");
      newDateFormat = dateArrayRemove[2] + '-' + dateArrayRemove[0] + '-' + dateArrayRemove[1];
      var holidayResult = holidayCheck(newDateFormat);
      var weekendResult = weekendCheck(dateArrayRemove[1], dateArrayRemove[0], dateArrayRemove[2]);
      if(weekendResult == 0 || weekendResult == 6) {
        var index = datesWeekend.indexOf(newDateFormat);
        datesWeekend.splice(index, 1);
      }
      /*Check if date was a holiday*/
      if (holidayResult != -1) {
        //if it is remove from array//
        var index = holidayDates.indexOf(newDateFormat);
        holidayDates.splice(index, 1);
      }
      var index = bookingDates.indexOf(newDateFormat);
      bookingDates.splice(index, 1);
      $("#" + id).removeClass('editOn');
      $("#" + id).html("<span class='dayCareDate'>" + date + "</span><div class='dayCareDateBoxTools'><a onclick='editDayCareDate(" + parseInt(date2[0], 10) + date2[1] + date2[2] + ");'>edit</a> | "+
      "<a onclick='deleteDayCareDate(" + parseInt(date2[0], 10) + date2[1] + date2[2] + ");'>delete</a></div>");
      $("#" + id).attr('id', parseInt(date2[0], 10) + date2[1] + date2[2]);
      $('#editSwitch').val('Off');
    }
  }else{
    $("#dayCareDateError").html('This date has already been added.');
    $("#" + parseInt(date2[0], 10) + date2[1] + date2[2]).addClass('redHighlight');
  }

}
/*Delete Day Care Booking Selection*/
function deleteDayCareDate(id) {
  var date = $("#" + id + "> .dayCareDate").html();
  date = date.split("/");
  newDateFormat = date[2] + '-' + date[0] + '-' + date[1];
  var holidayResult = holidayCheck(newDateFormat);
  var weekendResult = weekendCheck(date[1], date[0], date[2]);
  if(weekendResult == 0 || weekendResult == 6) {
    var index = datesWeekend.indexOf(newDateFormat);
    datesWeekend.splice(index, 1);
  }
  /*Check if date was a holiday*/
  if (holidayResult != -1) {
    //if it is remove from array//
    var index = holidayDates.indexOf(newDateFormat);
    holidayDates.splice(index, 1);
  }
  var index = bookingDates.indexOf(newDateFormat);
  bookingDates.splice(index, 1);
  $("#" + id).remove();
}
/*Edit Day Care Booking Selection*/
function editDayCareDate(id) {
  $("#" + id).addClass('editOn');
  $('#editSwitch').val('On');
  $('#editId').val(id);

}
/*Save or remove selected kennel from an array*/
function storeORremoveKennel(number) {
  if ($("#" + number + "progress").hasClass('ui-progressbar-indeterminate') || $("#" + number + "progress").hasClass('myBooking')) {
    kennelsSelected.push(number);
  }else{
    var index = kennelsSelected.indexOf(number);
    kennelsSelected.splice(index, 1);
  }
}
/*Book kennel buttons*/
function buttonBook(id) {
    id = id.replace('button', '');
    $("#" + id + "progress").click();
}
/*Check For Double Booking*/
function checkForBooking(checkIn, checkOut) {
  $("#bookKennelsBox > .overlay").show();
  var height = $("#bookingKennels").height();
  var halfHeight = height / 2;
  $("#bookKennelsBox > .overlay > .fa-spinner").css({"margin-top":halfHeight + "px",});
  $("#bookKennelsBox > .overlay").height(height);
  checkIn = formatDate(checkIn);
  checkOut = formatDate(checkOut);
  var totalKennels = $('#totalKennel').val();
  $.post("checkForBooking.php", {
      checkIn1: checkIn,
      checkOut1: checkOut,
      totalKennels1: totalKennels,
  }, function(data) {
      if(data) {
        data = data.split("@");
        var doubleBookedFront = data[0].split('&');
        var doubleBookedBack = data[1].split('&');
        var booked = data[2].split("&");
        /*Show double front bookings*/
        for (var i = 0; i < doubleBookedFront.length; i++) {
          $('#' + doubleBookedFront[i] + "progress").find(".ui-progressbar-value").removeClass('available');
          $('#' + doubleBookedFront[i] + "progress").progressbar( "option", "value", 50 );
          $('#' + doubleBookedFront[i] + "progress").find(".ui-progressbar-value").addClass('doubleBooking');
          $('#' + doubleBookedBack[i] + "progress").find(".ui-progressbar-value").css({
            float:"left",
          });
        }
        /*Show double back bookings*/
        for (var i = 0; i < doubleBookedBack.length; i++) {
          $('#' + doubleBookedBack[i] + "progress").find(".ui-progressbar-value").removeClass('available');
          $('#' + doubleBookedBack[i] + "progress").progressbar( "option", "value", 50 );
          $('#' + doubleBookedBack[i] + "progress").find(".ui-progressbar-value").addClass('doubleBooking');
          $('#' + doubleBookedBack[i] + "progress").find(".ui-progressbar-value").css({
            float:"right",
          });
        }
        /*remove kennels that are already booked*/
        for (var i = 0; i < booked.length; i++) {
            $("#" + booked[i] + "KennelRow").hide();
            if ($("#" + booked[i] + "KennelRow").hasClass("editNow")) {
              buttonBook(booked[i] + "button");
            }
        }
        $("#bookKennelsBox > .overlay").hide();
      }
  });
}
/*select and deselect loaded kennels at will*/
function atWillKennelSelect() {
  $(".editNow").each(function () {
    var id = $(this).attr('id');
    id = id.replace("KennelRow", "");
    buttonBook(id + "button");
  });
}
/*Add meet n greet appointment*/
function addMeetGreetDate() {
  $("#meetGreetDateError").html('');
  $('div').removeClass('redHighlight');
  var date = $("#meetGreetDateSelect").val();
  date2 = date.split("/");
  if (!$("#" + parseInt(date2[0], 10) + date2[1] + date2[2]).length) {
    storeMeetGreetDates(date);
    if ($('#meetSwitch').val() == 'Off') {
      //loadDatesForDogs();//
      $("#meetGreetSelected").append("<div id=" + parseInt(date2[0], 10) + date2[1] + date2[2] + " class='dayCareDateBox'><span class='meetGreetDate'>" + date + "</span><div class='dayCareDateBoxTools'><a onclick='editMeetGreetDate(" + parseInt(date2[0], 10) + date2[1] + date2[2] + ");'>edit</a> | "+
      "<a onclick='deleteMeetGreetDate(" + parseInt(date2[0], 10) + date2[1] + date2[2] + ");'>delete</a></div></div>");
    }else{
      var id = $('#meetEditId').val();
      var dateArrayRemove = $("#" + id + "> .meetGreetDate").html();
      dateArrayRemove = dateArrayRemove.split("/");
      newDateFormat = dateArrayRemove[2] + '-' + dateArrayRemove[0] + '-' + dateArrayRemove[1];
      var index = meetGreetDates.indexOf(newDateFormat);
      meetGreetDates.splice(index, 1);
      loadDatesForDogs();
      $("#" + id).removeClass('editOn');
      $("#" + id).html("<span class='meetGreetDate'>" + date + "</span><div class='dayCareDateBoxTools'><a onclick='editMeetGreetDate(" + parseInt(date2[0], 10) + date2[1] + date2[2] + ");'>edit</a> | "+
      "<a onclick='deleteMeetGreetDate(" + parseInt(date2[0], 10) + date2[1] + date2[2] + ");'>delete</a></div>");
      $("#" + id).attr('id', parseInt(date2[0], 10) + date2[1] + date2[2]);
      $('#meetSwitch').val('Off');
    }
  }else{
    $("#meetGreetDateError").html('This date has already been added.');
    $("#" + parseInt(date2[0], 10) + date2[1] + date2[2]).addClass('redHighlight');
  }
}
/*Delete Day Care Booking Selection*/
function deleteMeetGreetDate(id) {
  var date = $("#" + id + "> .meetGreetDate").html();
  date = date.split("/");
  newDateFormat = date[2] + '-' + date[0] + '-' + date[1];
  var index = meetGreetDates.indexOf(newDateFormat);
  meetGreetDates.splice(index, 1);
  $("#" + id).remove();
  loadDatesForDogs();
}
/*Edit Day Care Booking Selection*/
function editMeetGreetDate(id) {
  $("#" + id).addClass('editOn');
  $('#meetSwitch').val('On');
  $('#meetEditId').val(id);

}
/*Load kennels booking Page*/
function loadService() {
    var service = $("#bookingService").val();
    checkIn  = $('#nightCheckIn').val();
    checkOut = $('#nightCheckOut').val();
    if(service === "Day Care") {
      $('#bookingKennelSummaryButton').hide();
      $("#meetGreetSelected").hide();
      $("#overNightSelected").hide();
      $("#dayCareSelected").show();
    }else if (service === "Over Night" && checkOut != "") {
      if (formatDate(checkOut) <= formatDate(checkIn)) {
        $('#nightCheckOut').val("");
        alert("You must select a date after the check in date.");
        $('#bookKennelsBox').hide();
      }else{
        storeOverNightDates();
        $(".kennelBookingRow").show();
        $('#bookingKennelSummaryButton').show();
        $(".progressbar").removeClass('myBooking');
        $(".progressbar").find(".ui-progressbar-value").removeClass('myBooking');
        $(".progressbar").find(".ui-progressbar-value").removeClass('doubleBooking');
        $(".progressbar").find(".ui-progressbar-value").css({float:"none",});
        $(".progressbar").find(".ui-progressbar-value").addClass('available');
        $(".progressbar").progressbar( "option", "value", 100 );
        $('#bookKennelsBox').show();
        $("#meetGreetSelected").hide();
        $("#dayCareSelected").hide();
        $("#overNightSelected").show();
        checkForBooking(checkIn, checkOut);
        $("#bookingSummary button").removeClass('selected');
        $("#bookingDateSummaryButton").hide();
        $('#bookingDateSummary').html("<button class='dateSummaryDate'><i class='fa fa-calendar' aria-hidden='true'></i> " + checkIn + " - " + checkOut +
        "<i class='fa fa-check floatRight checkmark' aria-hidden='true'></i></button>");
        $("#bookingKennelSummaryButton > button").addClass('selected');
      }
    }else if(service === "Over Night") {
      $('#bookingKennelSummaryButton').show();
      $("#meetGreetSelected").hide();
      $("#dayCareSelected").hide();
      $("#overNightSelected").show();
    }else if (service === "Meet N'Greet"){
      $('#bookingKennelSummaryButton').hide();
      $("#dayCareSelected").hide();
      $("#overNightSelected").hide();
      $("#meetGreetSelected").show();
    }
    typeOfBooking = service;
}
/*Check if date is a weekend*/
function weekendCheck(day, month, year) {
  var d = new Date(year, (parseInt(month) - 1), day);
  var n = d.getDay();
  return n;
}
function holidayCheck(date) {
  var result = holidays.indexOf(date);
  return result;
}
/*Get booking Dates if day care*/
function storeDayCareDates(date) {
  date = date.split("/");
  newDateFormat = date[2] + '-' + date[0] + '-' + date[1];
  //check if date is a holiday//
  var holidayCheckResult = holidayCheck(newDateFormat);
  if (holidayCheckResult != -1) {
    holidayDates.push(newDateFormat);
  }
  //check if it is a weekend date//
  var weekendResult = weekendCheck(date[1], date[0], date[2]);
  if (weekendResult == 0 || weekendResult == 6) {
    datesWeekend.push(newDateFormat);
    bookingDates.push(newDateFormat);
  }else{
    bookingDates.push(newDateFormat);
  }
}
/*Get booking Dates if meet n'greet*/
function storeMeetGreetDates(date) {
  date = date.split("/");
  newDateFormat = date[2] + '-' + date[0] + '-' + date[1];
  meetGreetDates.push(newDateFormat);
}
/*Store OverNight Dates*/
function storeOverNightDates() {
  /*Clear arrays*/
  bookingDates = [];
  holidayDates = [];
  datesWeekend = [];

  date = checkIn.split("/");
  newDateFormat = date[2] + '-' + date[0] + '-' + date[1];
  var firstDate = new Date(date[2], date[0] - 1, date[1]);
  date = checkOut.split("/");
  newDateFormat = date[2] + '-' + date[0] + '-' + date[1];
  var secondDate = new Date(date[2], date[0] - 1, date[1]);
  var oneDay = 24*60*60*1000; // hours*minutes*seconds*milliseconds
  var diffDays = Math.round(Math.abs((firstDate.getTime() - secondDate.getTime())/(oneDay)));
  while (firstDate < secondDate) {
      var monthZero = firstDate.getMonth();
      var monthZeroPlusOne = firstDate.getMonth() + 1;
      var dayZero = firstDate.getDate();
      if (firstDate.getMonth() <= 9) {
        monthZero = "0" + monthZero;
      }
      if (monthZeroPlusOne <= 9) {
        monthZeroPlusOne = "0" + monthZeroPlusOne;
      }
      if(firstDate.getDate()<= 9) {
        dayZero = "0" + dayZero;
      }
      var nextDate = firstDate.getFullYear() + "-" + monthZeroPlusOne + "-" + dayZero;
      console.log(nextDate);
      bookingDates.push(nextDate);
      var holidayCheckResult = holidayCheck(firstDate.getFullYear() + "-" + monthZeroPlusOne + "-" + dayZero);
      if (holidayCheckResult != -1) {
        holidayDates.push(nextDate);
      }
      //check if it is a weekend date//
      var weekendResult = weekendCheck(dayZero, monthZeroPlusOne, firstDate.getFullYear());
      if (weekendResult == 0 || weekendResult == 6) {
        datesWeekend.push(nextDate);
      }
      firstDate.setDate(firstDate.getDate() + 1);
  }
}
/*Click next buttons on booking page*/
function bookingNext(buttonId) {
    var check = bookingPart1Check();
    if (check == true){
      $("#dateSummaryButton").hide();
      if(typeOfBooking == "Day Care") {
        $('#bookingDateSummary').html("");
        for(var i = 0; i <= bookingDates.length - 1; i++) {
          $('#bookingDateSummary').append("<button class='dateSummaryDate'><i class='fa fa-calendar' aria-hidden='true'></i> " + bookingDates[i] + " <i class='fa fa-check floatRight checkmark' aria-hidden='true'></i></button>");
        }
        subTotal = calculatePrice(bookingDates.length, numberOfDogs);
        tax = addTax(subTotal);
        addTotal(subTotal, tax);
        if (deposit != 0) {
          changeDeposit(deposit);
        }
      }else if (typeOfBooking == "Meet N'Greet") {
        $('#bookingDateSummary').html("");
        for(var i = 0; i <= meetGreetDates.length - 1; i++) {
          $('#bookingDateSummary').append("<button class='dateSummaryDate'><i class='fa fa-calendar' aria-hidden='true'></i> " + meetGreetDates[i] + " <i class='fa fa-check floatRight checkmark' aria-hidden='true'></i></button>");
        }
        var price = 0.00;
        var totalNoTax = price;
        var tax = $('#taxPrice').val();
        taxPrice = (tax / 100) * totalNoTax;
        $("#invoiceList").html("<div class='floatLeft'>Meet N'Greet x " + meetGreetDates.length + "</div><div class='floatRight'>$" + totalNoTax.toFixed(2) + "</div><div class='clear'></div>");
        $("#insertTax").html('<p class="floatRight">$' + taxPrice.toFixed(2) + '</p>');
      }else if (typeOfBooking == "Over Night"){
        $("#bookingKennelSummaryButton button").html("<i class='fa fa-square' aria-hidden='true'></i> Kennel(s) " + kennelsSelected);
        subTotal = calculatePrice(bookingDates.length, numberOfDogs);
        tax = addTax(subTotal);
        addTotal(subTotal, tax);
        if (deposit != 0) {
          changeDeposit(deposit);
        }
      }
      //reload kennels or dates for selecting in dog container, only if dogs are already loaded//
      if (dogsId[0] != "" && typeOfBooking == "Day Care" && bookingid == "") {
        loadDatesForDogs();
      }else if(dogsId[0] != "" && typeOfBooking =="Over Night") {
        loadKennelsForDogs();
      }
      $("#whichContainer").val('bookingCustomer');
      whichContainer("bookingCustomer");
    }
  }
/*Split Booking Container*/
/*Reposition Split Booking Calendar and Bookings*/
function splitRePosition() {
  if ($(".overlay").hasClass("overlayHide")) {
    $(".overlay").removeClass("overlayHide");
    $("#splitBookingCalendar").css({"left":"0",});
  }else{
    $("#splitBookingCalendar").css({"left":"-9999px",});
    $(".overlay").addClass("overlayHide");
  }
}
/*Check if date on split calendar has been selected*/
function splitDateSelected(id) {
  var validate;
  var element = $("#" + id);
  if (element.hasClass('splitDateSelected')) {
    element.removeClass('splitDateSelected');
    var index = selectedSplitDates.indexOf(id);
    selectedSplitDates.splice(index, 1);
  }else if(element.hasClass('booked') && !element.hasClass('end') && !element.hasClass('start')){
    alert('You cannot book a date that is already filled.');
  }else{
    element.addClass('splitDateSelected');
    selectedSplitDates.push(id);
    validate = splitDateValidate();
  }

  /*Apply Validation*/
  if(validate == false) {
    element.removeClass('splitDateSelected');
    var index = selectedSplitDates.indexOf(id);
    selectedSplitDates.splice(index, 1);
    alert("You must select a consecutive day. Please Try Again.");
  }
}
/*Validate Split Date Selections*/
function splitDateValidate() {
  if(selectedSplitDates.length > 1) {
      var length = selectedSplitDates.length;
      /*Get the last date & current date and format them*/
      var firstID = selectedSplitDates[length - 2];
      firstID = firstID.split("-");
      firstDate = firstID[1] + "/" + firstID[2] + "/" + firstID[3];

      var secondID = selectedSplitDates[length - 1];
      secondID = secondID.split("-");
      secondDate = secondID[1] + "/" + secondID[2] + "/" + secondID[3];

      var date1 = new Date(firstDate);
      var date2 = new Date(secondDate);
      var timeDiff = date2.getTime() - date1.getTime();
      var diffDays = Math.ceil(timeDiff / (1000 * 3600 * 24));
      if(diffDays === 1) {
        return true;
      }else{
        return false;
      }
  }

}
/*Save selected split dates*/
function saveSplitDates() {

  /*empty needed arrays*/
  kennelsSelected = [];
  splitDatesForSaving =[];
  var count = 0;
  var checkingKennel;
  var kennel;
  var lastdate;
  /*check to see that there are dates selected*/
  if (selectedSplitDates.length <= 1) {
    alert('You must have at least 2 dates selected.');
  }else{
    $(".splitBookingRow").remove();
    $("#splitBookingBox hr").remove();
    splitRePosition();
    /*Now Hide the kennels legend and table*/
    $("#overNightSelected .bookingColumn").hide();
    $("#legend").hide();
    $("#bookingKennels").hide();
    /*Show Split Booking Box*/
    $("#splitBookingBox").show();
    $("#splitBookingButton").html('<i class="fa fa-pencil" aria-hidden="true"></i> Edit Split Booking')
    /*Loop through selected dates and seperate kennel from date*/
    for(var i = 0; i <= selectedSplitDates.length; i++) {
      if (i < selectedSplitDates.length){
        var split = selectedSplitDates[i].split("-");
        kennel = split[0].replace("K", "");
      }else{
        kennel="";
      }
      if(checkingKennel != kennel){
        if(i != 0) {
          $("#splitKennel" + count).append(" to " + lastdate[2] + "/" + lastdate[3] + "/" + lastdate[1]);
          splitDatesForSaving.push(lastdate[2] + "/" + lastdate[3] + "/" + lastdate[1]);
        }
        if (i < selectedSplitDates.length){
          count++;
          checkingKennel = kennel;
          $("#splitBookingBox").append("<div id='splitKennel" + count + "' class='splitBookingRow'>Kennel " + kennel + "<br>From: " + split[2] + "/" + split[3] + "/" + split[1] + "</div><hr>");
          splitDatesForSaving.push(split[2] + "/" + split[3] + "/" + split[1]);
          kennelsSelected.push(kennel);
        }
      }
      //set date for checkout from a kennel//
      if (i < selectedSplitDates.length){
        lastdate = selectedSplitDates[i].split("-");
      }
      /*set checkIn Date*/
      if(i == 0) {
        checkIn = split[2] + "/" + split[3] + "/" + split[1];
      }else if(i == selectedSplitDates.length - 1) {
        checkOut = split[2] + "/" + split[3] + "/" + split[1];
      }
    }
    /*display new dates*/
    $('#bookingDateSummary').html("<button class='dateSummaryDate'><i class='fa fa-calendar' aria-hidden='true'></i> " + checkIn + " - " + checkOut +
    "<i class='fa fa-check floatRight checkmark' aria-hidden='true'></i></button>");
    storeOverNightDates();
    splitBooking = true;
  }
}

/*Client Container*/
/*Live search for client*/
function liveSearch() {
  var search = $('#clientLiveSearch').val();
  if(search === " " || search === "%" || search === "" || search.length <= 3) {
    $('#liveResult').hide();
  }else{
    var width = $('#clientLiveSearch').width();
    $('#liveResult').width(width - 7.5);
    $.ajax({
          method: "POST",
          url: "getResult.php",
          data: { search1: search},
      }) .done(function( data ) {
        if(data) {
          $('#liveResult').show();
          $('#liveResult').html(data);
        }else{
          $('#liveResult').hide();
        }
      })
    }
}
function liveSearchReset() {
  customerType = "New";
  $('#clientLiveSearch').val("");
  $('#liveResult').hide();
  for(var i = 0; i <= 13; i++) {
    $("#bookingClient" + i).val("");
  }
  $("#bookingLoadDogs").html("");
}
function liveSearchLoadClient(id, callback) {
  customerType = "Old";
  $('#liveResult').hide();
    $.ajax({
        method: "POST",
        url: "getClient.php",
        data: { id1: id},
    }) .done(function( data ) {
      if(data) {
        clientId = id;
        data = data.split("&");
        clientName = data[0] + " " + data[1];
        if (bookingid != "") {
          callback();
        }
        for(var i = 0; i <= data.length; i++) {
          $("#bookingClient" + i).val(data[i]);
        }
        customerId = id;
      }
    })
}
function liveSearchLoadDogs(id, callback) {
    dogsId = [];
    $.ajax({
        method: "POST",
        url: "getDogs.php",
        data: { id1: id},
    }) .done(function( data ) {
      $("#userId").val(id);
      if(data) {
        $('#addDog').hide();
        $('#bookingLoadDogs').html(data);
        numberOfDogs = $("#dogCount").val() - 1;
        /*Store dog ids for later booking*/
        for(i = 1; i <= numberOfDogs; i++) {
          dogsId.push($("#dogId" + i).val());
        }
        if (typeOfBooking == "Day Care") {
          loadDatesForDogs();
        }else if(typeOfBooking =="Over Night") {
          loadKennelsForDogs();
        }
      }else{
        numberOfDogs = 0;
        $('#addDog').show();
      }
      if (bookingid != "") {
        callback();
      }
    });

}
function bookingClientNext() {
  if ($("#bookingClient0").val() == "") {
    alert("Please select a client.");
  }else{
    $('#clientNext').html('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
    var clientInfo = [""];
    $('#customerMessage').html("");
    $("span").remove('#redError');
    $('input').removeClass('error');
    $('select').removeClass('error');
    $('textarea').removeClass('error');
    for(var i = 0; i <= 13; i++) {
      clientInfo[i] = $("#bookingClient" + i).val();
    }
    $.ajax({
        method: "POST",
        url: "saveCustomerBooking.php",
        data: { clientInfo1: clientInfo, customerType1: customerType, customerId1:customerId},
    }) .done(function( data ) {
      $('#clientNext').html("Next");
      if(data === "Fail"){
          $('#customerMessage').html("Something went wrong. Please try again.");
      }else if(data === "customerFirst") {
          $('#bookingClient0').addClass('error');
          $('html, body').animate({ scrollTop: $("#bookingClient0").offset().top - 25 }, 'slow');
          $("#bookingClient0").before("<span id='redError'>This field is required.</span>");
      }else if(data === "customerLast") {
          $("#bookingClient1").addClass('error');
          $("#bookingClient1").before("<span id='redError'>This field is required.</span>");
          $('html, body').animate({ scrollTop: $("#bookingClient1").offset().top - 25 }, 'slow');
      }else if(data === "customerPhone") {
          $("#bookingClient3").addClass('error');
          $("#bookingClient3").before("<span id='redError'>Please remove any spaces or dashes.</span>");
          $('body').animate({ scrollTop: $("#bookingClient3").offset().top - 25 }, 'slow');
      }else if(data === "customerEphone") {
          $("#bookingClient12").addClass('error');
          $("#bookingClient12").before("<span id='redError'>Please remove any spaces or dashes.</span>");
          $('body').animate({ scrollTop: $("#bookingClient12").offset().top - 25 }, 'slow');
      }else if(data === "customerMobile") {
          $("#bookingClient4").addClass('error');
          $("#bookingClient4").before("<span id='redError'>Please remove any spaces or dashes.</span>");
          $('body').animate({ scrollTop: $("#bookingClient4").offset().top - 25 }, 'slow');
      }else if(data === "customerWork") {
          $("#bookingClient5").addClass('error');
          $("#bookingClient5").before("<span id='redError'>Please remove any spaces or dashes.</span>");
          $('body').animate({ scrollTop: $("#bookingClient5").offset().top - 25 }, 'slow');
      }else if(data === "customerEmail") {
          $("#bookingClient2").addClass('error');
          $("#bookingClient2").before("<span id='redError'>Not a valid email.</span>");
          $('body').animate({ scrollTop: $("#bookingClient2").offset().top - 25 }, 'slow');
      }else{
        /*Clear all form errors*/
        $('#customerMessage').html("");
        $("span").remove('#redError');
        $('input').removeClass('error');
        $('select').removeClass('error');
        $('textarea').removeClass('error');
        /*Signify changes were successful*/
        alert("Saved");
        customerId = data;
        /*Update dogs*/
        if ( customerType == "New") {
            liveSearchLoadDogs(customerId);
          /*Change need variables*/
          customerType = "Old";
        }
        /*Change Prices based on number of dogs*/
        subTotal = calculatePrice(bookingDates.length, numberOfDogs);
        tax = addTax(subTotal);
        addTotal(subTotal, tax);
        var customerName = $("#bookingClient0").val() + " " + $("#bookingClient1").val();
        $("#bookingCustomerSummaryButton").html("<i class='fa fa-users' aria-hidden='true'></i> " + customerName);
        whichContainer("bookingPet");
      }
    })
  }
}
/*Dogs Container*/
/*Load Dates in to be Selected For Dogs*/
function loadDatesForDogs() {
  var countDays = bookingDates.length;
  if(typeOfBooking == 'Day Care') {
    if (numberOfDogs < 1 || countDays < 0) {
      $('.bookingChoice').hide();
      $('.bookingChoiceLabel').hide();
    }else{
      $('.bookingChoice').show();
      $('.bookingChoiceLabel').show();
      $(".bookingChoiceLabel").hide();
      for (var i = 0; i <= numberOfDogs - 1; i++) {
        var bookingChoice = '#bookingChoice' + (i + 1);
        $(bookingChoice + " option").remove();
        $(bookingChoice).attr('multiple','multiple');
        $(bookingChoice).hide();
        $(bookingChoice).attr('data-placeholder','Choose your dates ...');
        $(bookingChoice).attr('tabindex','2');
        $(bookingChoice).addClass("chosen-select");
        $('#dateSelectDog' + (i + 1)).removeClass("floatRight");
        if (bookingid != "") {
          var tempDate = dayCareDates[i];
          if (tempDate == undefined) {
            //Do nothing//
          }else{
            tempDate = tempDate.replace(/\"/g, "");
            tempDate = tempDate.replace(/[\[\]']+/g, "");
            tempDate = tempDate.split("*");
            for(var j = 0; j <= countDays-1; j++) {
              $(bookingChoice).append("<option name='date' value='" + bookingDates[j] + "'>" + bookingDates[j] + "</option>");
            }
            for(var k = 0; k <= tempDate.length - 2; k++) {
              $(bookingChoice + " option[value=" + tempDate[k] + "]").attr("selected", true);
            }
          }
        }else{
          for(var j = 0; j <= countDays-1; j++) {
            $(bookingChoice).append("<option name='date' value='" + bookingDates[j] + "' selected>" + bookingDates[j] + "</option>");
          }
        }
        $(bookingChoice).trigger("chosen:updated");
        $(bookingChoice).chosen({width: "100%"});
      }
    }
  }else if(typeOfBooking == 'Meet N\'Greet') {

  }
}
function loadKennelsForDogs() {
  var count = $('#dogCount').val();
  var countKennels = kennelsSelected.length;
  $('.bookingChoice').show();
  $('.bookingChoiceLabel').show();
    for (var i = 0; i <= count - 2; i++) {
      loadKennelSelect(i + 1);
    }
}
function loadKennelSelect(number) {
  var count = kennelsSelected.length;
  $("#bookingChoice" + number).html("<option name='kennel' value='None'>None</option>");
  if (splitBooking == false){
    for(var i = 0; i <= count-1; i++) {
      $("#bookingChoice" + number).append("<option name='kennel' value='" + kennelsSelected[i] + "'>Kennel " + kennelsSelected[i] + "</option>");
    }
  }else{
    $("#bookingChoice" + number).append("<option name='kennel' value='Set'>Kennel Set</option>");
  }
}
/*Show pop up based on button clicked for selecting dates*/
function clickSelectDatesButton(buttonId) {
  /*Show only the one popup*/
  buttonId = buttonId.replace('bookingChoice', '');
  $("#bookingChoicePopUp" + buttonId).toggle();
}
/*Get number of dates selected for dogs and there values*/
function retrieveCheckListDates(number) {
  var checkedValues = [];
  $("#dateSelectDog" + number).find(".search-choice").each(function(){
    checkedValues.push($(this).text());
  });
  return checkedValues;
}
/*Display Kennel Set*/
function dogsKennelSet() {
  var price = $("#overNightPrice").val();
  var additionalPrice = $("#overNightAdditionalPrice").val();
  var weekendPrice = $("#weekendPrice").val();
  var weekendActive = $("#weekendActive").val();
  var holidayPrice = $("#holidayPrice").val();
  var holidayActive = $("#holidayActive").val();
  var weekendPriceTotal;
  var holidayPriceTotal;
  var additionalPriceTotal;
  var priceTotal;
  var subTotalPrice = 0;
  $("#invoiceList").html("");
  /*Set the price for the weekend days*/
  if (weekendActive == 1) {
    weekendPriceTotal = parseInt(weekendPrice, 10) * datesWeekend.length;
  }
  /*Set the price for the holiday days*/
  if(holidayActive == 1) {
    holidayPriceTotal = holidayPrice * holidayDates.length;
  }
  /*See what kennels were used*/
  for (var i = 0; i <= numberOfDogs - 1; i++) {
    var kennel = $("#dateSelectDog" + (i+1) + " input[type=radio]:checked").val();
    var id = "kennelContainer" + kennel;
    if ($("#" + id).length == 0){
      priceTotal = price * bookingDates.length + weekendPriceTotal + holidayPriceTotal;
      subTotalPrice = subTotalPrice + priceTotal;
      $("#invoiceList").append("<div id='" + id + "'>Kennel " + kennel + "<div class='clear'></div></div><hr>");
      $("#" + id).append("<div class='floatLeft'>Dog " + (i+1) + " " + typeOfBooking + " x " + bookingDates.length + "</div><div class='floatRight'>$" + priceTotal.toFixed(2) + "</div><div class='clear'></div>");
    }else{
      //additional dogs are another price//
      additionalPriceTotal = additionalPrice * bookingDates.length + weekendPriceTotal + holidayPriceTotal;
      subTotalPrice = subTotalPrice + additionalPriceTotal;
      $("#" + id).append("<div class='floatLeft'>Dog " + (i+1) + " " + typeOfBooking + " x " + bookingDates.length + "</div><div class='floatRight'>$" + additionalPriceTotal.toFixed(2) + "</div><div class='clear'></div>");
    }
  }
  subTotal = subTotalPrice;
  $("#insertSubTotal").html("$" + subTotalPrice.toFixed(2));
  var tax = addTax(subTotalPrice);
  addTotal(subTotalPrice, tax)
  /*Add dogs name to summary box*/
  var dogsBookingNames = "";
  for (var i = 1; i <= numberOfDogs; i++) {
    dogsBookingNames = dogsBookingNames + $("#dogName" + i).html() + ", ";
  }
  $("#bookingPetSummaryButton").html("<i class='fa fa-paw' aria-hidden='true'></i> " + dogsBookingNames);
  whichContainer("bookingCost");
}
/*Load Prices for dog next*/
function loadDogsDayCarePrice() {
  var result;
  //check how many days for each dog & save those dates//
  var dayCarePrice = $("#dayCarePrice").val();
  var dayCareAdditionalPrice = $("#dayCareAdditionalPrice").val();
  var weekendPrice = $("#weekendPrice").val();
  var weekendActive = $("#weekendActive").val();
  var holidayPrice = $("#holidayPrice").val();
  var holidayActive = $("#holidayActive").val();
  var subTotalPrice = 0;
  for (var i = 1; i <= numberOfDogs; i++) {
    result = retrieveCheckListDates(i);
    var selectedNumberOfDays = result.length;
    var selectedNumberOfWeekendDays = 0;
    var selectedNumberOfHolidays = 0;
    //if weekend rates are active do this for loop//
    if (weekendActive == 1) {
      //go through selected dates and check to see if its a weekend//
      for(var j= 0; j <= selectedNumberOfDays - 1; j++) {
        var index = result[j];
        var weekendDay = datesWeekend.indexOf(index);
        if(weekendDay != -1){
          selectedNumberOfWeekendDays++;
        }
      }
      var dayCareWeekendPriceTotal = parseInt(weekendPrice, 10) * selectedNumberOfWeekendDays;
    }

    //check if holiday rates are active//
    if(holidayActive == 1) {
      //go through selected dates and check to see if its a holiday/
      for(var j= 0; j <= selectedNumberOfDays - 1; j++) {
        var index = result[j];
        var holidayDay = holidayDates.indexOf(index);
        if(holidayDay != -1){
          selectedNumberOfHolidays++;
        }
      }
      var holidayPriceTotal = holidayPrice * selectedNumberOfHolidays;
    }
    //first dog is normal price//
    if (i == 1) {
      var dayCarePriceTotal = dayCarePrice * selectedNumberOfDays + dayCareWeekendPriceTotal + holidayPriceTotal;
      subTotalPrice = subTotalPrice + dayCarePriceTotal;
      $("#priceList" + i).html("<div class='floatLeft'>Dog " + i + " Daycare x " + selectedNumberOfDays + "</div><div class='floatRight'>$" + dayCarePriceTotal.toFixed(2) + "</div>");
    }else{
      //additional dogs are another price//
      var dayCareAdditionalPriceTotal = dayCareAdditionalPrice * selectedNumberOfDays + dayCareWeekendPriceTotal + holidayPriceTotal;
      subTotalPrice = subTotalPrice + dayCareAdditionalPriceTotal;
      $("#priceList" + i).html("<div class='floatLeft'>Dog " + i + " Daycare x " + selectedNumberOfDays + "</div><div class='floatRight'>$" + dayCareAdditionalPriceTotal.toFixed(2) + "</div>");
    }
  }
  subTotalPrice = subTotalPrice + reloadOtherCharges(subTotalPrice);
  subTotal = subTotalPrice;
  $("#insertSubTotal").html("$" + subTotalPrice.toFixed(2));
  var tax = addTax(subTotalPrice);
  addTotal(subTotalPrice, tax)
  if(deposit != 0) {
    changeDeposit(deposit);
  }
  /*Add dogs name to summary box*/
  var dogsBookingNames = "";
  for (var i = 1; i <= numberOfDogs; i++) {
    dogsBookingNames = dogsBookingNames + $("#dogName" + i).html() + ", ";
  }
  $("#bookingPetSummaryButton").html("<i class='fa fa-paw' aria-hidden='true'></i> " + dogsBookingNames);
}
function loadDogsOverNightPrice() {
  var price = $("#overNightPrice").val();
  var additionalPrice = $("#overNightAdditionalPrice").val();
  var weekendPrice = $("#weekendPrice").val();
  var weekendActive = $("#weekendActive").val();
  var holidayPrice = $("#holidayPrice").val();
  var holidayActive = $("#holidayActive").val();
  var weekendPriceTotal;
  var holidayPriceTotal;
  var additionalPriceTotal;
  var priceTotal;
  var subTotalPrice = 0;
  $("#invoiceList").html("");
  /*Set the price for the weekend days*/
  if (weekendActive == 1) {
    weekendPriceTotal = parseInt(weekendPrice, 10) * datesWeekend.length;
  }
  /*Set the price for the holiday days*/
  if(holidayActive == 1) {
    holidayPriceTotal = holidayPrice * holidayDates.length;
  }
  /*See what kennels were used*/
  var dogsBookingNames = "";
  for (var i = 0; i <= numberOfDogs - 1; i++) {
    var kennel = $("#dateSelectDog" + (i+1) + " option:selected").val();
    if(kennel != "None"){
      var id = "kennelContainer" + kennel;
      if ($("#" + id).length == 0){
        priceTotal = price * bookingDates.length + weekendPriceTotal + holidayPriceTotal;
        subTotalPrice = subTotalPrice + priceTotal;
        $("#invoiceList").append("<div id='" + id + "'>Kennel " + kennel + "<div class='clear'></div></div><hr>");
        $("#" + id).append("<div class='floatLeft'>Dog " + (i+1) + " " + typeOfBooking + " x " + bookingDates.length + "</div><div class='floatRight'>$" + priceTotal.toFixed(2) + "</div><div class='clear'></div>");
      }else{
        //additional dogs are another price//
        additionalPriceTotal = additionalPrice * bookingDates.length + weekendPriceTotal + holidayPriceTotal;
        subTotalPrice = subTotalPrice + additionalPriceTotal;
        $("#" + id).append("<div class='floatLeft'>Dog " + (i+1) + " " + typeOfBooking + " x " + bookingDates.length + "</div><div class='floatRight'>$" + additionalPriceTotal.toFixed(2) + "</div><div class='clear'></div>");
      }
      dogsBookingNames = dogsBookingNames + $("#dogName" + (i + 1)).html() + ", ";
    }
  }
  var subTotalPrice = subTotalPrice + reloadOtherCharges(subTotalPrice);
  subTotal = subTotalPrice;
  $("#insertSubTotal").html("$" + subTotalPrice.toFixed(2));
  var tax = addTax(subTotalPrice);
  addTotal(subTotalPrice, tax)
  if(deposit != 0) {
    changeDeposit(deposit);
  }
  $("#bookingPetSummaryButton").html("<i class='fa fa-paw' aria-hidden='true'></i> " + dogsBookingNames);
}
/*Click next after selecting dog dates*/
function bookingDogNext() {
  var check = bookingPart2Check();
  if (check && typeOfBooking == "Day Care"){
    loadDogsDayCarePrice();
    whichContainer("bookingCost");
  }else if (check){
    loadDogsOverNightPrice();
    whichContainer("bookingCost");
  }
}
/*Delete Dog From Booking Page*/
function bookingDeleteDog(dogid, id) {
  $.post("bookingDogsDelete.php", {
      dogid1: dogid,
  }, function(data) {
      if(data === "Success") {
          alert("success");
          liveSearchLoadDogs(id);
      }else{
          alert('Something went wrong.');
      }
  });
}
/*Save Dog Details on Booking Page*/
function bookingUpdateDog(id, number) {
  /*Show Loading Spinner*/
  $("#update" + number).html('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
    /*Clear Previous Errors*/
    $('#addDogMessage' + number).html("");
    $("span").remove('#redError');
    $('input').removeClass('error');
    $('select').removeClass('error');
    $('textarea').removeClass('error');
    /*Get values from form*/
    var dogid = $('#dogId' + number).val();
    var name = $('#addName' + number).val();
    var breed = $('#addBreed' + number).val();
    var color = $('#addColor' + number).val();
    var age = $('#addAge' + number).val();
    var gender = $('#addGender' + number).val();
    var fixed = $('#addFixed' + number).val();
    var weight = $('#addWeight' + number).val();
    var vdate = $('#addVdate' + number).val();
    var vphone = $('#addVphone' + number).val();
    var vname = $('#addVname' + number).val();
    var brand = $('#addBrand' + number).val();
    var often = $('#addOften' + number).val();
    var amount = $('#addAmount' + number).val();
    var message = $('#addMessage' + number).val();

    $.post("dogUpdate.php", {
        dogid1: dogid,
        name1: name,
        breed1: breed,
        color1: color,
        gender1: gender,
        age1: age,
        fixed1: fixed,
        weight1: weight,
        brand1: brand,
        amount1: amount,
        often1: often,
        vdate1: vdate,
        vname1: vname,
        vphone1: vphone,
        message1: message,
    }, function(data) {
      /*Show Loading Spinner*/
      $("#update" + number).html('Save Dog');
        if(data == 'Success') {
            $('#addDogMessage' + number).html("");
            $("span").remove('#redError');
            $('input').removeClass('error');
            $('select').removeClass('error');
            $('textarea').removeClass('error');
            liveSearchLoadDogs(id);
            $(".editDog").hide();
        }else if(data == "Fail"){
            $('#addDogMessage' + number).html("Something went wrong. Please try again.");
        }else if(data === "addVdate") {
            $('#' + data).addClass('error');
            $('body').animate({ scrollTop: $("#" + data + number).offset() }, 'slow');
            $('#' + data + number).before("<span id='redError'>Already Expired.</span>");
            $('body').animate({ scrollTop: $("#redError").offset().top }, 'slow');
        }else if(data === "addVphone") {
            $('#' + data + number).addClass('error');
            $('#' + data + number).before("<span id='redError'>Please enter a phone number.</span>");
            $('body').animate({ scrollTop: $("#redError").offset().top }, 'slow');
        }else if(data === "addFixed") {
            $('#' + data + number).addClass('error');
            $('#' + data + number).before("<span id='redError'>This is a requirement after 8 months.</span>");
            $('body').animate({ scrollTop: $("#redError").offset().top }, 'slow');
        }else if(data === "addName") {
            $('#' + data + number).addClass('error');
            $('#' + data + number).before("<span id='redError'>This field is required.</span>");
            $('body').animate({ scrollTop: $("#redError").offset().top }, 'slow');
        }
    });
}
/*Payment Container*/
function loadCostList() {
  $.ajax({
      method: "POST",
      url: "loadCostList.php",
  }) .done(function( data ) {
      if (data != "Fail") {
        data = data.split("&");
        var tempData;
        tempData = data[0].replace(/\"/g, "");
        tempData = tempData.replace(/[\[\]']+/g, "");
        loadAdditionalDesc = tempData.split(",");
        tempData = data[1].replace(/\"/g, "");
        tempData = tempData.replace(/[\[\]']+/g, "");
        loadAdditionalPrice = tempData.split(",");
        tempData = data[2].replace(/\"/g, "");
        tempData = tempData.replace(/[\[\]']+/g, "");
        loadAdditionalType = tempData.split(",");
      }
  });
}
function loadCostListDetails(id) {
  var number = $("#" + id + " .extraChargeChoice").val();
  $("#" + id + " .extraChargeDesc").val(loadAdditionalDesc[number]);
  $("#" + id + " .extraChargePrice").val(loadAdditionalPrice[number]);
  $("#" + id + " .extraChargeType").val(loadAdditionalType[number]);
  addExtraChargePrice(id);
}
function loadOtherCharge() {
  var options = "";
  for (var i = 0; i <= loadAdditionalDesc.length -1; i++) {
    options = options + "<option value='" + i + "'>" + loadAdditionalDesc[i] + "</option>";
  }
  var count = $("#otherChargeCount").val();
  count++;
  $("#addChargeBox").append("<div id='extraCharge" + count + "' class='extraCharge newCharge'>" +
  "<div class='formInput'><label>Quantity</label><br/><input onchange='updateExtraChargePrice(\"extraCharge" + count + "\");' class='extraChargeQuantity' type='number' min='1' value='1'/></div>" +
  "<div class='formInput'><label>Charge</label><br/><select class='extraChargeChoice' onchange='loadCostListDetails(\"extraCharge" + count + "\");'><option>Other</option>" + options + "</select></div>" +
  "<div class='formInput'><label>Description</label><br/><input onchange='updateExtraChargePrice(\"extraCharge" + count + "\");' class='extraChargeDesc' type='text' value=''/></div>" +
  "<div class='formInput'><label>Cost</label><br/><input onchange='updateExtraChargePrice(\"extraCharge" + count + "\");' class='extraChargePrice' type='number' value='1'/></div>" +
  "<div class='formInput'><label>Type</label><br/><select onchange='updateExtraChargePrice(\"extraCharge" + count + "\");' class='extraChargeType'><option>Fixed</option><option>Percentage</option></select></div>" +
  "<div id='deleteExtraCharge" + count + "' class='deleteExtraCharge' onclick='deleteOtherCharge($(this).attr(\"id\"));'><i class='fa fa-times' aria-hidden='true'></i></div>" +
  "</div>");
  $("#otherChargeCount").val(count);
}
function deleteOtherCharge(id) {
  var number = id.replace("deleteExtraCharge", "");
  var currentNumber = $("#otherChargeCount").val();
  var index = additionalId.indexOf("extraCharge" + number);
  if ($("#extraCharge" + number).hasClass('updateCharge')) {
    deleteIdList.push(index);
  }
  $("#extraCharge" + number).remove();
  $("#extraCharge" + number + "Item").remove();
  $("#otherChargeCount").val(currentNumber - 1);
  $(".extraCharge").each(function() {
    var id = $(this).attr('id');
    var idNumber = id.replace("extraCharge", "");
    if (idNumber >= currentNumber ) {
      $(this).attr('id', 'extraCharge' + (idNumber - 1));
      $("#deleteExtraCharge" + idNumber).attr('id', "deleteExtraCharge" + (idNumber - 1))
    }
  });
  if (index != -1) {
    additionalId.splice(index, 1);
    additionalNames.splice(index, 1);
    var subtractPrice = additionalPrices[index] * additionalQuantity[index];
    subTotal = subTotal - subtractPrice;
    additionalQuantity.splice(index, 1);
    additionalType.splice(index, 1);
    additionalDesc.splice(index, 1);
    additionalPrices.splice(index, 1);
    addTax(subTotal);
    addTotal(subTotal, tax);
    $("#insertSubTotal").html("$" + subTotal.toFixed(2));
  }
}
function updateExtraChargePrice(id) {
  var index = additionalId.indexOf(id);
  if (index == -1) {
    additionalId.push(id);
    index = additionalId.indexOf(id);
  }
  //Clear Previous Errors//
  $("#" + id + " input").removeClass("error");
  var price = $("#" + id + " .extraChargePrice").val();
  var description = $("#" + id + " .extraChargeDesc").val();
  var type = $("#" + id + " .extraChargeType").val();
  var quantity = $("#" + id + " .extraChargeQuantity").val();
  var validate = true;
  /*Validate Form*/
  if(price == "") {
    $("#" + id + " .extraChargePrice").addClass('error');
    validate = false;
  }
  if(description == "") {
    $("#" + id + " .extraChargeDesc").addClass('error');
    validate = false;
  }
  if(quantity == "") {
    $("#" + id + " .extraChargeQuantity").addClass('error');
    validate = false;
  }
  if (validate == true) {
    additionalNames[index] = description;
    additionalQuantity[index] = quantity;
    additionalType[index] = type;
    additionalDesc[index] = description;
    if (type == "Fixed") {
      additionalPrices[index] = price;
      var extraPriceTotal = price * quantity;
      subTotal = subTotal + extraPriceTotal;
    }else{
      additionalPrices[index] = price / 100 * subTotal;
      var extraPriceTotal = (price / 100 * subTotal) * quantity;
      subTotal = subTotal + extraPriceTotal;
    }
    $("#insertSubTotal").html("$" + subTotal.toFixed(2));
    addTax(subTotal);
    addTotal(subTotal, tax);
    if ($("#" + id + "Item").length == 0) {
      $("#extraChargesList").append("<div id='" + id + "Item'><div class='floatLeft'>" + description + " x " + quantity + "</div> <div class='floatRight'>$" +
      extraPriceTotal.toFixed(2) + "</div></div><div class='clear'></div>");
    }else{
      $("#" + id + "Item").html("<div class='floatLeft'>" + description + " x " + quantity + "</div> <div class='floatRight'>$" +
      extraPriceTotal.toFixed(2) + "</div>");
    }

  }
}
function reloadOtherCharges(priceSubTotal) {
  var otherCharges = 0;
  for(var i =0; i <= additionalPrices.length - 1; i++) {
    /*Check if the type is fixed or a percent*/
    if (additionalType[i] == "Fixed") {
      otherCharges = additionalPrices[i] * additionalQuantity[i] + otherCharges;
    }else{
      var percentCost = $("#" + additionalId[i] + " .extraChargePrice").val();
      var showCharge = (percentCost / 100 * priceSubTotal) * additionalQuantity[i];
      otherCharges = showCharge + otherCharges;
      $("#" + additionalId[i] + "Item").html("<div class='floatLeft'>" + additionalDesc[i] + " x " + additionalQuantity[i] + "</div> <div class='floatRight'>$" +
      showCharge.toFixed(2) + "</div>");
    }
  }
  return otherCharges;
}
function changeDeposit(depositAmount) {
  depositAmount = parseFloat(depositAmount);
  deposit = depositAmount;
  addTotal(subTotal, tax);
  if (depositAmount == 0 || depositAmount == ""){
    $("#insertDeposit").html("");
  }else if(depositAmount > total) {
    $("#insertDeposit").html("");
    alert("The deposit cannot be more than the total price.");
  }else{
    depositTotal = subTotal - depositAmount;
    addTotal(depositTotal, tax);
    $("#insertDeposit").html("<div class='floatLeft'>Deposit:</div><div class='floatRight'>- $" + depositAmount.toFixed(2) + "</div>");
  }
}
/*Summary Container*/
/*Calculate Tax*/
function addTax(price) {
  taxPercent = $("#taxPrice").val();
  taxPercent = taxPercent / 100;
  tax = taxPercent * price;
  var priceWithTax = tax;
  $("#insertTax").html("$" + priceWithTax.toFixed(2));
  return priceWithTax;
}

/*Calculate Day Care Rate*/
function calculatePrice(numberOfDays, numberOfDogs) {
  if(typeOfBooking == "Day Care") {
    var price = $("#dayCarePrice").val();
    var additionalPrice = $("#dayCareAdditionalPrice").val();
    var weekendPrice = $("#weekendPrice").val();
    var weekendActive = $("#weekendActive").val();
    var holidayPrice = $("#holidayPrice").val();
    var holidayActive = $("#holidayActive").val();
  }else if(typeOfBooking == "Over Night"){
    var price = $("#overNightPrice").val();
    var additionalPrice = $("#overNightAdditionalPrice").val();
    var weekendPrice = $("#weekendPrice").val();
    var weekendActive = $("#weekendActive").val();
    var holidayPrice = $("#holidayPrice").val();
    var holidayActive = $("#holidayActive").val();
  }

  //check if holiday rates are active//
  if(holidayActive == 1) {
    var holidayPriceTotal = holidayPrice * holidayDates.length;
  }else{
    var holidayPriceTotal = 0;
  }
  //Check if weekend rates are active//
  if (weekendActive == 1) {
    var weekendPriceTotal =  parseInt(weekendPrice, 10) * datesWeekend.length;
  }else{
    var weekendPriceTotal = 0;
  }
  var priceTotal = price * numberOfDays + weekendPriceTotal + holidayPriceTotal ;
  var additionalPriceTotal = additionalPrice * numberOfDays + weekendPriceTotal + holidayPriceTotal;

  var priceSubTotal = 0;
  if (numberOfDogs > 1) {
    $("#invoiceList").html("");
    for(var i = 1; i <= numberOfDogs; i++) {
      //first dog is normal price//
      if (i == 1) {
        $("#invoiceList").append("<span id='priceList" + i + "'><div class='floatLeft'>Dog " + i + " " + typeOfBooking + " x " + numberOfDays + "</div><div class='floatRight'>$" + priceTotal.toFixed(2) + "</div></span><div class='clear'></div>");
        priceSubTotal = priceSubTotal + priceTotal;
      }else{
        //every other dog is a different price//
        $("#invoiceList").append("<span id='priceList" + i + "'><div class='floatLeft'>Dog " + i + " " + typeOfBooking + " x " + numberOfDays + "</div><div class='floatRight'>$" + additionalPriceTotal.toFixed(2) + "</div></span><div class='clear'></div>");
        priceSubTotal = priceSubTotal + additionalPriceTotal;
      }
    }
    /*Add Other Charges If there is any*/
    priceSubTotal = priceSubTotal + reloadOtherCharges(priceSubTotal);

    $("#subTotal").show();
    $("#insertSubTotal").html("$" + priceSubTotal.toFixed(2));
  }else{
    $("#invoiceList").html("<div class='floatLeft'>" + typeOfBooking + " x " + numberOfDays + "</div><div class='floatRight'>$" + priceTotal.toFixed(2) + "</div><div class='clear'></div>");
    priceSubTotal =priceTotal + reloadOtherCharges(priceTotal);
    $("#subTotal").hide();
  }
  return priceSubTotal;
}

/*Calculate Total*/
function addTotal(subtotal, tax) {
  total = subtotal + tax;
  $("#insertTotal").html("$" + total.toFixed(2));
}
/*Adjust Price when dates are deselected*/
function adjustPrice(id) {
  var n = $("#" + id).find("input:checked").length;
  var dayCarePrice = $("#dayCarePrice").val();
  var dayCarePriceTotal = dayCarePrice * n;
  id = id.replace("dateSelectDog", "");
  if (n == 0) {
    $("#priceList" + id).hide();
  }else{
    $("#priceList" + id).html("<div class='floatLeft'>Dog " + id + " " + typeOfBooking + " x " + n + "</div><div class='floatRight'>$" + dayCarePriceTotal.toFixed(2) + "</div>");
  }

}
function sendEmail(bookingId) {
  var name = $("#bookingClient0").val() + " " + $("#bookingClient1").val();
  var email = $("#bookingClient2").val();
  if (typeOfBooking == "Over Night") {
    var newCheckOut = formatDate(checkOut);
  }
  $.ajax({
      method: "POST",
      url: "sendEmail.php",
      data: { email:email, name:name, bookingDates:bookingDates, typeOfBooking:typeOfBooking, newCheckOut:newCheckOut},
  }) .done(function( data ) {
      location.href= "booking.php?bookingid=" + bookingId;
  });
}
/*Save Costs*/
function saveCosts() {
  var finalTotal = subTotal + tax;
  var priceRow = $("#priceRowId").val();
  var paymentType = $("#paymentType").val();
  var paymentNote = $("#paymentNote").val();
  $.ajax({
      method: "POST",
      url: "saveCosts.php",
      data: { priceRow:priceRow, subTotal:subTotal, tax:tax, finalTotal:finalTotal, deposit:deposit, paymentType:paymentType, typeOfBooking:typeOfBooking, clientId:clientId, paymentNote:paymentNote},
  }) .done(function( data ) {
    if (typeOfBooking == "Over Night") {
      saveOverNight();
    }else{
      saveAppointment();
    }
  });
}
/*Save Additional Costs*/
function saveAdditional(bookingId) {
  $.ajax({
      method: "POST",
      url: "saveAdditional.php",
      data: { additionalDesc: additionalDesc, additionalQuantity:additionalQuantity, additionalPrices:additionalPrices, additionalType:additionalType },
  }) .done(function( data ) {
    if (window.location.href.indexOf("bookingid") > -1 ) {
      location.href= "booking.php?bookingid=" + bookingId;
    }else if(data == "Success" && $("#bookingClient2").val() != "") {
      sendEmail(bookingId);
    }else if (data =="Success") {
      location.href= "booking.php?bookingid=" + bookingId;
    }else{
      alert("Something went wrong!");
    }
  });
}
/*Save Appointment*/
function saveAppointment() {
  var count = 0;
  var bookingId;
  var email = false;
  /*Insert per dog booking in database*/
  for( var i = 0; i <= numberOfDogs - 1; i++) {
    var id = dogsId[i];
    var selectedDates = $("#bookingChoice" + (i + 1) +" option:selected").map(function(){
      return $(this).val();
    }).get();
    $.ajax({
        method: "POST",
        url: "saveAppointment.php",
        data: { selectedDates: selectedDates, clientId: clientId, id:id},
    }) .done(function( data ) {
      if (data != "Fail") {
        bookingId = data;
        count++;
        if(count == numberOfDogs && additionalDesc.length != 0) {
          //now insert additionalCosts into database//
          saveAdditional(bookingId);
        }else if(count == numberOfDogs && $("#bookingClient2").val() != ""){
          sendEmail(bookingId);
        }else if(count == numberOfDogs){
          location.href= "schedule/booking.php?bookingid=" + bookingId;
        }
      }
    });
  }
}
/*Save overnight*/
function saveOverNight() {
  var count = 0;
  var bookingId;
  var email = false;
  var newCheckIn = formatDate(checkIn);
  var newCheckOut = formatDate(checkOut);
  var splitValue = "";
  //loop through the kennels//
  for( var i = 0; i <= kennelsSelected.length-1; i++) {
    var kennel = kennelsSelected[i];
    var kennelDogs = new Array();
      //now loop through the dogs to see which ones are in that kennel//
      for( var j = 0; j <= numberOfDogs - 1; j++) {
        var kennel2 = $("#bookingChoice" + (j+1) + " option:selected").val();
        if(kennel == kennel2 && splitBooking == false) {
          kennelDogs.push(dogsId[j]);
        }else if(kennel2 == "Set" && splitBooking == true){
          kennelDogs.push(dogsId[j]);
        }
      }
      //save checkin and checkout to send to php if a split booking//
      if (splitBooking == true && i != (kennelsSelected.length - 1)) {
        newCheckIn = splitCheckIn[i];
        newCheckOut = splitCheckOut[i];
        splitValue = "split";
      }else if(splitValue == "split" && i == (kennelsSelected.length - 1)) {
        newCheckIn = splitCheckIn[i];
        newCheckOut = splitCheckOut[i];
        splitValue = "";
      }

    $.ajax({
        method: "POST",
        url: "saveOverNight.php",
        data: { newCheckIn: newCheckIn, newCheckOut: newCheckOut, kennelDogs:kennelDogs, clientId:clientId, kennel:kennel, splitValue:splitValue},
    }) .done(function( data ) {
      if (data != "Fail") {
        bookingId = data;
        count++;
        if(count == kennelsSelected.length && additionalDesc.length != 0) {
          //now insert additionalCosts into database//
          saveAdditional(bookingId);
        }else if(count == kennelsSelected.length && $("#bookingClient2").val() != ""){
          sendEmail(bookingId);
        }else if(count == kennelsSelected.length){
          location.href= "booking.php?bookingid=" + bookingId;
        }
      }else{

      }
    });
  }
}
function splitCheckingDates() {
  var tempdate;
  for (var i = 0; i<= splitDatesForSaving.length - 1; i+=2) {
    tempdate = formatDate(splitDatesForSaving[i]);
    splitCheckIn.push(tempdate);
    tempdate = formatDate(splitDatesForSaving[i+1]);
    splitCheckOut.push(tempdate);
  }
}
/*Check if dates are already booked*/
function isBooked(startdate, enddate, kennels) {
  $.ajax({
      method: "POST",
      url: "isBooked.php",
      data: { startdate:startdate, enddate:enddate, kennels:kennels, splitBooking:splitBooking},
  }) .done(function( data ) {
    if (data == "success") {
      if(window.location.href.indexOf("bookingid") > -1) {
        updateCosts();
      }else{
        //Now begin adding data to database//
        saveCosts();
      }
  }else{
    alert("Sorry some of the dates you selected are already booked.");
    }
  });
}
/*Save Booking*/
function saveBooking(saveButtonId) {
  $(".overlay").removeClass("overlayHide");
  $(".loadingContainer").css({"left":"45%"});
  /*Validate all containers*/
  if ($("#bookingDateSummaryButton").hasClass("selected")) {
    alert("Please select a date.");
  }else if ($("#bookingCustomerSummaryButton").hasClass("selected")) {
    alert("Please select a client.");
  }else if($("#bookingPetSummaryButton").hasClass("selected")) {
    alert("Please select your pets.");
  }else if (typeOfBooking == "Over Night"){
    if (splitBooking == true) {
      splitCheckingDates();
      var checkKennels = kennelsSelected;
      isBooked(splitCheckIn, splitCheckOut, checkKennels);
    }else{
      var newCheckIn = formatDate(checkIn);
      var newCheckOut = formatDate(checkOut);
      var checkKennels = kennelsSelected;
      isBooked(newCheckIn, newCheckOut, checkKennels);
    }
  }else{
    saveCosts();
  }
}
function bookingPart1Check() {
  var check = true;
  if(!$('.dayCareDateBox').length && typeOfBooking != "Over Night") {
    var check = false;
    alert("Please select a date.")
  }else if(typeOfBooking == "Over Night" && $("#nightCheckOut").val() == ""){
    var check = false;
    alert("Please select a checkout date.");
  }else if(typeOfBooking == "Over Night" && kennelsSelected.length == 0) {
    var check = false;
    alert("Please select a kennel.");
  }
  return check;
}
function bookingPart2Check() {
  var check = true;
  if(numberOfDogs == 0) {
    alert('Please add a dog.');
    check = false;
  }
  return check;
}
//----CALENDAR-FUNCTIONS----//
/*function snapToMiddle(dragger, target){
  var location = target.position();
    var topMove = location.top + 15;
    var leftMove= location.left;
    alert(topMove + " " + leftMove);
    dragger.animate({top:topMove,left:leftMove},{duration:600,easing:'easeOutBack'});
}*/
function updateDragBooking(bookingid, dates) {
  //split dates into start and end//
  startDate = dates[0].split("-");
  var kennelNumber = startDate[0];
  kennelNumber = kennelNumber.replace('K','');
  startDate.splice(0,1);
  endDate = dates[dates.length-1].split('-');
  endDate.splice(0,1);
  $.ajax({
        method: "POST",
        url: "updateBooking.php",
        data: { startDatesArray: startDate, endDatesArray: endDate, id: bookingid, kennel: kennelNumber},
    }) .done(function( data ) {
      if(data === "Fail") {
        alert('There was a problem editing the booking!');
      }else{
      }
  });

}
function editableBookings() {
/*Get Original Date ID's*/

/*Create Draggable bookings*/
  $( ".draggable" ).draggable({
      create: function(){$(this).data('position',$(this).position())},
      cancel: ".split",
      cursor:'move',
      start:function(){
        $(this).stop(true,true);
      },
      containment: "#calendar",
      scrollSensitivity: 100,
      revert: 'invalid',
  });
/*Edit a booking by dropping it on a new set of dates*/
  var location;
  var hoverId;
  var dateLength;
  $('.calendarTD').droppable({
     accept: '.draggable',
     tolerance: "pointer",
     drop:function(event, ui){
       //Remove booking class from previous booking//
       var startDate = $(ui.draggable).find('.startDate').val();
       var index = $("td").index($("#" + startDate));
       $("#" + startDate).removeClass('booked');
       $("#" + startDate).find('button').addClass('bookButton');
       for (var i = 1; i <= dateLength - 1; i++) {
        var nextDayId = $("td").eq(index + i).attr("id");
        $("#" + nextDayId).removeClass('booked');
         $("#" + nextDayId).find('button').addClass('bookButton');
       }
       //Relocate draggable to new booking dates//
       $(ui.draggable).css({ top: location.top + 16, left: location.left, marginLeft: '11px'});
       $(ui.draggable).find('.startDate').val(hoverId);
       $("#" + hoverId).find('button').removeClass('bookButton');
       $("#" + hoverId).find('button').hide();
       $("#" + hoverId).addClass('booked');
       index = $("td").index($("#" + hoverId));
       var newDates = [hoverId];
       for (var i = 1; i <= dateLength - 1; i++) {
        var nextDayId = $("td").eq(index + i).attr("id");
        newDates[i] = nextDayId;
        $("#" + nextDayId).find('button').removeClass('bookButton');
        $("#" + nextDayId).find('button').hide();
        $("#" + nextDayId).addClass('booked');
       }
       $("#bookDragHighLight").hide();
       updateDragBooking($(ui.draggable).attr('id'),newDates);
     },
     over: function (event, ui) {
       if(!$(this).hasClass('booked')) {
         $("#bookDragHighLight").width("76");
          hoverId = $(this).attr('id');
          location = $("#" + hoverId).position();
          var widthOfBooking = Math.round($(ui.draggable).width() / 76);
          dateLength = widthOfBooking;
          var widthOfHighLight = widthOfBooking * 85;
          $("#bookDragHighLight").show();
          $("#bookDragHighLight").width(widthOfHighLight);
          $("#bookDragHighLight").css({ top: location.top, left: location.left});
          widthOfHighLight = 0;
          widthOfBooking = 0;
        }
      }
  });
/*Check if trying to drop on booked date*/
  $('.draggable').droppable({
    drop:function(){
        alert('Sorry one these dates is already booked!');
      },
      over: function() {
        $("#bookDragHighLight").hide();
      }
  });
}
function loadBookings(month, year, day) {
    //send month and year over the server//
    $.ajax({
        method: "POST",
        url: "getBookings.php",
        data: {month1: month, year1: year, day1: day},
    }) .done(function( data ) {
      /*Get calendarDates position for popups*/
      var calendarLocation = $("#calendarDates").position();
    //set widths of bookings depending on view type//
    if(day == null){
      //30-Day View//
      var marginLeftBegin = '11px';
      var widthBegin = '66px';
      var widthBookingMiddle = 87;
      var widthBookingEnd = 77;
      var marginLeftDouble = "44px";
      var widthDouble = "33px";
      var widthPreviousDouble = 36;
      var heightPadding = 15;
    }else if (window.location.href.indexOf("printWeek") > -1){
      var marginLeftBegin = '12.13px';
      var widthBegin = '72.8px';
      var widthBookingMiddle = 95.96;
      var widthBookingEnd = 84.93;
      var marginLeftDouble = "48.53px";
      var widthDouble = "36.4px";
      var widthPreviousDouble = 39.7;
      var heightPadding = 10;
    }else{//7-Day View//
      var marginLeftBegin = '17.82px';
      var widthBegin = '106.92px';
      var widthBookingMiddle = 140.94;
      var widthBookingEnd = 124.74;
      var marginLeftDouble = "71.28px";
      var widthDouble = "53.46px";
      var widthPreviousDouble = 58.32;
      var heightPadding = 15;
    }
       //get the data back and split the data into readable chunks by day//
        data = data.split("&");
        //store the id of the last positioned booking//
        var lastid;
        var lastEndDate;
        var location;
        //loop through the data/bookings and insert into the calendar view//
        for (var i = 0; i <= data.length-2; i++) {
            //get the data for the day//
            var name = data[i];
            //split relevant data for the day//
            name = name.split("*");
            if (name[4] != "" && name[2] == "split") {
              $("#" + name[1]).remove();
            }
            //using the id stored find table cell and remove booking button//
             $("#" + name[0]).find('button').removeClass('bookButton');
             $("#" + name[0]).find('button').hide();
            //if this day does not contain the same id as the last then continue making a new booking//
             if(name[1] != lastid) {
                 //if last id is set shrink the width of the last booking//
                $("#" + lastid).width($("#" + lastid).width() - 10);
                $("#" + lastEndDate).addClass('end');
                 //check to see if the booking id already exists on the calendar//
                if ($("." + name[3]).length ) {
                    //if it does then show a split booking//
                    var htmlToAppend = "<div id='"+ name[1] +"' class='draggable ui-widget-content split " + name[3] +"'><i class='fa fa-chevron-right floatLeft black start' aria-hidden='true'></i>" +
                    "<i class='fa fa-info-circle bookingInfoButton' aria-hidden='true'></i><div class='dogNamesCalendar'>" + name[4] + "</div><input class='startDate' type='hidden' value='" + name[0] + "'/>"+
                    "<input class='endDate' type='hidden' value=''/><input class='bookingNames' type='hidden' value='" + name[4] + "'/><input class='bookingDogIds' type='hidden' value='" + name[5] + "'/></div>";
                    $("#bookingBlocks").append(htmlToAppend);
                     location = $("#" + name[0]).position();
                    if (name[2] == "split") {
                        //other wise just post as a normal booking//
                        $("#" + name[1]).append('<i class="fa fa-chevron-right floatRight black end" aria-hidden="true"></i>');
                    }
                }else{
                    //if its the same booking but not a split booking post on the calendar//
                    $("#bookingBlocks").append("<div id='"+ name[1] +"' class='draggable ui-widget-content " + name[3] +"'><i class='fa fa-info-circle bookingInfoButton' aria-hidden='true'></i><div class='dogNamesCalendar'>" + name[4] + "</div><input class='startDate' type='hidden' value='" + name[0] + "'/>"+
                    "<input class='endDate' type='hidden' value=''/><input class='bookingNames' type='hidden' value='" + name[4] + "'/><input class='bookingDogIds' type='hidden' value='" + name[5] + "'/></div>");
                    location = $("#" + name[0]).position();
                    $("#" + name[1]).css({ top: location.top + heightPadding, left: location.left, marginLeft: marginLeftBegin, width: widthBegin });
                    //Check if it is a split booking//
                    //if it contains the word split add an arrow to signify that it is a split booking//
                    if (name[2] == "split") {
                        $("#" + name[1]).append('<i class="fa fa-chevron-right floatRight black end" aria-hidden="true"></i>');
                        $("#" + name[1]).addClass('split');
                        $("#" + name[1] + "> .endDate").val(name[0]);
                    }
                }
                //check for a double booking//
                if($("#" + name[0]).hasClass('booked')) {
                  $("#" + name[1]).css({ top: location.top + heightPadding, left: location.left, marginLeft: marginLeftDouble, width: widthDouble });
                  var previousWidth = $("#" + lastid).width();
                  $("#" + lastid).width(previousWidth - widthPreviousDouble);
                }else{
                  if (location == undefined) {
                    alert(name[0]);
                    location = $("#" + name[0]).position();
                  }
                  $("#" + name[1]).css({ top: location.top + heightPadding, left: location.left, marginLeft: marginLeftBegin, width: widthBegin });
                  $("#" + name[0]).addClass('booked');
                  $("#" + name[0]).addClass('start');
                }
             }else if( i == data.length - 2){
               //adding the end part of a booking here//
                $("#" + name[1]).width($("#" + name[1]).width() + widthBookingEnd);
                $("#" + name[0]).addClass('booked');
                $("#" + name[0]).addClass('end');
                $("#" + name[1] + "> .endDate").val(name[0]);
             }else{
               //adding the middle part of a booking here//
                $("#" + name[1]).width($("#" + name[1]).width() + widthBookingMiddle);
                $("#" + name[0]).addClass('booked');
                $("#" + name[1] + "> .endDate").val(name[0]);
             }
             lastid= name[1];
             lastEndDate = name[0];
        }
        $(".bookingInfoButton").click(function() {
          var parentid = $(this).parent().attr("id");
          /*Get Check In Date from parent and insert into popup*/
          var parentCheckIn = $("#" + parentid).find(".startDate").val();
          $("#popCheckIn").html(parentCheckIn.substring(3));

          /*Get Check Out Date from parent and insert into popup*/
          var parentCheckOut = $("#" + parentid).find(".endDate").val();
          $("#popCheckOut").html(parentCheckOut.substring(3));

          /*Get Dog Names from parent and insert into popup*/
          var parentDogNames = $("#" + parentid).find(".bookingNames").val();
          var parentDogIds = $("#" + parentid).find(".bookingDogIds").val();
          parentDogNames = parentDogNames.split(" ");
          parentDogIds = parentDogIds.split(" ");
          $("#popDogNames").html("");
          for (var i = 1; i <= parentDogNames.length - 1; i++) {
              $("#popDogNames").append("<button onclick='location.href=\"kennelsheet.php?dogid=" + parentDogIds[i] + "\";'>" + parentDogNames[i] + "</button>");
          }


          var parentPosition = $("#" + parentid).offset();
          var calendarContLocation = $("#calendarContainer").offset();
          hideAndSeek("bookingPopUp");
          $(".bookingPopUp").css({"top" : parentPosition.top - calendarContLocation.top, "left": parentPosition.left - 20});
        });
        //Run function for drag and drop bookings//
        /*editableBookings();*/
    });
}
function hideAndSeek(id) {
  var visibleState = $("." + id).css('display');
  if(visibleState == "none") {
    $("." + id).show();
  }else{
    $("." + id).hide();
  }

}
function adjustPosition(numberOfDays) {
    adjustedWidth = numberOfDays * 87;
    $(".draggable").each( function() {
       var location = $(this).position();
        $(this).css({ left: location.left + adjustedWidth });
    });
}
function daysInMonth(month,year) {
    return new Date(year, month, 0).getDate();
}
$( document ).ready(function() {
    /*Load Selected Menu Item*/
    var url = window.location.href;
    navSelected(url);
    $(".mobileNavIcon").click(function() {
      $("body").toggleClass("contentMove");
      $(".mobileNavIcon").toggleClass("contentMove");
      $(".mobileNav").toggleClass("navOpen");
    });
    $(".reportsMobile").click(function() {
      $(".reportsMobile i").toggleClass("fa-rotate-180");
      $(".haveToMove").toggleClass("iMoved");
      $(".reportsDrop").toggleClass("subMenuOpen");

    });
    /*-----Arrival Container-----*/

    $('#arrivalBack').click(function() {
      var date = $('#arrivalDate').val();
      clickDate(date, 'back', 'arrival');
    });
    $('#arrivalNext').click(function() {
      var date = $('#arrivalDate').val();
      clickDate(date, 'next', 'arrival');
    });
    $("#arrivalContainer").swipe( {
      //Generic swipe handler for all directions
      swipe:function(event, direction, distance, duration, fingerCount, fingerData) {
        if (direction == "right") {
          var date = $('#arrivalDate').val();
          clickDate(date, 'back', 'arrival');
        }else if(direction == "left"){
          var date = $('#arrivalDate').val();
          clickDate(date, 'next', 'arrival');
        }
      }
    });

    $('#departureBack').click(function() {
      var date = $('#departureDate').val();
      clickDate(date, 'back', 'departure');
    });
    $('#departureNext').click(function() {
      var date = $('#departureDate').val();
      clickDate(date, 'next', 'departure');
    });
    $("#departureContainer").swipe( {
      //Generic swipe handler for all directions
      swipe:function(event, direction, distance, duration, fingerCount, fingerData) {
        if (direction == "right") {
          var date = $('#departureDate').val();
          clickDate(date, 'back', 'departure');
        }else if(direction == "left"){
          var date = $('#departureDate').val();
          clickDate(date, 'next', 'departure');
        }
      }
    });
    $('#appointmentBack').click(function() {
      var date = $('#appointmentDate').val();
      clickDate(date, 'back', 'appointment');
    });
    $('#appointmentNext').click(function() {
      var date = $('#appointmentDate').val();
      clickDate(date, 'next', 'appointment');
    });
    $("#appointmentContainer").swipe( {
      //Generic swipe handler for all directions
      swipe:function(event, direction, distance, duration, fingerCount, fingerData) {
        if (direction == "right") {
            var date = $('#appointmentDate').val();
          clickDate(date, 'back', 'appointment');
        }else if(direction == "left"){
            var date = $('#appointmentDate').val();
          clickDate(date, 'next', 'appointment');
        }
      }
    });
    $('#outstandingDateRangeForm > #dateRange2').keypress(function(e){
        if(e.which == 13){//Enter key pressed
            $('#outstandingDateRangeForm').submit();
        }
    });
    /*-----Customer Page-------*/

    /*Search bar*/
    $('#customerSearch').keypress(function(e){
        if(e.which == 13){//Enter key pressed
            $('#customerSearchForm').submit();
        }
    });
    $('#customerDataTable tr td').click(function() {
        if(!$(this).hasClass('dogs')) {
            window.document.location = $(this).data("href");
        }
    });

    /*----Customer Profile Page----*/
    $('.profileMenu').click(function() {
        var id = $(this).attr('id');
        profileSelect(id);
    });
    $('.addDogButtons').click(function(event) {
        event.preventDefault();
        addDogForm();
    });
    $('#saveDogButton').click(function() {
        event.preventDefault();
        var id = $("#userId").val();
        if (id != "") {
          saveDog(id);
        }else{
          alert("Please select a client first.");
        }

    });

    //----BOOKING-PAGE----//
    $(".toggle-button").on('click', function(event){
      $(this).toggleClass("toggleChange");
      $(".toggle-button i").toggleClass("fa-rotate-180");
      $(".mobileSummary").toggleClass("menuToggleChange");
    });
    /*check if user reaches the end of the calendar and load next month*/
     $(function() {
        $('#calendarSplitDates').scroll( function() {
          console.log($('#calendarSplitDates').scrollLeft() + " " + ($('#calendarSplitDates table').width() - $('#calendarSplitDates').width()));
            if ( $('#calendarSplitDates').scrollLeft() == Math.round($('#calendarSplitDates table').width() - $('#calendarSplitDates').width())) {
                if(nextMonth >=12 ) {
                    nextMonth = 1;
                    nextYear++;
                }else{
                    nextMonth++;
                }
                $(this).css({"cursor":"progress",});
                $.ajax({
                    method: "POST",
                    url: "nextSplitMonth.php",
                    data: { month: nextMonth, year: nextYear},
                }) .done(function( data ) {
                    var string = data.split("*");
                    $('#calendarDateRow').append(string[0]);
                    for (var i = 1; i <= string.length; i++) {
                        $("#kennel" + i).append(string[i]);
                    }
                      loadBookings(nextMonth, nextYear);
                    $('#calendarSplitDates').css({"cursor":"default",});
                });

            }
        });
     });
     /*check if user reaches the end of the calendar and load last month*/
      $(function() {
         $('#calendarSplitDates').scroll( function() {
             if ( $('#calendarSplitDates').scrollLeft() == 0) {
                 if(lastMonth <=1 ) {
                     lastMonth = 12;
                     lastYear--;
                 }else{
                     lastMonth--;
                 }
                 $('#calendarSplitDates').css({"cursor":"progress",});
                 $.ajax({
                     method: "POST",
                     url: "lastSplitMonth.php",
                     data: { month: lastMonth, year: lastYear},
                 }) .done(function( data ) {
                     var string = data.split("*");
                     $('#calendarDateRow').prepend(string[0]);
                     for (var i = 1; i <= string.length; i++) {
                         $("#kennel" + i).prepend(string[i]);
                     }
                     loadBookings(lastMonth, lastYear);
                     var numberOfDays = daysInMonth(lastMonth,lastYear);
                     adjustPosition(numberOfDays);

                     $('#calendarSplitDates').css({"cursor":"default",});
                 });
             }
         });
     });

    /*Allow summary container to scroll with the page*/
    if ($(document).width() > 1024) {
      $(window).scroll(function () {
        var scroll = 0;
        var marginTop = 10;
          marginTop = ($(document).scrollTop() - scroll - 200) + marginTop;
          scroll = $(document).scrollTop();
          if (scroll > 216) {
              $("#bookingSummary").animate({"marginTop": marginTop+"px"}, {duration:400,queue:false} );
          }else{
            $("#bookingSummary").animate({"marginTop": "20px"}, {duration:400,queue:false} );
          }
      });
    }
    /*Stop accoridan arrows from refreshing the page*/
    $(".accordianArrow").click(function() {
      event.preventDefault();

    });
    /*Activate all datepickers*/
     $('#dayCareDateSelect').datepicker({
        'format': 'yyyy/m/d',
        'autoclose': true,
      });
    $('#nightCheckIn').datepicker({
       'format': 'yyyy/m/d',
       'autoclose': true,
     });
   $('#nightCheckOut').datepicker({
      'format': 'yyyy/m/d',
      'autoclose': true,
    });
    $('#meetGreetDateSelect').datepicker({
       'format': 'yyyy/m/d',
       'autoclose': true,
   });
   /*Stop all services buttons from refreshing the page*/
    $("#addDayDate").click(function(){
      event.preventDefault();
    });
    $("#addMeetDate").click(function(){
      event.preventDefault();
    });
    $("#splitBookingButton").click(function() {
      event.preventDefault();
    });
    /*Setting value and styles of progress bar for kennel bookings*/
     $( ".progressbar" ).progressbar({
      value: 100
    });
     $(".ui-progressbar-value").addClass('available');

     $(".progressbar").click(function() {
       event.preventDefault();
        if ($(this).hasClass('ui-progressbar-indeterminate')) {
            $(this).progressbar( "option", "value", 100 );
            $(this).find(".ui-progressbar-value").removeClass('myBooking');
            $(this).find(".ui-progressbar-value").addClass('available');
        }else if($(this).find(".ui-progressbar-value").hasClass('doubleBooking')) {
          if($(this).hasClass('myBooking')) {
            $(this).removeClass('myBooking');
          }else{
            $(this).addClass('myBooking');
          }
        }else{
            $(this).progressbar( "option", "value", false );
            $(this).children(".ui-progressbar-value").removeClass('available');
            $(this).children(".ui-progressbar-value").addClass('myBooking');
        }
     });
     /*Prevent button from refreshing the page*/
     $(".button2").click(function() {
        event.preventDefault();
     });
    var timeout;
    $("#bookingClientReset").click(function() {
       event.preventDefault();
    });
    $('#clientLiveSearch').on('input', function() {
      clearTimeout($.data(this, 'timer'));
      var wait = setTimeout(liveSearch, 100);
      $(this).data('timer', wait);
    });
     /*----Personal-Settings-Page----*/
     $("#kennelsEdit").click(function() {
        event.preventDefault();
     });
     $("#saveKennels").click(function() {
        $('#kennelSettings').submit();
     });
     $("#addCharge").click(function() {
       event.preventDefault();
       loadOtherCharge();
     });
     $(".savebooking").click(function() {
       event.preventDefault();
     });
     $("#splitBookingSave").click(function () {
       event.preventDefault();
     });
     $("#markAsPaid").click(function () {
       event.preventDefault();
     });
     //-----EDIT-BOOKING-----//
     $("#bookingShowDetailButton").click(function() {
       event.preventDefault();
       var txt = $("#bookingShowDetailButton").is(':visible') ? 'Show Booking Details' : 'Hide Booking Details';
       $("#bookingShowDetailButton").html(txt);
       $("#bookingShowDetailContainer").slideToggle('800');
     });
     $("#deleteEntireBooking").click(function() {
       $(".overlay").removeClass("overlayHide");
       $(".loadingContainer").show();
       $.ajax({
           method: "POST",
           url: "deleteBooking.php",
           data: { typeOfBooking: typeOfBooking},
       }) .done(function( data ) {
         if (data == "Success"){
           location.href= "booking";
         }else{
           alert("There was a problem deleting the booking.")
         }
       })
     });
     //----CALENDAR-PAGE----//
    /*check if user reaches the end of the calendar and load next month*/
     $(function() {
        $('#calendarDates').scroll( function() {
          /*Hide ALL PopUps*/
          $(".bookingPopUp").hide();

            if ( $('#calendarDates').scrollLeft() == ($('#calendarDates table').width() - $('#calendarDates').width())) {
                if(nextMonth >=12 ) {
                    nextMonth = 1;
                    nextYear++;
                }else{
                    nextMonth++;
                }
                $('#calendarDates').css({"cursor":"progress",});
                $.ajax({
                    method: "POST",
                    url: "nextMonth.php",
                    data: { month: nextMonth, year: nextYear},
                }) .done(function( data ) {
                    var string = data.split("*");
                    $('#calendarDateRow').append(string[0]);
                    for (var i = 1; i <= string.length; i++) {
                        $("#kennel" + i).append(string[i]);
                    }
                    $('.bookingInfoButton').unbind('click');
                    loadBookings(nextMonth, nextYear);
                    $('#calendarDates').css({"cursor":"default",});
                });
            }
        });
     });
        /*check if user reaches the end of the calendar and load last month*/
     $(function() {
        $('#calendarDates').scroll( function() {
          /*Hide ALL PopUps*/
          $(".bookingPopUp").hide();

            if ( $('#calendarDates').scrollLeft() == 0) {
                if(lastMonth <=1 ) {
                    lastMonth = 12;
                    lastYear--;
                }else{
                    lastMonth--;
                }
                $('#calendarDates').css({"cursor":"progress",});
                $.ajax({
                    method: "POST",
                    url: "lastMonth.php",
                    data: { month: lastMonth, year: lastYear},
                }) .done(function( data ) {
                    var string = data.split("*");
                    $('#calendarDateRow').prepend(string[0]);
                    for (var i = 1; i <= string.length; i++) {
                        $("#kennel" + i).prepend(string[i]);
                    }
                    $('.bookingInfoButton').unbind('click');
                    loadBookings(lastMonth, lastYear);
                    var numberOfDays = daysInMonth(lastMonth,lastYear);
                    adjustPosition(numberOfDays);
                    $('#calendarDates').css({"cursor":"default",});
                });
            }
        });
    });
    $('#calendarSelectedDates').datepicker({
        'format': 'yyyy/m/d',
        'autoclose': true
    });
    $('.calendarTD').hover( function() {
        $(this).find(".bookButton").toggle();
    });
});
