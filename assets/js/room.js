// get state
jQuery(document).on('change', '#room select#building-name', function (e) {
    e.preventDefault();
    var countryID = jQuery(this).val();
    getStatesList(countryID);
});

// get city
jQuery(document).on('change', '#room select#room-name', function (e) {
    e.preventDefault();
    let city_id = jQuery(this).val();
    getBedList(city_id);
});

jQuery(document).on('change', '#special-room-fee select#building-name', function (e) {
    e.preventDefault();
    var buildingID = jQuery(this).val();
    getRoomListAll(buildingID);
});

jQuery(document).on('change', '#special-room-fee select#room-name', function (e) {
    e.preventDefault();
    let roomID = jQuery(this).val();
    getBedListAll(roomID);
});

// Global function get All States   //room list
function getStatesList(countryID) {
    $.ajax({
        url: baseUrl + "room/getRooms",
        type: 'post',
        data: {countryID: countryID,
            "csrf-token": Cookies.get("CSRF-TOKEN")},
        dataType: 'json',
        beforeSend: function () {
            jQuery('select#room-name').find("option:eq(0)").html("Please wait..");
        },
        complete: function () {
            // code
        },
        success: function (room) {
            var options = '';
            options +='<option value="">Select Room</option>';
            for (var i = 0; i < room.length; i++) {
                if (room[i].status == 'Vacant')
                    options += '<option value="' + room[i].id + '">' + room[i].name+ '</option>';
                else
                    options += '<option value="' + room[i].id + '" disabled>' + room[i].name +' - '+room[i].status+ '</option>';
            }
            jQuery("select#room-name").html(options);

        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}

// get Bed List
function getBedList(stateID) {
    $.ajax({
        url: baseUrl + "room/getBeds",
        type: 'post',
        data: {stateID: stateID,
            "csrf-token": Cookies.get("CSRF-TOKEN")},
        dataType: 'json',
        beforeSend: function () {
            jQuery('select#city-name').find("option:eq(0)").html("Please wait..");
        },
        complete: function () {
            // code
        },
        success: function (beds) {
            var options = '';
            options +='<option value="">Select Bed</option>';
            for (var i = 0; i < beds.length; i++) {
                if (beds[i].status=='Vacant')
                    options += '<option value="' + beds[i].id + '">' + beds[i].name + '</option>';
                else
                    options += '<option value="' + beds[i].id + '" disabled>' + beds[i].name +' - '+beds[i].status+ '</option>';
            }
            jQuery("select#city-name").html(options);

        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}

function showRoomModal(customerid) {
    console.log("show room:",customerid);
    let data = {
            'customerid': customerid,
            'csrf-token': csrf
        },
        url = baseUrl + "students/room/view",
        dataHolder = ".update-holder-room",
        loader = true;

    if (loader) {
        showLoader();
        $(dataHolder).html('<div class="loader-box"><div class="circle-loader"></div></div>');
    }
    var posting = $.post(url, data);
    posting.done(function (response) {
        if (loader) {
            hideLoader()
        }
        $('#room').modal({show: true, backdrop: 'static', keyboard: false});
        $(dataHolder).html(response);
    });
}

// Get All Room List
function getRoomListAll(countryID) {
    $.ajax({
        url: baseUrl + "room/getRooms",
        type: 'post',
        data: {countryID: countryID,
            "csrf-token": Cookies.get("CSRF-TOKEN")},
        dataType: 'json',
        beforeSend: function () {
            jQuery('select#room-name').find("option:eq(0)").html("Please wait..");
        },
        complete: function () {
            // code
        },
        success: function (room) {
            var options = '';
            options +='<option value="">Select Room</option>';
            for (var i = 0; i < room.length; i++) {
                options += '<option value="' + room[i].id + '">' + room[i].name+ '</option>';
            }
            jQuery("select#room-name").html(options);

        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}

// get All Bed List
function getBedListAll(stateID) {
    $.ajax({
        url: baseUrl + "room/getBeds",
        type: 'post',
        data: {stateID: stateID,
            "csrf-token": Cookies.get("CSRF-TOKEN")},
        dataType: 'json',
        beforeSend: function () {
            jQuery('select#city-name').find("option:eq(0)").html("Please wait..");
        },
        complete: function () {
            // code
        },
        success: function (beds) {
            var options = '';
            options +='<option value="">Select Bed</option>';
            for (var i = 0; i < beds.length; i++) {
                options += '<option value="' + beds[i].id + '">' + beds[i].name + '</option>';
            }
            jQuery("select#city-name").html(options);

        },
        error: function (xhr, ajaxOptions, thrownError) {
            console.log(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
    });
}