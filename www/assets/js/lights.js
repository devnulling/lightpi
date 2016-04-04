var hostname = window.location.hostname;
if (hostname === 'lpi') {
    var pins = [0, 1];
} else if (hostname === 'switchpi' || hostname === 'switchpi2') {
    var pins = [0, 1, 2, 3, 4, 5, 6, 7];
}

window.onload = function() {
    drawPage();
    setTimeout(function() {
        $.each(pins, function(k, v) {
            ckpin(v);
        });
    }, 100);

    setInterval(function() {
        $.each(pins, function(k, v) {
            setTimeout(function() {
                ckpin(v);
            }, (k * 1000));
        });
    }, 60000);
};

function ckpin(pin) {
    var rs = getJson("/ck.php?pin=" + pin);
    drawButtons(pin, rs.read);
}

function updatepin(pin, mode) {
    var rs = getJson("/gpio.php?pin=" + pin + "&mode=" + mode);
    drawButtons(pin, rs.read);
}

function drawPage() {
    html = '';
    html += '<div class="row">';
    $.each(pins, function(k, v) {
        html += '<div class="col-md-6 col-sm-12 col-xs-12">';
        html += '<div class="page-header">';
        html += '<h1>Plug ' + v + '</h1><small id="pin' + v + '-status">Status: Pending</small>';
        html += '</div>';
        html += '<p>';
        html += '<button type="button" class="btn btn-lg btn-success" id="pin' + v + '-on" onClick="javascript:updatepin(' + v + ',0);return false;">On</button>';
        html += '<button type="button" class="btn btn-lg btn-danger" id="pin' + v + '-off" onClick="javascript:updatepin(' + v + ',1);return false;">Off</button>';
        html += '</p>';
        html += '</div>';
    });
    html += '</div>';
    $('#datbody').html(html);
}

function drawButtons(pin, read) {
    $('#pin' + pin + '-status').html('Status: ' + (read == "0" ? 'On' : 'Off'));
    if (read == "0") {
        $('#pin' + pin + '-off').show();
        $('#pin' + pin + '-on').hide();
    } else {
        $('#pin' + pin + '-off').hide();
        $('#pin' + pin + '-on').show();
    }
}

function getJson(url) {
    return JSON.parse($.ajax({
        type: 'GET',
        url: url,
        dataType: 'json',
        global: false,
        async: false,
        success: function(data) {
            return data;
        }
    }).responseText);
}
