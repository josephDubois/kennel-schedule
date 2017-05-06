//--EDIT-BOOKING-VARIABLES//
var bookedDogsIds;
var dayCareDates;
var checkInDates;
var checkOutDates;
var masterIdList = new Array();
var deleteIdList = new Array();
var calendarid;
//-----EDIT-BOOKING-FUNCTIONS-----//
function loadAllBookingData () {
  $(".overlay").removeClass("overlayHide");
  $(".loadingContainer").css({"left":"45%",});
  /*open all accordians*/
  switchAccordian('bookingCustomerAccordianButton');
  switchAccordian('bookingPetAccordianButton');
  switchAccordian('bookingCostAccordianButton');
  bookingid = $("#editBookingId").val();
  //load costs data//
  $.ajax({
      method: "POST",
      url: "loadEditCosts.php",
      data: { bookingid:bookingid},
  }) .done(function( data ) {
      if (data != "Fail") {
        data = data.split("*");
        typeOfBooking = data[0];
        //load booking data//
        loadBookingData(typeOfBooking);
        paymentType = data[1];
        deposit = data[2];
        bookingDate = data[3];
        paymentNote = data[4];
      }else{
        alert("There was a problem loading the booking details.");
      }
  });
  //load additional cost data//
  $.ajax({
      method: "POST",
      url: "loadEditAdditional.php",
  }) .done(function( data ) {
    if (data != "Fail") {
      //split data//
      data = data.split("*");
      if(data[0] != "null") {
        //clean up the id's//
        data[0] = data[0].replace(/\"/g, "");
        data[0] = data[0].replace(/[\[\]']+/g, "");
        masterIdList = data[0].split(",");
        data[1] = data[1].replace(/\"/g, "");
        data[1] = data[1].replace(/[\[\]']+/g, "");
        additionalDesc = data[1].split(",");
        data[2] = data[2].replace(/\"/g, "");
        data[2] = data[2].replace(/[\[\]']+/g, "");
        additionalQuantity = data[2].split(",");
        data[3] = data[3].replace(/\"/g, "");
        data[3] = data[3].replace(/[\[\]']+/g, "");
        additionalPrices = data[3].split(",");
        data[4] = data[4].replace(/\"/g, "");
        data[4] = data[4].replace(/[\[\]']+/g, "");
        additionalType = data[4].split(",");

        //loop to create additional charges forms and insert information//
        for (var i = 0; i <= masterIdList.length - 1; i++) {
          loadOtherCharge();
          var number = $("#otherChargeCount").val();
          $(".extraCharge").each(function() {
            $(this).removeClass("newCharge");
            $(this).addClass("updateCharge");
          });
          $("#extraCharge" + number + " .formInput .extraChargeDesc").val(additionalDesc[i]);
          $("#extraCharge" + number + " .formInput .extraChargeQuantity").val(additionalQuantity[i]);
          $("#extraCharge" + number + " .formInput .extraChargePrice").val(additionalPrices[i]);
          $("#extraCharge" + number + " .formInput .extraChargeType").val(additionalType[i]);
        }
        var otherCharge = $("#otherChargeCount").val();
        for (var i = 1; i <= otherCharge; i++) {
          updateExtraChargePrice("extraCharge" + i);
        }
      }
    }else{
      alert("There was a problem loading the additional charges/discounts.")
    }
  });
}
function loadBookingData(bookingtype, callback) {
  $.ajax({
      method: "POST",
      url: "loadEditBooking.php",
      data: { bookingtype:bookingtype},
  }) .done(function( data ) {
    if (data != "Fail") {
      if(bookingtype == "Over Night"){
        data = data.split("*");
        clientId = data[2];
        liveSearchLoadClient(clientId, function() { insertclientdataovernight(); });
        liveSearchLoadDogs(clientId, function(){ insertdogsdataovernight(); });
        //load kennels//
        kennelsBooked = data[4];
        kennelsBooked = kennelsBooked.replace(/\"/g, "");
        kennelsBooked = kennelsBooked.replace(/[\[\]']+/g, "");
        kennelsBooked = kennelsBooked.split(",");
        /*load dog ids*/
        bookedDogsIds = data[3];
        bookedDogsIds = bookedDogsIds.replace(/\"/g, "");
        bookedDogsIds = bookedDogsIds.replace(/[\[\]']+/g, "");
        bookedDogsIds = bookedDogsIds.split(",");
        //check if its a split booking//
        if (data[5] == "split") {
          splitBooking = true;
          //load split dates//
          checkInDates = data[0];
          checkOutDates = data[1];
          checkInDates = checkInDates.replace(/\"/g, "");
          checkInDates = checkInDates.replace(/[\[\]']+/g, "");
          checkInDates = checkInDates.split(",");
          checkOutDates = checkOutDates.replace(/\"/g, "");
          checkOutDates = checkOutDates.replace(/[\[\]']+/g, "");
          checkOutDates = checkOutDates.split(",");
          checkIn = formatDateReverse(checkInDates[0]);
          checkOut = formatDateReverse(checkOutDates[checkInDates.length-1]);
          calendarid = data[6];
        }else{
          //if not split booking//
          //load dates//
          checkIn = data[0];
          checkOut = data[1];
          checkIn = checkIn.split("-");
          checkIn = checkIn[1] + "/" + checkIn[2] + "/" + checkIn[0];
          checkOut = checkOut.split("-");
          checkOut = checkOut[1] + "/" + checkOut[2] + "/" + checkOut[0];
        }
        //Appointments below//
      }else{
          data = data.split(".");
          clientId = data[0];
          //clean up data//
          for (var i = 1; i <= data.length-1; i++) {
              data[i] = data[i].replace(/\"/g, "");
              data[i] = data[i].replace(/[\[\]']+/g, "");
            }
          //save daycare dates//
          dayCareDates = data[2];
          dayCareDates = dayCareDates.split(",");
          var addDates = data[2].replace(/\,/g, "");
          addDates = addDates.split("*");
          for (var i = 0; i <= addDates.length-2; i++) {
            var tempDate = addDates[i].split("-");
            tempDate = tempDate[1] + "/" + tempDate[2] + "/" + tempDate[0];
            if (bookingDates.indexOf(addDates[i]) == -1) {
              $("#dayCareDateSelect").val(tempDate);
              addDayCareDate();
            }
          }
          $("#bookingDetailDates").html(bookingDates[0] + " to " + bookingDates[bookingDates.length - 1]);
          /*load dog ids*/
          bookedDogsIds = data[1];
          bookedDogsIds = bookedDogsIds.replace(/\"/g, "");
          bookedDogsIds = bookedDogsIds.replace(/[\[\]']+/g, "");
          bookedDogsIds = bookedDogsIds.split(",");

          liveSearchLoadClient(clientId, function() { insertclientdataappointment(); });
          liveSearchLoadDogs(clientId, function() { insertdogsdataappointment(); });
        }
    }else{
      alert("There was a problem loading the booking details.");
    }
  });
}
//insert data for booking details container//
function insertclientdataappointment() {
  $("#bookingDetailCustomerName").html(clientName);
  $("#bookingCustomerSummaryButton").html("<i class='fa fa-users' aria-hidden='true'></i> " + clientName);
}
function insertdogsdataappointment() {
  for(var i = 0; i<= bookedDogsIds.length-1; i++) {
    var tempDogIds = bookedDogsIds[i].split(".");
    for (var j = 0; j <= tempDogIds.length - 1; j++) {
      tempDogIds[j] = tempDogIds[j].replace(" ", "");
      var tempindex = dogsId.indexOf(tempDogIds[j]) + 1;
      var tempId = $("#dogId" + tempindex ).val();
      var tempName = $("#dogName" + tempindex ).html();
      $("#bookingDetailDogButtons").append("<button onclick='location.href=\"kennelsheet.php?dogid=" + tempId + "\";' class='floatLeft profileButton3'>" + tempName + "</button>");
    }
  }

  $("#bookingDetailDateBooked").html("Date of Booking: " + bookingDate);
  loadService();
  bookingNext();
  bookingDogNext();
  insertPaymentData();
  $(".overlay").addClass("overlayHide");
  $(".loadingContainer").css({"left":"-9999px",});;
}
function insertclientdataovernight() {
  $("#bookingDetailCustomerName").html(clientName);
  $("#bookingCustomerSummaryButton").html("<i class='fa fa-users' aria-hidden='true'></i> " + clientName);
}
function insertdogsdataovernight() {
  for(var i = 0; i<= bookedDogsIds.length-1; i++) {
    var tempDogIds = bookedDogsIds[i].split(".");
    for (var j = 0; j <= tempDogIds.length - 1; j++) {
      tempDogIds[j] = tempDogIds[j].replace(" ", "");
      var tempindex = dogsId.indexOf(tempDogIds[j]) + 1;
      var tempId = $("#dogId" + tempindex ).val();
      var tempName = $("#dogName" + tempindex ).html();
      $("#bookingDetailDogButtons").append("<button onclick='location.href=\"kennelsheet.php?dogid=" + tempId + "\";' class='floatLeft profileButton3'>" + tempName + "</button>");
    }
  }

  $("#bookingDetailDateBooked").html("Date of Booking: " + bookingDate);
  $("#bookingService").val(typeOfBooking);
  $("#bookingDetailDates").html(checkIn + " to " + checkOut);
  $("#nightCheckIn").val(checkIn);
  $("#nightCheckOut").val(checkOut);
  loadService();
  //if split booking//
  if (splitBooking == true) {
    bookingDates.push(checkOutDates[checkInDates.length-1]);
    $("." + calendarid).remove();
    for (var i = 0; i <= kennelsBooked.length - 1; i++) {
      var tempKennel = kennelsBooked[i];
      var index1 = bookingDates.indexOf(checkInDates[i]);
      var index2 = bookingDates.indexOf(checkOutDates[i]);
      for (j = index1; j <= index2; j++) {
        $("#K" + tempKennel + "-" + bookingDates[j]).removeClass('booked');
        splitDateSelected("K" + tempKennel + "-" + bookingDates[j]);
      }
    }

    saveSplitDates();
  }else{
    loadEditKennels(kennelsBooked);
  }
  bookingNext();
  loadEditAssignKennels(kennelsBooked, bookedDogsIds);
  bookingDogNext();
  insertPaymentData();
  $(".overlay").addClass("overlayHide");
  $(".loadingContainer").css({"left":"-9999px"});
}
//load kennels//
function loadEditKennels(editKennel) {
  for (var i = 0; i <= editKennel.length-1; i++) {
    $("#" + editKennel[i] + "KennelRow").addClass("editNow");
  }
  //select kennels that are loaded already//
  atWillKennelSelect();
}
//assign kennels//
function loadEditAssignKennels(editKennel, editDogsIds) {
  for(var i = 0; i<= editDogsIds.length-1; i++) {
    var tempDogIds = editDogsIds[i].split(".");
    for (var j = 0; j <= tempDogIds.length - 1; j++) {
      tempDogIds[j] = tempDogIds[j].replace(" ", "");
      var tempPosition = dogsId.indexOf(tempDogIds[j]);
      if (splitBooking == true) {
          $('#bookingChoice' + (tempPosition + 1)).val("Set");
      }else{
        $('#bookingChoice' + (tempPosition + 1)).val(editKennel[i]);
      }
    }
  }
}
function insertPaymentData() {
  $("#deposit").val(deposit);
  $("#paymentType").val(paymentType);
  $("#paymentNote").val(paymentNote);
}
//update booking functions//
function updateBooking(updateButtonId) {
  $(".overlay").removeClass("overlayHide");
  $(".loadingContainer").css({"left":"45%"});
  $('#' + updateButtonId).html('<i class="fa fa-spinner fa-pulse fa-fw"></i>');
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
    updateCosts();
  }
  $('#' + updateButtonId).html("Update Booking");
}
/*Save Costs*/
function updateCosts() {
  var finalTotal = subTotal + tax;
  var priceRow = $("#priceRowId").val();
  var paymentType = $("#paymentType").val();
  var paymentNote = $("#paymentNote").val();
  $.ajax({
      method: "POST",
      url: "updateCosts.php",
      data: { priceRow:priceRow, subTotal:subTotal, tax:tax, finalTotal:finalTotal, deposit:deposit, paymentType:paymentType, typeOfBooking:typeOfBooking, paymentNote:paymentNote},
  }) .done(function( data ) {
    if (typeOfBooking == "Over Night"){
      updateOverNight();
    }else{
      updateAppointment();
    }
  });
}
/*update overnight booking*/
function updateOverNight() {
  var count = 0;
  var bookingId;
  var email = false;
  var newCheckIn = formatDate(checkIn);
  var newCheckOut = formatDate(checkOut);
  var splitValue = "";
  var updateKennels = new Array();
  var updateDogs = new Array();
  //loop through the kennels//
  for( var i = 0; i <= kennelsSelected.length-1; i++) {
    updateKennels[i] = kennelsSelected[i];
    updateDogs[i] = "";
      //now loop through the dogs to see which ones are in that kennel//
      for( var j = 0; j <= numberOfDogs - 1; j++) {
        var kennel2 = $("#dateSelectDog" + (j+1) + " option:selected").val();
        if(updateKennels[i] == kennel2 && updateDogs[i] == "" && splitBooking==false) {
          updateDogs[i] = dogsId[j];
        }else if(updateKennels[i] == kennel2 && splitBooking==false) {
          updateDogs[i] = updateDogs[i] + ", " + dogsId[j];
        }else if(kennel2 == "Set" && updateDogs[i] == "" && splitBooking==true){
          updateDogs[i] = dogsId[j];
        }else if(kennel2 == "Set" && splitBooking==true){
          updateDogs[i] = updateDogs[i] + ", " + dogsId[j];
        }
      }
    }
    if (splitBooking == true) {
      newCheckIn = splitCheckIn;
      newCheckOut = splitCheckOut;
      splitValue = "split";
    }else{
      splitValue = "";
    }
   $.ajax({
        method: "POST",
        url: "updateOverNight.php",
        data: { newCheckIn: newCheckIn, newCheckOut: newCheckOut, updateDogs:updateDogs, clientId:clientId, updateKennels:updateKennels, splitValue:splitValue},
    }) .done(function( data ) {
      console.log(data);
      if (data != "Fail") {
        bookingId = data;
        if(additionalDesc.length != 0) {
          //now insert additionalCosts into database//
          updateAdditional(function(success) { if (success == 2) {location.href= "booking.php?bookingid=" + bookingId;} });
        }else{
          location.href= "booking.php?bookingid=" + bookingId;
        }
      }else{
        alert("Something went wrong updating the booking.")
      }
    });
}
//update additional costs//
function updateAdditional(callback) {
  //now seperate in costs that will be deleted, updated and saved//
  //deleted costs//
  var deleteChargesIds = new Array();
  var success = 0;
  for ( var i = 0; i <= deleteIdList.length - 1; i++) {
    deleteChargesIds.push(masterIdList[deleteIdList[i]]);
    masterIdList.splice(deleteIdList[i], 1);
  }
  //now send to server to delete//
  if (deleteChargesIds.length != 0) {
    $.ajax({
        method: "POST",
        url: "deleteAdditional.php",
        data: { deleteChargesIds: deleteChargesIds},
    }) .done(function( data ) {
        if (data == "fail") {
          alert("There was a problem deleting some of the additional charges.")
        }else{
          success++;
        }
    });
  }else{
    success++;
  }
  //now update the costs that need to be updated.
  $.ajax({
      method: "POST",
      url: "updateAdditional.php",
      data: { masterIdList: masterIdList, additionalDesc:additionalDesc, additionalQuantity:additionalQuantity, additionalPrices:additionalPrices, additionalType:additionalType },
  }) .done(function( data ) {
    if (data == "fail") {
      alert("There was a problem updating the additional costs.");
    }else{
      success++;
      callback(success);
    }
  });

}
/*Save Appointment*/
function updateAppointment() {
  var count = 0;
  var bookingId;
  var email = false;
  /*Insert per dog booking in database*/
  for( var i = 0; i <= numberOfDogs - 1; i++) {
    var id = dogsId[i];
    var selectedDates = $("#dateSelectDog" + (i + 1)).find(".search-choice").map(function(){

      return $(this).text();
    }).get();

    $.ajax({
        method: "POST",
        url: "updateAppointment.php",
        data: { selectedDates: selectedDates, clientId: clientId, id:id},
    }) .done(function( data ) {
      if (data != "Fail") {
        bookingId = data;
        count++;
        if(count == numberOfDogs && additionalDesc.length != 0) {
          //now insert additionalCosts into database//
          updateAdditional(function(success) { if (success == 2) {location.href= "booking.php?bookingid=" + bookingId;} });
        }else if(count == numberOfDogs){
          location.href= "booking.php?bookingid=" + data;
        }
      }
    });
  }
}
function markPaid() {
  $(".overlay").removeClass("overlayHide");
  $(".loadingContainer").css({"left":"45%",});
  var thisTotal = subTotal + tax;
  $.ajax({
      method: "POST",
      url: "markPaid.php",
      data: { thisTotal:thisTotal },
  }) .done(function( data ) {
      location.href= "booking.php?bookingid=" + data;
  });
}
